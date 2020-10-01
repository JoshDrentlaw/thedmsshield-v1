{{'on page'}}
@extends('layouts.app')
{{'got layout'}}

@section('content')
    {{'loaded content'}}
    <div id="map-container">
        <div class="alert alert-success fixed-top invisible" style="z-index: 10000;" role="alert">
            <h4>Note saved!</h4>
        </div>
        <div id="ajax-message" class="alert fixed-top invisible" style="z-index: 10000;" role="alert">
            <h4 id="alert-message"></h4>
        </div>
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
                <div class="leaflet-sidebar-pane" id="marker">
                    <h1 class="leaflet-sidebar-header mb-4 d-flex align-items-center justify-content-between">
                        <span id="place-name" contenteditable="true"></span>
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
                            <div id="body-editor" class=""></div>
                            <button type="button" id="change-view-btn" class="btn btn-secondary btn-block mt-4">Change view</button>
                        </div>
                
                        <div id="body-display" class="interactive"></div>
                    </div>
                    <button id="delete-marker" class="mt-3 btn btn-danger btn-block">Delete Marker</button>
                </div>
                <div class="leaflet-sidebar-pane" id="compendium">
                    <h1 class="leaflet-sidebar-header d-flex align-items-center justify-content-between">
                        Compendium
                        <div class="leaflet-sidebar-close">
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </h1>
                    <div class="py-3">
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
        @csrf
    </div>
@endsection

@section('scripts')
    <script>
        const mapModel = {!!$map!!}
        let mapUrl = '{!!$map->map_image_url!!}'
        let map_id = {!!$map->id!!}
        let mapWidth = {!!$map->map_width!!}
        let mapHeight = {!!$map->map_height!!}
        let markers = {!!json_encode($markers)!!}
        let campaign_id = {!!$campaign->id!!}
    </script>
    <script type="module" src="{{ asset('js/maps.js') }}"></script>
@endsection