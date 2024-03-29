@extends('layouts.app')

@section('content')
<?php
$img = '';
if ($campaign->cover_public_id) {
    $img = env('CLOUDINARY_IMG_PATH') . 'h_203' . '/v' . time() . '/' . $campaign->cover_public_id . '.jpg';
}
?>
<div class="container">
    <div class="jumbotron text-center campaign-imagetron">
        {{-- <img src="" class="img-fluid" alt="Campaign cover image"> --}}
        <h1 class="display-4">{{$campaign->name}}</h1>
    </div>
    {{-- MAPS --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md">
            {{-- MAPS CARD --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="d-inline-block">
                        <i class="fa fa-map"></i>
                        Maps
                    </h3>
                    @if ($isDm)
                        <button id="add-map" class="btn btn-success" data-toggle="modal" data-target="#add-map-modal">Add map</button>
                    @endif
                </div>
                <div class="card-body">
                    {{-- MAP LIST GROUP --}}
                    <div id="map-rows" class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                        @forelse($maps->sortBy(['created_at', 'desc']) as $map)
                            <x-map-list :map="$map" :is-dm="$isDm" />
                        @empty
                            <p class="px-3"><i>No maps...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row players-row justify-content-center mb-4">
        <div class="col-md">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="d-inline-block">
                        <i class="fa fa-user"></i>
                        Players
                    </h3>
                    @if ($isDm)
                        <button class="btn btn-success float-right add-players" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#add-players-modal">Add Players</button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($campaign->players as $player)
                            <div class="col-6 col-sm-2 col-md-2 col-lg-1">
                                <a class="dmshield-link" href="/profile/{{$player->user->id}}">
                                    <figure class="figure">
                                        @if ($player->user->avatar_public_id)
                                            <img src="{{env('CLOUDINARY_IMG_PATH') . 'c_thumb/v' . time() . '/' . $player->user->avatar_public_id . '.jpg'}}" class="figure-img img-fluid rounded" alt="player avater">
                                        @else
                                            <div style="padding:0.5em;"><i class="w-100 h-100 fa fa-user"></i></div>
                                        @endif
                                        <figcaption class="figure-caption text-center">{{$player->user->name}}</figcaption>
                                    </figure>
                                </a>
                            </div>
                        @empty
                            <div class="col-sm-3">
                                <p class="px-3"><i>No players...</i></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CAMPAIGN COMPENDIUM --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h2>
                <i class="fa fa-book"></i>
                Compendium
            </h2>
        </div>
        <div class="card-body">
            <x-compendium :campaign="$campaign" :is-dm="$isDm" path="campaign" />
        </div>
    </div>
    @if ($isDm)
        {{-- DM TOOLS --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h2>
                    <i class="fa fa-tools"></i>
                    DM Tools
                </h2>
            </div>
            <div class="card-body">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h2 class="d-inline-block">
                            <i class="fa fa-table"></i>
                            Random Tables
                        </h2>
                        <button class="btn btn-success float-right add-random-table" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#new-random-table-modal">
                            New Random Table
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="list-group" id="random-table-list">
                            @forelse($campaign->random_tables as $table)
                                <li class="list-group-item list-group-item-action show-random-table" data-id="{{$table->id}}" data-toggle="modal" data-target="#show-random-table-modal">
                                    {{$table->name}}
                                </li>
                            @empty
                                <p>No random tables...</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ADD MAP MODAL --}}
<div class="modal" id="add-map-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add map</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="map-upload">
                    <input type="hidden" id="campaign-id" name="campaign-id" value="{{$campaign->id}}">
                    <div class="form-group">
                        <label for="map-image">Select an image to upload.</label>
                        <input type="file" accept=".jpg, .jpeg, .png" class="form-control" name="map-image" id="map-image" required>
                    </div>
                    <div class="form-group">
                        <label for="map-name">Map name</label>
                        <input type="text" class="form-control" id="map-name" name="map-name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="map-upload" class="btn btn-primary" id="confirm-add-map">Add map</button>
            </div>
        </div>
    </div>
</div>

{{-- CONFIG MAP MODAL --}}
<div class="modal" id="config-map-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Config <span id="config-map-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="config-map-id" name="old-map-id">
                {{-- REPLACE MAP IMAGE --}}
                <form id="new-map-form">
                    <div class="form-group">
                        <label for="new-map-image">Change map image</label>
                        <div class="input-group">
                            <input type="file" accept=".jpg, .jpeg, .png" name="new-map-image" class="form-control" id="new-map-image" required>
                            <div class="input-group-append">
                                <button type="submit" form="new-map-form" id="new-map-btn" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- CHANGE MAP NAME --}}
                <form id="map-name-form">
                    <div class="form-group">
                        <label for="map-name">Change map name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="new-map-name" name="new-map-name">
                            <div class="input-group-append"><button type="submit" id="map-name-btn" class="btn btn-primary">Save</button></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm-change-map">Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- ADD PLAYERS MODAL --}}
<div class="modal" id="add-players-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Players</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="add-player-campaign-id">
                <div class="form-group">
                    <label for="player-search"><strong>Search for players in our database</strong></label>
                    <small class="form-text text-muted">Search by name or player #</small>
                    <div class="input-group">
                        <select class="custom-select" id="player-search"></select>
                        <div class="input-group-append"><button class="btn btn-primary" id="confirm-add-player">Add Player</button></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pending-invite-list"><strong>Pending Invites</strong></label>
                    <div class="list-group" id="pending-invite-list"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- DELETE MAP MODAL --}}
