<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserCreated;

class UserController extends Controller
{
    /**
     * Store a newly created user in the database and notify the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'], // Store the user's role
        ]);

        // Find an admin user (or adjust based on your admin role system)
        $admin = User::where('role', 'true')->first(); // Adjust based on your role logic

        // If an admin exists, send the notification
        if ($admin) {
            $admin->notify(new UserCreated($user)); // Send the notification
        }

        // Redirect to the admin users list page or other appropriate route
        return redirect()->route('admin.users'); // Adjust the route as needed
    }
}
