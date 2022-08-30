<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $users=[
        ['name'=>'Rafi','email'=>'rafi@gmail.com','password'=>'12345678'],
        ['name'=>'Raju','email'=>'raju@gmail.com','password'=>'12345678'],
        ['name'=>'Arif','email'=>'arif@gmail.com','password'=>'12345678'],
      ];
      User::insert($users);
    }
}
