<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MapList extends Component
{
    public $map;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($map)
    {
        $this->map = $map;
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
