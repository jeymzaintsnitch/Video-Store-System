<?php

namespace App\Http\Controllers\Movie;

use App\Models\Tape;
use App\Models\Movie;
use App\Services\AuditService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TapeController extends Controller
{


    public function index(Request $request)
    {
        $movies = Movie::orderBy('title')->get();

        $tapes = Tape::with('movie.category')
            ->when($request->search, fn($q) => $q->where('tape_number', 'like', "%{$request->search}%"))
            ->when($request->movie_id, fn($q) => $q->where('movie_id', $request->movie_id))
            ->when($request->format, fn($q) => $q->where('format', $request->format))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $availableCount = Tape::where('status', 'available')->count();
        $rentedCount    = Tape::where('status', 'rented')->count();

        AuditService::log('VIEW', 'Viewed tapes list');

        return view('tapes.index', compact('tapes', 'movies', 'availableCount', 'rentedCount'));
    }

    public function create()
    {
        $movies = Movie::orderBy('title')->get();
        return view('tapes.create', compact('movies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // ✅ Enforces the strict T-0000 tracking number format pattern
            'tape_number'    => [
                'required',
                'string',
                'max:50',
                'unique:tapes,tape_number',
                'regex:/^T-\d{4}$/'
            ],
            'movie_id'       => 'required|exists:movies,id',
            'format'         => 'required|in:VHS,Beta,DVD',
            // ✅ Enforces a strict Zone-Number layout grid format pattern (e.g., A-01)
            'shelf_location' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Z]-\d{2}$/'
            ],
            'condition'      => 'required|in:Good,Fair,Poor',
        ], [
            // Custom descriptive warning strings to show in the view forms
            'tape_number.regex'   => 'The Tracking Number must strictly follow the format T-0000 (e.g., T-0001).',
            'shelf_location.regex' => 'The Warehouse Grid location must follow a clean Zone-Number format (e.g., A-01 or B-12).',
        ]);

        $tape = Tape::create($data);

        \App\Services\AuditService::log(
            'CREATE',
            "Created tape: {$tape->tape_number} ({$tape->format}) for movie ID {$tape->movie_id}",
            Tape::class,
            $tape->id,
            null,
            $tape->toArray()
        );

        return redirect()->route('tapes.index')
            ->with('success', "Tape \"{$tape->tape_number}\" created successfully.");
    }

    public function show(Tape $tape)
    {
        $tape->load('movie.category');
        AuditService::log('VIEW', "Viewed tape: {$tape->tape_number}", Tape::class, $tape->id);
        return view('tapes.show', compact('tape'));
    }

    public function edit(Tape $tape)
    {
        Gate::authorize('edit tapes');

        $movies = Movie::orderBy('title')->get();
        return view('tapes.edit', compact('tape', 'movies'));
    }

    public function update(Request $request, Tape $tape)
    {

        Gate::authorize('edit tapes');
        $data = $request->validate([
            // ✅ Enforces strict format parity on updates while ignoring the current record ID row
            'tape_number'    => [
                'required',
                'string',
                'max:50',
                'unique:tapes,tape_number,' . $tape->id,
                'regex:/^T-\d{4}$/'
            ],
            'movie_id'       => 'required|exists:movies,id',
            'format'         => 'required|in:VHS,Beta,DVD',
            'shelf_location' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Z]-\d{2}$/'
            ],
            'condition'      => 'required|in:Good,Fair,Poor',
        ], [
            'tape_number.regex'   => 'The Tracking Number must strictly follow the format T-0000 (e.g., T-0001).',
            'shelf_location.regex' => 'The Warehouse Grid location must follow a clean Zone-Number format (e.g., A-01 or B-12).',
        ]);

        $old = $tape->toArray();
        $tape->update($data);

        \App\Services\AuditService::log(
            'UPDATE',
            "Updated tape: {$tape->tape_number}",
            Tape::class,
            $tape->id,
            $old,
            $tape->fresh()->toArray()
        );

        return redirect()->route('tapes.index')
            ->with('success', "Tape \"{$tape->tape_number}\" updated successfully.");
    }

    public function destroy(Tape $tape)
    {
        Gate::authorize('delete tapes');

        $number = $tape->tape_number;
        $old    = $tape->toArray();

        $tape->delete();

        AuditService::log('DELETE', "Deleted tape: {$number}", Tape::class, $tape->id, $old);

        return redirect()->route('tapes.index')
            ->with('success', "Tape \"{$number}\" deleted successfully.");
    }
}
