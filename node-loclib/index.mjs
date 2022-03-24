import assert from "assert/strict";
import { app } from "./src/app.mjs";
import * as database from "./src/database.mjs";

const port = 3000;
app.listen(port, () => console.log("Server is listening on port:", port));

try {
    await database.init();
    assert(database.conn, "database connection was not set after init");

    const { host, port } = database.conn.config;
    console.log(`MySQL successfully connected on ${host}:${port}`);
} catch (err) {
    console.error(err);
    process.exit(1);
}
