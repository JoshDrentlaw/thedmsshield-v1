<?php
use App\Debug\Debug;
use App\Models\Marker;
$isDm = $isDm ? 1 : 0;
?>
@extends('layouts.app')

@section('content')
    <div id="map-container">
        @csrf
        <div id="map-sidebar" class="leaflet-sidebar collapsed">
            <!-- Nav tabs -->
            <div class="leaflet-sidebar-tabs">
                <ul role="tablist"> <!-- top aligned tabs -->
                    <li><a href="#the-table" role="tab" class="sidebar-tab-link"><i class="fa fa-users"></i></a></li>
                    <li><a href="#die-rollers" role="tab" class="sidebar-tab-link"><i class="fa fa-dice-d20"></i></a></li>
                    <li><a href="#compendium" role="tab" class="sidebar-tab-link"><i class="fa fa-book"></i></a></li>
                    <li class="d-none"><a href="#marker" role="tab" class="sidebar-tab-link"><i class="fa fa-map-marker-alt"></i></a></li>
                </ul>
                <!-- bottom aligned tabs -->
                @if($isDm)
                    <ul role="tablist">
                        <li><a href="#map-settings" role="tab" class="sidebar-tab-link"><i class="fa fa-cog"></i></a></li>
                    </ul>
                @endif
            </div>
            <!-- Tab panes -->
            <div class="leaflet-sidebar-content" style="background:#fff;">
                {{-- THE TABLE --}}
                <div class="leaflet-sidebar-pane" id="the-table">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        The Table
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div class="py-3">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div id="logged-in-users-container">
                                    <h2>DM</h2>
                                    <div class="media mb-3" id="user-{{$campaign->dm->user->id}}">
                                        @if($campaign->dm->user->avatar_public_id)
                                            <img src="{{env('CLOUDINARY_IMG_PATH')}}c_thumb,w_100,h_100/v{{date('z')}}/{{$campaign->dm->user->avatar_public_id}}.jpg" class="mr-3">
                                        @else
                                            <div style="width:100px;height:100px;padding:0.25em;" class="img-thumbnail mr-3"><i class="fa fa-user w-100 h-100"></i></div>
                                        @endif
                                        <div class="media-body">
                                            <h3>
                                                {{$campaign->dm->user->username}}
                                            </h3>
                                            <h6>
                                                <span class="badge badge-danger online-indicator">Offline</span>
                                            </h6>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="color" data-user-id="{{$campaign->dm->user->id}}" class="user-map-color map-color-picker" value="{{$campaign->dm->user->map_color}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h2>Players</h2>
                                    @forelse($campaign->players as $player)
                                        <div class="media mb-3" id="user-{{$player->user->id}}">
                                            @if($player->user->avatar_public_id)
                                                <img src="{{env('CLOUDINARY_IMG_PATH')}}c_thumb,w_100,h_100/v{{date('z')}}/{{$player->user->avatar_public_id}}.jpg" class="mr-3">
                                            @else
                                                <div style="width:100px;height:100px;padding:0.25em;" class="img-thumbnail mr-3"><i class="fa fa-user w-100 h-100"></i></div>
                                            @endif
                                            <div class="media-body">
                                                <h3>
                                                    {{$player->user->username}}
                                                </h3>
                                                <h6>
                                                    <span class="badge badge-danger online-indicator">Offline</span>
                                                </h6>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="color" data-user-id="{{$player->user->id}}" class="user-map-color map-color-picker" value="{{$player->user->map_color}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- DIE ROLLER --}}
                <div class="leaflet-sidebar-pane" id="die-rollers">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        Die Rollerz
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div class="py-3">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <form>
                                    <div class="form-row die-roll-group">
                                        <div class="col-auto my-2">
                                            <input type="number" value="1" min="1" style="width:55px;" class="die-amount form-control">
                                        </div>
                                        <div class="col-auto my-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text">D</label>
                                                </div>
                                                <select class="custom-select die-select">
                                                    <option value="4" selected>4</option>
                                                    <option value="6">6</option>
                                                    <option value="8">8</option>
                                                    <option value="10">10</option>
                                                    <option value="12">12</option>
                                                    <option value="20">20</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-auto my-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text">+</label>
                                                </div>
                                                <input type="number" value="0" min="1" style="width:55px;" class="mod-amount form-control">
                                            </div>
                                        </div>
                                        <div class="col-auto my-2">
                                            <button type="button" class="btn btn-primary" id="die-roll-btn">Roll!!</button>
                                        </div>
                                        <div class="col-auto my-2">
                                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="fa fa-angle-double-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="form-row die-roll-group">
                                            <div class="col-auto my-2">
                                                <input type="number" value="1" min="1" style="width:55px;" class="die-amount form-control">
                                            </div>
                                            <div class="col-auto my-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">D</label>
                                                    </div>
                                                    <select class="custom-select die-select">
                                                        <option value="4" selected>4</option>
                                                        <option value="6">6</option>
                                                        <option value="8">8</option>
                                                        <option value="10">10</option>
                                                        <option value="12">12</option>
                                                        <option value="20">20</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto my-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">+</label>
                                                    </div>
                                                    <input type="number" value="0" min="1" style="width:55px;" class="mod-amount form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 w-100 border rounded">
                                        <ul class="list-unstyled mb-0 p-2" id="chat-message-list" style="box-shadow:inset -3px -11px 15px 0px #e2e2e2;">
                                            @forelse($messages->sortByDesc('created_at') as $message)
                                                <li class="media border rounded mb-4">
                                                    <div class="media-body">
                                                        <h5 class="my-0 p-2">{!!$message->message!!}</h5>
                                                        <div class="media p-2 border-top" style="background:#e9ecef;">
                                                            <div class="mr-2">
                                                                @if($message->user->avatar_public_id)
                                                                    <img src="{{env('CLOUDINARY_IMG_PATH')}}c_thumb,w_25,h_25/v{{date('z')}}/{{$message->user->avatar_public_id}}.jpg" alt="User avatar" class="rounded">
                                                                @else
                                                                    <div style="width:25px;height:25px;padding:0.25em;" class="rounded border"><i class="fa fa-user w-100 h-100"></i></div>
                                                                @endif
                                                            </div>
                                                            <div class="media-body d-flex align-items-center" style="height:25px;">
                                                                <span>{{$message->user->username}} <span class="chat-timestamp">{{$message->created_at->format('c')}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <p>Be the first to send a message</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                </form>         
                            </div>
                        </div>
                    </div>
                </div>
                {{-- COMPENDIUM --}}
                <div class="leaflet-sidebar-pane px-0" id="compendium">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between" style="padding:0 40px;">
                        Compendium
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div style="overflow-y:auto;overflow-x:hidden;">
                        <x-compendium :campaign="$campaign" :is-dm="$isDm" path="map" />
                    </div>
                </div>
                {{-- MARKER --}}
                <div class="leaflet-sidebar-pane" id="marker">
                    <h1 class="leaflet-sidebar-header mb-4 d-flex align-items-center justify-content-between">
                        <span class="show-place-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>"></span>
                        <div class="leaflet-sidebar-close d-block">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <input id="marker-id" type="hidden">
                    <input id="place-id" type="hidden">
                    <div class="show-place-editor-container d-none">
                        <span>Last updated: <em class="save-time"></em></span>
                        <div class="show-place-body-editor"></div>
                        <button type="button" class="show-place-change-view-btn btn btn-secondary btn-block mt-4">Change view</button>
                    </div>
                
                    <div class="show-place-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>"></div>
                    @if($isDm)
                        <div class="card mt-3">
                            <div class="card-body">
                                <label>Marker Icon</label>
                                <select id="marker-icon-select">
                                    <?php
                                        $marker = new Marker;
                                    ?>
                                    @foreach($marker->place_icons as $icon)
                                        <?php
                                            $text = Str::title(str_replace('-', ' ', $icon));
                                        ?>
                                        <option value="{{$icon}}">{{$text}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button id="delete-marker" class="mt-3 btn btn-danger btn-block">Delete Marker</button>
                    @endif
                </div>
                {{-- MAP SETTINGS --}}
                @if($isDm)
                    <div class="leaflet-sidebar-pane" id="map-settings">
                        <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                            Map Settings
                            <div class="leaflet-sidebar-close">
                                <i class="fa fa-caret-left"></i>
                            </div>
                        </h1>
                        <div class="py-3">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Map Bound Options</h5>
                                            <form id="bounds-form">
                                                <div class="form-group">
                                                    <label for="lat-bound">Lat bound</label>
                                                    <input type="text" class="form-control" id="lat-bound" name="latBound" value="{{$map->height}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="lng-bound">Lng bound</label>
                                                    <input type="text" class="form-control" id="lng-bound" name="lngBound" value="{{$map->width}}">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Player Marker Options</h5>
                                            <div class="form-group">
                                                <label>Player Marker Icon</label>
                                                <select id="player-marker-icon-select">
                                                    <?php
                                                        $marker = new Marker;
                                                    ?>
                                                    @foreach($marker->player_icons as $icon)
                                                        <?php
                                                            $text = Str::title(str_replace('-', ' ', $icon));
                                                        ?>
                                                        <option value="{{$icon}}">{{$text}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Player Marker Color</label>
                                                <input type="color" class="map-color-picker" id="player-marker-color" value="{{$map->player_marker_color}}">
                                            </div>
                                            <div class="form-group" style="display:none;">
                                                <label>Player Marker Selected Color</label>
                                                <input type="color" class="map-color-picker" id="player-marker-selected-color" value="{{$map->player_marker_selected_color}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="map-container-underlay"></div> --}}

    {{-- SHOW CREATURE MODAL --}}
    <div class="modal" id="show-creature-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Creature</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SHOW PLACE MODAL --}}
    <div class="modal" id="show-place-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Place</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SHOW THING MODAL --}}
    <div class="modal" id="show-thing-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SHOW IDEA MODAL --}}
    <div class="modal" id="show-idea-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Idea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW CREATURE MODAL --}}
    <div class="modal" id="new-creature-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Creature</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-create-creature />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="new-creature-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW PLACE MODAL --}}
    <div class="modal" id="new-place-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Place</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-create-place />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="new-place-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW THING MODAL --}}
    <div class="modal" id="new-thing-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Thing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-create-thing />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="new-thing-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW IDEA MODAL --}}
    <div class="modal" id="new-idea-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Idea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-create-idea />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="new-idea-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const mapModel = {!!$map!!}
        const CLOUDINARY_IMG_PATH = '{!!env('CLOUDINARY_IMG_PATH')!!}'
        let mapUrl = '{!!$map_url!!}'
        let map_id = {!!$map->id!!}
        let user_id = {!!$user_id!!}
        let mapWidth = {!!$map->width!!}
        let mapHeight = {!!$map->height!!}
        let markers = {!!json_encode($markers)!!}
        let campaign = {!!$campaign!!}
        let campaign_id = {!!$campaign->id!!}
        let isDm = {!!$isDm!!}
        let place_id = ''
        let thing_id = ''
        let idea_id = ''
        let creature_id = ''
        let sidebar
        let showMessage
        let campaignMapChannel

        new Promise((res, rej) => {
            while (!Echo.socketId) {
                setTimeout(() => {}, 50)
            }
            res(true)
        }).then(() => {
            campaignMapChannel = Echo.join(`campaign-map-${map_id}`)
            .here(users => {
                toggleLoggedInUsers(users, 'Online')
            })
            .joining(user => {
                toggleLoggedInUser(user, 'Online')
            })
            .leaving(user => {
                toggleLoggedInUser(user, 'Offline')
            })
        })

        function toggleLoggedInUsers(users, status) {
            users.forEach(u => {
                toggleLoggedInUser(u, status)
            })
        }

        function toggleLoggedInUser(user, status) {
            const $ind = $(`#user-${user.id}`).find('.online-indicator')
            console.log($ind)

            $ind.toggleClass('badge-success badge-danger')
            $ind.text(status)
        }
    </script>

    <script type="module" src="{{ asset('js/compendium.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/maps.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-thing.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-idea.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-creature.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/die-roller.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/mapChatMessages.js') . '?' . time() }}"></script>
@endsection