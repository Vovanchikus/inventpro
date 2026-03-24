// migrate_all.js
// Migrate all tables with prefix samvol_inventory_ from MySQL -> PostgreSQL
require("dotenv").config({ path: ".env.migration" });
const mysql = require("mysql2/promise");
const { Client } = require("pg");

const MYSQL_CONFIG = {
    host: process.env.MYSQL_HOST || "127.0.0.1",
    port: parseInt(process.env.MYSQL_PORT || "3306", 10),
    user: process.env.MYSQL_USER || "migrator",
    password: process.env.MYSQL_PASSWORD || "",
    database: process.env.MYSQL_DATABASE || "inventpro-test",
};
const PG_CONFIG = {
    host: process.env.PG_HOST || "localhost",
    port: parseInt(process.env.PG_PORT || "5432", 10),
    user: process.env.PG_USER || "postgres",
    password: process.env.PG_PASSWORD || "",
    database: process.env.PG_DATABASE || "inventpro-test",
};
const BATCH_SIZE = parseInt(process.env.BATCH_SIZE || "1000", 10);
const PREFIX = process.env.MY_PREFIX || "samvol_inventory_";

function mapType(mysqlType) {
    const t = mysqlType.toLowerCase();
    if (t.includes("int")) return "bigint";
    if (t.includes("varchar") || t.includes("char")) return "text";
    if (t.includes("text")) return "text";
    if (t.includes("decimal") || t.includes("numeric")) return "numeric";
    if (t.includes("double") || t.includes("float")) return "double precision";
    if (t.includes("timestamp") || t.includes("datetime")) return "timestamptz";
    if (t.includes("date")) return "date";
    if (t.includes("tinyint(1)") || /^tinyint\(1\)/.test(t)) return "boolean";
    return "text";
}

async function createPgTable(pg, tableName, cols, hasLatLng) {
    const colsSql = cols.map(
        (c) => `\"${c.COLUMN_NAME}\" ${mapType(c.COLUMN_TYPE)}`,
    );
    if (hasLatLng) colsSql.push("location geometry(Point,4326)");
    const sql = `CREATE TABLE IF NOT EXISTS ${tableName} (${colsSql.join(", ")});`;
    await pg.query(sql);
    if (hasLatLng)
        await pg.query(
            `CREATE INDEX IF NOT EXISTS idx_${tableName}_location ON ${tableName} USING GIST (location);`,
        );
}

async function migrateTable(mysqlConn, pg, schema, table) {
    console.log("Migrating", table);
    const [cols] = await mysqlConn.execute(
        `SELECT COLUMN_NAME, COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION`,
        [schema, table],
    );
    const colNames = cols.map((r) => r.COLUMN_NAME);
    const hasLat = colNames.includes("latitude");
    const hasLng = colNames.includes("longitude");
    const pgTable = table.replace(/[^a-z0-9_]/gi, "_");

    await createPgTable(pg, pgTable, cols, hasLat && hasLng);

    // stream rows from MySQL
    const selectSql = `SELECT ${colNames.map((c) => `\`${c}\``).join(", ")} FROM \`${table}\``;
    const [rowsStream] = await mysqlConn.query(selectSql);
    // rowsStream is array when using promise API; use batched selects instead
    const [countRow] = await mysqlConn.execute(
        `SELECT COUNT(*) AS cnt FROM \`${table}\``,
    );
    const total = countRow[0].cnt || 0;
    console.log(`Total rows: ${total}`);

    for (let offset = 0; offset < total; offset += BATCH_SIZE) {
        // Some MySQL clients have issues with parameterized LIMIT/OFFSET; inject numeric values directly
        const batchSql = `${selectSql} LIMIT ${BATCH_SIZE} OFFSET ${offset}`;
        const [batchRows] = await mysqlConn.execute(batchSql);
        if (!batchRows.length) break;
        // build INSERT
        const params = [];
        const valueBlocks = [];
        for (let r = 0; r < batchRows.length; r++) {
            const row = batchRows[r];
            const base = r * colNames.length;
            const placeholders = [];
            for (let i = 0; i < colNames.length; i++) {
                params.push(row[colNames[i]]);
                placeholders.push(`$${base + i + 1}`);
            }
            if (hasLat && hasLng) {
                const latIdx = colNames.indexOf("latitude");
                const lngIdx = colNames.indexOf("longitude");
                const latParam = `$${base + latIdx + 1}`;
                const lngParam = `$${base + lngIdx + 1}`;
                placeholders.push(
                    `ST_SetSRID(ST_MakePoint(${lngParam}, ${latParam}), 4326)`,
                );
            }
            valueBlocks.push(`(${placeholders.join(", ")})`);
        }
        const insertCols = colNames.map((c) => `\"${c}\"`);
        if (hasLat && hasLng) insertCols.push("location");
        const updateCols = insertCols.filter((c) => c !== '"id"');
        const updateClause = updateCols
            .map((c) => `${c} = EXCLUDED.${c.replace(/"/g, "")}`)
            .join(", ");
        const insertSql = `INSERT INTO ${pgTable} (${insertCols.join(",")}) VALUES ${valueBlocks.join(", ")} ON CONFLICT DO NOTHING;`;
        try {
            await pg.query("BEGIN");
            await pg.query(insertSql, params);
            await pg.query("COMMIT");
            process.stdout.write(`.${offset + batchRows.length}`);
        } catch (err) {
            await pg.query("ROLLBACK");
            console.error("Insert error", err);
            throw err;
        }
    }
    console.log("\nFinished", table);
}

async function main() {
    const mysqlConn = await mysql.createConnection(MYSQL_CONFIG);
    const pg = new Client(PG_CONFIG);
    await pg.connect();

    const [tables] = await mysqlConn.execute(
        `SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE ?`,
        [MYSQL_CONFIG.database, `${PREFIX}%`],
    );
    if (!tables.length) {
        console.log("No tables found with prefix", PREFIX);
        process.exit(0);
    }
    // simple order: try to migrate tables without FK first by scanning information_schema.KEY_COLUMN_USAGE
    // For now, proceed in the order found
    for (const t of tables) {
        await migrateTable(mysqlConn, pg, MYSQL_CONFIG.database, t.TABLE_NAME);
    }

    await pg.end();
    await mysqlConn.end();
    console.log("All done");
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});
