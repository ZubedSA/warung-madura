<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20);
        $actions = AuditLog::ACTIONS;

        return view('admin.log.index', compact('logs', 'actions'));
    }
}
