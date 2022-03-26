import assert from "assert/strict";
import { GenreModel } from "./model.mjs";

async function findGenre(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param");

    const { id } = req.params;
    const genre = await GenreModel.find({ id });

    if (!genre) {
        throw new Error(`Genre with id ${id} not found`);
    }

    req.genre = genre;
    next();
}

function validateGenre(req, res, next) {
    const { name } = req.body;
    let errors = {};

    if (!name || !name.trim()) {
        errors.name = "Name is required";
    } else if (name.length < 2 || name.length > 255) {
        errors.name = "Name must be between 2 and 255 characters long";
    }

    req.form = { name, errors };
    next();
}

export { findGenre, validateGenre };
