<div id="creature-body" class="card card-body">
    @csrf
    <h1 class="card-title">
        <span class="show-creature-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {{$creature->name}}
        </span>
        @if($creature->marker)
            <?php
                $hideLocation = ' d-none';
                if (!$isDm && $creature->marker->visible) {
                    $hideLocation = '';
                }
            ?>
            <br><small id="marker-location" class="text-muted mt-3{{$hideLocation}}"><i class="fa fa-map-marker-alt mr-2"></i>{{$creature->marker->map->name}}</small>
        @endif
    </h1>
    <div class="form-group mt-4">
        @if(!$creature->markerless)
            <input id="marker-id" value="{{$creature->marker->id}}" type="hidden">
        @endif
        <input id="creature-id" value="{{$creature->id}}" type="hidden">
        <div class="show-creature-editor-container d-none">
            <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
            <div class="show-creature-body-editor">
                {!!$creature->body!!}
            </div>
            <button type="button" class="show-creature-change-view-btn btn btn-secondary mt-4">Change view</button>
        </div>

        <div class="show-creature-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {!!$creature->body!!}
        </div>
    </div>

    @if($isDm && $onMap)
        <button id="show-to-players" class="mt-2 btn btn-info btn-block" data-id="{{$creature->id}}" data-type="creatures">Show to Players</button>
    @endif

    @if($isDm)
        <div id="creature-options" class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Creature Options</h4>
                    <button class="btn btn-danger" id="creature-visible"><i class="fa fa-eye-slash"></i></button>
                </div>
            </div>
        </div>
        @if(!$creature->markerless)
            <div id="marker-options" class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Marker Options</h4>
                        <label>Marker Icon</label>
                        <select id="marker-icon-select">
                            <?php
                                $marker = new App\Models\Marker;
                            ?>
                            @foreach($marker->creature_icons as $icon)
                                <?php
                                    $text = Str::title(str_replace('-', ' ', $icon));
                                ?>
                                <option value="{{$icon}}">{{$text}}</option>
                            @endforeach
                        </select>
                        <div class="btn-group mt-3">
                            <?php
                                if ($creature->marker->locked == 0) {
                                    $lockBtn = 'success';
                                    $lockIcon = '-open';
                                } else {
                                    $lockBtn = 'danger';
                                    $lockIcon = '';
                                }
                                if ($creature->marker->visible == 1) {
                                    $visibleBtn = 'success';
                                    $visibleIcon = '';
                                } else {
                                    $visibleBtn = 'danger';
                                    $visibleIcon = '-slash';
                                }
                            ?>
                            <button id="lock-marker" class="btn btn-{{$lockBtn}}"><i class="fa fa-lock{{$lockIcon}}"></i></button>
                            <button id="marker-visible" class="btn btn-{{$visibleBtn}}" data-type="creature"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                        </div>
                        <button class="mt-3 btn btn-danger btn-block" data-toggle="modal" data-target="#delete-marker-modal">Delete Marker</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

@section('component-scripts')
    @if(!$onMap)
        <script>
            const isDm = {!!$isDm!!}
        </script>
        <script type="module" src="{{ asset('js/show-creature.js') . '?' . time() }}"></script>
    @endif
@endsection