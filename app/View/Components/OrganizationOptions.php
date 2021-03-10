<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OrganizationOptions extends Component
{
    public $organization;
    public $isDm;
    public $onMap;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($organization, $isDm, $onMap)
    {
        $this->organization = $organization;
        $this->isDm = $isDm;
        $this->onMap = $onMap;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.organization-options');
    }
}