<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthCertificate;
use App\Models\DeathCertificate;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $birthStats = [
            'total' => BirthCertificate::count(),
            'pending' => BirthCertificate::where('status', 'pending')->count(),
            'in_process' => BirthCertificate::whereIn('status', ['verified', 'in_process'])->count(),
            'completed' => BirthCertificate::where('status', 'completed')->count(),
            'rejected' => BirthCertificate::where('status', 'rejected')->count(),
        ];

        $deathStats = [
            'total' => DeathCertificate::count(),
            'pending' => DeathCertificate::where('status', 'pending')->count(),
            'in_process' => DeathCertificate::whereIn('status', ['verified', 'in_process'])->count(),
            'completed' => DeathCertificate::where('status', 'completed')->count(),
            'rejected' => DeathCertificate::where('status', 'rejected')->count(),
        ];

        $latestBirths = BirthCertificate::latest()->take(5)->get();
        $latestDeaths = DeathCertificate::latest()->take(5)->get();

        return view('admin.dashboard', compact('birthStats', 'deathStats', 'latestBirths', 'latestDeaths'));
    }
}
