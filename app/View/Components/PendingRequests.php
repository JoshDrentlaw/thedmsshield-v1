<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PendingRequests extends Component
{
    public $players;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($players)
    {
        $this->players = $players;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.pending-requests');
    }
}
