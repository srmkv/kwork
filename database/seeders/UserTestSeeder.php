<?php

namespace Database\Seeders;

use App\Models\Profiles\ProfileIndividual;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Test',
            'email' => '',
            'phone' => '79811111111',
            'verified' => 1,
            'password' => Hash::make(12345678),
        ]);
        
        ProfileIndividual::create([
            'user_id' => $user->id,
            'name' => 'Test', 
            'phone' => '79811111111',
        ]);

        $token = $user->createToken('access_token')->plainTextToken;

        \DB::table('dev_tokens')->insert([

            'user_id' => $user->id,
            'type_user' => 'individual/verified',
            'user_token' => $token
        ]);
    }
}
