<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">{{$campaign->name}} Creatures</h1>
    </div>
    <?php
        $markerless = collect([]);
        $mapCreatures = collect([]);
        foreach ($creatures as $creature) {
            if ($creature->markerless) {
                $markerless->push($creature);
            } else {
                $mapName = $creature->marker->map->name;
                if (!$mapCreatures->has($mapName)) {
                    $mapCreatures->put($mapName, collect([]));
                }
                $mapCreatures[$mapName]->push($creature);
            }
        }
    ?>
    @forelse($mapCreatures as $name => $map)
        <div class="card mb-3">
            <div class="card-header">{{$name}}</div>
            <ul class="list-group list-group-flush">
                @foreach($map as $creature)
                    <a href="/campaigns/{{$campaign->url}}/compendium/creatures/{{$creature->url}}" class="list-group-item list-group-item-action ellipsis">
                        <strong class="ellipsis">{{$creature->name}}</strong>
                        <span class="ellipsis">
                            {{$creature->description}}
                        </span>
                    </a>
                @endforeach
            </ul>
        </div>
    @empty
    @endforelse
    <div class="card">
        <div class="card-header">Creatures with no marker</div>
        <ul class="list-group list-group-flush">
            @forelse($markerless as $creature)
                <a href="/campaigns/{{$campaign->url}}/compendium/creatures/{{$creature->url}}" class="list-group-item list-group-item-action ellipsis">
                    <strong class="ellipsis">{{$creature->name}}</strong>
                    <span class="ellipsis">
                        {{$creature->description}}
                    </span>
                </a>
            @empty
                <li class="list-group-item">All creatures have markers</li>
            @endforelse
        </ul>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/dashboard.js') . '?' . time() }}"></script> --}}
@endsection