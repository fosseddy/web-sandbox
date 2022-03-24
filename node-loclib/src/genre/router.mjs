import { Router } from "express";

import * as controller from "./controller.mjs";
import { findGenre } from "./middleware.mjs";

let id = 1;
let genres = [
    { id: id++, name: "Poetry" },
    { id: id++, name: "Science fiction" },
    { id: id++, name: "Classic" }
];

export function addGenre(name) {
    genres.push({ id: id++, name });
}

export function getGenres() {
    return genres;
}

export function removeGenre(id) {
    genres = genres.filter(g => g.id != id);;
}

export function updateGenre(id, name) {
    genres = genres.map(g => {
        if (g.id != id) return g;
        g.name = name;
        return g;
    });
}

const router = Router();

router.get("/", controller.indexView);

router.get("/:id(\\d+)", findGenre, controller.showView);

router.route("/create")
    .get(controller.createView)
    .post(controller.create);

router.route("/:id(\\d+)/update")
    .get(findGenre, controller.updateView)
    .post(controller.update);

router.route("/:id(\\d+)/delete")
    .get(findGenre, controller.removeView)
    .post(controller.remove);

export { router };
