<?php

declare(strict_types=1);

namespace App\View\Components\Conference;

use Illuminate\View\Component;

class Tags extends Component
{
    public function __construct(public $tags)
    {
    }

    public function render()
    {
        return view('components.conference.tags');
    }
}
