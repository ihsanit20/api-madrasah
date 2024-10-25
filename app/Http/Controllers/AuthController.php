<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class AuthController extends Controller
{
    // Admin Login API
    public function adminLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('phone', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('admin-token')->plainTextToken;
            return response()->json([
                'message' => 'Admin/User logged in successfully',
                'user' => $user,
                'token' => $token
            ], 201);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Admin Register API
    public function adminRegister(Request $request)
    {
        if(User::count()) {
            return response('Not Found!', 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // নতুন অ্যাডমিন ইউজার তৈরি করা
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Admin registered successfully',
            'user' => $user,
        ], 201);
    }

    // Student Login API
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
                'token' => $token
            ], 201);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
