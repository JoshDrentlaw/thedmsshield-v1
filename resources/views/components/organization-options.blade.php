@if($isDm)
    <div id="organization-options" class="mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Organization Options</h4>
                <button class="btn btn-danger" id="organization-visible"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>
    </div>
    @if(!$organization->markerless)
        <div id="marker-options" class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Marker Options</h4>
                    <label>Marker Icon</label>
                    <select id="marker-icon-select">
                        <?php
                            $marker = new App\Models\Marker;
                        ?>
                        @foreach($marker->organization_icons as $icon)
                            <?php
                                $text = Str::title(str_replace('-', ' ', $icon));
                            ?>
                            <option value="{{$icon}}">{{$text}}</option>
                        @endforeach
                    </select>
                    <div class="btn-group mt-3">
                        <?php
                            if ($organization->marker->locked == 0) {
                                $lockBtn = 'success';
                                $lockIcon = '-open';
                            } else {
                                $lockBtn = 'danger';
                                $lockIcon = '';
                            }
                            if ($organization->marker->visible == 1) {
                                $visibleBtn = 'success';
                                $visibleIcon = '';
                            } else {
                                $visibleBtn = 'danger';
                                $visibleIcon = '-slash';
                            }
                        ?>
                        <button id="lock-marker" class="btn btn-{{$lockBtn}}"><i class="fa fa-lock{{$lockIcon}}"></i></button>
                        <button id="marker-visible" class="btn btn-{{$visibleBtn}}" data-type="organization"><i class="fa fa-eye{{$visibleIcon}}"></i></button>
                    </div>
                    <button class="mt-3 btn btn-danger btn-block" data-toggle="modal" data-target="#delete-marker-modal">Delete Marker</button>
                </div>
            </div>
        </div>
    @endif
@endif