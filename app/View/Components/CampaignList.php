<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CampaignList extends Component
{
    public $campaign;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.campaign-list');
    }
}