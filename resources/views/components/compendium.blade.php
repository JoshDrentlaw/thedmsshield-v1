<?php
    switch ($path) {
        case 'map':
            $btnGrpFloat = '';
            $titleInline = '';
            break;
        case 'campaign':
            $btnGrpFloat = 'float-right';
            $titleInline = 'd-inline-block';
            break;
        default:
            $btnGrpFloat = 'float-right';
            $titleInline = 'd-inline-block';
            break;
    }
?>
<div class="row justify-content-center">
    <div class="col-md-12">
        @if($path === 'campaign')
            <h3 class="card-title">
                <i class="fa fa-book"></i>
                Compendium
            </h3>
        @endif
        <ul class="list-group list-group-flush">
            {{-- CREATURES --}}
            <li class="list-group-item">
                <div class="mb-2">
                    <h4 class="mb-2 {{$titleInline}}">
                        <i class="fa fa-users"></i>
                        Creatures
                    </h4>
                    <div class="btn-group btn-group-sm mb-2 {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#creatureDescription" data-toggle="collapse" aria-expanded="false" aria-controls="creatureDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary btn-sm float-right" id="new-creature-btn">New creature</button>
                        @endif
                    </div>
                </div>
                <div class="collapse mb-2" id="creatureDescription">
                    <div class="card card-body">
                        <p>A person or creature is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-creatures-list">
                    @foreach ($campaign->creatures as $creature)
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-creature compendium-item" data-creature-id="{{$creature->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/creatures/' . $creature->url}}>
                            {{$creature->name}}
                        </a>
                    @endforeach
                </div>
            </li>
            {{-- PLACES --}}
            <li class="list-group-item">
                <div class="mb-2">
                    <h4 class="mb-2 {{$titleInline}}">
                        <i class="fa fa-landmark"></i>
                        Places
                    </h4>
                    <div class="btn-group btn-group-sm mb-2 {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#placesDescription" data-toggle="collapse" aria-expanded="false" aria-controls="placesDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary" id="new-place-btn">New place</button>
                        @endif
                    </div>
                </div>
                <div class="collapse mb-2" id="placesDescription">
                    <div class="card card-body">
                        <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                        @if ($isDm)
                            <p>While in a map, you can turn places into markers by clicking the "Turn place into marker" button.</p>
                        @endif
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-places-list">
                    @foreach ($campaign->places as $place)
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-place compendium-item" data-place-id="{{$place->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/places/' . $place->url}}>
                            {{$place->name}}
                            @if($place->marker)
                                <i class="fa fa-map-marker-alt"></i>
                                <small class="text-muted">{{$place->marker->map->name}}</small>
                            @else
                                @if($path === 'map')
                                    <button class="btn btn-success btn-sm float-right to-marker-btn" data-place-id="{{$place->id}}"><i class="fa fa-map-marker-alt"></i></button>
                                @endif
                            @endif
                        </a>
                    @endforeach
                </div>
            </li>
            {{-- THINGS --}}
            <li class="list-group-item">
                <div class="mb-2">
                    <h4 class="mb-2 {{$titleInline}}">
                        <i class="fa fa-magic"></i>
                        Things
                    </h4>
                    <div class="btn-group btn-group-sm mb-2 {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#thingsDescription" data-toggle="collapse" aria-expanded="false" aria-controls="thingsDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary btn-sm float-right" id="new-thing-btn">New thing</button>
                        @endif
                    </div>
                </div>
                <div class="collapse mb-2" id="thingsDescription">
                    <div class="card card-body">
                        <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-things-list">
                    @foreach ($campaign->things as $thing)
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-thing compendium-item" data-thing-id="{{$thing->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/things/' . $thing->url}}>
                            {{$thing->name}}
                        </a>
                    @endforeach
                </div>
            </li>
            {{-- IDEAS --}}
            {{-- <li class="list-group-item">
                <h4 class="mb-2 {{$titleInline}}">
                    <i class="fa fa-lightbulb"></i>
                    Ideas
                </h4>
                <div class="btn-group btn-group-sm mb-2 {{$btnGrpFloat}}">
                    <button class="btn btn-secondary" data-target="#ideasDescription" data-toggle="collapse" aria-expanded="false" aria-controls="ideasDescription">Description</button>
                    @if ($isDm)
                        <button class="btn btn-primary btn-sm float-right" id="new-idea-btn">New idea</button>
                    @endif
                </div>
                <div class="collapse mb-2" id="ideasDescription">
                    <div class="card card-body">
                        <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-ideas-list">
                    @foreach ($campaign->ideas as $idea)
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-idea" data-idea-id="{{$idea->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/ideas/' . $idea->url}}>
                            {{$idea->name}}
                        </a>
                    @endforeach
                </div>
            </li> --}}
        </ul>
    </div>
</div>