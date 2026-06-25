<?php

namespace App\Http\Controllers\Movie;

use App\Models\Movie;
use App\Models\Rental;
use App\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Top 10 most rented movies (by total rental count)
        $topMovies = Movie::withCount(['tapes as total_rentals' => function ($q) {
                $q->join('rentals', 'rentals.tape_id', '=', 'tapes.id');
            }])
            ->with('category')
            ->orderByDesc('total_rentals')
            ->take(10)
            ->get();

        // Bottom 10 least rented (including 0)
        $lowMovies = Movie::withCount(['tapes as total_rentals' => function ($q) {
                $q->join('rentals', 'rentals.tape_id', '=', 'tapes.id');
            }])
            ->with('category')
            ->orderBy('total_rentals')
            ->take(10)
            ->get();

        // Current active rentals count
        $activeRentals = Rental::whereNull('returned_at')->count();

        // Monthly rental trend (last 6 months)
        $monthlyTrend = Rental::selectRaw("DATE_FORMAT(rented_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('rented_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Overall rental stats
        $totalRentals    = Rental::count();
        $returnedRentals = Rental::whereNotNull('returned_at')->count();
        $overdueRentals  = Rental::whereNull('returned_at')->where('due_date', '<', today())->count();

        AuditService::log('VIEW', 'Viewed rental demand report');

        return view('reports.index', compact(
            'topMovies', 'lowMovies',
            'activeRentals', 'totalRentals',
            'returnedRentals', 'overdueRentals',
            'monthlyTrend'
        ));
    }
}
