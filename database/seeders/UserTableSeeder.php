<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(['email' => 'portal_manager@mailinator.com'],[
			'name' => 'Administrator',
			'email' => 'portal_manager@mailinator.com',
			'password' => bcrypt('pass@portal'),
			'role' => 1,
			'created_at' => date('Y-m-d H:i:s'),
		]);
        $user = User::updateOrCreate(['email' => 'developersd.shinedezign@gmail.com'],[
			'name' => 'Developersd',
			'email' => 'developersd.shinedezign@gmail.com',
			'password' => bcrypt('pass@user'),
			'created_at' => date('Y-m-d H:i:s'),
		]);
        $user = User::updateOrCreate(['email' => 'developersd.shinedezign@mailinator.com'],[
			'name' => 'Developersd',
			'email' => 'developersd.shinedezign@mailinator.com',
			'password' => bcrypt('pass@user'),
			'created_at' => date('Y-m-d H:i:s'),
		]);
    }
}
