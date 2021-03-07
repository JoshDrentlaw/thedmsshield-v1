<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CompendiumItem extends Component
{
    public $item;
    public $itemType;
    public $isDm;
    public $lastUpdated;
    public $onMap;
    public $options;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $itemType, $isDm, $lastUpdated, $onMap, $options)
    {
        $this->item = $item;
        $this->itemType = $itemType;
        $this->isDm = $isDm;
        $this->lastUpdated = $lastUpdated;
        $this->onMap = $onMap;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.compendium-item');
    }
}