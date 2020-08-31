@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="jumbotron text-center">
        <h1 class="display-4">Profile</h1>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Hi, my name is <span data-user-id="{{$user->id}}">{{$user->name}}</span>!
                        <small class="text-muted float-right">Player #{{$user->id}}</small>
                    </h3>
                </div>
                <div class="card-body">
                    <input type="hidden" id="user-id" value="{{$user->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="media">
                                <?php if ($user->avatar_url): ?>
                                <img src="{{$user->avatar_url}}" class="img-thumbnail mr-3" id="edit-avatar" alt="Player profile picture">
                                <?php else: ?>
                                <div style="width:180px;height:180px;padding:1em;" class="img-thumbnail mr-3" id="edit-avatar"><i class="fa fa-user w-100 h-100"></i></div>
                                <?php endif; ?>
                                <div class="media-body">
                                    <h5 class="mt-0"><strong>Bio</strong></h5>
                                    <p id="bio" class="form-control-static">{{$user->bio}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            {{-- CAMPAIGNS CARD --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        Campaigns
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
                            <x-campaign-list-profile :campaign="$campaign" />
                        @empty
                            <p><i>No campaigns...</i></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection --}}
