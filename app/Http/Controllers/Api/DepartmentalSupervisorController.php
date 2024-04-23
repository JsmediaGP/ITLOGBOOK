<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DepartmentalSupervisorController extends Controller
{
    //this function will display all students under the supervisor's department
    public function allStudents(){

        if(\request()->user()->role!== 'department') {
            return response()->json(['message' => 'You are not authorized to access this page'], 401);
        }
        $departmentId = \request()->user()->department_id;

        $students = Student::where('department_id', $departmentId)->get();
        return response()->json([$students]);
    }

    public function viewStudentLogbook($id){
        $student = Student::with('logbook')->findOrFail($id);
        return response()->json($student->logbook);
    }

    

    public function exportStudentLogbookCsv($studentId) {
        $student = Student::with('logbook')->findOrFail($studentId);
        $logbook = $student->logbook;

        // Convert logbook data to CSV format
        $csvData = $this->convertToCsv($logbook);

        // Save the CSV file to storage
        $fileName = 'logbook_' . $studentId . '.csv';
        Storage::put($fileName, $csvData);

        // Return the file for download
        return Storage::download($fileName);
    }

    private function convertToCsv($logbook) {
        // Implement your CSV conversion logic here
        // This is a placeholder for your actual conversion logic
        return "id,entry\n" . implode("\n", array_map(function ($entry) {
            return $entry->id . ',' . $entry->entry;
        }, $logbook));
    }
}
