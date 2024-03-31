<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
     // View All Organizations
     public function viewAllOrganizations()
     {
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $organizations = Organization::all();
         return response()->json($organizations);
     }
 
     // View Single Organization (with the number of students in their organization)
     public function viewSingleOrganization($id)
     {
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $organization = Organization::findOrFail($id);
         $studentsCount = $organization->students->count();
         return response()->json(['organization' => $organization, 'students_count' => $studentsCount]);
     }
 
     // Create Departments
     public function createDepartment(Request $request)
     {
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $request->validate([
             'name' => 'required|unique:departments,name',
         ]);
 
         $department = Department::create([
             'name' => $request->name,
         ]);
 
         return response()->json(['message' => 'Department created successfully', 'department' => $department], 201);
     }
 
     // View All Students (also by their department)
     public function viewAllStudents()
     {
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }

         $students = Student::with('department')->get();
         return response()->json($students);
     }
 
     // View a Student's Logbook
     public function viewStudentLogbook($studentId)
     {
        if(\request()->user()->role !== 'admin' && \request()->user()->role !== 'supervisor') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $student = Student::findOrFail($studentId);
         $logbook = $student->logbooks()->with('comments')->latest()->get();
         if($logbook->isEmpty()) {
            return response()->json(['message' => 'No logbook entries found for this student'], 404);
         }
         return response()->json($logbook);
     }
}
