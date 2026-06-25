<?php

namespace App\Http\Controllers\Movie;

use App\Models\Tape;
use App\Models\Movie;
use App\Models\Rental;
use App\Services\AuditService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RentalController extends Controller
{
    /**
     * List all active (unreturned) rentals.
     */
    public function index(Request $request)
    {
        $rentals = Rental::with('tape.movie')
            ->when($request->status === 'returned', fn($q) => $q->whereNotNull('returned_at'))
            ->when(!$request->status || $request->status === 'active', fn($q) => $q->whereNull('returned_at'))
            ->when($request->status === 'all', fn($q) => $q)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        AuditService::log('VIEW', 'Viewed rentals list');

        return view('rentals.index', compact('rentals'));
    }

    /**
     * Quick rent: find the first available tape for a movie and redirect to its rental form.
     */
    public function quickRent(Movie $movie)
    {
        $tape = $movie->tapes()->where('status', 'available')->first();

        if (!$tape) {
            return redirect()->back()->with('error', "No available physical copies for \"{$movie->title}\".");
        }

        return redirect()->route('rentals.create', $tape);
    }

    /**
     * Show the rent form for a specific tape.
     */
    public function create(Tape $tape)
    {
        if ($tape->status !== 'available') {
            return redirect()->back()->with('error', "Tape \"{$tape->tape_number}\" is already rented out.");
        }

        $tape->load('movie');
        return view('rentals.rent', compact('tape'));
    }

    /**
     * Process renting a tape.
     */
    public function store(Request $request, Tape $tape)
    {
        if ($tape->status !== 'available') {
            return redirect()->back()->with('error', "Tape \"{$tape->tape_number}\" is already rented out.");
        }

        $data = $request->validate([
            'rented_by' => 'required|string|max:255',
            'due_date'  => 'required|date|after:today',
        ]);

        $data['tape_id']   = $tape->id;
        $data['rented_at'] = today();

        $rental = Rental::create($data);

        // Update tape status
        $tape->update(['status' => 'rented']);

        AuditService::log('CREATE', "Rented tape {$tape->tape_number} to {$data['rented_by']}", Tape::class, $tape->id);

        return redirect()->route('rentals.index')
            ->with('success', "Tape \"{$tape->tape_number}\" rented to {$data['rented_by']} successfully.");
    }

    /**
     * Return a rental (mark tape as available).
     */
    public function returnRental(Rental $rental)
    {
        if ($rental->returned_at) {
            return redirect()->back()->with('error', 'This rental has already been returned.');
        }

        $rental->update(['returned_at' => today()]);
        $rental->tape->update(['status' => 'available']);

        AuditService::log('UPDATE', "Returned tape {$rental->tape->tape_number} from rental #{$rental->id}", Tape::class, $rental->tape_id);

        return redirect()->route('rentals.index')
            ->with('success', "Tape \"{$rental->tape->tape_number}\" has been returned successfully.");
    }
}
