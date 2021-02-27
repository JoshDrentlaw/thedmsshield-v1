<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShowPlace extends Component
{
    public $place;
    public $isDm;
    public $lastUpdated;
    public $onMap;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($place, $isDm, $lastUpdated, $onMap)
    {
        $this->place = $place;
        $this->isDm = $isDm;
        $this->lastUpdated = $lastUpdated;
        $this->onMap = $onMap;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.show-place');
    }
}