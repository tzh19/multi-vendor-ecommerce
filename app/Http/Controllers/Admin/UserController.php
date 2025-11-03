<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        // Example: paginate users
        $users = User::paginate(10)->through(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at->format('d M, Y'),
        ]);

        // Send users to Inertia
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }
}
