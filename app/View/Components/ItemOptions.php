<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ItemOptions extends Component
{
    public $item;
    public $isDm;
    public $onMap;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $isDm, $onMap)
    {
        $this->item = $item;
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
        return view('components.item-options');
    }
}