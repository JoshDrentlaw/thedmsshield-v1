<?php
    use App\Debug\Debug;
    $isDm = $isDm ? 1 : 0;
    switch ($path) {
        case 'map':
            $btnGrpFloat = 'btn-group-vertical';
            $titleInline = '';
            $show = ' show-component';
            break;
        case 'campaign':
            $btnGrpFloat = 'btn-group float-right';
            $titleInline = 'd-inline-block';
            $show = '';
            break;
        default:
            $btnGrpFloat = 'btn-group float-right';
            $titleInline = 'd-inline-block';
            $show = '';
            break;
    }
?>
<div class="row justify-content-center">
    <div class="col-12">
        <ul class="list-group list-group-flush mx-n3">
            {{-- CREATURES --}}
            <x-compendium-list-item item-type="creature" :path="$path" :item="$campaign->creatures" :is-dm="$isDm" :campaign="$campaign">
                <x-slot name="header">
                    <i class="fa fa-users"></i>
                    NPC's & Creatures
                </x-slot>
                <x-slot name="description">
                    <p>A person or creature is any NPC or creature that your players might encounter.</p>
                </x-slot>
            </x-compendium-list-item>
            {{-- PLACES --}}
            <x-compendium-list-item item-type="place" :path="$path" :item="$campaign->places" :is-dm="$isDm" :campaign="$campaign">
                <x-slot name="header">
                    <i class="fa fa-map-marked-alt"></i>
                    Places
                </x-slot>
                <x-slot name="description">
                    <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                    <p>While in a map, you can turn places into markers by clicking the "Turn place into marker" button.</p>
                </x-slot>
            </x-compendium-list-item>
            {{-- ORGANIZATIONS --}}
            <x-compendium-list-item item-type="organization" :path="$path" :item="$campaign->organizations" :is-dm="$isDm" :campaign="$campaign">
                <x-slot name="header">
                    <i class="fa fa-users"></i>
                    Organizations
                </x-slot>
                <x-slot name="description">
                    <p>Organization description.</p>
                </x-slot>
            </x-compendium-list-item>
            {{-- ITEMS --}}
            <x-compendium-list-item item-type="item" :path="$path" :item="$campaign->items" :is-dm="$isDm" :campaign="$campaign">
                <x-slot name="header">
                    <i class="fa fa-magic"></i>
                    Items
                </x-slot>
                <x-slot name="description">
                    <p>Item description.</p>
                </x-slot>
            </x-compendium-list-item>
        </ul>
    </div>
</div>