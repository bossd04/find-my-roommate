<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Course;

class DepartmentController extends Controller
{
    public function getCoursesByDepartment($departmentId)
    {
        $department = Department::find($departmentId);
        
        if (!$department) {
            return response()->json(['error' => 'Department not found'], 404);
        }
        
        $courses = $department->activeCourses()->get(['id', 'name']);
        
        return response()->json($courses);
    }
}
