@extends('layouts.app')
@section('title', 'Liste des Trajets')
@section('content')


<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Bus</th>
            <th>Chauffeur</th>
            <th>Étapes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trajets as $trajet)
        <tr>
            <td>{{ $trajet->name }}</td>
            <td>{{ $trajet->bus->name }}</td>
            <td>{{ $trajet->chauffeur->name }}</td>
            <td>
                <ul>
                    @foreach($trajet->sousTrajets as $sousTrajet)
                    <li>{{ $sousTrajet->departure_city }} → {{ $sousTrajet->destination_city }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <a href="{{ route('trajets.edit', $trajet) }}">Modifier</a>
                <form action="{{ route('trajets.destroy', $trajet) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection