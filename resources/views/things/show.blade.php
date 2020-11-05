<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<x-show-thing :thing="$thing" :is-dm="$isDm" :last-updated="$lastUpdated" />
@endsection

@section('scripts')
    <script>
        const thing_id = {!!$thing->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/things.js') . '?' . time() }}"></script>
@endsection