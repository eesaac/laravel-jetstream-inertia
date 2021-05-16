<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

//        $users = User::factory()->count(50)->make();
        $tags = Tag::factory(50)->create();

        User::factory(30)->create()->each(function ($user) use ($tags){
            Post::factory(5)->create([
                'user_id' => $user->id
            ])->each(function ($post) use ($tags){
                $post->tags()->attach(
                    $tags->random(rand(2,3))->pluck('id')->toArray()
                );
            });
        });



    }
}
