<?php
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
                    <li><a href="#home" role="tab" class="sidebar-tab-link"><i class="fa fa-bars"></i></a></li>
                    <li><a href="#marker" role="tab" class="sidebar-tab-link"><i class="fa fa-map-marker-alt"></i></a></li>
                    <li><a href="#compendium" role="tab" class="sidebar-tab-link"><i class="fa fa-book"></i></a></li>
                    <li><a href="#die-rollers" role="tab" class="sidebar-tab-link"><i class="fa fa-dice-d20"></i></a></li>
                </ul>
                <!-- bottom aligned tabs
                <ul role="tablist">
                    <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
                </ul> -->
            </div>
            <!-- Tab panes -->
            <div class="leaflet-sidebar-content">
                {{-- ALL MARKERS --}}
                <div class="leaflet-sidebar-pane" id="home">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        All Markers
                        <div class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></div>
                    </h1>
                    @if($isDm)
                        <button id="new-marker" class="mt-3 btn btn-success btn-block">New Marker</button>
                    @endif
                    <div id="marker-list" class="list-group list-group-flush">
                        @if (count($markers) > 0)
                            @foreach($markers as $i => $marker)
                                <button
                                    type="button"
                                    class="list-group-item list-group-item-action marker-list-button"
                                    data-place-id="{{$marker->place->id}}"
                                    data-marker-id="{{$marker->id}}"
                                >
                                    {{$marker->place->name}}
                                </button>
                            @endforeach
                        @endif
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
                        <button id="delete-marker" class="mt-3 btn btn-danger btn-block">Delete Marker</button>
                    @endif
                </div>
                {{-- COMPENDIUM --}}
                <div class="leaflet-sidebar-pane" id="compendium">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        Compendium
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div class="container-fluid py-3">
                        <x-compendium :campaign="$campaign" :is-dm="$isDm" path="map" />
                    </div>
                </div>
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
                                <form id="first-die-form" class="form-inline">
                                    <div class="form-group mb-3 die-roll-group">
                                        <input type="number" value="1" min="1" style="width:100px;" class="die-amount form-control mr-2">
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
                                        <button type="button" class="btn btn-primary" id="die-roll-btn">Roll!!</button>
                                    </div>
                                    <p>
                                        
                                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="fa fa-angle-double-down"></i>
                                        </button>
                                    </p>
                                    <div class="collapse" id="collapseExample">
                                        <div class="form-group mb-3 die-roll-group">
                                            <input type="number" value="1" min="1" style="width:100px;" class="die-amount form-control mr-2">
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
                                    </div>
                                    
                                    <div class="form-group w-100">
                                        <ul class="list-unstyled" id="chat-message-list">
                                            @forelse($messages->sortByDesc('created_at') as $message)
                                                <?php
                                                $timestamp = date('M d, Y, h:i:s A', strtotime($message->created_at));
                                                ?>
                                                <li class="media">
                                                    <div class="media-body">
                                                        <h5 class="mt-0 mb-1">{{$message->message}}</h5>
                                                        <p>{{$message->user->username}} {{$timestamp}}</p>
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
            </div>
        </div>
        <div id="logged-in-users-container"></div>
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
            console.log('got socket id')
            campaignMapChannel = Echo.join(`campaign-map-${map_id}`)
            .here(users => {
                console.log(users)
                showLoggedInUsers(users)
            })
            .joining(user => {
                showLoggedInUser(user)
            })
            .leaving(user => {
                $(`#user-${user.id}`).remove()
            })

            console.log({campaignMapChannel})
        })

        function showLoggedInUsers(users) {
            $('#logged-in-users-container').children().remove()
            users.forEach(u => {
                showLoggedInUser(u)
            })
        }

        function showLoggedInUser(user) {
            let content = `
                <div class="media" id="user-${user.id}">
            `
            if (user.avatar) {
                content += `
                    <img src="${CLOUDINARY_IMG_PATH}c_thumb,w_25,h_25/v${luxon.local().valueOf()}/${user.avatar}.jpg" class="mr-3">
                `
            } else {
                content += `
                    <div style="width:25px;height:25px;padding:0.25em;" class="img-thumbnail mr-3" id="edit-avatar"><i class="fa fa-user w-100 h-100"></i></div>
                `
            }
            content += `
                    <div class="media-body">
                        <h5>${user.username}</h5>
                    </div>
                </div>
            `
            if (user.isDm) {
                $('#logged-in-users-container').prepend(content)
            } else {
                $('#logged-in-users-container').append(content)
            }
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