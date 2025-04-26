@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-3xl font-semibold mb-4">Bienvenue sur VoyageAI</h1>
        <p class="text-gray-700 leading-relaxed">
            VoyageAI est une application pour simplifier vos déplacements en bus.
        </p>

            @if (session('message'))
            
                {{ session('message') }}
            
            @endif
            
    </div>
@endsection