// move_prefix_ordered.js
// Migrate Postgres tables with prefix to tables without the prefix in dependency order
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

async function listPrefixedTables(pg) {
    const res = await pg.query(
        `SELECT tablename FROM pg_tables WHERE schemaname='public' AND tablename LIKE $1 ORDER BY tablename`,
        [PREFIX + "%"],
    );
    return res.rows.map((r) => r.tablename);
}

async function getTableFks(pg, table) {
    // returns array of referenced table names (unqualified)
    const sql = `
    SELECT
      tc.constraint_name, kcu.column_name, ccu.table_name AS foreign_table_name, ccu.column_name AS foreign_column_name
    FROM
      information_schema.table_constraints AS tc
      JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
      JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
    WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name = $1;
  `;
    const res = await pg.query(sql, [table]);
    return res.rows.map((r) => ({
        column: r.column_name,
        foreign_table: r.foreign_table_name,
        foreign_column: r.foreign_column_name,
    }));
}

async function ensureParentTableExists(pg, table) {
    const exists = await tableExists(pg, table);
    if (!exists) {
        console.log(
            `Creating minimal parent table ${table} (id bigint primary key)`,
        );
        await pg.query(`CREATE TABLE ${table} (id bigint PRIMARY KEY)`);
    }
}

function placeholderForType(data_type, udt) {
    const t = (data_type || udt || "").toLowerCase();
    if (t.includes("timestamp")) return "now()";
    if (t.includes("date")) return "now()";
    if (t.includes("bool")) return "false";
    if (t.includes("int") || t.includes("numeric") || t.includes("decimal"))
        return "0";
    if (t.includes("uuid")) return "gen_random_uuid()";
    if (t.includes("text") || t.includes("char") || t.includes("varchar"))
        return "''";
    return "NULL";
}

async function getNotNullColumns(pg, table) {
    const res = await pg.query(
        `SELECT column_name, data_type, udt_name, column_default FROM information_schema.columns WHERE table_schema='public' AND table_name = $1 AND is_nullable='NO' ORDER BY ordinal_position`,
        [table],
    );
    return res.rows.map((r) => ({
        name: r.column_name,
        data_type: r.data_type,
        udt: r.udt_name,
        default: r.column_default,
    }));
}

