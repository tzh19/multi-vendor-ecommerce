<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        $users = User::paginate(10)->through(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at->format('d M, Y'),
        ]);

        // Send users to Inertia
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255,' . $id,
            ],
            [
                'name.required' => 'User name is required.'
            ]
        );

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');

    }
}
