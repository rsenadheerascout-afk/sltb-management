<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    use LogsActivity;

    public function __construct(private NotificationService $notify) {}

    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('employee_id', 'like', "%{$request->search}%")
                  ->orWhere('nic', 'like', "%{$request->search}%"))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        $employees = $query->paginate(15)->withQueryString();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'nic'      => ['required', 'string', 'unique:users,nic'],
            'phone'    => ['required', 'string', 'max:15'],
            'address'  => ['required', 'string'],
            'role'     => ['required', 'in:executive_officer,timekeeper,storekeeper,driver,conductor,employee'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $employeeId = $this->generateEmployeeId();

        $employee = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'nic'         => $validated['nic'],
            'phone'       => $validated['phone'],
            'address'     => $validated['address'],
            'role'        => $validated['role'],
            'employee_id' => $employeeId,
            'status'      => 'active',
            'is_approved' => true,
        ]);

        $this->logActivity('created', 'User', $employee->id,
            "Employee {$employee->name} ({$employeeId}) added by " . auth()->user()->name);

        return redirect()->route('employees.index')
            ->with('success', "Employee {$employee->name} added with ID {$employeeId}.");
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', "unique:users,email,{$employee->id}"],
            'nic'     => ['required', 'string', "unique:users,nic,{$employee->id}"],
            'phone'   => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'role'    => ['required', 'in:executive_officer,timekeeper,storekeeper,driver,conductor,employee'],
        ]);

        $old = $employee->only(['name', 'email', 'role', 'phone']);
        $employee->update($validated);

        $this->logActivity('updated', 'User', $employee->id,
            "Employee {$employee->name} updated", $old, $validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function disable(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'You cannot disable your own account.');
        }

        $employee->update(['status' => 'disabled']);
        $this->logActivity('disabled', 'User', $employee->id, "Employee {$employee->name} disabled");

        return back()->with('success', "{$employee->name}'s account has been disabled.");
    }

    public function enable(User $employee)
    {
        $employee->update(['status' => 'active']);
        $this->logActivity('enabled', 'User', $employee->id, "Employee {$employee->name} re-enabled");

        return back()->with('success', "{$employee->name}'s account has been re-enabled.");
    }

    private function generateEmployeeId(): string
    {
        $last = User::where('employee_id', 'like', 'E%')
            ->orderByDesc('employee_id')
            ->value('employee_id');

        $next = $last ? (int) substr($last, 1) + 1 : 1;
        return 'E' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}