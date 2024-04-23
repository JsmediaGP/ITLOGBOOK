<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DepartmentSupervisor;
use Illuminate\Support\Facades\Hash;

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
     public function departmentalSupervisors(Request $request)
     {
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
        $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|email|unique:departmentsupervisors',
            'password'=>'required|string',
            'phone'=>'required|string',
            'department_id'=>'required|exists:departments,id'
        ]);
        

        $department = new DepartmentSupervisor();
        $department->name = $request->input('name');
        $department->email = $request->input('email');
        $department->phone = $request->input('phone');
        $department->password = Hash::make($request->input('password'));
        $department->department_id = $request->input('department_id');
        $department->save();

        return response()->json(['message'=>'New Supervisor Added Successfully', $department]);
        
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

     public function viewAllDepartments(){
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $departments = Department::all();
         return response()->json($departments);

     }

     
}
