<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
	private $totalUsers = 6;

    public function run() {

    	$counter = 0;

    	$this->command->line("");
    	$this->command->line("Creating Users");

    	$faker = \Faker\Factory::create('en_US');

    	for($i = 0; $i < $this->totalUsers; $i++){
    		$counter++;
    		$user = $this->fakeUser($faker, 'user', $i);
    		\DB::table('users')->insert($user);
    		$this->command->info("Created User $counter/$this->totalUsers: " . $user['name']);
    	}

    }

    private function FakeUser(\Faker\Generator $faker, $type, $idx, $softDelete = false){

    	$createdAt = \Carbon\Carbon::now()->subdays(600);

    	$gender = rand(0,1) == 1 ? 'male' : 'female';
        $token = \Str::random(10);

    	return [

    		'name' => $faker->name($gender),
    		'email' => $type.$idx.'@mail.com',
    		'password' => bcrypt('123'),
    		'remember_token' => $token,
    		'created_at' => $createdAt,
    		'updated_at' => $faker->dateTimeBetween($createdAt),
    		'deleted_at' => $softDelete ? $faker->dateTimeBetween($createdAt) : null,

    	];

    }
}
