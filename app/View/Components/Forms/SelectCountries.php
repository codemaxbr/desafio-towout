<?php

namespace App\View\Components\Forms;

use App\Services\FootballService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCountries extends Component
{
    private FootballService $service;

    /**
     * Create a new component instance.
     */
    public function __construct(FootballService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $countries = $this->service->getCountries();

        return view('components.forms.select-countries', [
            'countries' => $countries
        ]);
    }
}
