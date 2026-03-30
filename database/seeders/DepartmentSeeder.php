<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'College of Arts and Sciences', 'code' => 'CAS'],
            ['name' => 'College of Business and Accountancy', 'code' => 'CBA'],
            ['name' => 'College of Computer Studies', 'code' => 'CCS'],
            ['name' => 'College of Criminology', 'code' => 'CCRIM'],
            ['name' => 'College of Education', 'code' => 'CED'],
            ['name' => 'College of Engineering and Architecture', 'code' => 'CEA'],
            ['name' => 'College of Health Sciences', 'code' => 'CHS'],
            ['name' => 'College of Law', 'code' => 'CLAW'],
            ['name' => 'College of Hospitality Management', 'code' => 'CHM'],
            ['name' => 'Graduate School', 'code' => 'GS'],
            ['name' => 'Senior High School', 'code' => 'SHS'],
            ['name' => 'Other', 'code' => 'OTHER'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
