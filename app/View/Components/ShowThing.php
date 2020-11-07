<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShowThing extends Component
{
    public $thing;
    public $isDm;
    public $lastUpdated;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($thing, $isDm, $lastUpdated)
    {
        $this->thing = $thing;
        $this->isDm = $isDm;
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.show-thing');
    }
}
