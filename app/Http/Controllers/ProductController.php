<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\MercadoLivreService;



class ProductController extends Controller
{
    public function create(MercadoLivreService $mercadoLivreService) {
            $accessToken = Session::get('mercadolivre_access_token'); 
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get('https://api.mercadolibre.com/sites/MLB/categories/all');
            
            if ($response->successful()) {
                $categories = $response->json();
            } else if ($response->status() == 401 ) {
                try{
                    $mercadoLivreService->refreshAcessToken(auth()->user());

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken
                    ])->get('https://api.mercadolibre.com/sites/MLB/categories/all');

                    if ($response->successful()) {
                        $categories = $response->json();
                    } else {
                        $categories = [];
                        Log::error("Failed to fetch categories from Mercado Livre", ['response' => $response->body()]);
                    }
        
                } catch (\Exception $e) {
                    return redirect()->route('product.create')->with([
                        'error' => 'Failed to refresh access token'
                    ]);
                };
            }
    
        return view('product.create', compact('categories'));
    }

    public function store(Request $request, MercadoLivreService $mercadoLivreService) {
        $accessToken = Session::get('mercadolivre_access_token');         

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|numeric|gte:0',
            'category' => 'required',
            'image' => 'required'
        ]);


        $attributesValues = array_filter($request['attributes'], function($value) {
            return !empty($value);
        });

        $productData = [
            'title' => $validated['name'],
            'price' => $validated['price'],
            'available_quantity' => $validated['quantity'],
            'currency_id' => 'BRL',
            'condition' => 'new',
            'category_id' => $validated['category'],
            'listing_type_id' => 'bronze',
            "buying_mode" => "buy_it_now",
            'pictures' => [
                [
                    'source' => $validated['image']
                ]
            ],
            'attributes' => array_map(function($value, $id) {
                return ['id' => $id, 'value_name' => $value];
            }, array_keys($attributesValues), array_keys($attributesValues))
            
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('https://api.mercadolibre.com/items', $productData);


        if ($response->status() === 201) {
            $product = $response->json();

            Product::create([
                'name' => $product['title'],
                'price' => $product['price'],
                'quantity' => $product['available_quantity'],
                'category' => $product['category_id'],
                'mercado_livre_id'  => $product['id'],
                'description' => $validated['description'],
                'status' => $product['status'],
                'user_id' => auth()->user()->id,
                'image' => $product['thumbnail']
            ]);

            return redirect()->route('product.create')->with([
                'success' => 'Product created successfully!',
                'productID' => $product['id'],
                'productStatus' => $product['status']
            ]);

            } else if ($response->status() == 401 ) {
                try{
                    $mercadoLivreService->refreshAcessToken(auth()->user());
                    
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken
                    ])->get('https://api.mercadolibre.com/sites/MLB/categories/all');

                    if ($response->successful()) {
                        $categories = $response->json();
                    } else {
                        $categories = [];
                        Log::error("Failed to fetch categories from Mercado Livre", ['response' => $response->body()]);
                    }
        
                } catch (\Exception $e) {
                    return redirect()->route('product.create')->with([
                        'error' => 'Failed to refresh access token'
                    ]);
                };
            } else {
                return redirect()->route('product.create')->with([
                    'error' => $response->json()
                ]);
            }
    }

    public function getCategoryAttributes($categoryID) {
        $accessToken = Session::get('mercadolivre_access_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://api.mercadolibre.com/categories/{$categoryID}/attributes");

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Could not fetch attributes'], 400);
        }
    }
}
