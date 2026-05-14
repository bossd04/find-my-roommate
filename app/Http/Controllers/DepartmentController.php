<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Course;

class DepartmentController extends Controller
{
    public function getCoursesByDepartment($departmentId)
    {
        try {
            \Log::info('Getting courses for department: ' . $departmentId);
            
            $department = Department::find($departmentId);
            
            if (!$department) {
                \Log::warning('Department not found: ' . $departmentId);
                return response()->json(['error' => 'Department not found'], 404);
            }
            
            $courses = $department->activeCourses()->get(['id', 'name']);
            
            \Log::info('Found ' . $courses->count() . ' courses for department: ' . $department->name);
            
            return response()->json($courses)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            \Log::error('Error loading courses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load courses: ' . $e->getMessage()], 500);
        }
    }
}
