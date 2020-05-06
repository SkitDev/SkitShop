@extends('layouts.master')
@section('extra')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="col-md-12">
        <h1>Page de paiement</h1>
        <div class="row">
            <div class="col-md-6">
                <form id="payment-form" class="my-4" action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div id="card-element">
                      <!-- Elements will create input elements here -->
                    </div>
                    <!-- We'll put the error messages in this element -->
                    <div id="card-errors" role="alert"></div>
                    <button id="submit" class="btn btn-success mt-4" type="submit">Payer</button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Récapitulatif  de votre commande :</div>
                <div class="p-4">
                    <p class="font-italic mb-4">Les taxes sont calculés à partir des valeurs indiquées.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Sous total</strong><strong>{{ getPrice(Cart::subtotal()) }}</strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Taxes</strong><strong>{{ getPrice(Cart::tax()) }}</strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                            <h5 class="font-weight-bold">{{ getPrice(Cart::total()) }}</h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY', '')}}");
        const elements = stripe.elements();
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        const card = elements.create("card", { style: style });
        card.mount("#card-element");
        card.addEventListener('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.classList.add('alert', 'alert-warning');
                displayError.textContent = error.message;
            } else {
                displayError.classList.remove('alert', 'alert-warning');
                displayError.textContent = '';
            }
        });

        const submitButton = document.getElementById('submit');

        submitButton.addEventListener('click', (ev) => {
            ev.preventDefault();
            submitButton.disabled = true;
            stripe.confirmCardPayment("{{ $clientSecret }}", {
                payment_method: {
                card: card
            }
            }).then((result) => {
                if (result.error) {
                    console.log(result.error.message);
                    submitButton.disabled = false;
                } else {
                    if (result.paymentIntent.status === 'succeeded') {
                        const paymentIntent = result.paymentIntent;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const form = document.getElementById('payment-form');
                        const url = form.action;
                        const redirect = "{{ route('checkout.thanks') }}";

                        fetch(
                            url,
                            {
                                headers: {
                                    "Content-Type": "application/json",
                                    "Accept": "application/json, text-plain, */*",
                                    "X-Requested-With": "XMLHttpRequest",
                                    "X-CSRF-TOKEN": token
                                },
                                method: 'post',
                                body: JSON.stringify({
                                    paymentIntent: paymentIntent
                                })
                            }
                        ).then((data) => {
                            console.log(data);
                            form.reset();
                            window.location.href = redirect;
                        }).catch((error) => {
                            console.log(error);
                        })
                    }
                }
            });
        });
    </script>
@endsection
