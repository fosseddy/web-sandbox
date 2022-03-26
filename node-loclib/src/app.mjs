import express from "express";
import { router as catalogRouter } from "#components/catalog/router.mjs";
import { router as genreRouter } from "#components/genre/router.mjs";

const app = express();

app.use(express.urlencoded({ extended: false }));

app.set("view engine", "pug");
app.locals.basedir = app.get("views");

app.use("/catalog", catalogRouter);
app.use("/catalog/genre", genreRouter);

export { app };
