<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Student;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
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

        $student = Student::where('email', $email)->first();
        if ($student && Hash::check($password, $student->password)) {
            return $student;
        }

        $organization = Organization::where('email', $email)->first();
        if ($organization && Hash::check($password, $organization->password)) {
            return $organization;
        }

        return null;
    }
    public function logout(Request $request)
    {
       // Get the authenticated user
        $user = $request->user();

        // Check if the user is authenticated
        if ($user) {
            // Revoke the token that was used to authenticate this request
            $user->token()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        } else {
            // If the user is not authenticated, return an error response
            return response()->json(['message' => 'No user is currently authenticated.'], 401);
        }
    }
}

    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if($admin = User::where('email', $request->email)->first()){
    //         if (!$admin || !Hash::check($request->password, $admin->password)) {
    //             return response()->json([
    //                 'message' => 'Invalid credentials',
    //             ], 401);
    //         }
    //         // if ($admin && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //             $token = $admin->createToken('MyApp')->plainTextToken;
    //             return response()->json([
    //                 'message' => 'Login successful',
    //                 'user' => $admin,
    //                 'access_token' => $token,
    //             ]);
    //         }

        
        


    //     // Check if the credentials belong to a user
    //     // if (Auth::attempt($credentials)) {
    //     //     $user = Auth::user();
    //     //     $token = $user->createToken('authToken')->plainTextToken;
    //     //     return response()->json(['token' => $token]);
    //     // }

    //     // Check if the credentials belong to a student
    //    if($student = Student::where('email', $request->email)->first()) {
    //     if (!$student || !Hash::check($request->password, $student->password)) {
    //         return response()->json([
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }
    //     $token = $student->createToken('authToken')->plainTextToken;
    //     return response()->json([
    //         'message' => 'Login successful',
    //         'organization' => $student,
    //         'token' => $token,
    //     ]);

    //    }
    //     // if (!$student || !Hash::check($request->password, $student->password)) {
    //     //     return response()->json([
    //     //         'message' => 'Invalid credentials',
    //     //     ], 401);
    //     // }
    //     // $token = $student->createToken('authToken')->plainTextToken;
    //     // return response()->json(['token' => $token]);
    //     // if ($student && Auth::attempt($credentials)) {
    //     //     $token = $student->createToken('authToken')->plainTextToken;
    //     //     return response()->json(['token' => $token]);
    //     // }

    //     // Check if the credentials belong to an organization
    //     if($organization = Organization::where('supervisor_email', $request->email)->first()){
    //         if (!$organization || !Hash::check($request->password, $organization->password)) {
    //             return response()->json([
    //                 'message' => 'Invalid credentials',
    //             ], 401);
    //         }
    //         $token = $organization->createToken('authToken')->plainTextToken;
    //         return response()->json([
    //             'message' => 'Login successful',
    //             'organization' => $organization,
    //             'token' => $token,
    //         ]);

    //     }
        

    //     // return response()->json(['error' => 'Unauthorized'], 401);
    // }
    // //     // Validate the request data
    // //     $request->validate([
    // //         'email' => 'required|email',
    // //         'password' => 'required|string',
    // //     ]);

    // //     // Attempt to authenticate the user as an admin
    // //     $admin = User::where('email', $request->email)->first();
    // //     if ($admin && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         $token = $admin->createToken('MyApp')->plainTextToken;
    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'user' => $admin,
    // //             'access_token' => $token,
    // //         ]);
    // //     }

    // //     // Attempt to authenticate the user as a student
    // //     $student = Student::where('email', $request->email)->first();
    // //     if ($student && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         $token = $student->createToken('MyApp')->plainTextToken;
    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'student' => $student,
    // //             'access_token' => $token,
    // //         ]);
    // //     }

    // //     // Attempt to authenticate the user as an organization or supervisor
    // //     $organization = Organization::where('supervisor_email', $request->email)->first();
    // //     if ($organization && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         $token = $organization->createToken('MyApp')->plainTextToken;
    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'organization' => $organization,
    // //             'access_token' => $token,
    // //         ]);
    // //     }

    // //     // If none of the above conditions are met, return an error response
    // //     return response()->json(['message' => 'Invalid credentials'], 401);
    // // }
    // // public function login(Request $request)
    // // {

    // //     $admin = User::where('email', $request->email)->first();
    // //     // dd($admin);
    // //     if ($admin && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         // dd('Authenticated');
    // //         $token = $admin->createToken('MyApp')->plainTextToken;

    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'student' => $admin,
    // //             'access_token' => $token,
    // //         ]);
    // //     }
    // //     // $credentials = $request->only('email', 'password');

    // //     // if (Auth::attempt($credentials)) {
    // //     //     $user = Auth::user();
    // //     //     // $token = $user->createToken('MyApp')->accessToken;
    // //     //     $token = $user->user->createToken('MyApp')->accessToken;

    // //     //     return response()->json([
    // //     //         'message' => 'Login successful',
    // //     //         'user' => $user,
    // //     //         'access_token' => $token,
    // //     //     ]);
    // //     // }

    // //     // Check if user exists in Student table
    // //     $student = Student::where('email', $request->email)->first();
    // //     if ($student && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         $token = $student->user->createToken('MyApp')->plainTextToken;

    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'student' => $student,
    // //             'access_token' => $token,
    // //         ]);
    // //     }

    // //     // Check if user exists in Organization table
    // //     $organization = Organization::where('supervisor_email', $request->email)->first();
    // //     if ($organization && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    // //         $token = $organization->user->createToken('MyApp')->plainTextToken;

    // //         return response()->json([
    // //             'message' => 'Login successful',
    // //             'organization' => $organization,
    // //             'access_token' => $token,
    // //         ]);
    // //     }

    // //     return response()->json(['message' => 'Invalid credentials'], 401);
    // // }



    // public function logout(Request $request)
    // {
    //    // Get the authenticated user
    //     $user = $request->user();

    //     // Check if the user is authenticated
    //     if ($user) {
    //         // Revoke the token that was used to authenticate this request
    //         $user->tokens()->delete();

    //         return response()->json(['message' => 'Logged out successfully']);
    //     } else {
    //         // If the user is not authenticated, return an error response
    //         return response()->json(['message' => 'No user is currently authenticated.'], 401);
    //     }
    // }
    
