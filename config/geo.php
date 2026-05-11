
<?php

return [

    'api_url' => env('GEO_API_URL', 'http://ip-api.com/json/'),

    'timeout' => env('GEO_API_TIMEOUT', 5),

    'retries' => env('GEO_API_RETRIES', 1),
];
