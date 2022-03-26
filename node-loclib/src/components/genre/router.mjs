import { Router } from "express";
import { findGenre } from "./middleware.mjs";
import * as controller from "./controller.mjs";

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
