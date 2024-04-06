<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Attempt to authenticate user
        $user = $this->getUserByCredentials($request->email, $request->password);

        if ($user) {
            // Generate token
            $token = $user->createToken('AuthToken')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        // Authentication failed
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    protected function getUserByCredentials($email, $password)
    {
        // Check if the user exists in any of the tables
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        $student = Student::where('matric_number', $email)->first();
        if ($student && Hash::check($password, $student->password)) {
            return $student;
        }

        $organization = Organization::where('email', $email)->first();
        if ($organization && Hash::check($password, $organization->password)) {
            return $organization;
        }

        $supervisor = Supervisor::where('email', $email)->first();
       if ($supervisor && Hash::check($password, $supervisor->password)) {
        return $supervisor;
       }

        return null;
    }
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        // Logout successful, return success response
        return response()->json(['message' => 'Logout successful'], 200);
    }

    
}
