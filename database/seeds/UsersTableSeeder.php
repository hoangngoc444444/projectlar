<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Hoang Ngoc',
            'email' => 'hanh@gmail.com',
            'password' => bcrypt('1234567'),
            'remember_token' => str_random(10),
        ]);
        factory(User::class, 50)->create();
    }
}
