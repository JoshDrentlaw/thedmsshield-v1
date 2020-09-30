<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MapList extends Component
{
    public $map;
    public $isDm;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($map, $isDm)
    {
        $this->map = $map;
        $this->isDm = $isDm;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.map-list');
    }
}
