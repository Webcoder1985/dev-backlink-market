<?php

namespace App\View\Components\Frontend\Layout;

use Illuminate\View\Component;

class Login extends Component
{

    //public $loadLogin;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->loadLogin = $loadLogin;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.frontend.layout.login');
    }
}
