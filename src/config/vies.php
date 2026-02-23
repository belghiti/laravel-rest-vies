<?php
return [
    'base_uri' => env('VIES_BASE_URI', 'https://ec.europa.eu/taxation_customs/vies/rest-api'),
    'cache_ttl' => (int) env('VIES_CACHE_TTL', 3600),
];
