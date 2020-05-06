<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        if(request()->category){
            $products = Product::with('categories')->whereHas('categories', function($query) {
                $query->where('slug', request()->category);
            })->orderBy('created_at', 'DESC')->paginate(6);
        }else{
            $products = Product::with('categories')->orderBy('created_at', 'DESC')->paginate(6);
        }
        return view('shop.index')->with('products', $products);
    }
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('shop.products.show')->with('product', $product);
    }
    public function search()
    {
        request()->validate([
            'q' => 'required|min:3'
        ]);
        $q = request()->input('q');
        $products = Product::where('title', 'like', "%$q%")
            ->orWhere('description', 'like', '%$q%')
            ->paginate(6);
        return view('shop.products.search', ['products' => $products, 'q' => $q]);
    }
}
