@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="mt-3 display-2">
            <small class="text-muted d-block">Welcome to</small>
            The DM's Shield
        </h1>
        <p class="lead mt-3">
            An all-in-one DM prep and session tool.
        </p>
        <div class="row mt-5">
            <div class="col-12">
                <div class="row mb-5">
                    <div class="col-sm-4">
                        <img src="{{ asset('images/map-example.JPG') }}" alt="" class="img-responsive img-thumbnail">
                    </div>
                    <div class="col-sm-8 text-md-left text-sm-center">
                        <h2 class="mt-0">Upload your own custom maps</h2>
                        Upload any image file and then add markers from the places, creatures, NPC's, and organizations from your compendium.
                    </div>
                </div>
                <li class="row mb-5">
                    <div class="col-sm-8 text-md-right text-sm-center">
                        <h2 class="mt-0">View compendium information directly in the maps</h2>
                        All the markers you add are clickable and will display their information in a built in sidebar.
                    </div>
                    <div class="col-sm-4">
                        <img src="{{ asset('images/notes-example.JPG') }}" alt="" class="img-responsive img-thumbnail">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection