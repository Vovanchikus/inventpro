<?php

return [
    'api' => [
        'access_ttl_minutes' => env('SAMVOL_API_ACCESS_TTL_MINUTES', 30),
        'refresh_ttl_days' => env('SAMVOL_API_REFRESH_TTL_DAYS', 30),
        'cache_ttl_seconds' => env('SAMVOL_API_CACHE_TTL_SECONDS', 60),
        'max_per_page' => env('SAMVOL_API_MAX_PER_PAGE', 100),
        'idempotency_ttl_seconds' => env('SAMVOL_API_IDEMPOTENCY_TTL_SECONDS', 86400),
    ],
];
