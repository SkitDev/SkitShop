@extends('layouts.master')

@section('content')
@if ($products->isNotEmpty())
<h3 class="mt-3 mb-3">{{ $products->total() }} résultat(s) trouvé pour la recherche {{ request()->q }} :</h3>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-6">
                <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <small class="d-inline-block mb-2 text-info font-weight-bold">
                            @foreach ($product->categories as $category)
                                {{ $category->name }}
                            @endforeach
                        </small>
                        <h5 class="mb-0">{{ $product->title }}</h5>
                        <p class="text-justify mb-auto">{{ $product->subtitle }}</p>
                        <strong class="mb-auto">{{ $product->getPrice() }}</strong>
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-info">Voir le produit</a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $products->appends(request()->input())->links() }}
@else
    <h2 class="mt-3 mb-3">Aucun résultat pour la recherche {{ request()->q }} !</h2>
@endif
@endsection
