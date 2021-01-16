@extends('layouts.app')

@section('content')
<div class="container">
    @csrf
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="jumbotron text-center">
        <h1 class="display-4">Dashboard</h1>
    </div>
    {{-- ANCHOR USER --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Hello <span class="interactive" data-user-id="{{$user->id}}" contenteditable="true">{{$user->username}}</span>!
                        <small class="text-muted float-right">Player #{{$user->id}}</small>
                    </h3>
                </div>
                <div class="card-body">
                    <input type="hidden" id="user-id" value="{{$user->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="media">
                                @if ($user->avatar_public_id)
                                    <img
                                        src="{{env('CLOUDINARY_IMG_PATH') . 'c_thumb,w_180,h_180/v' . time() . '/' . $user->avatar_public_id . '.jpg'}}"
                                        class="img-thumbnail mr-3 interactive"
                                        id="edit-avatar"
                                        alt="Player profile picture"
                                        data-toggle="modal"
                                        data-target="#edit-avatar-modal"
                                    >
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
    {{-- ANCHOR MESSAGES --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md">
            <div class="card">
                <div class="card-header"><h3>Messages</h3></div>
                <div class="card-body">
                    <div class="btn-toolbar" role="toolbar" aria-label="Message toolbar">
                        <div class="btn-group mr-3" role="group" aria-label="Main buttons">
                            <button id="dashboard-message-select-all" class="btn btn-outline-primary">Select all</button>
                            <button id="dashboard-message-mark-read" class="btn btn-outline-secondary check-btn" disabled>Mark read</button>
                            <button id="dashboard-message-delete" class="btn btn-outline-danger check-btn" disabled>Delete</button>
                        </div>
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($user->received_messages as $message)
                        <a href="/message/{{$message->id}}" class="list-group-item list-group-item-action ellipsis">
                            <input class="mr-3 message-select" type="checkbox" name="message-select" data-id="{{$message->id}}" data-message-read="{{$message->read}}">
                            <i class="mr-3 read-icon fa fa-envelope{{($message->read ? '-open-text' : '')}}"></i>
                            @if($message->message_type === 'invite')
                                @if($message->invite->accepted)
                                    <i class="fa fa-check-circle mr-3 text-success"></i>
                                @else
                                    <div class="btn-group mr-3" role="group" aria-label="Invite buttons">
                                        <button class="btn btn-outline-success btn-sm" id="accept" data-invite-id="{{$message->invite_id}}">Accept</button>
                                        <button class="btn btn-outline-danger btn-sm" id="deny" data-invite-id="{{$message->invite_id}}">Deny</button>
                                    </div>
                                @endif
                            @endif
                            <strong class="mr-3 dashboard-message-title ellipsis">{{$message->title}}</strong>
                            {{$message->body}}
                        </a>
                    @empty
                        <div class="list-group-item">
                            <i>No messages...</i>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    {{-- ANCHOR CAMPAIGNS --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md">
            {{-- ANCHOR CAMPAIGNS CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        My Campaigns
                        <button id="new-campaign" class="btn btn-success float-right" data-toggle="modal" data-target="#new-campaign-modal">New campaign</button>
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- ANCHOR CAMPAIGN LIST GROUP --}}
                    <div id="campaign-rows" class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                        @forelse($campaigns as $campaign)
                            <x-campaign-list :campaign="$campaign" />
                        @empty
                            <p class="px-3"><i>No campaigns...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ANCHOR PLAYING IN --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md">
            {{-- ANCHOR PLAYING IN CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>Playing In</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- ANCHOR CAMPAIGN LIST GROUP --}}
                    <div id="campaign-rows" class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                        @forelse($playingIn as $campaign)
                            <x-campaign-list :campaign="$campaign" />
                        @empty
                            <p class="px-3"><i>No campaigns...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ANCHOR NEW CAMPAIGN MODAL --}}
<div class="modal" id="new-campaign-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Campaign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="new-campaign-form">
                    <div class="form-group">
                        <label for="campaign-image-image">Select an image to upload.</label>
                        <input type="file" accept=".jpg, .jpeg, .png" class="form-control" name="campaign-image" id="campaign-image">
                    </div>
                    <div class="form-group">
                        <label for="campaign-name">Campaign name</label>
                        <input type="text" class="form-control" id="campaign-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="campaign-description">Campaign description</label>
                        <textarea type="text" class="form-control" id="campaign-description" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="new-campaign-form" class="btn btn-primary" id="confirm-new-campaign">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- ANCHOR EDIT AVATAR MODAL --}}
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

{{-- ANCHOR CONFIG MAP MODAL --}}
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
                {{-- ANCHOR REPLACE MAP IMAGE --}}
                <form id="campaign-image-form">
                    <div class="form-group">
                        <label for="new-campaign-image">Change campaign image</label>
                        <div class="input-group">
                            <input type="file" accept=".jpg, .jpeg, .png" name="new-campaign-image" class="form-control" id="new-campaign-image" required>
                            <div class="input-group-append">
                                <button type="submit" form="campaign-image-form" id="new-campaign-btn" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- ANCHOR CHANGE MAP NAME --}}
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
            </div>
        </div>
    </div>
</div>

{{-- ANCHOR ADD PLAYERS MODAL --}}
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
    <script src="{{ asset('js/message.js') . '?' . time() }}"></script>
@endsection