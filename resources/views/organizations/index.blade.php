<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">{{$campaign->name}} Organizations</h1>
    </div>
    <?php
        $markerless = collect([]);
        $mapOrganizations = collect([]);
        foreach ($organizations as $organization) {
            if ($organization->markerless) {
                $markerless->push($organization);
            } else {
                $mapName = $organization->marker->map->name;
                if (!$mapOrganizations->has($mapName)) {
                    $mapOrganizations->put($mapName, collect([]));
                }
                $mapOrganizations[$mapName]->push($organization);
            }
        }
    ?>
    @forelse($mapOrganizations as $name => $map)
        <div class="card mb-3">
            <div class="card-header">{{$name}}</div>
            <ul class="list-group list-group-flush">
                @foreach($map as $organization)
                    <a href="/campaigns/{{$campaign->url}}/compendium/organizations/{{$organization->url}}" class="list-group-item list-group-item-action ellipsis">
                        <strong class="ellipsis">{{$organization->name}}</strong>
                        <span class="ellipsis">
                            {{$organization->description}}
                        </span>
                    </a>
                @endforeach
            </ul>
        </div>
    @empty
    @endforelse
    <div class="card">
        <div class="card-header">Organizations with no marker</div>
        <ul class="list-group list-group-flush">
            @forelse($markerless as $organization)
                <a href="/campaigns/{{$campaign->url}}/compendium/organizations/{{$organization->url}}" class="list-group-item list-group-item-action ellipsis">
                    <strong class="ellipsis">{{$organization->name}}</strong>
                    <span class="ellipsis">
                        {{$organization->description}}
                    </span>
                </a>
            @empty
                <li class="list-group-item">All organizations have markers</li>
            @endforelse
        </ul>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/dashboard.js') . '?' . time() }}"></script> --}}
@endsection