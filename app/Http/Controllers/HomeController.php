<?php

namespace App\Http\Controllers;

use App\Services\FootballService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private FootballService $service;
    public $countries = [];

    public function __construct(FootballService $service)
    {
        $this->service = $service;
        $this->countries = $service->getCountries();
    }

    public function index(Request $request)
    {
        return view('home', [
            'countries' => $this->countries
        ]);
    }
}
