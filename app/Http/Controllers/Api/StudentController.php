<?php

namespace App\Http\Controllers\Api;

use Log;
use Carbon\Carbon;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // Register (with name, matric number, email, password, department, duration)
    public function register(Request $request)
    {

         // Validate the incoming request data
         $request->validate([
            'name' => 'required|string',
            'matric_number' => 'required|string|unique:students,matric_number',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            // 'organization_id' => 'required|exists:organizations,id',
            'company_name' => 'required|string', // New field for company name
            'duration' => 'required|in:3 months,6 months',
        ]);

        // Extract company name from the request data
        $companyName = strtoupper($request->input('company_name'));
        

        // Check if the organization already exists
        $organization = Organization::where('name', $companyName)->first();

        // If the organization does not exist, create a new organization record
        if (!$organization) {
            $organization = Organization::create(['name' => $companyName]);
        }

        // Create a new student record and associate it with the organization
        $student = new Student();
        $student->name = $request->input('name');
        $student->matric_number = $request->input('matric_number');
        $student->email = $request->input('email');
        $student->password = Hash::make($request->input('password'));
        $student->department_id = $request->input('department_id');
        $student->organization_id = $organization->id; // Associate with organization
        $student->duration = $request->input('duration');
        $student->save();

        

        return response()->json(['message' => 'Student registered successfully', $student]);
    }
    public function fillLogbook(Request $request)
    {
        
            // Check if the authenticated user is a student
            $user = $request->user();
            if ($user->role !== 'student') {
                return response()->json(['message' => 'You are not authorized to access this resource'], 401);
            }

            // Check if the student has already submitted a logbook entry for the current day
            $existingEntry = Logbook::where('student_id', $user->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($existingEntry) {
                return response()->json(['message' => 'You have already submitted a logbook entry for today'], 422);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'work_done' => 'required|string',
                'attachment' => 'nullable|file',
            ]);
        // try {

            // Create a new logbook entry
            $logbook = new Logbook();
            $logbook->work_done = $validatedData['work_done'];
            
            if ($request->hasFile('attachment')) {
                $logbook->attachment_file = $request->file('attachment')->store('attachments');
            }

            $logbook->created_at = now();
            $logbook->student_id = $user->id;
            $logbook->save();

            return response()->json(['message' => 'Logbook entry created successfully']);
        // } catch (\Exception $e) {
        //     // Log the exception for debugging purposes
        //     Log::error($e);

        //     // Return an error response
        //     return response()->json(['error' => 'An error occurred while processing your request'], 500);
        // }
    }


        //normal registration working
    //     $request->validate([
            // 'name' => 'required|string',
            // 'matric_number' => 'required|string|unique:students,matric_number',
            // 'email' => 'required|email|unique:students,email',
            // 'password' => 'required|string',
            // 'department_id' => 'required|exists:departments,id',
            // 'organization_id' => 'required|exists:organizations,id',
            // 'duration' => 'required|in:3 months,6 months',
    //     ]);

    //     $student = Student::create([
    //         'name' => $request->name,
    //         'matric_number' => $request->matric_number,
    //         'email' => $request->email,
    //         'password' => Hash::make ($request->password),
    //         'department_id' => $request->department_id,
    //         'organization_id' => $request->organization_id,
    //         'duration' => $request->duration,
    //     ]);

    //     return response()->json(['message' => 'Student registered successfully', 'student' => $student], 201);
    // }

    // Fill logbook (based on the duration entered) with work done for the day, attachment file, and current timestamp
//     public function fillLogbook(Request $request)
//     {
//     try{

//        // Check if the authenticated user is a student
//        $user = $request->user();
//        if ($user->role !== 'student') {
//            return response()->json(['message' => 'You are not authorized to access this resource'], 401);
//        }

//        // Check if the student has already submitted a logbook entry for the current day
//        $existingEntry = Logbook::where('student_id', $user->id)
//            ->whereDate('created_at', Carbon::today())
//            ->exists();

//        if ($existingEntry) {
//            return response()->json(['message' => 'You have already submitted a logbook entry for today'], 422);
//        }

//        // Validate the incoming request data
//        $request->validate([
//            'work_done' => 'required|string',
//            'attachment' => 'nullable|file',
//        ]);

//       // Create a new logbook entry
//        $logbook = new Logbook();
//        $logbook->work_done = $request->input('work_done');
//        if ($request->hasFile('attachment')) {
//            $logbook->attachment_file = $request->file('attachment')->store('attachments');
//        }
//        $logbook->timestamp = now();
//        $logbook->student_id = $user->id;
//        $logbook->save();

//        return response()->json(['message' => 'Logbook entry created successfully']);
//    }catch (\Exception $e) {
//     // Handle any unexpected exceptions
//     return response()->json(['error' => 'An error occurred while processing your request'], 500);
// }
    // }
    
    //     $request->validate([
    //         'work_done' => 'required|string',
    //         'attachment' => 'nullable|file',
    //     ]);
    

    //     // Get the authenticated user
        // $user = $request->user();

        // // Check if the authenticated user is a student
        // if ($user->role !== 'student') {
        //     return response()->json(['message' => 'You are not authorized to access this resource'], 401);
        // }

    //     // Assuming the authenticated user is a student
    //     $student = $user;

    //     // Check if the student can fill the logbook for the current day
    //     if (!$this->canFillLogbook($student)) {
    //         return response()->json(['message' => 'You cannot fill the logbook for today'], 403);
    //     }

    //     $logbook = Logbook::create([
    //         'student_id' => $student->id,
    //         'work_done' => $request->work_done,
    //         'attachment' => $request->hasFile('attachment') ? $request->file('attachment')->store('attachments') : null,
    //         'created_at' => now(),
    //     ]);

    //     return response()->json(['message' => 'Logbook filled successfully', 'logbook' => $logbook], 201);
    // }

    // View logbook
    public function viewLogbook()
{
    // Check if the authenticated user is a student
    if (\request()->user()->role !== 'student') {
        return response()->json(['message' => 'You are not authorized to access this resource'], 401);
    }

    // Assuming the authenticated user is a student
    $student = \request()->user(); // Since the user is a student, we can directly use the authenticated user

    // Retrieve the logbooks associated with the student
    $logbooks = $student->logbooks()->latest()->get();

    // Check if the student has any logbooks
    if ($logbooks->isEmpty()) {
        return response()->json(['message' => 'No logbooks found for this student.'], 404);
    }

    // Return the logbooks
    return response()->json($logbooks);
}

    // Check if a student can fill the logbook for the current day
    private function canFillLogbook($student)
    {
        // If the student has not filled the logbook for today and has not missed any days, they can fill the logbook
        return !$student->logbook()->whereDate('created_at', today())->exists() && !$this->hasMissedDays($student);
    }

    // Check if a student has missed any days for logbook filling
    private function hasMissedDays($student)
    {
        // Calculate the number of days between the first logbook entry and today
        $daysSinceFirstEntry = $student->logbook()->min('created_at')->diffInDays(now());

        // Check if the student has missed any days based on the duration
        return $daysSinceFirstEntry > ($student->duration == '3 months' ? 90 : 180);
    }
}
