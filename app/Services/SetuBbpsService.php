<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SetuBbpsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
     private function baseUrl(): string
    {
        return config('bbps.base_url');
    }

    private function token(): string
    {
        return Cache::remember(
            'setu_bbps_access_token',
            now()->addSeconds(540),
            function () {
                $response = Http::acceptJson()->asJson()->timeout(20)->post($this->baseUrl() . 'auth/token', [
                        'clientID' => config('bbps.client_id'),
                        'secret' => config('bbps.secret'),
                ]);

                $response->throw();

                $token = $response->json('token');

                if (!is_string($token) || $token === '') {
                    throw new RuntimeException(
                        'Setu did not return an access token.'
                    );
                }

                return $token;
            }
        );
    }

    public function request(string $endpoint, array $payload = []): array
    {
        $endpoint = ltrim($endpoint, '/');

        if (str_contains($endpoint, '..')) {
            throw new RuntimeException('Invalid API endpoint.');
        }

        $response = Http::acceptJson()->asJson()->withToken($this->token())->withHeaders([
                'X-PARTNER-ID' => config('bbps.partner_id'),
            ])->timeout(30)->post($this->baseUrl() . $endpoint, $payload);

        $response->throw();

        return $response->json() ?? [];
    }
}
