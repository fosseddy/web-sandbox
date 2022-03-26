import mysql from "mysql2/promise";

let conn = await mysql.createConnection({
    host: "localhost",
    user: "root",
    database: "local_library"
}).catch(err => {
    console.error(err);
    process.exit(1);
});

await conn.query(
    "CREATE TABLE IF NOT EXISTS genre (" +
        "id INT AUTO_INCREMENT PRIMARY KEY," +
        "name VARCHAR(255) NOT NULL" +
    ");"
).catch(console.error);

await conn.query(
    "CREATE TABLE IF NOT EXISTS author (" +
        "id INT AUTO_INCREMENT PRIMARY KEY," +
        "name VARCHAR(255) NOT NULL," +
        "date_of_birth DATE NOT NULL," +
        "date_of_death DATE NOT NULL" +
    ")"
).catch(console.error);

console.log("All tabels are successfully created...");
process.exit(0);
