<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    public $mapElemId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($mapElemId = null)
    {
        $this->mapElemId = $mapElemId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.Footer');
    }
}
