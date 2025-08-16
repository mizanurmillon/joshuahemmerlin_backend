<?php

namespace App\Services;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;

class ShopwareServices
{
    use ApiResponse;

    function testConnection()
    {
        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', 'http://localhost/store-api/context', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'sw-access-key' => 'jDUPcIRg1Mi7WZQJAm1nFTqhoMc0Eqev',
                ],
            ]);

            echo $response->getBody();

            if ($response->successful()) {
                return $this->success($response->json(), 'Connection successful', 200);
            }

            return $this->error($response->json(), 'Connection failed', $response->status());

        } catch (\Exception $e) {
            return $this->error([], 'Connection failed: ' . $e->getMessage(), 500);
        }

    }

}
