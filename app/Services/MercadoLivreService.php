<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MercadoLivreService {

    public function getAccessToken(User $user) {
        $accessToken = Session::get('mercadolivre_access_token');

        if (!$accessToken) {
            $this->refreshAcessToken($user);

            $accessToken = Session::get('mercadolivre_access_token');
        }

        return $accessToken;
    }

    public function refreshAcessToken(User $user) {
        if ($user->refresh_token) {
            $response = Http::Post('https://api.mercadolibre.com/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
                'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
                'refresh_token' => $user->refresh_token
            ]);

            if ($response->successful()) {
                $tokens = $response->json();

                $user->update([
                    'access_token' => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                ]);

                return true;
            } else {
                Session::forget('mercadolivre_access_token');
                return false;
            }
        }
    }

    public function fetchCategories($accessToken) {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('https://api.mercadolibre.com/sites/MLB/categories/all');

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Failed to fetch categories from Mercado Livre", ['response' => $response->body()]);
        return [];
    }

    public function getCategoryAttributes($categoryID, $accessToken) {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://api.mercadolibre.com/categories/{$categoryID}/attributes");

        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error('Error fetching category attributes', [
                'categoryID' => $categoryID,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            throw new \Exception('Could not fetch category attributes');
        }
    }

    public function createProduct($accessToken, $data) {
        
        return  Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('https://api.mercadolibre.com/items', $data);

    }
}