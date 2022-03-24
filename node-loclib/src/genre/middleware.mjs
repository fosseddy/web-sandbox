import assert from "assert/strict";
import { getGenres } from "./router.mjs";

export function findGenre(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param?");

    const genre = getGenres().find(g => g.id == req.params.id);

    if (!genre) {
        throw new Error(`Genre with id ${req.params.id} not found`);
    }

    req.genre = genre;
    next();
}

