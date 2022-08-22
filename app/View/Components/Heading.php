<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Heading extends Component
{
    public function __construct(public $title, public $type = '')
    {
    }

    public function render()
    {
        return view('components.heading');
    }
}
