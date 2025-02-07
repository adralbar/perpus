<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'rafe@gmail.com',
            'nama' => 'rafe',
            'password' => bcrypt('1234'),
            'id' => '2',  // default
            'role_id' => '2',  // default
        ]);
    }
}
