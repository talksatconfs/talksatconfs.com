<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class ErrorPage extends Component
{
    public function __construct(public $errorcode, public $errortitle, public $errordescription)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.error-page');
    }
}
