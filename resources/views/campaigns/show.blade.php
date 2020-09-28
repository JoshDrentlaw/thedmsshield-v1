@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="jumbotron text-center">
        <h1 class="display-4">{{$campaign->name}}</h1>
    </div>
    <div class="row players-row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fa fa-user"></i>
                        Players
                        @if ($isDm)
                            <button class="btn btn-primary float-right add-players" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#add-players-modal">Add Players</button>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($campaign->active_players as $player)
                            <div class="col-sm-3">
                                <a class="player-link" href="/profile/{{$player->user->id}}">
                                    <figure class="figure">
                                        @if ($player->user->avatar_url_small)
                                            <img src="{{$player->user->avatar_url_small}}" class="mr-3 figure-img rounded" alt="player avater">
                                        @else
                                            <div style="width:64px;height:64px;padding:0.5em;"><i class="w-100 h-100 fa fa-user"></i></div>
                                        @endif
                                        <figcaption class="figure-caption">{{$player->user->name}}</figcaption>
                                    </figure>
                                </a>
                            </div>
                        @empty
                            <div class="col-sm-3">
                                <p><i>No players...</i></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MAPS --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            {{-- MAPS CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fa fa-map"></i>
                        Maps
                        @if ($isDm)
                            <button id="add-map" class="btn btn-primary float-right" data-toggle="modal" data-target="#add-map-modal">Add map</button>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- MAP LIST GROUP --}}
                    <div id="map-rows" class="list-group">
                        @forelse($maps as $map)
                            <x-map-list :map="$map" :is-dm="$isDm" />
                        @empty
                            <p><i>No maps...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CAMPAIGN COMPENDIUM --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fa fa-book"></i>
                        Campaign Compendium
                    </h3>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h4 class="mb-4 d-inline-block">
                            <i class="fa fa-users"></i>
                            People & Creatures
                        </h4>
                        <div class="btn-group btn-group-sm float-right">
                            <button class="btn btn-secondary" data-target="#peopleDescription" data-toggle="collapse" aria-expanded="false" aria-controls="peopleDescription">Description</button>
                            @if ($isDm)
                                <button class="btn btn-primary btn-sm float-right">New person</button>
                            @endif
                        </div>
                        <div class="collapse" id="peopleDescription">
                            <div class="card card-body">
                                <p>A person or creature is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <h4 class="mb-4 d-inline-block">
                            <i class="fa fa-landmark"></i>
                            Places
                        </h4>
                        <div class="btn-group btn-group-sm float-right">
                            <button class="btn btn-secondary" data-target="#placesDescription" data-toggle="collapse" aria-expanded="false" aria-controls="placesDescription">Description</button>
                            @if ($isDm)
                                <button class="btn btn-primary">New place</button>
                            @endif
                        </div>
                        <div class="collapse" id="placesDescription">
                            <div class="card card-body">
                                <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                                @if ($isDm)
                                    <p>While in a map, you can turn places into markers by clicking the "Turn place into marker" button.</p>
                                @endif
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            {{-- @foreach ($maps as $map)
                                @foreach ($map->markers as $marker)
                                    <a class="list-group-item list-group-item-action" href="#">
                                        <h5>{{$marker->note_title}} <small>{{$map->map_name}}</small></h5>
                                    </a>
                                @endforeach
                            @endforeach --}}
                            @foreach ($campaign->places as $place)
                                <a class="list-group-item list-group-item-action" href="/campaigns/{{$campaign->url}}/compendium/places/{{$place->url}}">
                                    <h5>
                                        {{$place->name}}
                                        @if($place->marker)
                                            <small class="text-muted">{{$place->marker->map->map_name}}</small>
                                        @endif
                                    </h5>
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <li class="list-group-item">
                        <h4 class="mb-4 d-inline-block">
                            <i class="fa fa-magic"></i>
                            Things
                        </h4>
                        <div class="btn-group btn-group-sm float-right">
                            <button class="btn btn-secondary" data-target="#thingsDescription" data-toggle="collapse" aria-expanded="false" aria-controls="thingsDescription">Description</button>
                            @if ($isDm)
                                <button class="btn btn-primary btn-sm float-right">New thing</button>
                            @endif
                        </div>
                        <div class="collapse" id="thingsDescription">
                            <div class="card card-body">
                                <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <h4 class="mb-4 d-inline-block">
                            <i class="fa fa-lightbulb"></i>
                            Ideas
                        </h4>
                        <div class="btn-group btn-group-sm float-right">
                            <button class="btn btn-secondary" data-target="#ideasDescription" data-toggle="collapse" aria-expanded="false" aria-controls="ideasDescription">Description</button>
                            @if ($isDm)
                                <button class="btn btn-primary btn-sm float-right">New idea</button>
                            @endif
                        </div>
                        <div class="collapse" id="ideasDescription">
                            <div class="card card-body">
                                <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
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
                    <input type="hidden" id="map-id" name="map-id" value="{{$dm->id}}">
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
@endsection

@section('scripts')
    <script src="{{ asset('js/campaign.js') }}"></script>
@endsection