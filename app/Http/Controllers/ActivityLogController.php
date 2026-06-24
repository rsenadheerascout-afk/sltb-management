<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->user_id,   fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->action,    fn($q) => $q->where('action', $request->action))
            ->when($request->model,     fn($q) => $q->where('model_type', $request->model))
            ->when($request->date,      fn($q) => $q->whereDate('created_at', $request->date))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $users   = User::whereNotIn('role', ['passenger'])
            ->orderBy('name')
            ->get(['id', 'name', 'employee_id']);

        $actions = ActivityLog::distinct()
            ->orderBy('action')
            ->pluck('action');

        $models  = ActivityLog::distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        return view('admin.activity-log', compact('logs', 'users', 'actions', 'models'));
    }
}