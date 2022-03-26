import { Router } from "express";
import { GenreModel } from "#components/genre/model.mjs";

const router = Router();

router.get("/", async (req, res) => {
    let counts = {};
    counts.genre = await GenreModel.count();

    res.render("catalog/index", { counts });
});

export { router };
