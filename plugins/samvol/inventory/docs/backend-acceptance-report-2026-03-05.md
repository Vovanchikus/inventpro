# Backend API Contract Verification Report (2026-03-05)

## 1) Final Response Contract

Unified envelope for all `/api/v1/*` responses:

```json
{
    "success": true,
    "data": {},
    "meta": {
        "request_id": "2fb95b25-d3f2-4af8-b2ac-fdb98b617c17"
    },
    "error": null
}
```

### 200 success (list)

```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 10,
                "name": "Category A",
                "updated_at": "2026-03-05T02:27:17Z"
            }
        ]
    },
    "meta": {
        "request_id": "a07a6a66-50f1-41fa-8b6d-e827c47004ec",
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 1,
            "last_page": 1
        }
    },
    "error": null
}
```

### 200 success (detail)

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Api Test User",
        "email": "api.test@example.com",
        "organization_id": 1,
        "organization_role": "admin",
        "organization_status": "approved"
    },
    "meta": {
        "request_id": "7e2ca2f2-4ac5-4a8f-9216-5d0f7b4fc1de"
    },
    "error": null
}
```

### 401 auth error

```json
{
    "success": false,
    "data": null,
    "meta": {
        "request_id": "5aaef665-4f75-4a11-bc03-96d19cfec30a"
    },
    "error": {
        "code": "AUTH_BEARER_REQUIRED",
        "message": "Bearer token is required",
        "details": {}
    }
}
```

### 422 validation error

```json
{
    "success": false,
    "data": null,
    "meta": {
        "request_id": "4a9f6635-df26-4e75-9fdc-c4a2af2d53f2"
    },
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Validation failed",
        "details": {
            "doc_name": ["The doc name field is required."]
        }
    }
}
```

### 500 server error

```json
{
    "success": false,
    "data": null,
    "meta": {
        "request_id": "d001c79f-d8e4-451f-b651-e8a92552ee06"
    },
    "error": {
        "code": "SERVER_ERROR",
        "message": "Internal server error",
        "details": {}
    }
}
```

## 2) No HTML Exception Page for `/api/v1/*`

Status: `DONE`.

Evidence:

- `routes.php` wraps `/api/v1` in `api.request_context` + `api.exception_json`.
- Catch-all added: `ANY api/v1/{any}` returning JSON envelope with `RESOURCE_UNAVAILABLE` (404).
- `php artisan route:list --path=api/v1` shows fallback route registered.

## 3) Auth Guarantees

### Bearer extraction logic (ordered sources)

1. `Request::bearerToken()`
2. `Authorization` header
3. `HTTP_AUTHORIZATION`
4. `REDIRECT_HTTP_AUTHORIZATION`
5. `X-HTTP_AUTHORIZATION`
6. `X-Authorization` header
7. `getallheaders()['Authorization']`
8. query param `access_token` (as last-resort)
9. body param `access_token` (as last-resort)

All parsed via `parseBearerToken()` using `^Bearer\s+(.+)$`.

### Guaranteed auth failure behavior

- Missing token -> `401` + `AUTH_BEARER_REQUIRED` JSON.
- Invalid token -> `401` + `AUTH_TOKEN_INVALID` JSON.
- Expired token -> `401` + `AUTH_TOKEN_EXPIRED` JSON.
- No 500 expected for auth path (tests cover malformed/expired/missing).

### `GET /api/v1/auth/me` success shape (valid token)

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Api Test User",
        "email": "api.test@example.com",
        "organization_id": 1,
        "organization_role": "admin",
        "organization_status": "approved"
    },
    "meta": {
        "request_id": "e34df339-2ab3-4b1d-ab47-d8ed26b2eb87"
    },
    "error": null
}
```

## 4) Infrastructure Config (Authorization Forwarding)

Reference file: `plugins/samvol/inventory/docs/deployment-auth-forwarding.md`.

### Apache

```apache
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

### Nginx + PHP-FPM

```nginx
location ~ \.php$ {
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param HTTP_AUTHORIZATION $http_authorization;
    fastcgi_pass unix:/run/php/php8.2-fpm.sock;
}
```

### Reverse proxy

```nginx
proxy_set_header Authorization $http_authorization;
proxy_set_header X-Request-Id $request_id;
```

Environment confirmation:

- `dev`: code/config present, runtime behavior observed in local logs.
- `stage`: `PENDING` (needs ops evidence from stage host config + curl proof).
- `prod`: `PENDING` (needs ops evidence from prod host config + curl proof).

## 5) Resource Endpoints Stability

Data source: `storage/logs/system.log` (`api.request`), status `200` samples.

| Endpoint             | Status | Avg response |   Min |   Max | Errors                                   |
| -------------------- | ------ | -----------: | ----: | ----: | ---------------------------------------- |
| `/api/v1/products`   | 200    |     46.22 ms | 38 ms | 53 ms | no recent errors in latest sample window |
| `/api/v1/categories` | 200    |     43.00 ms | 32 ms | 52 ms | no recent errors in latest sample window |
| `/api/v1/operations` | 200    |     51.78 ms | 38 ms | 69 ms | no recent errors in latest sample window |
| `/api/v1/documents`  | 200    |     46.78 ms | 38 ms | 53 ms | no recent errors in latest sample window |

Example success log lines (02:27):

- `products` status `200`, `duration_ms=39`
- `categories` status `200`, `duration_ms=41`
- `documents` status `200`, `duration_ms=38`
- `operations` status `200`, `duration_ms=52`

### Why `/categories` failed earlier and how fixed

Root cause was not categories business logic itself. A global API envelope bug caused TypeError:

- `ApiResponse::buildMeta()` returned `stdClass` instead of `array` in old revision.
- This broke error response path and cascaded to `500` on protected endpoints (including `/categories`).

Fix:

- `ApiResponse::buildMeta()` now always returns `array`.
- Auth and exception middleware now consistently return JSON errors.
- Latest logs show `/categories` `200` with stable latency.

## 6) Sync Parameters Semantics

Documented in `openapi-v1.yaml` and implemented in controllers.

- `updated_since`:
    - ISO 8601 UTC.
    - Exclusive cursor (`updated_at > updated_since`).
- `page` / `per_page`:
    - paginated responses with `meta.pagination`.
    - `per_page` is capped by backend max.
- `sort_by` / `sort_dir`:
    - whitelist per endpoint.
    - deterministic tie-break with `id` when `sort_by != id`.

Incremental sync stability guarantee:

- For sync reads, backend applies deterministic ordering + exclusive cursor to prevent duplicates/instability across pages.

## 7) Error Codes Map

| Code                   | HTTP | Message (typical)                         |
| ---------------------- | ---: | ----------------------------------------- |
| `AUTH_BEARER_REQUIRED` |  401 | Bearer token is required                  |
| `AUTH_TOKEN_INVALID`   |  401 | Invalid access token / Unauthorized       |
| `AUTH_TOKEN_EXPIRED`   |  401 | Invalid access token (expired token case) |
| `AUTH_FORBIDDEN`       |  403 | Forbidden / Account pending approval      |
| `VALIDATION_ERROR`     |  422 | Validation failed                         |
| `RESOURCE_UNAVAILABLE` |  404 | Resource not found / Endpoint not found   |
| `SERVER_ERROR`         |  500 | Internal server error                     |
| `RATE_LIMITED`         |  429 | (reserved in OpenAPI enum)                |

## 8) Logs / Observability

Confirmed fields in logs:

- `request_id`
- `path`
- `method`
- `status`
- `duration_ms`
- `user_id`
- `organization_id`

Success log example:

```text
[2026-03-05 02:27:09] development.INFO: api.request {"request_id":"314c2350-78ed-4a26-a1b8-d0eecc09e198","path":"/api/v1/categories","method":"GET","status":200,"duration_ms":41,"user_id":2,"organization_id":1}
```

Error log example (historical, before fix):

```text
[2026-03-05 02:11:58] development.ERROR: TypeError: Samvol\Inventory\Classes\Api\ApiResponse::buildMeta(): Return value must be of type array, stdClass returned
```

## 9) OpenAPI / Docs

- File: `plugins/samvol/inventory/docs/openapi-v1.yaml`
- Version: `1.1.0`
- Includes envelope schemas (`SuccessEnvelope`, `ErrorEnvelope`), sync params, auth responses, idempotency header.

Conformance status:

- `PARTIAL-DONE`: envelope/auth/error/sync semantics align with code paths and tests.
- `PENDING`: full runtime schema conformance check on stage/prod traffic via contract tests.

## 10) Acceptance Checklist

- [x] auth works (token middleware + unit tests + auth health endpoint)
- [x] no HTML on API (`/api/v1/{any}` JSON fallback + exception middleware)
- [x] 4 resource endpoints stable 200 (recent logs + avg durations)
- [ ] mobile sync smoke-test end-to-end on target mobile build (`PENDING`, needs RN client run against stage/prod)

## 11) Verification Commands Run

```bash
php artisan route:list --path=api/v1
vendor/bin/phpunit -c plugins/samvol/inventory/phpunit.xml
```

Latest test status:

- `OK (5 tests, 24 assertions)`
