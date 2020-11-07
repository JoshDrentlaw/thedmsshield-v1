<div id="place-body">
    <h1 class="card-title">
        <span class="<?= $isDm ? 'interactive' : '' ?>" id="show-place-name" contenteditable="true">
            @csrf
            {{$place->name}}
        </span>
        @if($place->marker)
            <small class="text-muted">{{$place->marker->map->name}}</small>
        @endif
    </h1>
    <div id="show-place-editor-container" class="d-none">
        <span>Last updated: <em id="show-place-save-time">{{$lastUpdated->format('c')}}</em></span>
        <div id="show-place-body-editor" class="">
            {!!$place->body!!}
        </div>
        <button type="button" id="show-place-change-view-btn" class="btn btn-secondary mt-4">Change view</button>
    </div>

    <div id="show-place-body-display" class="<?= $isDm ? 'interactive' : '' ?>" contenteditable="true">
        {!!$place->body!!}
    </div>
</div>

@section('component-scripts')
    <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
@endsection