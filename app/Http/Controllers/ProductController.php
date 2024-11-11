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
        try {
            $accessToken = $mercadoLivreService->getAccessToken(auth()->user());

            $categories = $mercadoLivreService->fetchCategories($accessToken);

            return view('product.create', compact('categories'));
            
        } catch (\Exception $e) {
            Log::error("Error fetching categories: " . $e->getMessage());
            return redirect()->route('product.create')->with(['error' => 'Failed to fetch categories']);
        }
    }

    public function store(Request $request, MercadoLivreService $mercadoLivreService) {
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


        $accessToken = $mercadoLivreService->getAccessToken(auth()->user());

        if (!$accessToken) {
            return redirect()->route('product.create')->withErrors([
                'error' => 'Access token not found'
            ]);
        }

        $response = $mercadoLivreService->createProduct($accessToken, $productData);

        if ($response->status() === 201) {
            $product    = $response->json();

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

        } else {
            return redirect()->route('product.create')->withErrors([
                'error' => $response->json()
            ]);
        }
      
    }

    public function getCategoryAttributes($categoryID, MercadoLivreService $mercadoLivreService) {

        try {
            $accessToken = $mercadoLivreService->getAccessToken(auth()->user());
            $categoryAttributes = $mercadoLivreService->getCategoryAttributes($categoryID, $accessToken);

            return response()->json($categoryAttributes);

        } catch (\Exception $e) {
            Log::error("Error fetching category attributes: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch category attributes'], 400);
        } 
        
    }
        
}
