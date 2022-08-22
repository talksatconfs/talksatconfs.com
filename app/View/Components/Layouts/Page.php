<?php

declare(strict_types=1);

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class Page extends Component
{
    public function __construct(public $title, public $description, public $canonicalurl = '')
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.layouts.page');
    }
}
