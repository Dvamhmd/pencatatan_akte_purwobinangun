<?php

namespace App\Http\Controllers;

use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'birth_total' => BirthCertificate::count(),
            'birth_completed' => BirthCertificate::where('status', 'completed')->count(),
            'death_total' => DeathCertificate::count(),
            'death_completed' => DeathCertificate::where('status', 'completed')->count(),
        ];

        $recentBirths = BirthCertificate::latest()->take(3)->get();
        $recentDeaths = DeathCertificate::latest()->take(3)->get();

        return view('home', compact('stats', 'recentBirths', 'recentDeaths'));
    }

    public function guidelines()
    {
        return view('information.guidelines');
    }
}
