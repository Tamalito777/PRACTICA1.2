<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
       public function run(): void
    {
        User::create([
            'name' => 'Admin Prueba',
            'email' => 'miday.martinez.j@gmail.com',
            'password' => Hash::make('12345678'),
            'rol' => 'administrador',
        ]);

        User::create([
            'name' => 'Empleado Prueba',
            'email' => 'empleado@gmail.com',
            'password' => Hash::make('12345678'),
            'rol' => 'empleado',
        ]);
    }

    }

