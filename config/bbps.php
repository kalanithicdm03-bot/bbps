<?php

return [
    'client_id' => env('BBPS_CLIENT_ID'),
    'secret' => env('BBPS_SECRET'),
    'partner_id' => env('BBPS_PARTNER_ID'),
    'agent_id' => env('BBPS_AGENT_ID'),
    'base_url' => rtrim(
        env('BBPS_BASE_URL', 'https://sandbox-coudc.setu.co/api/v2/'),
        '/'
    ) . '/',
];