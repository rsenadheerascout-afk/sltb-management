<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PassengerLayout extends Component
{
    public function __construct(public string $title = 'My Account') {}

    public function render(): View
    {
        return view('layouts.passenger');
    }
}