<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoLivreController extends Controller
{

    public function redirectToProvider() {
        $url = 'https://auth.mercadolivre.com.br/authorization';

        $queryParams = [
            'response_type' => 'code',
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'redirect_uri' => env('MERCADO_LIVRE_REDIRECT_URI'),
        ];

        return redirect()->to($url . '?' . http_build_query($queryParams));
    }
    
    public function getAccessToken(Request $request) {
        $code = $request->query('code');
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ])->post('https://api.mercadolibre.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => env('MERCADO_LIVRE_REDIRECT_URI'),
        ]);
        
    if ($response->successful()) {
            // Parse the response JSON to get the tokens
            $tokens = $response->json();

            $request->session()->put('mercadolivre_access_token', $tokens['access_token']);
            $request->session()->put('mercadolivre_refresh_token', $tokens['refresh_token']);

            Log::info('Access token and refresh token received', $tokens);

            auth()->user()->update([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ]);

            // Return the tokens as a response (optional, store them securely)
            return redirect()->route('product.create')->with('success', 'Tokens saved successfully!');

        } else {
            // Log the error and return the error response
            Log::error('Failed to retrieve tokens', ['error' => $response->body()]);
            return response()->json(['error' => 'Failed to retrieve tokens', 'details' => $response->body()], 500);
        }

    }
}
