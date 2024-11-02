<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Fetch the currently logged-in user's details
    public function getUser()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json([
                'message' => 'Authenticated user data fetched successfully',
                'user' => $user
            ], 200);
        }

        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // Fetch all users list
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Users list fetched successfully',
            'users' => $users
        ], 200);
    }

    // Add a new user
    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Developer,Super Admin,Academic Admin,Accounts Admin,General Teacher',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    // Update an existing user by ID
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|unique:users,phone,' . $id,
            'role' => 'sometimes|required|in:Developer,Super Admin,Academic Admin,Accounts Admin,General Teacher',
        ]);

        $user->update($request->only('name', 'phone', 'role'));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    // Delete a user by ID
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
