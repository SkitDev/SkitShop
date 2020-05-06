@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vos {{count(Auth()->user()->orders)}} commandes</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach (Auth()->user()->orders as $order)
                        <div class="card mb-4">
                            <div class="card-header">
                                Commande passée le {{ Carbon\Carbon::parse($order->payment_created_at)->format('d/m/Y à H:i') }} d'un montant de <strong>{{ getPrice($order->amount) }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nom du produit</th>
                                                <th scope="col">Prix</th>
                                                <th scope="col">Quantité</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (unserialize($order->products) as $product)
                                                <tr>
                                                    <td scope="row">{{$product[0]}}</td>
                                                    <td>{{getPrice($product[1])}}</td>
                                                    <td>{{$product[2]}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
