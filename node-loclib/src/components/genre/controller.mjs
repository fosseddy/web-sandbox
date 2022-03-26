import { GenreModel } from "./model.mjs";

export async function indexView(req, res) {
    const genres = await GenreModel.findAll();
    res.render("genre/index", { genres });
}

export function showView(req, res) {
    res.render("genre/show", { genre: req.genre });
}

export function createView(req, res) {
    res.render("genre/create");
}

export function updateView(req, res) {
    res.render("genre/update", { genre: req.genre });
}

export function removeView(req, res) {
    res.render("genre/delete", { genre: req.genre });
}

export async function create(req, res) {
    const { name } = req.body;
    let errors = {};

    if (!name || !name.trim()) {
        errors.name = "Name is required";
    } else if (name.length < 2 || name.length > 255) {
        errors.name = "Name must be between 2 and 255 characters long";
    }

    if (Object.keys(errors).length) {
        return res.render("genre/create", {
            form: { name, errors }
        });
    }

    const id = await GenreModel.insert({ name });

    res.redirect(`/catalog/genre/${id}`);
}

export async function update(req, res) {
    const { name } = req.body;
    let errors = {};

    if (!name || !name.trim()) {
        errors.name = "Name is required";
    } else if (name.length < 2 || name.length > 255) {
        errors.name = "Name must be between 2 and 255 characters long";
    }

    if (Object.keys(errors).length) {
        return res.render("genre/update", {
            form: { name, errors }
        });
    }

    await GenreModel.update(req.params.id, { name });

    res.redirect(`/catalog/genre/${req.params.id}`);
}

export async function remove(req, res) {
    const { id } = req.body;

    if (id != req.params.id) {
        throw new Error(`Genre with id ${id} does not exist`);
    }

    await GenreModel.remove(id);

    res.redirect("/catalog/genre");
}
