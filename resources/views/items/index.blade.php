<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">{{$campaign->name}} Items</h1>
    </div>
    <?php
        $markerless = collect([]);
        $mapItems = collect([]);
        foreach ($items as $item) {
            if ($item->markerless) {
                $markerless->push($item);
            } else {
                $mapName = $item->marker->map->name;
                if (!$mapItems->has($mapName)) {
                    $mapItems->put($mapName, collect([]));
                }
                $mapItems[$mapName]->push($item);
            }
        }
    ?>
    @forelse($mapItems as $name => $map)
        <div class="card mb-3">
            <div class="card-header">{{$name}}</div>
            <ul class="list-group list-group-flush">
                @foreach($map as $item)
                    <a href="/campaigns/{{$campaign->url}}/compendium/items/{{$item->url}}" class="list-group-item list-group-item-action ellipsis">
                        <strong class="ellipsis">{{$item->name}}</strong>
                        <span class="ellipsis">
                            {{$item->description}}
                        </span>
                    </a>
                @endforeach
            </ul>
        </div>
    @empty
    @endforelse
    <div class="card">
        <div class="card-header">Items with no marker</div>
        <ul class="list-group list-group-flush">
            @forelse($markerless as $item)
                <a href="/campaigns/{{$campaign->url}}/compendium/items/{{$item->url}}" class="list-group-item list-group-item-action ellipsis">
                    <strong class="ellipsis">{{$item->name}}</strong>
                    <span class="ellipsis">
                        {{$item->description}}
                    </span>
                </a>
            @empty
                <li class="list-group-item">All items have markers</li>
            @endforelse
        </ul>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/dashboard.js') . '?' . time() }}"></script> --}}
@endsection