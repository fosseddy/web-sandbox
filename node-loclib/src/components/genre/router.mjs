import { Router } from "express";
import { findEntity } from "#src/middleware.mjs";
import { GenreModel } from "./model.mjs";
import * as controller from "./controller.mjs";

const findGenre = findEntity(GenreModel);

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

const router = Router();

router.get("/", controller.indexView);

router.get("/:id(\\d+)", findGenre, controller.showView);

router.route("/create")
    .get(controller.createView)
    .post(validateGenre, controller.create);

router.route("/:id(\\d+)/update")
    .get(findGenre, controller.updateView)
    .post(validateGenre, controller.update);

router.route("/:id(\\d+)/delete")
    .get(findGenre, controller.removeView)
    .post(controller.remove);

export { router };
