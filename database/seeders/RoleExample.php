<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleExample extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name'  => 'admin'],
            ['name'  => 'supervisor'],
            ['name'  => 'teacher'],
            ['name'  => 'student'],
        ]);

        DB::table('users')->insert([
            'email'  => 'bmvt@hcmut.edu.vn',
            'name' => 'BMVT',
            'password' =>  Hash::make('Bmvt@hcmut'),
            'role_id' => 1
        ]);
        DB::table('users')->insert([
            'email'  => 'gv@hcmut.edu.vn',
            'name' => 'GVHCMUT',
            'password' =>  Hash::make('gv@hcmut'),
            'role_id' => 3
        ]);
        DB::table('users')->insert([
            'email'  => 'supervisor@hcmut.edu.vn',
            'name' => 'GVHCMUT',
            'password' =>  Hash::make('supervisor@hcmut'),
            'role_id' => 2
        ]);
    }
}
