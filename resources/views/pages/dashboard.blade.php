@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="jumbotron text-center">
        <h1 class="display-4">Dashboard</h1>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Hello {{$user->name}}!</h3>
                </div>
                <div class="card-body">
                    This is some text within a card body.
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            {{-- MAPS CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        Maps
                        <button id="add-map" class="btn btn-success float-right" data-toggle="modal" data-target="#add-map-modal">Add map</button>
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- MAP TABLE --}}
                    <table id="map-table" class="table table-stripped">
                        <tbody id="map-rows">
                            @foreach($maps as $map)
                                <tr id="map-{{$map->id}}" class="map-row">
                                    {{-- NAME AND IMAGE --}}
                                    <td>
                                        <a class="map-link" href="/maps/{{$map->map_url}}">
                                            <h4 id="map-name-header-{{$map->id}}">{{$map->map_name}}</h4>
                                            <img id="{{$map->map_url}}" src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="img-thumbnail">
                                        </a>
                                    </td>
                                    {{-- CONFIG --}}
                                    <td>
                                        <button class="btn btn-secondary config-map" data-map-id="{{$map->id}}" data-map-name="{{$map->map_name}}" data-toggle="modal" data-target="#config-map-modal">Configure</button>
                                    </td>
                                    {{-- DELETE --}}
                                    <td>
                                        <button class="btn btn-danger delete-map" data-map-id="{{$map->id}}" data-toggle="modal" data-target="#delete-map-modal">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                    <input type="hidden" id="map-id" name="map-id" value="{{$user->id}}">
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
@endsection

@section('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
