<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['name' => 'Pak Slamet', 'type' => 'security', 'phone' => '081234560001', 'base_salary' => 3000000, 'salary_type' => 'monthly', 'status' => 'active'],
            ['name' => 'Pak Rudi', 'type' => 'security', 'phone' => '081234560002', 'base_salary' => 3000000, 'salary_type' => 'monthly', 'status' => 'active'],
            ['name' => 'Bu Yanti', 'type' => 'cleaning', 'phone' => '081234560003', 'base_salary' => 100000, 'salary_type' => 'daily', 'status' => 'active'],
            ['name' => 'Pak Maman', 'type' => 'garbage', 'phone' => '081234560004', 'base_salary' => 150000, 'salary_type' => 'daily', 'status' => 'active'],
            ['name' => 'Pak Deni', 'type' => 'technical', 'phone' => '081234560005', 'base_salary' => 2500000, 'salary_type' => 'monthly', 'status' => 'active'],
        ];

        foreach ($employees as $employee) {
            $employee['employee_code'] = Employee::generateEmployeeCode($employee['type']);
            Employee::create($employee);
        }
    }
}
