<?php

namespace App\Http\Controllers\Api;


use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function newSupervisor(Request $request){
        
        //getting the super admin organization details
        $user = $request->user();


        $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|email|unique:supervisors',
            'password'=>'required|string',
            'phone'=>'required|string'
        ]);
        

        $supervisor = new Supervisor();
        $supervisor->name = $request->input('name');
        $supervisor->email = $request->input('email');
        $supervisor->phone = $request->input('phone');
        $supervisor->password = Hash::make($request->input('name'));
        $supervisor->organization_id = $user->id;
        $supervisor->save();

        return response()->json(['message'=>'New Supervisor Added Successfully', $supervisor]);
        
    }

    public function viewAllSupervisors(){
        if(\request()->user()->role!== 'organization') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
         $supervisors = Supervisor::all();
         return response()->json($supervisors);

    }
    //single supervisor with number of students
    public function viewSingleSupervisor($id){

        if(\request()->user()->role!== 'organization') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
        $supervisors = Supervisor::withCount('students')->where('organization_id', request()->user()->id)->get();

        return response()->json($supervisors);

        //  $supervisors = Supervisor::findOrFail($id);
        //  $studentsCount = $supervisors->students->count();
        //  return response()->json(['organization' => $supervisors, 'students_count' => $studentsCount]);

    }

    public function viewAllStudents(){
        if(\request()->user()->role!== 'admin') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }

        $students = Student::with('department')->where('organization_id', request()->user()->id)->get();

        return response()->json($students);
    
        //  $students = Student::with('department')->get();
        //  return response()->json($students);

    }

    public function assignStudentToSupervisor(Request $request, $supervisorId, $studentId)
{
    $supervisor = Supervisor::findOrFail($supervisorId);
    $student = Student::findOrFail($studentId);

    if ($supervisor->organization_id !== $request->user()->id) {
        return response()->json(['message' => 'You are not authorized to assign this student'], 401);
    }

    $student->supervisor_id = $supervisorId;
    $student->save();

    return response()->json(['message' => 'Student assigned to supervisor successfully']);
}

// Register (update organization details such as email, phone, address)
    public function updateOrganization(Request $request)
    { 
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'email' => 'required|email|unique:organizations,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|string',
        ]);

        $organization = Organization::findOrFail($request->organization_id);
        $organization->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Organization details updated successfully', 'organization' => $organization]);
    }



}
