<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of sample posts
        $posts = [
            [
                'title' => 'First Post',
                'content' => 'This is the content of the first post.'
            ],
            [
                'title' => 'Second Post',
                'content' => 'This is the content of the second post.'
            ],
            [
                'title' => 'Third Post',
                'content' => 'This is the content of the third post.'
            ],
            [
                'title' => 'Laravel Livewire',
                'content' => 'Livewire makes building dynamic interfaces simple.'
            ],
            [
                'title' => 'Repository Pattern',
                'content' => 'The repository pattern helps separate business logic from data access.'
            ]
        ];

        // Insert posts into the database
        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}