async function insertParentPlaceholders(
    pg,
    parentTable,
    parentColumn,
    missingIds,
) {
    if (!missingIds.length) return;
    await ensureParentTableExists(pg, parentTable);
    const notNullCols = await getNotNullColumns(pg, parentTable);
    const required = notNullCols.filter(
        (c) => c.name !== parentColumn && !c.default,
    );
    for (const id of missingIds) {
        try {
            await pg.query(
                `INSERT INTO ${parentTable} (${parentColumn}) VALUES ($1) ON CONFLICT DO NOTHING`,
                [id],
            );
            continue;
        } catch (e) {}
        const cols = [parentColumn, ...required.map((c) => c.name)];
        const values = [];
        const params = [];
        params.push(id);
        values.push(`$1`);
        for (let i = 0; i < required.length; i++) {
            const c = required[i];
            const ph = placeholderForType(c.data_type, c.udt);
            if (ph === "NULL") {
                params.push(null);
                values.push(`$${params.length}`);
            } else if (ph.endsWith("()") || ph.includes("gen_random_uuid")) {
                values.push(ph);
            } else {
                params.push(ph.replace(/'/g, ""));
                values.push(`$${params.length}`);
            }
        }
        const sql = `INSERT INTO ${parentTable} (${cols.map((c) => '"' + c + '"').join(",")}) VALUES (${values.join(",")}) ON CONFLICT DO NOTHING`;
        try {
            await pg.query(sql, params);
        } catch (err) {
            console.warn(
                `Failed to insert placeholder into ${parentTable} for id=${id}:`,
                err.message || err,
            );
        }
    }
}

function topoSort(nodes, edges) {
    // nodes: array of node names
    // edges: map node -> array of nodes it depends on (node -> [deps])
    const inDegree = new Map();
    const adj = new Map();
    for (const n of nodes) {
        inDegree.set(n, 0);
        adj.set(n, []);
    }
    for (const [n, deps] of Object.entries(edges)) {
        for (const d of deps) {
            if (!inDegree.has(d)) continue;
            inDegree.set(n, inDegree.get(n) + 1);
            adj.get(d).push(n);
        }
    }
    const q = [];
    for (const [n, deg] of inDegree.entries()) if (deg === 0) q.push(n);
    const out = [];
    while (q.length) {
        const n = q.shift();
        out.push(n);
        for (const m of adj.get(n)) {
            inDegree.set(m, inDegree.get(m) - 1);
            if (inDegree.get(m) === 0) q.push(m);
        }
    }
    if (out.length !== nodes.length) {
        // cycle exists; append remaining nodes in arbitrary order
        const remaining = nodes.filter((n) => !out.includes(n));
        return out.concat(remaining);
    }
    return out;
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
        await pg.query(`CREATE TABLE ${target} (LIKE ${source} INCLUDING ALL)`);
    }
    const srcCols = await getColumns(pg, source);
    const tgtCols = await getColumns(pg, target);
    const tgtNames = new Set(tgtCols.map((c) => c.name));
    for (const c of srcCols) {
        if (!tgtNames.has(c.name)) {
            const t = c.data_type || c.udt || "text";
            console.log(`Adding column ${c.name} ${t} to ${target}`);
            await pg.query(
                `ALTER TABLE ${target} ADD COLUMN \"${c.name}\" ${t}`,
            );
        }
    }
}

async function migrateTable(pg, source, target) {
    console.log(`Migrating ${source} -> ${target}`);
    const srcCols = await getColumns(pg, source);
    const colNames = srcCols.map((c) => c.name);
    if (!colNames.length) return;
    const srcColMap = Object.fromEntries(srcCols.map((c) => [c.name, c]));
    const targetNotNullCols = await getNotNullColumns(pg, target).catch(
        () => [],
    );
    const requiredSet = new Set(targetNotNullCols.map((c) => c.name));
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
        const params = [];
        const valueBlocks = [];
        let paramIndex = 0;
        for (let r = 0; r < rows.length; r++) {
            const row = rows[r];
            const placeholders = [];
            for (let i = 0; i < colNames.length; i++) {
                const col = colNames[i];
                let val = row[col];
                if (
                    (val === null || val === undefined) &&
                    requiredSet.has(col)
                ) {
                    const srcCol = srcColMap[col] || {};
                    const ph = placeholderForType(srcCol.data_type, srcCol.udt);
                    if (ph === "NULL") {
                        paramIndex += 1;
                        params.push(null);
                        placeholders.push(`$${paramIndex}`);
                    } else if (
                        ph.endsWith("()") ||
                        ph.includes("gen_random_uuid")
                    ) {
                        placeholders.push(ph);
                    } else {
                        paramIndex += 1;
                        params.push(ph.replace(/'/g, ""));
                        placeholders.push(`$${paramIndex}`);
                    }
                } else {
                    // regular value (may be null)
                    paramIndex += 1;
                    params.push(val);
                    placeholders.push(`$${paramIndex}`);
                }
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
            console.error("\nInsert error", err.message || err);
            // Return error to caller to decide retry or skip
            throw err;
        }
    }
    console.log("\nFinished", source);
}

async function main() {
    const pg = new Client(PG_CONFIG);
    await pg.connect();

    const prefixed = await listPrefixedTables(pg);
    if (!prefixed.length) {
        console.log("No prefixed tables found");
        await pg.end();
        return;
    }

    // build dependency edges among prefixed tables
    const edges = {}; // table -> [deps]
    for (const t of prefixed) {
        const fks = await getTableFks(pg, t);
        const deps = [];
        for (const fk of fks) {
            if (fk.startsWith(PREFIX)) deps.push(fk);
            else if (prefixed.includes(PREFIX + fk)) deps.push(PREFIX + fk);
        }
        edges[t] = deps;
    }

    const order = topoSort(prefixed, edges);
    console.log("Migration order:", order.join(", "));

    for (const src of order) {
        const tgt = src.replace(PREFIX, "");
        await ensureTargetTable(pg, src, tgt);
        // ensure parent placeholders for FKs referenced by this source
        try {
            const fks = await getTableFks(pg, src);
            for (const fk of fks) {
                const parent = fk.foreign_table;
                const parentCol = fk.foreign_column || "id";
                // collect missing ids from source table that are not present in parent
                const missingRes = await pg.query(
                    `SELECT DISTINCT t.\"${fk.column}\" AS val FROM ${src} t LEFT JOIN ${parent} p ON t.\"${fk.column}\" = p.\"${parentCol}\" WHERE t.\"${fk.column}\" IS NOT NULL AND p.\"${parentCol}\" IS NULL LIMIT 1000`,
                );
                const missing = missingRes.rows
                    .map((r) => r.val)
                    .filter((v) => v !== null && v !== undefined);
                if (missing.length) {
                    console.log(
                        `Creating ${missing.length} placeholder(s) in parent ${parent} for FK ${fk.column}`,
                    );
                    await insertParentPlaceholders(
                        pg,
                        parent,
                        parentCol,
                        missing,
                    );
                }
            }
        } catch (e) {
            console.warn(
                "Parent placeholder step failed for",
                src,
                e.message || e,
            );
        }
        try {
            await migrateTable(pg, src, tgt);
        } catch (err) {
            console.error(`Failed to migrate ${src}:`, err.message || err);
            // continue to next table; user can inspect failures
        }
    }

    await pg.end();
    console.log("Ordered migration finished");
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});
