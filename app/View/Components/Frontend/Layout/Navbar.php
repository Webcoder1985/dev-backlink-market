<?php

namespace App\View\Components\Frontend\Layout;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $navType;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($navType)
    {
        $this->navType = $navType;
       
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.frontend.layout.navbar');
    }
}
