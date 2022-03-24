import express from "express";
import mysql from "mysql2/promise";

import { router as catalogRouter } from "./catalog/router.mjs";
import { router as genreRouter } from "./genre/router.mjs";

const conn = await mysql.createConnection({
    host: "localhost",
    user: "root",
    database: "local_library"
}).catch(console.error);

await conn.query(
    "CREATE TABLE IF NOT EXISTS genre (" +
        "id INT AUTO_INCREMENT PRIMARY KEY," +
        "name VARCHAR(255) NOT NULL" +
    ");"
).catch(console.error);

const app = express();

app.use(express.urlencoded({ extended: false }));

app.set("view engine", "pug");
app.locals.basedir = app.get("views");

app.use("/catalog", catalogRouter);
app.use("/catalog/genre", genreRouter);

export { app, conn };
