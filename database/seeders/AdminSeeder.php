<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Admin::create([
            'name' => 'Administrator',
            'email' => 'rubickking04@gmail.com',
            'password' => bcrypt('admin123')
        ]);
        $success['token'] = $user->createToken('AdminToken')->plainTextToken;
        $success['name'] = $user->name;
        $user->tokens()->create([
            'name' => $user->name,
            'token' => hash('sha256', $success['token']),
            'abilities' => ['*'],
        ]);
    }
}
