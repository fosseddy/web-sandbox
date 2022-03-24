import { conn } from "../app.mjs";

export async function indexView(req, res) {
    const [genres] = await conn.execute("SELECT * FROM genre")
        .catch(console.error);

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

    await conn.execute("INSERT INTO genre (name) VALUES (?)", [name])
        .catch(console.error);

    res.redirect("/catalog/genre");
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

    await conn.execute(
        "UPDATE genre SET name = ? WHERE id = ?",
        [name, req.params.id]
    ).catch(console.error);

    res.redirect(`/catalog/genre/${req.params.id}`);
}

export async function remove(req, res) {
    const { id } = req.body;

    if (id != req.params.id) {
        throw new Error(`Genre with id ${id} does not exist`);
    }

    await conn.execute("DELETE FROM genre WHERE id = ?", [id])
        .catch(console.error);

    res.redirect("/catalog/genre");
}
