<?php
    $isDm = Auth::user()->id === $campaign->dm->id;
?>
<div class="col mb-4" id="campaign-{{$campaign->id}}">
    <div class="card campaign-row">
        <a class="dmshield-link" href="/campaigns/{{$campaign->url}}">
            @if ($campaign->cover_public_id)
                <img id="{{$campaign->url}}" src="{{env('CLOUDINARY_IMG_PATH') . 'c_thumb,h_175,w_' . floor(175 * (16 / 9)) . '/v' . time() . '/' . $campaign->cover_public_id . '.jpg'}}" alt="{{$campaign->name}}" class="card-img-top map-image-thumbnail">
            @else
                <div class="card-img-top bg-dark text-light d-flex justify-content-center align-items-center" style="height:175px;font-size:25px;"><span>No image</span></div>
            @endif
        </a>
        <div class="card-body">
            {{-- NAME AND IMAGE --}}
            <a class="dmshield-link card-title" href="/campaigns/{{$campaign->url}}">
                <h4 id="campaign-name-header-{{$campaign->id}}" class="campaign-name-header">{{$campaign->name}}</h4>
            </a>
            <div class="row players-row tight overflow-auto">
                <div class="col-12">
                    <label><strong>Players</strong></label>
                    <div class="row">
                        @forelse($campaign->players as $player)
                            <div class="col-4 col-lg-3">
                                <a class="dmshield-link" href="/profile/{{$player->user->id}}">
                                    <figure class="figure">
                                        @if ($player->user->avatar_public_id)
                                            <img src="{{env('CLOUDINARY_IMG_PATH') . 'c_thumb/v' . time() . '/' . $player->user->avatar_public_id . '.jpg'}}" class="figure-img img-fluid rounded" alt="player avater">
                                        @else
                                            <div style="padding:0.5em;"><i class="w-100 h-100 fa fa-user"></i></div>
                                        @endif
                                        <figcaption class="figure-caption text-center">{{$player->user->name}}</figcaption>
                                    </figure>
                                </a>
                            </div>
                        @empty
                            <div class="col-sm-12">
                                <p><i>No players...</i></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @if ($isDm)
            <div class="btn-group-vertical">
                {{-- CONFIG --}}
                <button class="btn btn-secondary btn-block config-campaign rounded-0" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#config-campaign-modal">Configure</button>
                {{-- PLAYERS --}}
                <button class="btn btn-primary btn-block add-players" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#add-players-modal">Add Players</button>
                {{-- DELETE --}}
                <button class="btn btn-danger btn-block delete-campaign" data-campaign-id="{{$campaign->id}}" data-toggle="modal" data-target="#delete-campaign-modal">Delete</button>
            </div>
        @endif
    </div>
</div>