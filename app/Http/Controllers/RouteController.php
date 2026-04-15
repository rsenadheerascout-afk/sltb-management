<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRouteRequest;
use App\Models\Route;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $routes = Route::withCount('schedules')->latest()->paginate(15);
        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        return view('routes.create');
    }

    public function store(StoreRouteRequest $request)
    {
        $route = Route::create($request->validated());
        $this->logActivity('created', 'Route', $route->id, "Route {$route->name} created");
        return redirect()->route('routes.index')->with('success', "Route '{$route->name}' added successfully.");
    }

    public function show(Route $route)
    {
        $schedules = $route->schedules()->with(['bus', 'driver'])->latest('schedule_date')->paginate(10);
        return view('routes.show', compact('route', 'schedules'));
    }

    public function edit(Route $route)
    {
        return view('routes.edit', compact('route'));
    }

    public function update(StoreRouteRequest $request, Route $route)
    {
        $route->update($request->validated());
        $this->logActivity('updated', 'Route', $route->id, "Route {$route->name} updated");
        return redirect()->route('routes.index')->with('success', "Route '{$route->name}' updated.");
    }

    public function toggleStatus(Route $route)
    {
        $route->update(['status' => $route->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', "Route status updated.");
    }
}