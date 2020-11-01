@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="jumbotron text-center">
        <h1 class="display-4">Dashboard</h1>
    </div>
    {{-- USER SECTION --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Hello <span class="interactive" data-user-id="{{$user->id}}" contenteditable="true">{{$user->name}}</span>!
                        <small class="text-muted float-right">Player #{{$user->id}}</small>
                    </h3>
                </div>
                <div class="card-body">
                    <input type="hidden" id="user-id" value="{{$user->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="media">
                                @if ($user->avatar_url)
                                    <img src="{{$user->avatar_url}}" class="img-thumbnail mr-3 interactive" id="edit-avatar" alt="Player profile picture" data-toggle="modal" data-target="#edit-avatar-modal">
                                @else
                                    <div style="width:180px;height:180px;padding:1em;" class="img-thumbnail mr-3 interactive" id="edit-avatar" data-toggle="modal" data-target="#edit-avatar-modal"><i class="fa fa-user w-100 h-100"></i></div>
                                @endif
                                <div class="media-body">
                                    <h5 class="mt-0"><strong>Bio</strong></h5>
                                    <p contenteditable="true" id="bio" class="form-control-static interactive">{{$user->bio}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MESSAGES --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Messages</h3></div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-sm-12">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary">Select all</button>
                                <button class="btn btn-outline-secondary">Mark read</button>
                                <button class="btn btn-outline-secondary">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($user->received_messages as $message)
                        <a href="/message/{{$message->id}}" class="list-group-item list-group-item-action">
                            <div class="row no-gutters">
                                <div class="col-sm-1">
                                    <input type="checkbox" name="message-select" data-id="{{$message->id}}">
                                </div>
                                <div class="col-sm-3"><strong>{{$message->message_title}}</strong></div>
                                <div class="col-sm-8">{{$message->body}}</div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item">
                            <div class="row no-gutters">
                                <div class="col-12">
                                    <i>No messages...</i>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    {{-- CAMPAIGNS --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            {{-- CAMPAIGNS CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        Campaigns
                        <button id="add-campaign" class="btn btn-success float-right" data-toggle="modal" data-target="#add-campaign-modal">Add campaign</button>
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- CAMPAIGN LIST GROUP --}}
                    <div id="campaign-rows" class="list-group">
                        @forelse($campaigns as $campaign)
                            <x-campaign-list :campaign="$campaign" />
                        @empty
                            <p><i>No campaigns...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- EDIT AVATAR MODAL --}}
<div class="modal" id="edit-avatar-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="avatar-upload">
                    <input type="hidden" id="user-id" name="user-id" value="{{$user->id}}">
                    <div class="form-group">
                        <label for="avatar">Select an image to upload.</label>
                        <input type="file" accept=".jpg, .jpeg" class="form-control" name="avatar" id="avatar" required>
                        <small id="emailHelp" class="form-text text-muted">1mb maximum upload size.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="avatar-upload" class="btn btn-primary" id="confirm-add-campaign">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- CONFIG MAP MODAL --}}
<div class="modal" id="config-campaign-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Config <span id="config-campaign-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="config-campaign-id" name="old-campaign-id">
                {{-- REPLACE MAP IMAGE --}}
                <form id="new-campaign-form">
                    <div class="form-group">
                        <label for="new-campaign-image">Change campaign image</label>
                        <div class="input-group">
                            <input type="file" accept=".jpg, .jpeg, .png" name="new-campaign-image" class="form-control" id="new-campaign-image" required>
                            <div class="input-group-append">
                                <button type="submit" form="new-campaign-form" id="new-campaign-btn" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- CHANGE MAP NAME --}}
                <form id="campaign-name-form">
                    <div class="form-group">
                        <label for="campaign-name">Change campaign name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="new-campaign-name" name="new-campaign-name">
                            <div class="input-group-append"><button type="submit" id="campaign-name-btn" class="btn btn-primary">Save</button></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm-change-campaign">Save changes</button>
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

@endsection

@section('scripts')
    <script src="{{ asset('js/dashboard.js') . '?' . time() }}"></script>
@endsection