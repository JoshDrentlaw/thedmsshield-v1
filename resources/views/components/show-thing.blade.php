<div id="thing-body">
    <h1 class="card-title">
        <span class="<?= $isDm ? 'interactive' : '' ?>" id="show-thing-name" contenteditable="true">
            @csrf
            {{$thing->name}}
        </span>
        @if($thing->marker)
            <small class="text-muted">{{$thing->marker->map->name}}</small>
        @endif
    </h1>
    <div id="show-thing-editor-container" class="d-none">
        <span>Last updated: <em id="show-thing-save-time">{{$lastUpdated->format('c')}}</em></span>
        <div id="show-thing-body-editor" class="">
            {!!$thing->body!!}
        </div>
        <button type="button" id="show-thing-change-view-btn" class="btn btn-secondary mt-4">Change view</button>
    </div>

    <div id="show-thing-body-display" class="<?= $isDm ? 'interactive' : '' ?>" contenteditable="true">
        {!!$thing->body!!}
    </div>
</div>

@section('component-scripts')
    <script type="module" src="{{ asset('js/show-thing.js') . '?' . time() }}"></script>
@endsection