<div class="modal" id="delete-map-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete map?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="map-id">
                <p>This will permanently delete the selected map and all markers associated with it.</p>
                <p>Are you sure you want to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-map">Delete map</button>
            </div>
        </div>
    </div>
</div>

{{-- NEW COMPENDIUM ITEM MODAL --}}
<div class="modal" id="new-compendium-item-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="new-compendium-item-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <x-create-compendium-item />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="new-compendium-item-submit">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- NEW RANDOM TABLES MODAL --}}
<div class="modal" id="new-random-table-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Random Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <x-create-random-table /> --}}
                <form>
                    <div class="form-group">
                        <label for="random-table-name">Name</label>
                        <input type="text" name="name" id="random-table-name" class="form-control" placeholder="Enter a name for this table" required>
                    </div>
                    <div class="form-group">
                        <label>Table Results</label>
                        <small class="form-text">Your random table will always have a column for that rows target result, but beyond that you can add as many other columns as you'd like.</small>
                        {{-- <small class="form-text">For each row you can set a single number or range of numbers that will select this row.</small> --}}
                        <div class="btn-group my-2" role="group" aria-label="Table tools">
                            <button type="button" class="btn btn-primary btn-sm add-table-column">Add Column</button>
                            <button type="button" class="btn btn-primary btn-sm add-table-row">Add Row</button>
                        </div>
                    </div>
                </form>
                <div id="new-random-table" class="table-bordered"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="new-random-table-submit">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- SHOW RANDOM TABLES MODAL --}}
<div class="modal" id="show-random-table-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="random-table-name-header"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <x-show-random-table /> --}}
                <input type="hidden" id="current-random-table-id">
                <div class="btn-group my-2" role="group" aria-label="Table tools">
                    <button type="button" class="btn btn-primary btn-sm add-table-column">Add Column</button>
                    <button type="button" class="btn btn-primary btn-sm add-table-row">Add Row</button>
                </div>
                <div id="show-random-table" class="table-bordered"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="get-random-table-result">Get Result</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const img_path = '{!!$img_path!!}'
        const campaign = {!!$campaign!!}
        const campaign_id = {!!$campaign->id!!}
    </script>
    <script src="{{ asset('js/campaign.js') . '?' . time() }}"></script>
    <script src="{{ asset('js/compendium.js') . '?' . time() }}"></script>
    <script src="{{ asset('js/randomTables.js') . '?' . time() }}"></script>
@endsection