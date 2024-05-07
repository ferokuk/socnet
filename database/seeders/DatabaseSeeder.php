<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ru_RU');

        // Заполнение таблицы users
        for ($i = 0; $i < 200; $i++) {
            DB::table('users')->insert([
                'name' => preg_replace("#\.#","_",$faker->unique()->userName),
                'email' => $faker->unique()->email,
                'password' => bcrypt('password'), // предполагая, что все пароли одинаковы
                'image' => 'image.png', // предполагая, что изображение одно и то же для всех пользователей
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заполнение таблицы friendships
        for ($i = 0; $i < 100; $i++) {
            DB::table('friendships')->insert([
                'user_id' => $faker->numberBetween(1, 200),
                'friend_id' => $faker->numberBetween(1, 200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заполнение таблицы posts
        for ($i = 0; $i < 400; $i++) {
            DB::table('posts')->insert([
                'user_id' => $faker->numberBetween(1, 200),
                'content' => $faker->realText(200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заполнение таблицы likes
        for ($i = 0; $i < 2000; $i++) {
            DB::table('likes')->insert([
                'user_id' => $faker->numberBetween(1, 200),
                'post_id' => $faker->numberBetween(1, 400),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заполнение таблицы comments
        for ($i = 0; $i < 500; $i++) {
            DB::table('comments')->insert([
                'user_id' => $faker->numberBetween(1, 200),
                'post_id' => $faker->numberBetween(1, 400),
                'content' => $faker->realText(100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Заполнение таблицы messages
        for ($i = 0; $i < 10000; $i++) {
            DB::table('messages')->insert([
                'sender_id' => $faker->numberBetween(1, 200),
                'receiver_id' => $faker->numberBetween(1, 200),
                'content' => $faker->realText(150),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
