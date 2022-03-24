import assert from "assert/strict";
import * as GenreModel from "./model.mjs";

export async function findGenre(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param?");

    const { id } = req.params;
    const genre = await GenreModel.find(id);

    if (!genre) {
        throw new Error(`Genre with id ${id} not found`);
    }

    req.genre = genre;
    next();
}

