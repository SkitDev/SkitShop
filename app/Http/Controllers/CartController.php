<?php

namespace App\Http\Controllers;

use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $error= [];

        $duplicata = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id == $request->product_id;
        });
        if($duplicata->isNotEmpty()){
            array_push($error, "Le produit a déjà été ajouté dans le panier!");
        }


        $product = Product::find($request->product_id);

        if($product == null){
            array_push($error, "Merci d'indiquer un Product ID valide !");
        }


        if($error != []){
            return redirect()->route('shop')->with('error', $error);
        }

        Cart::add($product->id, $product->title, 1, $product->price)->associate('App\Product');
        return redirect()->route('shop')->with('success', ['Le produit ' . $request->title . ' viens d\'être ajouté au panier !']);
    }

    public function empty()
    {
        Cart::destroy();
        return redirect()->back()->with('success', ['Votre panier a bien été vidé !']);
    }

    public function index()
    {
        return view('shop.cart.index');
    }
    public function destroy($rowId)
    {
        $product = Cart::get($rowId)->model;
        Cart::remove($rowId);
        return redirect()->back()->with('success', ["L'article \"" . $product->title . "\" a bien été retiré du panier !"]);
    }

    public function update(Request $request, $rowId){
        $data = $request->json()->all();
        $validator =Validator::make($request->all(), [
            'qty' => 'required|numeric|between:1,6'
        ]);

        if($validator->fails()){
            Session::flash('error', ['La quantité du produit doit être compris entre 1 et 6 !']);
            return response()->json(['success' => 'La quantité n\'as pas été mise à jour pour l\'article "' . Cart::get($rowId)->model->title . '" !']);
        }
        Cart::update($rowId, $data['qty']);
        Session::flash('success', ['La quantité a bien été mise à jour pour l\'article "' . Cart::get($rowId)->model->title . '" !']);
        return response()->json(['success' => 'La quantité a bien été mise à jour pour l\'article "' . Cart::get($rowId)->model->title . '" !']);
    }
}
