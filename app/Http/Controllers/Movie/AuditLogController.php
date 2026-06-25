<?php

namespace App\Http\Controllers\Movie;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            // FIXED: Wrapped the search terms in a nested closure to isolate the 'OR' statement
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($subQuery) use ($request) {
                    $subQuery->where('description', 'like', "%{$request->search}%")
                             ->orWhere('user_name', 'like', "%{$request->search}%");
                });
            })
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $actions = AuditLog::distinct()->pluck('action')->sort()->values();

        return view('audit-logs.index', compact('logs', 'actions'));
    }

   
   /**
     * Display the specified audit log details.
     */
    public function show($id)
    {
        // CHANGE THIS: Rename the variable to $auditLog to match your Blade view
        $auditLog = AuditLog::findOrFail($id);

        // CHANGE THIS: Pass 'auditLog' through the compact array matrix
        return view('audit-logs.show', compact('auditLog'));
    }
}