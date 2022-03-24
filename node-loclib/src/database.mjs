import mysql from "mysql2/promise";

let conn = null;

export async function init() {
    conn = await mysql.createConnection({
        host: "localhost",
        user: "root",
        database: "local_library"
    });
}

export { conn };

//await conn.query(
//    "CREATE TABLE IF NOT EXISTS genre (" +
//        "id INT AUTO_INCREMENT PRIMARY KEY," +
//        "name VARCHAR(255) NOT NULL" +
//    ");"
//).catch(console.error);
