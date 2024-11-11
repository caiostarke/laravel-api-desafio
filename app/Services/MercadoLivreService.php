<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MercadoLivreService {

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

}