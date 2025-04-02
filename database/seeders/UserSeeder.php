<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'pegawai_id'    =>  1,
            'email'         =>  'admin@admin.com',
            'password'      =>  Hash::make('123'),
            'role_id'       =>  1,
            'status'        =>  1,
        ]);
    }
}
