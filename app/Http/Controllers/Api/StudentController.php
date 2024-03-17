<?php

namespace App\Http\Controllers\Api;

use App\Models\Logbook;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // Register (with name, matric number, email, password, department, duration)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'matric_number' => 'required|string|unique:students,matric_number',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'organization_id' => 'required|exists:organizations,id',
            'duration' => 'required|in:3 months,6 months',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'matric_number' => $request->matric_number,
            'email' => $request->email,
            'password' => Hash::make ($request->password),
            'department_id' => $request->department_id,
            'organization_id' => $request->organization_id,
            'duration' => $request->duration,
        ]);

        return response()->json(['message' => 'Student registered successfully', 'student' => $student], 201);
    }

    // Fill logbook (based on the duration entered) with work done for the day, attachment file, and current timestamp
    public function fillLogbook(Request $request)
    {
        $request->validate([
            'work_done' => 'required|string',
            'attachment' => 'nullable|file',
        ]);

        // Check if the authenticated user is a student
        if (\request()->user()->role !== 'student') {
            return response()->json(['message' => 'You are not authorized to access this resource'], 401);
        }

        // Assuming the authenticated user is a student
        $student = \request()->user(); // Since the user is a student, we can directly use the authenticated user

        // Check if the student can fill the logbook for the current day
        if (!$this->canFillLogbook($student)) {
            return response()->json(['message' => 'You cannot fill the logbook for today'], 403);
        }

        $logbook = Logbook::create([
            'student_id' => $student->id,
            'work_done' => $request->work_done,
            'attachment' => $request->hasFile('attachment') ? $request->file('attachment')->store('attachments') : null,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Logbook filled successfully', 'logbook' => $logbook], 201);
    }

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
