<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">{{$campaign->name}} Places</h1>
    </div>
    <?php
        $markerless = collect([]);
        $mapPlaces = collect([]);
        foreach ($places as $place) {
            if ($place->markerless) {
                $markerless->push($place);
            } else {
                $mapName = $place->marker->map->name;
                if (!$mapPlaces->has($mapName)) {
                    $mapPlaces->put($mapName, collect([]));
                }
                $mapPlaces[$mapName]->push($place);
            }
        }
    ?>
    @forelse($mapPlaces as $name => $map)
        <div class="card mb-3">
            <div class="card-header">{{$name}}</div>
            <ul class="list-group list-group-flush">
                @foreach($map as $place)
                    <a href="/campaigns/{{$campaign->url}}/compendium/places/{{$place->url}}" class="list-group-item list-group-item-action ellipsis">
                        <strong class="ellipsis">{{$place->name}}</strong>
                        <span class="ellipsis">
                            {{$place->description}}
                        </span>
                    </a>
                @endforeach
            </ul>
        </div>
    @empty
    @endforelse
    <div class="card">
        <div class="card-header">Places with no marker</div>
        <ul class="list-group list-group-flush">
            @forelse($markerless as $place)
                <a href="/campaigns/{{$campaign->url}}/compendium/places/{{$place->url}}" class="list-group-item list-group-item-action ellipsis">
                    <strong class="ellipsis">{{$place->name}}</strong>
                    <span class="ellipsis">
                        {{$place->description}}
                    </span>
                </a>
            @empty
                <li class="list-group-item">All places have markers</li>
            @endforelse
        </ul>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/dashboard.js') . '?' . time() }}"></script> --}}
@endsection