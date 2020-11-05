<div class="card my-4">
    <div class="card-body <?= $isDm ? 'interactive' : '' ?>" id="creature-body">
        <h1 class="card-title">
            <span class="<?= $isDm ? 'interactive' : '' ?>" id="show-creature-name" contenteditable="true">
                @csrf
                {{$creature->name}}
            </span>
            @if($creature->marker)
                <small class="text-muted">{{$creature->marker->map->name}}</small>
            @endif
        </h1>
        <div id="show-creature-editor-container" class="d-none">
            <span>Last updated: <em id="show-creature-save-time">{{$lastUpdated->format('c')}}</em></span>
            <div id="show-creature-body-editor" class="">
                {!!$creature->body!!}
            </div>
            <button type="button" id="show-creature-change-view-btn" class="btn btn-secondary mt-4">Change view</button>
        </div>

        <div id="show-creature-body-display">
            {!!$creature->body!!}
        </div>
    </div>
</div>

@section('component-scripts')
    <script type="module" src="{{ asset('js/show-creature.js') . '?' . time() }}"></script>
@endsection