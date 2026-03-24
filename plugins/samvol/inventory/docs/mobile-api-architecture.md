# InventPro Mobile API Architecture

## Layers

- `controllers/api/*`: transport layer for REST endpoints (`/api/v1/*`).
- `classes/Api/*`: token lifecycle, middleware, image optimization.
- `models/*`: domain and persistence models.
- `updates/*`: schema migrations for mobile and auth token storage.

## Authentication

- Access token: JWT (HS256, short TTL).
- Refresh token: opaque string stored as SHA-256 hash in DB.
- Refresh rotation: old refresh token is revoked and replaced.
- ACL: `api.scope` middleware checks endpoint-level scopes.

## API Conventions

- All responses use a single envelope: `success`, `data`, `meta`, `error`.
- `meta.request_id` is always present and mirrored as `X-Request-Id` response header.
- Errors use deterministic codes: `AUTH_BEARER_REQUIRED`, `AUTH_TOKEN_INVALID`, `AUTH_TOKEN_EXPIRED`, `VALIDATION_ERROR`, `RESOURCE_UNAVAILABLE`, `SERVER_ERROR`.
- Resource endpoints support `page`, `per_page`, `sort_by`, `sort_dir`, `updated_since` (ISO 8601 UTC, exclusive cursor).
- Eager loading is enabled for related entities.

## Sync Contract

- Pull cursor: `updated_since` with exclusive semantics (`updated_at > updated_since`).
- Stable ordering for incremental sync: sorted by requested column plus `id` tie-breaker.
- Write idempotency: send `X-Client-Request-Id`; backend returns replay marker in `meta.idempotency`.
- Partial sync warnings are returned through `meta.partial` / `meta.warnings`.

## Observability

- Every API request logs structured record with `request_id`, path, method, status, duration.
- Middleware and unhandled exceptions are logged with request correlation.

## Performance

- API list scopes: `Product::apiList()`, `Operation::apiList()`, `Document::apiList()`.
- Pagination defaults are enforced and capped.
- Minimal payload mapping per endpoint.

## Files / Media

- Images and documents are uploaded via `/api/v1/media/*`.
- Image optimizer downsizes/compresses images before storage when GD is available.
- API returns direct file URLs to use in mobile clients.

## Realtime (optional)

- Prepare with Laravel broadcasting + queue workers.
- Mobile app can subscribe via WebSocket provider (Pusher-compatible) when enabled.
