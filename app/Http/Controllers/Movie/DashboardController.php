<?php

namespace App\Http\Controllers\Movie;

use App\Models\Movie;
use App\Models\Tape;
use App\Models\Actor;
use App\Models\Category;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Rental;
use App\Services\AuditService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'movies'          => Movie::count(),
            'tapes'           => Tape::count(),
            'available_tapes' => Tape::where('status', 'available')->count(),
            'rented_tapes'    => Tape::where('status', 'rented')->count(),
            'actors'          => Actor::count(),
            'categories'      => Category::count(),
            'users'           => User::count(),
        ];

        $recentLogs = AuditLog::latest('created_at')->take(10)->get();

        // Movies available for rent with cover images (for gallery)
        $availableMovies = Movie::with(['category'])
            ->withCount([
                'tapes as available_tapes_count' => fn($q) => $q->where('status', 'available'),
                'tapes as rented_tapes_count'    => fn($q) => $q->where('status', 'rented'),
            ])
            ->availableForRent()
            ->latest()
            ->take(12)
            ->get();

        // Active rentals for overdue warning
        $overdueCount = Rental::whereNull('returned_at')->where('due_date', '<', today())->count();

        AuditService::log('VIEW', 'Visited dashboard');

        return view('dashboard', compact('stats', 'recentLogs', 'availableMovies', 'overdueCount'));
    }
}