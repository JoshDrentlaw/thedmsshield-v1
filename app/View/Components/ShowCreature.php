<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShowCreature extends Component
{
    public $creature;
    public $isDm;
    public $lastUpdated;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($creature, $isDm, $lastUpdated)
    {
        $this->creature = $creature;
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
        return view('components.show-creature');
    }
}
