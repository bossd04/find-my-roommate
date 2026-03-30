<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Department;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all()->keyBy('name');
        
        $courses = [
            // College of Arts and Sciences
            ['name' => 'Bachelor of Arts in Communication', 'department' => 'College of Arts and Sciences'],
            ['name' => 'Bachelor of Arts in Political Science', 'department' => 'College of Arts and Sciences'],
            ['name' => 'Bachelor of Arts in Psychology', 'department' => 'College of Arts and Sciences'],
            ['name' => 'Bachelor of Science in Psychology', 'department' => 'College of Arts and Sciences'],
            ['name' => 'Bachelor of Arts in English Language', 'department' => 'College of Arts and Sciences'],
            ['name' => 'Bachelor of Arts in History', 'department' => 'College of Arts and Sciences'],
            
            // College of Business and Accountancy
            ['name' => 'Bachelor of Science in Accountancy', 'department' => 'College of Business and Accountancy'],
            ['name' => 'Bachelor of Science in Accounting Information System', 'department' => 'College of Business and Accountancy'],
            ['name' => 'Bachelor of Science in Business Administration', 'department' => 'College of Business and Accountancy'],
            ['name' => 'Bachelor of Science in Entrepreneurship', 'department' => 'College of Business and Accountancy'],
            ['name' => 'Bachelor of Science in Marketing Management', 'department' => 'College of Business and Accountancy'],
            ['name' => 'Bachelor of Science in Human Resource Management', 'department' => 'College of Business and Accountancy'],
            
            // College of Computer Studies
            ['name' => 'Bachelor of Science in Computer Science', 'department' => 'College of Computer Studies'],
            ['name' => 'Bachelor of Science in Information Technology', 'department' => 'College of Computer Studies'],
            ['name' => 'Bachelor of Science in Information Systems', 'department' => 'College of Computer Studies'],
            ['name' => 'Bachelor of Science in Computer Engineering', 'department' => 'College of Computer Studies'],
            ['name' => 'Associate in Computer Technology', 'department' => 'College of Computer Studies'],
            
            // College of Criminology
            ['name' => 'Bachelor of Science in Criminology', 'department' => 'College of Criminology'],
            ['name' => 'Bachelor of Science in Criminal Justice', 'department' => 'College of Criminology'],
            ['name' => 'Certificate in Criminal Justice', 'department' => 'College of Criminology'],
            
            // College of Education
            ['name' => 'Bachelor of Science in Education', 'department' => 'College of Education'],
            ['name' => 'Bachelor of Elementary Education', 'department' => 'College of Education'],
            ['name' => 'Bachelor of Secondary Education', 'department' => 'College of Education'],
            ['name' => 'Bachelor of Physical Education', 'department' => 'College of Education'],
            ['name' => 'Bachelor of Technical Teacher Education', 'department' => 'College of Education'],
            
            // College of Engineering and Architecture
            ['name' => 'Bachelor of Science in Civil Engineering', 'department' => 'College of Engineering and Architecture'],
            ['name' => 'Bachelor of Science in Computer Engineering', 'department' => 'College of Engineering and Architecture'],
            ['name' => 'Bachelor of Science in Electrical Engineering', 'department' => 'College of Engineering and Architecture'],
            ['name' => 'Bachelor of Science in Mechanical Engineering', 'department' => 'College of Engineering and Architecture'],
            ['name' => 'Bachelor of Science in Architecture', 'department' => 'College of Engineering and Architecture'],
            
            // College of Health Sciences
            ['name' => 'Bachelor of Science in Nursing', 'department' => 'College of Health Sciences'],
            ['name' => 'Bachelor of Science in Medical Technology', 'department' => 'College of Health Sciences'],
            ['name' => 'Bachelor of Science in Pharmacy', 'department' => 'College of Health Sciences'],
            ['name' => 'Bachelor of Science in Radiologic Technology', 'department' => 'College of Health Sciences'],
            ['name' => 'Bachelor of Science in Physical Therapy', 'department' => 'College of Health Sciences'],
            
            // College of Law
            ['name' => 'Juris Doctor', 'department' => 'College of Law'],
            ['name' => 'Bachelor of Laws', 'department' => 'College of Law'],
            ['name' => 'Pre-Law Program', 'department' => 'College of Law'],
            
            // College of Hospitality Management
            ['name' => 'Bachelor of Science in Hospitality Management', 'department' => 'College of Hospitality Management'],
            ['name' => 'Bachelor of Science in Tourism Management', 'department' => 'College of Hospitality Management'],
            ['name' => 'Bachelor of Science in Hotel and Restaurant Management', 'department' => 'College of Hospitality Management'],
            
            // Graduate School
            ['name' => 'Master of Arts in Education', 'department' => 'Graduate School'],
            ['name' => 'Master of Business Administration', 'department' => 'Graduate School'],
            ['name' => 'Master of Science in Computer Science', 'department' => 'Graduate School'],
            ['name' => 'Doctor of Philosophy', 'department' => 'Graduate School'],
            ['name' => 'Doctor of Education', 'department' => 'Graduate School'],
            
            // Senior High School
            ['name' => 'STEM Strand', 'department' => 'Senior High School'],
            ['name' => 'ABM Strand', 'department' => 'Senior High School'],
            ['name' => 'HUMSS Strand', 'department' => 'Senior High School'],
            ['name' => 'GAS Strand', 'department' => 'Senior High School'],
            ['name' => 'TVL Strand', 'department' => 'Senior High School'],
        ];

        foreach ($courses as $courseData) {
            $department = $departments->get($courseData['department']);
            if ($department) {
                Course::create([
                    'name' => $courseData['name'],
                    'department_id' => $department->id,
                ]);
            }
        }
    }
}
