<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Course;
use App\Models\Media;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Media::factory(20)->create();
        Role::insert([
            [
                'guard_name' => 'web',
                'name' => 'admin'
            ],
            [
                'guard_name' => 'web',
                'name' => 'teacher'
            ],
            [
                'guard_name' => 'web',
                'name' => 'student'
            ],
        ]);
        Category::insert([
            [
                'name' => 'programming',
                'parent_id' => null,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'languages',
                'parent_id' => null,
                'created_at' => Carbon::now(),
            ],

            [
                'name' => 'c++',
                'parent_id' => 1,
                'created_at' => Carbon::now(),
            ],

            [
                'name' => 'php',
                'parent_id' => 1,
                'created_at' => Carbon::now(),
            ],

            [
                'name' => 'python',
                'parent_id' => 1,
                'created_at' => Carbon::now(),
            ],

            [
                'name' => 'english',
                'parent_id' => 2,
                'created_at' => Carbon::now(),
            ],

            [
                'name' => 'germany',
                'parent_id' => 2,
                'created_at' => Carbon::now(),
            ],
        ]);
        User::create([
            'first_name' => 'mario',
            'last_name' => 'andrawos',
            'email' => 'almowafratys09@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('student');

        User::create([
            'first_name' => 'mounir',
            'last_name' => 'maleh',
            'email' => 'mounirtoo.22@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('teacher');

        User::factory(10)->create();

        Course::factory(20)->create();


    }
}
