<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    DB::table('users')->truncate();
    DB::table('users')->insert([
      'uuid'=>(string) Str::uuid(),
      'name'=>'Administrator',
      'username'=>'admin',
      'password'=>bcrypt('admin123'),
      'api_token'=>Str::random(100),
      'role'=>'admin',
    ]);
  }
}
