// move_prefix_to_plain.js
// Move data from Postgres tables with prefix (samvol_inventory_) to tables without the prefix
// Usage: set PG env vars or use .env.migration
require("dotenv").config({ path: ".env.migration" });
const { Client } = require("pg");

const PG_CONFIG = {
    host: process.env.PG_HOST || "localhost",
    port: parseInt(process.env.PG_PORT || "5432", 10),
    user: process.env.PG_USER || "postgres",
    password: process.env.PG_PASSWORD || "",
    database: process.env.PG_DATABASE || "inventpro-test",
};

const PREFIX = process.env.MY_PREFIX || "samvol_inventory_";
const BATCH_SIZE = parseInt(process.env.BATCH_SIZE || "1000", 10);

function mapType(pgType) {
    const t = pgType.toLowerCase();
    if (t.includes("bigint") || t.includes("integer")) return t;
    if (t.includes("character") || t.includes("text") || t.includes("varchar"))
        return "text";
    if (t.includes("numeric") || t.includes("decimal")) return "numeric";
    if (t.includes("double") || t.includes("real")) return "double precision";
    if (t.includes("timestamp")) return "timestamptz";
    if (t.includes("date")) return "date";
    if (t.includes("boolean")) return "boolean";
    if (t.includes("geometry")) return "geometry";
    return t;
}

async function tableExists(pg, name) {
    const res = await pg.query(`SELECT to_regclass($1)`, [name]);
    return res.rows[0].to_regclass !== null;
}

async function getColumns(pg, table) {
    const res = await pg.query(
        `SELECT column_name, data_type, udt_name FROM information_schema.columns WHERE table_schema='public' AND table_name = $1 ORDER BY ordinal_position`,
        [table],
    );
    return res.rows.map((r) => ({
        name: r.column_name,
        data_type: r.data_type,
        udt: r.udt_name,
    }));
}

async function ensureTargetTable(pg, source, target) {
    const exists = await tableExists(pg, target);
    if (!exists) {
        console.log(`Creating target table ${target} LIKE ${source}`);
        // create table like source
        await pg.query(`CREATE TABLE ${target} (LIKE ${source} INCLUDING ALL)`);
    } else {
        console.log(`Target table ${target} exists`);
    }
    // ensure columns from source exist in target
    const srcCols = await getColumns(pg, source);
    const tgtCols = await getColumns(pg, target);
    const tgtNames = new Set(tgtCols.map((c) => c.name));
    for (const c of srcCols) {
        if (!tgtNames.has(c.name)) {
            const t = mapType(c.data_type || c.udt || "text");
            console.log(`Adding column ${c.name} ${t} to ${target}`);
            if (t === "geometry") {
                await pg.query(
                    `ALTER TABLE ${target} ADD COLUMN ${c.name} geometry`,
                );
            } else {
                await pg.query(
                    `ALTER TABLE ${target} ADD COLUMN \"${c.name}\" ${t}`,
                );
            }
        }
    }
}

async function migrate(pg, source, target) {
    console.log(`Migrating ${source} -> ${target}`);
    const srcCols = await getColumns(pg, source);
    const colNames = srcCols.map((c) => c.name);
    if (!colNames.length) {
        console.log("No columns, skipping");
        return;
    }
    const colsList = colNames.map((c) => `\"${c}\"`).join(",");
    const countRes = await pg.query(`SELECT count(*) AS cnt FROM ${source}`);
    const total = parseInt(countRes.rows[0].cnt, 10);
    console.log(`Total rows in ${source}: ${total}`);

    for (let offset = 0; offset < total; offset += BATCH_SIZE) {
        const res = await pg.query(
            `SELECT ${colsList} FROM ${source} ORDER BY 1 LIMIT ${BATCH_SIZE} OFFSET ${offset}`,
        );
        const rows = res.rows;
        if (!rows.length) break;
        // Build insert
        const params = [];
        const valueBlocks = [];
        for (let r = 0; r < rows.length; r++) {
            const row = rows[r];
            const placeholders = [];
            const base = r * colNames.length;
            for (let i = 0; i < colNames.length; i++) {
                params.push(row[colNames[i]]);
                placeholders.push(`$${base + i + 1}`);
            }
            valueBlocks.push(`(${placeholders.join(",")})`);
        }
        const insertCols = colNames.map((c) => `\"${c}\"`);
        const updateCols = insertCols.filter((c) => c !== '"id"');
        const updateAssignments = updateCols
            .map((c) => `${c} = EXCLUDED.${c.replace(/"/g, "")}`)
            .join(", ");
        const insertSql = `INSERT INTO ${target} (${insertCols.join(",")}) VALUES ${valueBlocks.join(",")} ON CONFLICT (id) DO UPDATE SET ${updateAssignments};`;
        try {
            await pg.query("BEGIN");
            await pg.query(insertSql, params);
            await pg.query("COMMIT");
            process.stdout.write(".");
        } catch (err) {
            await pg.query("ROLLBACK");
            console.error("\nInsert error", err);
            throw err;
        }
    }
    console.log("\nFinished", source);
}

async function main() {
    const pg = new Client(PG_CONFIG);
    await pg.connect();

    const tabRes = await pg.query(
        `SELECT tablename FROM pg_tables WHERE schemaname='public' AND tablename LIKE $1 ORDER BY tablename`,
        [PREFIX + "%"],
    );
    if (!tabRes.rows.length) {
        console.log("No tables with prefix", PREFIX);
        await pg.end();
        return;
    }

    for (const r of tabRes.rows) {
        const src = r.tablename;
        const tgt = src.replace(PREFIX, "");
        await ensureTargetTable(pg, src, tgt);
        await migrate(pg, src, tgt);
    }

    await pg.end();
    console.log("All prefix tables migrated");
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});
