<?php

namespace App\Database\Seeds;

use App\Models\User;
use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
	public function run()
	{
		$model = new User();
		for ($i = 0; $i < 100; $i++) {
			$model->insert($this->generateUser());
		}
	}

	private function generateUser(): array
	{
		$userTypes = ['admin', 'default', 'member'];

		$deletedAt = [null, null, null, date('Y-m-d H:i:s')];

		$faker = Factory::create();
		return [
			'name' => $faker->name(),
			'email' => $faker->email,
			'password' => 'password',
			'type' => $userTypes[rand(0,2)],
			'deleted_at' => $deletedAt[rand(0, 3)]
		];
	}
}
