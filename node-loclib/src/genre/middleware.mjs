import assert from "assert/strict";
import { conn } from "../app.mjs";

export async function findGenre(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param?");

    const [rows] = await conn.execute(
        "SELECT * FROM genre WHERE id = ?",
        [req.params.id]
    ).catch(console.error);

    if (!rows.length) {
        throw new Error(`Genre with id ${req.params.id} not found`);
    }

    req.genre = rows[0];
    next();
}

