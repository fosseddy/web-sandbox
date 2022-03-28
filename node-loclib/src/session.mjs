import cookie from "cookie";
import signature from "cookie-signature";
import { randomBytes } from "#src/crypto.mjs";

const SID = "EXPRESSID";
const secret = "cosmos";

async function create(res, userId, conn) {
    const sid = (await randomBytes(16)).toString("hex");
    const ssid = signature.sign(sid, secret);

    const maxAge = 1000 * 60 * 60 * 24; // 24h
    const expires = new Date(Date.now() + maxAge);

    res.cookie(SID, ssid, { httpOnly: true, sameSite: true, maxAge, expires });

    // save to database
    await conn.execute(
        "INSERT INTO session (id, user_id, expires) VALUES (?, ?, ?)",
        [sid, userId, expires]
    );
}

function session(conn) {
    return async function (req, res, next) {
        if (req.session) {
            console.warn("SESSION ALREADY EXIST");
            return next();
        }

        let cookies = req.get("Cookie");

        if (!cookies) return next();

        cookies = cookie.parse(cookies);

        if (!(SID in cookies)) {
            return next();
        }

        const sid = signature.unsign(cookies[SID], secret);
        let [rows] = await conn.execute(
            "SELECT * FROM session WHERE id = ?",
            [sid]
        );

        if (!rows.length) {
            console.warn(`No session with id ${sid} found`);
            return next();
        }

        [rows] = await conn.execute(
            "SELECT id, username FROM user WHERE id = ?",
            [rows[0].user_id]
        );

        if (!rows.length) {
            console.warn(`No user with id ${rows[0].user_id} and ` +
                         `session id ${sid} found`);
            return next();
        }

        const user = rows[0];

        const session = {};
        session.id = sid;
        session.user = user;

        req.session = session;

        next();
    }
}

export { session, create };
