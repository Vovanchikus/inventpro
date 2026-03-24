# Mobile Migration Guide (React Native) - API v1.1 Contract

## What changed

- Unified envelope for every `/api/v1/*` response.
- `error` is now an object: `{ code, message, details }`.
- `meta.request_id` and response header `X-Request-Id` are always present.
- Incremental sync uses `updated_since` (ISO 8601 UTC, exclusive cursor).
- Write endpoints support idempotency via `X-Client-Request-Id`.

## Non-breaking parts

- Routes and HTTP methods are unchanged.
- Existing `data` payload shape for core resources is preserved.

## Breaking change

- Error payload shape changed from string to object.

## Client updates required

1. Envelope parser

```ts
export type ApiError = {
    code: string;
    message: string;
    details?: Record<string, unknown>;
};
export type ApiEnvelope<T> = {
    success: boolean;
    data: T | null;
    meta: { request_id?: string; [k: string]: unknown };
    error: ApiError | null;
};
```

2. Auth handling

- `AUTH_BEARER_REQUIRED`, `AUTH_TOKEN_INVALID`, `AUTH_TOKEN_EXPIRED` => re-auth flow.
- `AUTH_FORBIDDEN` => show account/role state screen.

3. Sync pull strategy

- Keep checkpoint as max `updated_at` from received page.
- Send next pull with `updated_since=<checkpoint>`.
- Expect deterministic ordering with tie-break by `id`.

4. Sync push strategy

- Include `X-Client-Request-Id` on each write request.
- Reuse same request id when retrying after network failure.
- If `meta.idempotency.replayed=true`, treat as successful duplicate.

## Error-code mapping table

- `AUTH_BEARER_REQUIRED` -> 401 -> missing bearer.
- `AUTH_TOKEN_INVALID` -> 401 -> invalid/revoked token.
- `AUTH_TOKEN_EXPIRED` -> 401 -> expired access token.
- `VALIDATION_ERROR` -> 422 -> invalid input.
- `RESOURCE_UNAVAILABLE` -> 404 -> not found/unavailable.
- `SERVER_ERROR` -> 500 -> retry with backoff and report `request_id`.
