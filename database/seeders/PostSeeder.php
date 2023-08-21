<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the number of posts you want to create
        $numberOfPosts = 100;
        // Use the factory to create dummy posts
        Post::factory($numberOfPosts)->create();
    }
}
