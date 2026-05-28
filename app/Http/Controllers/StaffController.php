<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Display the staff list.
     */
    public function index(): View
    {
        $staffUsers = User::role(['Admin', 'Fundraising Manager', 'Finance', 'Marketing', 'Auditor'])->get();
        $roles = Role::whereNot('name', 'Donor')->pluck('name');

        return view('staff.index', compact('staffUsers', 'roles'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(): View
    {
        $roles = Role::whereNot('name', 'Donor')->pluck('name');

        return view('staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name', 'not_in:Donor'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff): RedirectResponse
    {
        // Prevent deleting yourself
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
