// migrate_products.js
// Stream migration from MySQL -> PostgreSQL (PostGIS) for `products` table.
// Configure via environment variables or edit MYSQL_CONFIG / PG_CONFIG below.

// Load .env.migration if present
require('dotenv').config({ path: '.env.migration' });

const mysql = require('mysql2');
const { Client } = require('pg');

// CONFIG: override with environment variables as needed
const MYSQL_CONFIG = {
  host: process.env.MYSQL_HOST || 'localhost',
  port: parseInt(process.env.MYSQL_PORT || '3306', 10),
  user: process.env.MYSQL_USER || 'mysql_user',
  password: process.env.MYSQL_PASSWORD || 'mysql_pass',
  database: process.env.MYSQL_DATABASE || 'mysql_db',
  // You can add charset, ssl etc here
};

const PG_CONFIG = {
  host: process.env.PG_HOST || 'localhost',
  port: parseInt(process.env.PG_PORT || '5432', 10),
  user: process.env.PG_USER || 'pg_user',
  password: process.env.PG_PASSWORD || 'pg_pass',
  database: process.env.PG_DATABASE || 'pg_db',
};

const BATCH_SIZE = parseInt(process.env.BATCH_SIZE || '1000', 10);

async function main() {
  console.log('Starting migration: MySQL -> Postgres');
  const mysqlConn = mysql.createConnection(MYSQL_CONFIG);
  // Graceful error handling for connection issues (auth, network)
  mysqlConn.on('error', err => {
    console.error('MySQL connection error:', err.message || err);
    process.exit(1);
  });
  const pg = new Client(PG_CONFIG);
  await pg.connect();

  // Adjust SELECT columns to match your products table schema
    // Таблица-источник можно указать в .env.migration через MYSQL_TABLE (по умолчанию 'products')
    const SOURCE_TABLE = process.env.MYSQL_TABLE || 'products';
    // Detect available columns in source table and build SELECT dynamically
    const connPromise = mysqlConn.promise();
    const [colRows] = await connPromise.query(
      `SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?`,
      [MYSQL_CONFIG.database, SOURCE_TABLE]
    );
    const availableCols = colRows.map(r => r.COLUMN_NAME.toLowerCase());

    const expectedCols = ['id','organization_id','category_id','name','quantity','unit','inv_number','price','mobile_summary','external_id','slug','latitude','longitude','created_at','updated_at'];
    const selectCols = expectedCols.filter(c => availableCols.includes(c));
    if (selectCols.length === 0) {
      console.error('No matching columns found in source table:', SOURCE_TABLE);
      process.exit(1);
    }
    const selectSql = `SELECT ${selectCols.join(', ')} FROM \`${SOURCE_TABLE}\``;

  const query = mysqlConn.query(selectSql);
  const stream = query.stream({ highWaterMark: 100 });

  let batch = [];
  let total = 0;

  function clearBatch() { batch = []; }

  async function flushBatch() {
    if (batch.length === 0) return;

    const cols = selectCols.slice();
    const hasLat = cols.includes('latitude');
    const hasLng = cols.includes('longitude');
    const rowParamCount = cols.length; // dynamic

    const params = [];
    const valueBlocks = [];

    for (let r = 0; r < batch.length; r++) {
      const row = batch[r];
      // Normalize values (convert zero-dates or empty strings to null if needed)
      const val = [];
      for (const c of cols) {
        let v = row[c];
        if (v === '0000-00-00' || v === '0000-00-00 00:00:00') v = null;
        val.push(v);
      }
      params.push(...val);
      const base = r * rowParamCount;
      // param positions are 1-based
      const paramNums = [];
      for (let i = 1; i <= rowParamCount; i++) paramNums.push(`$${base + i}`);
      // Build values block depending on presence of lat/lng
      const blockParts = [];
      for (let i = 0; i < cols.length; i++) {
        blockParts.push(paramNums[i]);
      }
      // If both lat and lng present, append location expression using their param positions
      if (hasLat && hasLng) {
        const latIndex = cols.indexOf('latitude');
        const lngIndex = cols.indexOf('longitude');
        const latParam = `$${base + latIndex + 1}`;
        const lngParam = `$${base + lngIndex + 1}`;
        blockParts.push(`ST_SetSRID(ST_MakePoint(${lngParam}, ${latParam}), 4326)`);
      }

      valueBlocks.push(`(${blockParts.join(', ')})`);
    }

    const insertCols = cols.slice();
    if (hasLat && hasLng) insertCols.push('location');
    // Build conflict update clause dynamically for available columns
    const updateAssignments = insertCols.filter(c => c !== 'id').map(c => `${c} = EXCLUDED.${c}`);
    const insertSql = `INSERT INTO products (${insertCols.join(',')}) VALUES ${valueBlocks.join(', ')} ON CONFLICT (id) DO UPDATE SET ${updateAssignments.join(', ')};`;

    try {
      await pg.query('BEGIN');
      await pg.query(insertSql, params);
      await pg.query('COMMIT');
      total += batch.length;
      console.log(`Inserted ${total} rows`);
    } catch (err) {
      await pg.query('ROLLBACK');
      console.error('Insert error', err);
      throw err;
    } finally {
      clearBatch();
    }
  }

  stream.on('data', row => {
    batch.push(row);
    if (batch.length >= BATCH_SIZE) {
      stream.pause();
      flushBatch().then(() => stream.resume()).catch(err => {
        console.error('Fatal', err);
        process.exit(1);
      });
    }
  });

  stream.on('end', async () => {
    try {
      if (batch.length) await flushBatch();
      console.log('Done, total:', total);
      await pg.end();
      mysqlConn.end();
    } catch (err) {
      console.error(err);
      process.exit(1);
    }
  });

  stream.on('error', err => {
    console.error('MySQL stream error', err);
    process.exit(1);
  });
}

main().catch(err => { console.error(err); process.exit(1); });
