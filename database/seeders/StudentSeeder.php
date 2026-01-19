<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Computer Science', 'Engineering', 'Business', 'Medicine', 'Law', 'Arts'];
        $studyLevels = ['undergraduate', 'postgraduate', 'diploma'];
        $genders = ['male', 'female'];

        // Create 50 test students
        for ($i = 1; $i <= 50; $i++) {
            $gender = $genders[array_rand($genders)];
            $fullName = $this->generateName($gender);
            $email = 'student' . $i . '@unilak.ac.rw';

            // Create user first
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            // Create student profile
            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'full_name' => $fullName,
                'gender' => $gender,
                'study_level' => $studyLevels[array_rand($studyLevels)],
                'department' => $departments[array_rand($departments)],
                'year' => rand(1, 4),
                'phone' => '+255' . rand(700000000, 799999999),
                'email' => $email,
            ]);
        }
    }

    private function generateName(string $gender): string
    {
        $maleFirstNames = ['John', 'David', 'Michael', 'James', 'Robert', 'Peter', 'Joseph', 'Daniel', 'Emmanuel', 'Frank'];
        $femaleFirstNames = ['Mary', 'Grace', 'Elizabeth', 'Sarah', 'Anna', 'Joyce', 'Mercy', 'Patricia', 'Ruth', 'Esther'];
        $lastNames = ['Mwakasege', 'Kileo', 'Ngowi', 'Massawe', 'Mrema', 'Lyimo', 'Mushi', 'Swai', 'Pallangyo', 'Minja'];

        $firstName = $gender === 'male'
            ? $maleFirstNames[array_rand($maleFirstNames)]
            : $femaleFirstNames[array_rand($femaleFirstNames)];

        $lastName = $lastNames[array_rand($lastNames)];

        return $firstName . ' ' . $lastName;
    }
}
