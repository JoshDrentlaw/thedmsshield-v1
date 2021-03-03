<div id="place-body" class="card card-body">
    @csrf
    <h1 class="card-title">
        <span class="show-place-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {{$place->name}}
        </span>
        @if($place->marker && ($isDm || (!$isDm && $place->marker->visible)))
            <br><small class="text-muted d-inline-block mt-3"><i class="fa fa-map-marker-alt mr-2"></i>{{$place->marker->map->name}}</small>
        @endif
    </h1>
    <div class="form-group mt-4">
        @if(!$place->markerless)
            <input id="marker-id" value="{{$place->marker->id}}" type="hidden">
        @endif
        <input id="place-id" value="{{$place->id}}" type="hidden">
        <div class="show-place-editor-container d-none">
            <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
            <div class="show-place-body-editor">
                {!!$place->body!!}
            </div>
            <button type="button" class="show-place-change-view-btn btn btn-secondary mt-4">Change view</button>
        </div>

        <div class="show-place-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {!!$place->body!!}
        </div>
    </div>

    @if($isDm && $onMap)
        <button id="show-to-players" class="mt-2 btn btn-info btn-block" data-id="{{$place->id}}" data-type="places">Show to Players</button>
    @endif

    @if($isDm)
        <div id="place-options" class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Place Options</h4>
                    <?php
                        if ($place->visible == 1) {
                            $visibleBtn = 'success';
                            $visibleIcon = '';
                        } else {
                            $visibleBtn = 'danger';
                            $visibleIcon = '-slash';
                        }
                    ?>
                    <button class="btn btn-{{$visibleBtn}}" id="place-visible"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                </div>
            </div>
        </div>
        @if (!$place->markerless)
            <div id="marker-options" class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Marker Options</h4>
                        <label>Marker Icon</label>
                        <select id="marker-icon-select">
                            <?php
                                $marker = new App\Models\Marker;
                            ?>
                            @foreach($marker->place_icons as $icon)
                                <?php
                                    $text = Str::title(str_replace('-', ' ', $icon));
                                ?>
                                <option value="{{$icon}}">{{$text}}</option>
                            @endforeach
                        </select>
                        <div class="btn-group btn-group-lg mt-3">
                            <?php
                                if ($place->marker->locked == 0) {
                                    $lockBtn = 'success';
                                    $lockIcon = '-open';
                                } else {
                                    $lockBtn = 'danger';
                                    $lockIcon = '';
                                }
                                if ($place->marker->visible == 1) {
                                    $visibleBtn = 'success';
                                    $visibleIcon = '';
                                } else {
                                    $visibleBtn = 'danger';
                                    $visibleIcon = '-slash';
                                }
                            ?>
                            <button id="lock-marker" class="btn btn-{{$lockBtn}}"><i class="fa fa-lock{{$lockIcon}}"></i></button>
                            <button id="marker-visible" class="btn btn-{{$visibleBtn}}" data-type="place"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                        </div>
                        <button id="delete-marker" class="mt-5 btn btn-danger btn-block">Delete Marker</button>
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
        <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
    @endif
@endsection