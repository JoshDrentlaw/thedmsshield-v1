<?php
    $isDm = $isDm ? 1 : 0;
    switch ($path) {
        case 'map':
            $btnGrpFloat = '';
            $titleInline = '';
            $show = ' show';
            break;
        case 'campaign':
            $btnGrpFloat = 'float-right';
            $titleInline = 'd-inline-block';
            $show = '';
            break;
        default:
            $btnGrpFloat = 'float-right';
            $titleInline = 'd-inline-block';
            $show = '';
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
                    <div class="btn-group btn-group-sm mb-2 compendium-btn-group {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#creatureDescription" data-toggle="collapse" aria-expanded="false" aria-controls="creatureDescription">Description</button>
                        <a href="/campaigns/{{$campaign->url}}/compendium/creatures" class="btn btn-primary">All creatures</a>
                        @if ($isDm)
                            <button class="btn btn-success btn-sm" id="new-creature-btn">New creature</button>
                        @endif
                    </div>
                </div>
                <div class="collapse mb-2 compendium-description" id="creatureDescription">
                    <div class="card card-body">
                        <p>A person or creature is any NPC or creature that your players might encounter.</p>
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-creatures-list">
                    <?php
                        $creatures = $campaign->creatures->sortBy(['name', 'asc']);
                    ?>
                    @forelse ($creatures as $creature)
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-creature compendium-item{{$show}}" data-creature-id="{{$creature->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/creatures/' . $creature->url}}>
                            {{$creature->name}}
                        </a>
                    @empty
                        <p class="mb-0 first-item">Add your first creature!</p>
                    @endforelse
                </div>
            </li>
            {{-- PLACES --}}
            <li class="list-group-item">
                <div class="mb-2">
                    <h4 class="mb-2 {{$titleInline}}">
                        <i class="fa fa-map-marked-alt"></i>
                        Places
                    </h4>
                    <div class="btn-group btn-group-sm mb-2 compendium-btn-group {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#placesDescription" data-toggle="collapse" aria-expanded="false" aria-controls="placesDescription">Description</button>
                        <a href="/campaigns/{{$campaign->url}}/compendium/places" class="btn btn-primary">All places</a>
                        @if ($isDm)
                            <button class="btn btn-success" id="new-place-btn">New place</button>
                        @endif
                    </div>
                </div>
                <div class="collapse mb-2 compendium-description" id="placesDescription">
                    <div class="card card-body">
                        <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                        @if ($isDm)
                            <p>While in a map, you can turn places into markers by clicking the "Turn place into marker" button.</p>
                        @endif
                    </div>
                </div>
                <div class="list-group list-group-flush compendium-list-group" id="compendium-places-list">
                    <?php
                        $places = $campaign->places->sortBy(['name', 'asc']);
                    ?>
                    @forelse ($places as $place)
                        <?php
                            $markerId = '';
                            if ($place->marker) {
                                $markerId = ' data-marker-id=' . $place->marker->id . '';
                            }
                        ?>
                        <a class="list-group-item list-group-item-action interactive dmshield-link compendium-place compendium-item{{$show}}"{{$markerId}} data-place-id="{{$place->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/places/' . $place->url}}>
                            {{$place->name}}
                            @if($place->marker)
                                <i class="fa fa-map-marker-alt"></i>
                                <small class="text-muted">{{$place->marker->map->name}}</small>
                            @else
                                @if($path === 'map' && $isDm)
                                    <button class="btn btn-success btn-sm float-right to-marker-btn" data-place-id="{{$place->id}}"><i class="fa fa-map-marker-alt"></i></button>
                                @endif
                            @endif
                        </a>
                    @empty
                        <p class="mb-0 first-item">Add your first place!</p>
                    @endforelse
                </div>
            </li>
        </ul>
    </div>
</div>