<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SetupAdminController extends Controller
{
    public function create(): View
    {
        return view('auth.setup-admin');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'setup_key' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value !== env('ADMIN_SETUP_KEY')) {
                    $fail('The setup key is invalid.');
                }
            }],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->assignRole('admin');

        Auth::login($admin);

        return redirect()->route('dashboard');
    }
}
