<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Your phone or password is incorrect'], 401);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is inactive. Please contact support.'], 401);
        }

        $token = $user->createToken('AUTH_TOKEN')->plainTextToken;

        return response()->json([
            'message' => 'Admin/User logged in successfully',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function adminRegister(Request $request)
    {
        if (User::count()) {
            return response()->json(['message' => 'Admin already registered.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        return response()->json([
            'message' => 'Admin registered successfully',
            'user' => $user,
        ], 201);
    }

    public function studentLogin(Request $request)
    {
        $request->validate([
            'guardian_phone' => 'required|string',
            'registration_number' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        $student = Student::where([
            ['guardian_phone', '=', $request->guardian_phone],
            ['registration_number', '=', $request->registration_number],
            ['birth_date', '=', $request->birth_date],
        ])->first();

        if ($student) {
            $token = $student->createToken('student-token')->plainTextToken;
            return response()->json([
                'message' => 'Student logged in successfully',
                'student' => $student,
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
