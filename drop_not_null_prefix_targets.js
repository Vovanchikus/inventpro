// drop_not_null_prefix_targets.js
// Drops NOT NULL constraint from columns in target tables corresponding to prefixed source tables.
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

async function listPrefixedTables(pg) {
    const res = await pg.query(
        `SELECT tablename FROM pg_tables WHERE schemaname='public' AND tablename LIKE $1 ORDER BY tablename`,
        [PREFIX + "%"],
    );
    return res.rows.map((r) => r.tablename);
}

async function getNotNullColumns(pg, table) {
    const res = await pg.query(
        `SELECT column_name FROM information_schema.columns WHERE table_schema='public' AND table_name = $1 AND is_nullable='NO' AND column_default IS NULL`,
        [table],
    );
    return res.rows.map((r) => r.column_name);
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

    for (const src of prefixed) {
        const tgt = src.replace(PREFIX, "");
        const existsRes = await pg.query(`SELECT to_regclass($1)`, [tgt]);
        if (!existsRes.rows[0].to_regclass) {
            console.log(`Target table ${tgt} does not exist, skipping`);
            continue;
        }
        const cols = await getNotNullColumns(pg, tgt);
        if (!cols.length) {
            console.log(`No NOT NULL columns to drop on ${tgt}`);
            continue;
        }
        console.log(
            `Dropping NOT NULL on ${cols.length} column(s) in ${tgt}: ${cols.join(", ")}`,
        );
        for (const c of cols) {
            try {
                await pg.query(
                    `ALTER TABLE ${tgt} ALTER COLUMN "${c}" DROP NOT NULL`,
                );
                console.log(`  dropped NOT NULL on ${tgt}.${c}`);
            } catch (err) {
                console.warn(
                    `  failed to drop NOT NULL on ${tgt}.${c}:`,
                    err.message || err,
                );
            }
        }
    }

    await pg.end();
    console.log("Done");
}

main().catch((err) => {
    console.error(err);
    process.exit(1);
});
