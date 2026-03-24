require("dotenv").config({ path: ".env.migration" });
const { Client } = require("pg");

const pg = new Client({
    host: process.env.PG_HOST || "localhost",
    port: parseInt(process.env.PG_PORT || "5432", 10),
    user: process.env.PG_USER || "postgres",
    password: process.env.PG_PASSWORD || "",
    database: process.env.PG_DATABASE || "inventpro-test",
});

const checks = [
    {
        child: "api_refresh_tokens",
        child_col: "user_id",
        parent: "users",
        parent_col: "id",
    },
    {
        child: "documents",
        child_col: "operation_id",
        parent: "operations",
        parent_col: "id",
    },
    {
        child: "note_products",
        child_col: "note_id",
        parent: "notes",
        parent_col: "id",
    },
    {
        child: "operation_products",
        child_col: "operation_id",
        parent: "operations",
        parent_col: "id",
    },
];

async function findMissing(child, child_col, parent, parent_col) {
    const sql = `SELECT DISTINCT c.\"${child_col}\" AS v FROM \"${child}\" c LEFT JOIN \"${parent}\" p ON c.\"${child_col}\" = p.\"${parent_col}\" WHERE c.\"${child_col}\" IS NOT NULL AND p.\"${parent_col}\" IS NULL LIMIT 100;`;
    const res = await pg.query(sql);
    return res.rows.map((r) => r.v);
}

async function main() {
    await pg.connect();
    for (const ch of checks) {
        try {
            const missing = await findMissing(
                ch.child,
                ch.child_col,
                ch.parent,
                ch.parent_col,
            );
            console.log(
                `${ch.child} -> ${ch.parent}: ${missing.length} missing ids${missing.length ? ", sample: " + missing.slice(0, 10).join(",") : ""}`,
            );
        } catch (err) {
            console.log(`${ch.child} -> ${ch.parent}: error: ${err.message}`);
        }
    }
    await pg.end();
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});
