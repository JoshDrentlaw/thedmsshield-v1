<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShowIdea extends Component
{
    public $idea;
    public $isDm;
    public $lastUpdated;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($idea, $isDm, $lastUpdated)
    {
        $this->idea = $idea;
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
        return view('components.show-idea');
    }
}
