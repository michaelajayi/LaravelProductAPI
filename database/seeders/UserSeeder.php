<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert(array(
      array(
        'id' => 'f3aa4117-849b-4310-b921-234aa33d9814',
        'name' => 'John Doe',
        'email' => 'jdoe@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ), array(
        'id' => '48e969c7-3b5e-4d7f-9c27-808dfd9abb74',
        'name' => 'Harry White',
        'email' => 'harry@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ), array(
        'id' => '83b5c393-bcad-4132-b067-bbb01ef74a97',
        'name' => 'Simon Cowell',
        'email' => 'simon@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'user',
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ),
    ));
  }
}
