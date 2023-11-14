<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name' => 'ikonic',
            'email' => 'ikonic@test.com',
            'type' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::factory(10)->create();
        Feedback::factory(50)->create();
        Comment::factory(50)->create();
        Vote::factory(50)->create();
    }
}
