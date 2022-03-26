import assert from "assert/strict";
import { AuthorModel } from "./model.mjs";

async function findAuthor(req, res, next) {
    assert(req.params.id, "middleware used on route with no :id param");

    const { id } = req.params;
    const author = await AuthorModel.find({ id });

    if (!author) {
        throw new Error(`Author with id ${id} not found`);
    }

    req.author = author;
    next();
}

function validateAuthor(req, res, next) {
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

export { findAuthor, validateAuthor };
