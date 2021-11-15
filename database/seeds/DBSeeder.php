<?php

use Illuminate\Database\Seeder;

class DBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 'admin', 1)->create();
        factory(App\User::class, 'user_pass', 1)->create();
        factory(App\User::class, 'user', 20)->create();
        factory(App\Category::class, 'categories1', 1)->create();
        factory(App\Category::class, 'categories2', 1)->create();
        factory(App\Category::class, 'categories3', 1)->create();
    }
}
