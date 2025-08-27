<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un solo dato con Random haciendo uso de insert
        // DB::table('posts')->insert([
        //     'title'=> Str::random(10),
        //     'content' => Str::random(10).'example@gmail.com',
        //     'status' => true,
        // ]);

        // Crear inserts o datos Mocks con Factory
        Post::factory()->count(50)->create();
    }
}
