# Mobile API Integration (React Native)

## 1. Existing API Endpoints

### Legacy endpoints (`/api/*`)

These endpoints are kept for backwards compatibility with the existing web app.

- `GET /api/products`
- `GET /api/products/{id}`
- `GET /api/operations`
- `GET /api/operations/{id}`
- `GET /api/documents`
- `GET /api/documents/{id}`
- `GET /api/documents/file/{id}` (binary file response)
- `GET /api/categories`
- `GET /api/categories/{id}`
- `GET /api/operation-types`
- `GET /api/operation-types/{id}`
- `GET /api/warehouse-products`
- `GET /api/history`
- `GET /api/counteragents`
- `GET /api/counteragents/{name}`
- `POST /api/upload`
- `GET /api/check-image`
- `GET /api/operation-doc-templates`
- `POST /api/operations/{id}/generate-doc`
- `GET /api/operations/doc-generation-status/{taskId}`
- `GET /api/settings/document-template`
- `POST /api/settings/document-template/field`
- `POST /api/settings/document-template/person/add`
- `POST /api/settings/document-template/person/update`
- `POST /api/settings/document-template/person/delete`
- `POST /api/settings/document-template/person/select`

### Mobile-ready endpoints (`/api/v1/*`)

Use this namespace for React Native.

#### Auth

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/refresh`
- `GET /api/v1/auth/me`
- `POST /api/v1/auth/logout`

#### Resources

- `GET /api/v1/products`
- `GET /api/v1/products/{id}`
- `GET /api/v1/categories`
- `GET /api/v1/categories/{id}`
- `GET /api/v1/operations`
- `POST /api/v1/operations`
- `GET /api/v1/operations/{id}`
- `PATCH /api/v1/operations/{id}`
- `DELETE /api/v1/operations/{id}`
- `GET /api/v1/documents`
- `POST /api/v1/documents`
- `GET /api/v1/documents/{id}`
- `PATCH /api/v1/documents/{id}`
- `DELETE /api/v1/documents/{id}`

#### Media

- `POST /api/v1/media/images`
- `POST /api/v1/media/documents`

#### Realtime

- `POST /api/v1/realtime/auth`
- `GET /api/v1/realtime/health`

#### API docs

- `GET /api/v1/openapi.yaml`
- `GET /api/v1/docs`

## 2. JSON-only behavior

- `/api/v1/*` endpoints return JSON envelopes: `{ success, data, error }`.
- Exception: file downloads in legacy API (for example, `GET /api/documents/file/{id}`) return binary content by design.
- For mobile clients use `/api/v1/*` and file URLs from JSON payloads.

## 3. Authorization model

- Access token: JWT (Bearer).
- Refresh token: opaque token (server stores only SHA-256 hash).
- Refresh rotation supported via `/api/v1/auth/refresh`.

### Login request example

`POST /api/v1/auth/login`

```json
{
    "login": "user@example.com",
    "password": "secret123"
}
```

### Login response example

```json
{
    "success": true,
    "data": {
        "access_token": "<jwt>",
        "token_type": "Bearer",
        "access_token_expires_at": "2026-03-05T08:20:00+00:00",
        "refresh_token": "<opaque_refresh>",
        "refresh_token_expires_at": "2026-04-04T08:20:00+00:00",
        "user": {
            "id": 12,
            "name": "John Doe",
            "email": "user@example.com",
            "organization_id": 3,
            "organization_role": "responsible"
        },
        "scopes": ["inventory.read", "inventory.write"]
    },
    "error": null
}
```

## 4. Data API features for mobile

Implemented on backend:

- Pagination: `page`, `per_page`.
- Filtering: resource-specific filters (`type_id`, `operation_id`, `category_id`, date ranges, etc.).
- Search: `q` for list endpoints.
- Sorting: `sort_by`, `sort_dir` (`asc|desc`) with whitelists.
- Server-side filtering/sorting only (no requirement to filter on mobile).

## 5. Files and media

- Image upload: `POST /api/v1/media/images` (`multipart/form-data`).
- Document upload: `POST /api/v1/media/documents` (`multipart/form-data`).
- Response contains file URLs for mobile rendering/download.

## 6. Realtime support

- Private channel pattern: `private-org.{organization_id}.inventory`.
- Event name: `.inventory.entity.changed`.
- Auth endpoint for websocket provider: `POST /api/v1/realtime/auth`.

## 7. Performance optimizations

- API response caching added for list/detail endpoints (`products`, `categories`, `operations`, `documents`).
- Cache invalidation added on mutations (`operations/documents` CRUD, media uploads where relevant).
- Lazy loading strategy:
    - paginated list endpoints for large data sets;
    - details loaded by ID endpoint only when needed.

## 8. Backend -> Mobile architecture

- React Native app -> `/api/v1/auth/*` for session lifecycle.
- React Native app -> `/api/v1/*` resources for data.
- React Native app -> `/api/v1/media/*` for uploads.
- React Native app -> websocket provider + `/api/v1/realtime/auth` for realtime.

## 9. React Native request examples

### Fetch products with filters/sorting

```javascript
const res = await fetch(
    `${API}/products?page=1&per_page=20&q=printer&sort_by=updated_at&sort_dir=desc`,
    {
        headers: {
            Authorization: `Bearer ${accessToken}`,
            Accept: "application/json",
        },
    },
);
const json = await res.json();
```

### Refresh token

```javascript
const res = await fetch(`${API}/auth/refresh`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Accept: "application/json" },
    body: JSON.stringify({ refresh_token: refreshToken }),
});
const json = await res.json();
```

### Upload image

```javascript
const form = new FormData();
form.append("product_id", String(productId));
form.append("file", {
    uri: imageUri,
    name: "photo.jpg",
    type: "image/jpeg",
});

const res = await fetch(`${API}/media/images`, {
    method: "POST",
    headers: { Authorization: `Bearer ${accessToken}` },
    body: form,
});
const json = await res.json();
```

## 10. Security recommendations

- Store tokens in secure storage (`react-native-keychain` or `expo-secure-store`), not AsyncStorage.
- Always use HTTPS in production.
- Keep access token short-lived and rotate refresh token.
- Use least-privilege scopes and organization role middleware.
- Add rate limiting (`throttle`) on auth endpoints at web server / middleware level.
- Sanitize upload MIME and file-size limits (already partially enforced).

## 11. UI style parity with website

To keep React Native UI visually consistent with the existing web app (buttons, colors, radii, spacing), use:

- `themes/invent-pro/assets/css/variables.css` as token source
- `plugins/samvol/inventory/docs/mobile-ui-style-sync.md` as implementation guide for RN
- `plugins/samvol/inventory/docs/swagger-ui.html` as styled API docs aligned with site look-and-feel
