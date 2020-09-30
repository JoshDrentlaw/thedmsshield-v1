<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Compendium extends Component
{
    public $campaign;
    public $isDm;
    public $path;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($campaign, $isDm, $path)
    {
        $this->campaign = $campaign;
        $this->isDm = $isDm;
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.compendium');
    }
}
