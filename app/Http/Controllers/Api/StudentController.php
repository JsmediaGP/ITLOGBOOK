<?php

namespace App\Http\Controllers\Api;

use Log;
use Carbon\Carbon;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
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
            'department'=>'required|string',
            'company_name' => 'required|string', // New field for company name
            // 'duration' => 'required|in:3 months,6 months',
        ]);

        // Extract company name from the request data
        $companyName = strtoupper($request->input('company_name'));

        $departmentName= strtoupper($request->input('department'));
        

        // Check if the organization already exists
        $organization = Organization::where('name', $companyName)->first();

        // If the organization does not exist, create a new organization record
        if (!$organization) {
            $organization = Organization::create(['name' => $companyName]);
        }

         // Check if the department already exists
        $department = Department::where('name', $departmentName)->first();

        // If the department does not exist, create a new department record
        if (!$department) {
            $department = Department::create(['name' => $departmentName]);
        }

        // Create a new student record and associate it with the organization
        $student = new Student();
        $student->name = $request->input('name');
        $student->matric_number = $request->input('matric_number');
        $student->email = $request->input('email');
        $student->password = Hash::make($request->input('password'));
        $student->department_id = $department->id;
        $student->organization_id = $organization->id; // Associate with organization
        // $student->duration = $request->input('duration');
        $student->save();

        

        return response()->json(['message' => 'Student registered successfully', $student]);
    }
    public function fillLogbook(Request $request)
    {
        
            // Check if the authenticated user is a student
            $user = $request->user();
            if ($user->role !== 'student') {
                return response()->json(['message' => 'You are not authorized to access this page'], 401);
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
       
    }


    // View logbook
    public function viewLogbook()
    {
        // Check if the authenticated user is a student
        if (\request()->user()->role !== 'student') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }

        // Assuming the authenticated user is a student
        $student = \request()->user(); // Since the user is a student, we can directly use the authenticated user

        // Retrieve the logbooks associated with the student
        $logbooks = $student->logbooks()->latest()->get();

        // Check if the student has any logbooks
        if ($logbooks->isEmpty()) {
            return response()->json(['message' => 'You have not filled any week out of your logbook.'], 404);
        }

        // Return the logbooks
        return response()->json($logbooks);
    }

    // Check if a student can fill the logbook for the current day
    
}
