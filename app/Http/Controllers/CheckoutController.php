<?php

namespace App\Http\Controllers;

use App\Order;
use DateTime;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cart::count() <= 0){
            return redirect()->route('shop');
        }
        Stripe::setApiKey(env('STRIPE_API_KEY', ''));

        $intent = PaymentIntent::create([
            'amount' => round(Cart::total()),
            'currency' => 'eur',
        ]);
        $clientSecret = $intent->client_secret;

        return view('shop.checkout.index')->with('clientSecret', $clientSecret);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->json()->all();

        $order = new Order();

        $order->payment_intent_id = $data['paymentIntent']['id'];
        $order->amount = $data['paymentIntent']['amount'];
        $order->payment_created_at = (new DateTime())
            ->setTimestamp( $data['paymentIntent']['created'])
            ->format('Y-m-d H:i:s');

        $products = [];
        $i = 0;

        foreach (Cart::content() as $product) {
            $products['product_' . $i][] = $product->model->title;
            $products['product_' . $i][] = $product->model->price;
            $products['product_' . $i][] = $product->qty;
            $i++;
        }

        $order->products = serialize($products);
        $order->user_id = Auth()->user()->id;

        $order->save();

        if($data['paymentIntent']['status'] == 'succeeded'){
            Cart::destroy();
            Session::flash('success', ['Votre commande a été traitée avec succès !']);
            return response()->json(['success' => ['Payment Intent Succeeded !']]);
        }else {
            return response()->json(['errors' => ['Payment Intent not Succeeded !']]);
        }
    }
    public function thanks()
    {
        return Session::has('success') ? view('shop.checkout.thanks') : redirect()->route('shop');
    }
}
