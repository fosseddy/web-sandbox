import { app } from "#src/app.mjs";
import * as database from "#src/database.mjs";

const port = 3000;
app.listen(port, () => console.log("Server is listening on port:", port));

try {
    await database.init();
    console.log(`MySQL successfully connected on ${database.getSocketAddr()}`);
} catch (err) {
    console.error(err);
    process.exit(1);
}
