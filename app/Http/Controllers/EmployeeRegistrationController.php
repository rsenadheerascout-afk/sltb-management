<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRegistration;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeRegistrationController extends Controller
{
    use LogsActivity;

    public function __construct(private NotificationService $notify) {}

    // Public form — no auth
    public function create()
    {
        return view('registrations.apply');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email', 'unique:employee_registrations,email'],
            'nic'          => ['required', 'string', 'unique:users,nic', 'unique:employee_registrations,nic'],
            'phone'        => ['required', 'string', 'max:15'],
            'address'      => ['required', 'string'],
            'applied_role' => ['required', 'in:executive_officer,timekeeper,storekeeper,driver,conductor,employee'],
        ]);

        $reg = EmployeeRegistration::create($validated);

        $this->notify->sendToRoles(
            ['admin', 'executive_officer'],
            'New employee application received',
            "{$reg->name} has applied for the role of " . ucfirst(str_replace('_', ' ', $reg->applied_role)) . '.',
            'info',
            route('registrations.index')
        );

        return redirect()->route('registration.success');
    }

    public function success()
    {
        return view('registrations.success');
    }

    // Admin/officer views
    public function index(Request $request)
    {
        $registrations = EmployeeRegistration::with('reviewer')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('registrations.index', compact('registrations'));
    }

    public function show(EmployeeRegistration $registration)
    {
        return view('registrations.show', compact('registration'));
    }

    public function approve(Request $request, EmployeeRegistration $registration)
    {
        if (!$registration->isPending()) {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $last = User::where('employee_id', 'like', 'E%')->orderByDesc('employee_id')->value('employee_id');
        $next = $last ? (int) substr($last, 1) + 1 : 1;
        $employeeId = 'E' . str_pad($next, 3, '0', STR_PAD_LEFT);

        User::create([
            'name'        => $registration->name,
            'email'       => $registration->email,
            'password'    => Hash::make('Password@123'),
            'nic'         => $registration->nic,
            'phone'       => $registration->phone,
            'address'     => $registration->address,
            'role'        => $registration->applied_role,
            'employee_id' => $employeeId,
            'status'      => 'active',
            'is_approved' => true,
        ]);

        $registration->update([
            'status'       => 'approved',
            'reviewed_by'  => auth()->id(),
            'review_notes' => $request->notes,
        ]);

        $this->logActivity('approved', 'EmployeeRegistration', $registration->id,
            "Registration approved for {$registration->name}, assigned ID {$employeeId}");

        return back()->with('success',
            "Approved. {$registration->name} added as employee with ID {$employeeId}. Temporary password: Password@123");
    }

    public function reject(Request $request, EmployeeRegistration $registration)
    {
        $request->validate(['notes' => 'required|string|min:5']);

        if (!$registration->isPending()) {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $registration->update([
            'status'       => 'rejected',
            'reviewed_by'  => auth()->id(),
            'review_notes' => $request->notes,
        ]);

        $this->logActivity('rejected', 'EmployeeRegistration', $registration->id,
            "Registration rejected for {$registration->name}");

        return back()->with('success', "Application for {$registration->name} has been rejected.");
    }
}