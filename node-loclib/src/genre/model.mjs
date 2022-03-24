import { conn } from "../database.mjs";

export async function findAll() {
    const [genres] = await conn.execute("SELECT * FROM genre");
    return genres;
}

export async function find(id) {
    const [rows] = await conn.execute(
        "SELECT * FROM genre WHERE id = ?",
        [id]
    );

    return rows.length > 0 ? rows[0] : null;
}

export async function update(id, data) {
    return conn.execute(
        "UPDATE genre SET name = ? WHERE id = ?",
        [data.name, id]
    );
}

export async function remove(id) {
    return conn.execute("DELETE FROM genre WHERE id = ?", [id]);
}

export async function insert(data) {
    const [res] = await conn.execute(
        "INSERT INTO genre (name) VALUES (?)",
        [data.name]
    );

    return res.insertId;
}
