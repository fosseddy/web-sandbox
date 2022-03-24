const express = require("express");

const app = express();
const port = 3000;

app.use(express.urlencoded({ extended: false }));

app.set("view engine", "pug");
app.locals.basedir = app.get("views");

app.use("/catalog", require("./src/catalog/router"));
app.use("/catalog/genre", require("./src/genre/router"));

app.listen(port, () => console.log("Server is listening on port:", port));
