<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Comment;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{

    // Register (update organization details such as email, phone, address)
    public function register(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'email' => 'required|email|unique:organizations,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'supervisor_name' => 'required|string',
            'supervisor_email' => 'required|email|unique:organizations,supervisor_email',
            'password' => 'required|string',
        ]);

        $organization = Organization::findOrFail($request->organization_id);
        $organization->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'supervisor_name' => $request->supervisor_name,
            'supervisor_email' => $request->supervisor_email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Organization details updated successfully', 'organization' => $organization]);
    }

    // View all students in their organization
    public function viewAllStudents()
    {
        if(\request()->user()->role !== 'supervisor') {
            return response()->json(['message' => 'You are not authorized to access this resource'], 401);
        }
        // $supervisor = auth()->user();
        // $students = $supervisor->organization->students;
        // return response()->json($students);
        $supervisor = auth()->user();
        $students = $supervisor->organization->students;

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No students found for this supervisor.'], 404);
        }

        return response()->json($students);
    }

    // View single student
    public function viewSingleStudent($id)
    {
        $user = \request()->user();
        // if ($user->role !== 'admin' && $user->role !== 'supervisor') {
        //     return response()->json(['message' => 'You are not authorized to access this resource'], 401);
        // }

        // Retrieve the student
        $student = Student::findOrFail($id);

        // If the user is a supervisor, check if their organization ID matches the student's organization ID
        if ($user->role === 'supervisor' && $user->organization_id !== $student->organization_id) {
            return response()->json(['message' => 'You do not have the right privileges to view this student.'], 403);
        }

        // Return the student
        return response()->json($student);
       
    }

    // View student logbook to add comments each week
    public function viewStudentLogbook($studentId)
    {
        $user = \request()->user();
        if($user->role !== 'admin' && $user->role !== 'supervisor') {
            return response()->json(['message' => 'You are not authorized to access this resource'], 401);
        }
        $student = Student::findOrFail($studentId);

        // Check if the authenticated user is a supervisor and if their organization ID matches the student's organization ID
        if ($user->role === 'supervisor' && $user->organization_id !== $student->organization_id) {
            return response()->json(['message' => 'You do not have the right privileges to view this student\'s logbook.'], 403);
        }

        $logbook = $student->logbooks()->latest()->get();
        if ($logbook->isEmpty()) {
            return response()->json(['message' => 'No logbooks found for this student.'], 404);
        }
    
        // Return the logbooks
        return response()->json($logbook);
    }

    
   // Add comment to student logbook
public function addCommentToLogbook(Request $request, $logbookId)
{
    if(\request()->user()->role !== 'supervisor') {
        return response()->json(['message' => 'You are not authorized to access this resource'], 401);
    }

    $request->validate([
        'comment' => 'required|string',
    ]);

    $logbook = Logbook::findOrFail($logbookId);
    $supervisor = auth()->user(); // Assuming the authenticated user is a supervisor

    // Check if the supervisor is authorized to comment on this logbook
    if ($logbook->student->organization_id !== $supervisor->organization_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Add comment to logbook for the current week
    $weekNumber = ceil($logbook->created_at->diffInDays(now()) / 7);
    $comment = Comment::create([
        'logbook_id' => $logbook->id,
        'organization_id' => $supervisor->organization_id, // Include organization_id
        'comment' => $request->comment,
        'week_number' => $weekNumber,
    ]);

    return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
}


}
