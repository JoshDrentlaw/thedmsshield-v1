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
                    <button class="mt-5 btn btn-danger btn-block" data-toggle="modal" data-target="#delete-marker-modal">Delete Marker</button>
                </div>
            </div>
        </div>
    @endif
@endif