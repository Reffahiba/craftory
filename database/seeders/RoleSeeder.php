<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'admin',
            'penjual',
            'pembeli',
        ];

        foreach($data as $role){
            Role::create([
                'nama_role' => $role,
            ]);
        }
    }
}
