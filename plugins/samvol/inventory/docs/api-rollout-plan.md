# API Rollout Plan (v1.1 Contract)

## Dev

1. Deploy backend changes and run `php artisan winter:up`.
2. Validate auth matrix manually (`/auth/health`, `/auth/me`, `/products`).
3. Run contract tests.

## Staging

1. Enable mobile app build with v1.1 parser.
2. Run smoke scenario:
    - login
    - pull all with `updated_since`
    - create operation offline
    - retry push with same `X-Client-Request-Id`
    - pull verify
3. Observe logs by `request_id` for all 4xx/5xx.

## Canary

1. Route 10% of mobile traffic to new app build.
2. Watch KPIs:
    - 5xx rate
    - auth 401 ratio
    - sync completion rate
    - p95 latency

## Production

1. Roll out mobile build to 100%.
2. Keep elevated logging for 48h.
3. Run post-release audit on top error codes.

## Rollback

1. Revert mobile app parser to previous build.
2. Revert backend to previous commit.
3. Keep DB schema intact (no destructive migration in this change set).
4. Verify `/api/v1/auth/health` and `/api/v1/auth/login` after rollback.
