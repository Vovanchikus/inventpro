# API Authorization Header Forwarding

## Apache (.htaccess)

Authorization header must be forwarded to PHP.

```apache
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

## Nginx + PHP-FPM

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param HTTP_AUTHORIZATION $http_authorization;
    fastcgi_pass unix:/run/php/php8.2-fpm.sock;
}
```

## Reverse Proxy (Nginx/Traefik)

```nginx
proxy_set_header Authorization $http_authorization;
proxy_set_header X-Request-Id $request_id;
```

## Stability limits for `/api/v1/*`

Use these baseline values to prevent dropped TCP connections during full backfill pulls.

### Apache / Nginx / PHP-FPM timeouts

- Apache: `Timeout 120`, `ProxyTimeout 120`, `RequestReadTimeout header=20-40,MinRate=500 body=20,MinRate=500`
- Nginx: `proxy_read_timeout 120s`, `fastcgi_read_timeout 120s`, `send_timeout 120s`
- PHP-FPM: `request_terminate_timeout = 120s`, `pm.max_children` sized for expected parallel mobile pulls

### PHP memory and buffering

- `memory_limit` at least `256M` for API workers (`512M` for large datasets)
- Keep `zlib.output_compression = Off` for API responses unless verified end-to-end
- Avoid extra output buffering layers for API routes (`output_buffering` default is fine; do not stack proxy buffering aggressively)

### WAF / mod_security

- Disable body/response mutation for `/api/v1/*`
- Exclude API JSON endpoints from HTML/error-page injection rules
- Ensure large query strings with sync params (`updated_since`, pagination) are not blocked

### Keep-alive and upstream behavior

- Keep upstream keep-alive enabled (`keepalive_timeout 65` on Nginx baseline)
- Do not close upstream connection on slow client reads for API JSON
- Preserve `X-Request-Id` through all proxies for traceability

## Verification checklist

1. Call `GET /api/v1/auth/health` and confirm `200` JSON envelope.
2. Call `GET /api/v1/auth/me` without token and confirm `401` with `AUTH_BEARER_REQUIRED`.
3. Call `GET /api/v1/auth/me` with valid bearer and confirm `200`.
4. Confirm response header `X-Request-Id` is present.
5. Run full backfill pull `updated_since=1970-01-01T00:00:00Z` and confirm no `ERR_NETWORK`.
