import cookie from "cookie";

const SID = "EXPRESSID";

const database = {
    "123": { id: 1, username: "Mark" }
};

function session() {
    return function (req, res, next) {
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

        const sid = cookies[SID];
        const user = database[sid];

        if (!user) return next();

        const session = {};
        session.id = sid;
        session.user = user;

        req.session = session;

        next();
    }
}

export { session };
