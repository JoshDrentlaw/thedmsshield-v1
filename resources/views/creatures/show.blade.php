<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <x-show-creature :creature="$creature" :is-dm="$isDm" :last-updated="$lastUpdated" />
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const creature_id = {!!$creature->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/creatures.js') . '?' . time() }}"></script>
@endsection