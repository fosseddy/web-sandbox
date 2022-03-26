import { AuthorModel } from "./model.mjs";
import { isFormValid } from "#src/validator.mjs";

export async function indexView(req, res) {
    const authors = await AuthorModel.findAll();
    res.render("author/index", { authors });
}

export function showView(req, res) {
    res.render("author/show", { author: req.author });
}

export function createView(req, res) {
    res.render("author/create", {
        form: { name: "", errors: {} }
    });
}

export function updateView(req, res) {
    res.render("author/update", {
        form: { name: req.author.name, errors: {} }
    });
}

export function removeView(req, res) {
    res.render("author/delete", { author: req.author });
}

export async function create(req, res) {
    const { form } = req;

    if (!isFormValid(form)) {
        return res.render("author/create", { form });
    }

    const id = await AuthorModel.insert({ name: form.name });
    res.redirect(`/catalog/author/${id}`);
}

export async function update(req, res) {
    const { form } = req;

    if (!isFormValid(form)) {
        return res.render("author/update", { form });
    }

    await AuthorModel.update(req.params.id, { name: form.name });
    res.redirect(`/catalog/author/${req.params.id}`);
}

export async function remove(req, res) {
    const { id } = req.body;

    if (id != req.params.id) {
        throw new Error(`Author with id ${id} does not exist`);
    }

    await AuthorModel.remove(id);

    res.redirect("/catalog/author");
}
