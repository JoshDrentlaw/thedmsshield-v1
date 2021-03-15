<?php
use App\Debug\Debug;
?>

<div id="compendium-item-container" class="card card-body">
    @csrf
    <input id="item-id" value="{{$item->id}}" type="hidden">
    <input id="item-type" value="{{$itemType}}" type="hidden">
    <h1 class="card-title">
        <span class="show-compendium-item-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {{$item->name}}
        </span>
        @if($item->marker)
            <?php
                $hideLocation = ' d-none';
                if (!$isDm && $item->marker->visible) {
                    $hideLocation = '';
                }
            ?>
            <br><small id="marker-location" class="text-muted mt-3{{$hideLocation}}"><i class="fa fa-map-marker-alt mr-2"></i>{{$item->marker->map->name}}</small>
        @endif
    </h1>

    <div class="form-group mt-4">
        @if(!$item->markerless)
            <input id="marker-id" value="{{$item->marker->id}}" type="hidden">
        @endif
        <div class="show-compendium-item-editor-container d-none">
            <h3>Notes</h3>
            <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
            <div id="all-editor" class="show-compendium-item-body-editor">
                {!!$item->body!!}
            </div>
            @if($isDm)
                <h3 class="mt-3">DM Notes</h3>
                <div id="dm-editor" class="show-compendium-item-dm-note-editor">
                    {!!$item->dm_notes!!}
                </div>
            @endif
            <button type="button" class="show-compendium-item-change-view-btn btn btn-secondary mt-4">Change view</button>
        </div>

        <div class="show-compendium-item-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            <div class="card card-body">
                <h5 class="card-title">Notes</h5>
                <div id="body-content">
                    {!!$item->body!!}
                </div>
            </div>
            @if($isDm)
                <div class="card card-body mt-3">
                    <h5 class="card-title">DM Notes</h5>
                    <div id="dm-note-content">
                        {!!$item->dm_notes!!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isDm && $onMap)
        <button id="show-to-players" class="mt-2 btn btn-info btn-block" data-id="{{$item->id}}" data-type="{{$itemType}}s">Show to Players</button>
    @endif

    @if($isDm)
        <div id="{{$itemType}}-options" class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?= ucwords($itemType) ?> Options</h4>
                    <?php
                        if ($item->visible == 1) {
                            $visibleBtn = 'success';
                            $visibleIcon = '';
                        } else {
                            $visibleBtn = 'danger';
                            $visibleIcon = '-slash';
                        }
                    ?>
                    <button class="btn btn-{{$visibleBtn}}" id="{{$itemType}}-visible"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                    <button class="mt-5 btn btn-danger btn-block" data-toggle="modal" data-target="#delete-compendium-item-modal">Delete <?= ucwords($itemType) ?></button>
                </div>
            </div>
        </div>
        @if (!$item->markerless)
            <div id="marker-options" class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Marker Options</h4>
                        <label>Marker Icon</label>
                        <select id="marker-icon-select">
                            <?php
                                $marker = new App\Models\Marker;
                            ?>
                            @foreach($marker["{$itemType}_icons"] as $icon)
                                <?php
                                    $text = Str::title(str_replace('-', ' ', $icon));
                                ?>
                                <option value="{{$icon}}">{{$text}}</option>
                            @endforeach
                        </select>
                        <div class="btn-group btn-group-lg mt-3">
                            <?php
                                if ($item->marker->locked == 0) {
                                    $lockBtn = 'success';
                                    $lockIcon = '-open';
                                } else {
                                    $lockBtn = 'danger';
                                    $lockIcon = '';
                                }
                                if ($item->marker->visible == 1) {
                                    $visibleBtn = 'success';
                                    $visibleIcon = '';
                                } else {
                                    $visibleBtn = 'danger';
                                    $visibleIcon = '-slash';
                                }
                            ?>
                            <button id="lock-marker" class="btn btn-{{$lockBtn}}"><i class="fa fa-lock{{$lockIcon}}"></i></button>
                            <button id="marker-visible" class="btn btn-{{$visibleBtn}}" data-type="{{$itemType}}"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                        </div>
                        <button class="mt-5 btn btn-danger btn-block" data-toggle="modal" data-target="#delete-marker-modal">Delete Marker</button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- @switch($itemType)
        @case('place')
            <x-place-options :place="$item" :is-dm="$isDm" :on-map="$onMap" />
            @break
        @case('creature')
            <x-creature-options :creature="$item" :is-dm="$isDm" :on-map="$onMap" />
            @break
        @case('item')
            <x-item-options :item="$item" :is-dm="$isDm" :on-map="$onMap" />
            @break
        @case('organization')
            <x-organization-options :organization="$item" :is-dm="$isDm" :on-map="$onMap" />
            @break
    @endswitch --}}
</div>

@section('component-scripts')
    @if(!$onMap)
        <script>
            const isDm = {!!$isDm!!}
            const map_id = 0
            @if(!$item->markerless)
                const icon = '{!!$item->marker->icon!!}'

                markerIconSelect2(icon)

                $(document).on('select2:select', '#marker-icon-select', function () {
                    let id = $('#marker-id').val(),
                        icon = $(this).val()

                    axios.put(`/markers/${id}`, {type: 'icon', icon, map_id})
                        .then(res => {
                            if (res.status === 200) {
                                pnotify.success({ title: 'Map marker icon updated!' })
                            }
                        })
                })

                $(document).on('click', '#lock-marker', function () {
                    const markerId = $('#marker-id').val(),
                        $this = $(this)
                        locked = !$this.hasClass('btn-danger')

                    axios.put(`/markers/${markerId}`, { type: 'lock', map_id, locked })
                        .then(res => {
                            if (res.status === 200) {
                                $this.toggleClass('btn-danger btn-success')
                                $this.children().remove()
                                let icon
                                if (locked) {
                                    icon = 'fa-lock'
                                } else {
                                    icon = 'fa-lock-open'
                                }
                                $this.append(`<i class="fa ${icon}"></i>`)
                            }
                        })
                })

                $(document).on('click', '#marker-visible', function () {
                    const markerId = $('#marker-id').val(),
                        $this = $(this),
                        type = $this.data('type'),
                        visible = !$this.hasClass('btn-success')

                    axios.put(`/markers/${markerId}`, { type: 'visibility', map_id, visible })
                        .then(res => {
                            if (res.status === 200) {
                                $this.toggleClass('btn-danger btn-success')
                                $this.children().remove()
                                let icon
                                if (visible) {
                                    icon = 'fa-eye'
                                } else {
                                    icon = 'fa-eye-slash'
                                }
                                $this.append(`<i class="fa ${icon}"></i>`)
                            }
                        })
                })

                $(document).on('click', '#delete-marker', function() {
                    const markerId = $('#marker-id').val()
                    let compendiumItemId,
                        type,
                        thisMapMarker = mapMarkers.filter(marker => marker.options.id == markerId)[0]

                    if (thisMapMarker.options.type === 'place') {
                        compendiumItemId = $('#place-id').val()
                        type = 'place'
                    } else if (thisMapMarker.options.type === 'creature') {
                        compendiumItemId = $('#creature-id').val()
                        type = 'creature'
                    }
                    axios.delete(`/markers/${markerId}`)
                        .then(res => {
                            if (res.status === 200) {
                                deleteMapMarker(thisMapMarker, compendiumItemId, type)
                            }
                        })
                })
            @endif
        </script>
    @endif
    <script type="module" src="{{ asset('js/' . $itemType . 'Options.js') . '?' . time() }}"></script>
@endsection