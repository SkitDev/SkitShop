@extends('layouts.master')

@section('content')
    <div class="jumbotron text-center bg-light">
        <h1 class="display-3">Merci !</h1>
        <p class="lead">Votre commande a bien été prise en compte !</p>
        <hr>
        <p class="lead">
        <a class="btn btn-primary btn-sm" href="{{ route('shop') }}" role="button">Retour a la page d'accueil</a>
        </p>
    </div>
@endsection
