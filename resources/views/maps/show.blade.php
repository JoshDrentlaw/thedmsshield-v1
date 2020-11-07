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
                    {{-- <li><a href="#players" role="tab" class="sidebar-tab-link"><i class="fa fa-users"></i></a></li> --}}
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
                    <button id="new-marker" class="mt-3 btn btn-success btn-block">New Marker</button>
                    <div id="marker-list" class="list-group list-group-flush">
                        @foreach($markers as $i => $marker)
                            <button type="button" class="list-group-item list-group-item-action marker-button" data-marker-index="{{$i}}" data-marker-id="{{$marker->id}}">{{$marker->place->name}}</button>
                        @endforeach
                    </div>
                </div>
                {{-- MARKER --}}
                <div class="leaflet-sidebar-pane" id="marker">
                    <h1 class="leaflet-sidebar-header mb-4 d-flex align-items-center justify-content-between">
                        <span id="place-name" class="interactive" contenteditable="true"></span>
                        <div class="leaflet-sidebar-close d-block">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <input id="marker-index" type="hidden">
                    <input id="marker-id" type="hidden">
                    <input id="place-id" type="hidden">
                    <div id="body-container">
                        <div id="editor-container" class="d-none">
                            <span>Last updated: <em id="save-time"></em></span>
                            <div id="" class="place-body-editor"></div>
                            <button type="button" id="change-view-btn" class="btn btn-secondary btn-block mt-4">Change view</button>
                        </div>
                
                        <div id="body-display" class="interactive"></div>
                    </div>
                    <button id="delete-marker" class="mt-3 btn btn-danger btn-block">Delete Marker</button>
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
                {{-- <div class="leaflet-sidebar-pane" id="players">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        Players
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div class="py-3">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <h3>{{$campaign->name}}</h3>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
        let mapUrl = '{!!$map_url!!}'
        let map_id = {!!$map->id!!}
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
    </script>

    <script type="module" src="{{ asset('js/compendium.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/maps.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-thing.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-idea.js') . '?' . time() }}"></script>
    <script type="module" src="{{ asset('js/show-creature.js') . '?' . time() }}"></script>
@endsection