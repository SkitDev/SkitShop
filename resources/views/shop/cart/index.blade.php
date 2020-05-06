@extends('layouts.master')
@section('extra')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @if (Cart::count() > 0)
    <div class="px-4 px-lg-0">
        <div class="pb-5">
          <div class="container">
            <div class="row">
              <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">

                <!-- Shopping cart table -->
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col" class="border-0 bg-light">
                          <div class="text-uppercase">Produit</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                          <div class="text-uppercase">Prix unitaire</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                          <div class="text-uppercase">Quantité</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                          <div class="py-2 text-uppercase">Prix total</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                          <div class="py-2 text-uppercase">Supprimer</div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach (Cart::content() as $product)
                      <tr>
                        <th scope="row" class="border-0">
                          <div class="p-2">
                          <img src="{{ asset('storage/' . $product->model->image) }}" alt="" width="70" class="img-fluid rounded shadow-sm">
                            <div class="ml-3 d-inline-block align-middle">
                              <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">{{ $product->model->title }}</a></h5>
                            </div>
                          </div>
                        </th>
                        <td class="border-0 align-middle"><strong>{{ $product->model->getPrice() }}</strong></td>
                        <td class="border-0 align-middle">
                            <select name="qty" id="qty" class="custom-select" data-id="{{ $product->rowId }}">
                                @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ $i == $product->qty ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </td>
                        <td class="border-0 align-middle"><strong>{{ getPrice($product->subtotal()) }}</strong></td>
                        <td class="border-0 align-middle">
                        <form action="{{ route('cart.destroy', $product->rowId) }}" method="post" id="{{ $product->rowId }}" name="{{ $product->rowId }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer <i class="fa fa-trash"></i></button>
                        </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
              <form action="{{ route('cart.empty') }}" method="post">
                @csrf
                @method('DELETE')
                <span class="font-weight-bold">Vous voulez vider votre panier ?</span>
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Vider le panier</button>
            </form>
                </div>
                <!-- End -->
              </div>
            </div>

            <div class="row py-5 p-4 bg-white rounded shadow-sm">
              <div class="col-lg-12">
                <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Récapitulatif  de votre commande :</div>
                <div class="p-4">
                  <p class="font-italic mb-4">Les taxes sont calculés à partir des valeurs indiquées.</p>
                  <ul class="list-unstyled mb-4">
                  <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Sous total</strong><strong>{{ getPrice(Cart::subtotal()) }}</strong></li>
                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Taxes</strong><strong>{{ getPrice(Cart::tax()) }}</strong></li>
                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                      <h5 class="font-weight-bold">{{ getPrice(Cart::total()) }}</h5>
                    </li>
                </ul><a href="{{ route('checkout.index') }}" class="btn btn-dark rounded-pill py-2 btn-block">Procéder au paiement</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    @else
      <h1>Votre panier est vide !</h1>
    @endif
@endsection

@section('js')
    <script>
        let selects = document.querySelectorAll('#qty');
        Array.from(selects).forEach((element) => {
            element.addEventListener('change', () => {
                const rowId = element.getAttribute('data-id');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(
                    `/cart/${rowId}`,
                    {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                        },
                        method: 'patch',
                        body: JSON.stringify({
                            qty: element.value
                        })
                    }
                ).then((data) => {
                    console.log(data);
                    location.reload();
                }).catch((error) => {
                    console.log(error);
                })
            });
        });
    </script>
@endsection
