<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CompendiumListItem extends Component
{
    public $itemType;
    public $path;
    public $item;
    public $isDm;
    public $campaign;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($itemType, $path, $item, $isDm, $campaign)
    {
        $this->itemType = $itemType;
        $this->path = $path;
        $this->item = $item;
        $this->isDm = $isDm;
        $this->campaign = $campaign;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.compendium-list-item');
    }
}