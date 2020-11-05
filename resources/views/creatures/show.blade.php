<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<x-show-creature :creature="$creature" :is-dm="$isDm" :last-updated="$lastUpdated" />
@endsection

@section('scripts')
    <script>
        const creature_id = {!!$creature->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/creatures.js') . '?' . time() }}"></script>
@endsection