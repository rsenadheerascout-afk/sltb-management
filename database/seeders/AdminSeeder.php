<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sltb.lk'],
            [
                'name'        => 'Depot Manager',
                'password'    => Hash::make('admin@123'),
                'role'        => 'admin',
                'employee_id' => 'E001',
                'status'      => 'active',
                'is_approved' => true,
                'nic'         => '000000000V',
                'phone'       => '0710000000',
                'address'     => 'Yatinuwara Depot, Kandy',
            ]
        );

        User::updateOrCreate(
            ['email' => 'officer@sltb.lk'],
            [
                'name'        => 'Executive Officer',
                'password'    => Hash::make('officer@123'),
                'role'        => 'executive_officer',
                'employee_id' => 'E002',
                'status'      => 'active',
                'is_approved' => true,
                'nic'         => '000000001V',
                'phone'       => '0710000001',
                'address'     => 'Yatinuwara Depot, Kandy',
            ]
        );
    }
}