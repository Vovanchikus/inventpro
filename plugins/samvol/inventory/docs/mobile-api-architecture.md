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

- All responses are JSON with `success`, `data`, `error`.
- Resource endpoints support `page`, `per_page`, filters and search.
- Eager loading is enabled for related entities.

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
