<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <x-compendium-item :item="$organization" itemType="organization" :is-dm="$isDm" :last-updated="$lastUpdated" :on-map="0" />
        </div>
    </div>
</div>

{{-- DELETE MARKER MODAL --}}
<div class="modal" id="delete-marker-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete marker?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This will permanently delete the selected marker.</p>
                <p>Are you sure you want to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="delete-marker">Delete marker</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const organization_id = {!!$organization->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/organizations.js') . '?' . time() }}"></script>
@endsection