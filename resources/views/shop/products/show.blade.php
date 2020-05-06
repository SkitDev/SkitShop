@extends('layouts.master')

@section('content')
<div class="row mb-2 mt-3">
    <div class="col-md-12">
        <div
            class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
                <small class="d-inline-block mb-2 text-info font-weight-bold">
                    @foreach($product->categories as $category)
                        {{ $category->name }}
                    @endforeach
                </small>
                <h5 class="mb-0">{{ $product->title }}</h5>
                <p class="text-justify mb-auto">@markdown($product->description)</p>
                <strong class="mb-auto">{{ $product->getPrice() }}</strong>
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-dark">Ajouter au panier</button>
                </form>
            </div>
            <div class="col-auto d-none d-lg-block">
                <img src="{{ asset('storage/' . $product->image) }}" alt="">
            </div>
        </div>
        @if ($product->images)
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="text-center mt-4 w-100">
                <h3>Quelques images du produit</h3>
            </div>
            <div class="col p-4 d-flex flex-column position-static">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach(json_decode($product->images, true) as $image)
                    <div class="carousel-item @if($loop->first) active @endif">
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 img-fluid">
                            </div>
                        @endforeach

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        @endif
        @endsection
