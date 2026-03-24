# Миграция MySQL -> PostgreSQL (PostGIS) для `products` — инструкция

Кратко: в репозитории добавлен `migrate_products.js` — минимальный Node.js-скрипт, который стримит данные из MySQL и вставляет в PostgreSQL с созданием `location` через PostGIS.

1. Установка

```bash
cd c:/ospanel/domains/inventpro-test
npm install
```

2. Настройка

Создайте `.env` или экспортируйте переменные окружения перед запуском:

```bash
export MYSQL_HOST=localhost
export MYSQL_PORT=3306
export MYSQL_USER=mysql_user
export MYSQL_PASSWORD=secret
export MYSQL_DATABASE=mysql_db

export PG_HOST=localhost
export PG_PORT=5432
export PG_USER=pg_user
export PG_PASSWORD=secret
export PG_DATABASE=pg_db

export BATCH_SIZE=1000
```

На Windows используйте `set` или PowerShell `$env:...`.

3. Требования в PostgreSQL

- Расширение PostGIS должно быть включено:

```sql
CREATE EXTENSION IF NOT EXISTS postgis;
```

- Таблица `products` должна существовать и иметь колонку `location geometry(Point,4326)` плюс PK по `id`.

Пример фрагмента SQL для таблицы (адаптируйте под ваш проект):

```sql
CREATE TABLE products (
  id bigint PRIMARY KEY,
  organization_id bigint,
  category_id bigint,
  name text,
  quantity numeric,
  unit text,
  inv_number text,
  price numeric,
  mobile_summary text,
  external_id text,
  slug text,
  latitude double precision,
  longitude double precision,
  location geometry(Point,4326),
  created_at timestamptz,
  updated_at timestamptz
);
CREATE INDEX idx_products_location ON products USING GIST (location);
```

4. Запуск миграции

```bash
npm run migrate:products
```

5. Примечания и грабли

- Скрипт использует `ON CONFLICT (id) DO UPDATE` — при необходимости измените логику.
- Убедитесь, что порядок координат при создании `ST_MakePoint(lng, lat)` соблюдён.
- Для больших объёмов можно улучшить производительность, используя `COPY` и `pg-copy-streams`.
- Если MySQL использует `caching_sha2_password` и `mysql2` не коннектится — создайте временного пользователя с `mysql_native_password`.

Если нужно — адаптирую скрипт под несколько таблиц или сделаю версию с `COPY`.
