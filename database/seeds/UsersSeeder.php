<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        User::truncate();

        $superadminRole = Role::create(['name' => 'Super-Administrador']);
        $adminRole = Role::create(['name' => 'Administrador']);
        $vendedorRole = Role::create(['name' => 'Vendedor']);
        $contadorRole = Role::create(['name' => 'Contador']);

        $user = new user;
        $user->name = 'Super Administrador';
        $user->email= 'superadmin@gmail.com';
        $user->password = bcrypt('superadmin');
        $user->username = 'superadmin';
        $user->estado = 1;
        $user->save();
        $user->assignRole($superadminRole); 

        $user = new user;
        $user->name = 'Administrador';
        $user->email= 'admin@gmail.com';
        $user->password = bcrypt('admin');
        $user->username = 'admin';
        $user->estado = 1;
        $user->save();
        $user->assignRole($adminRole);

        $user = new user;
        $user->name = 'Vendedor';
        $user->email= 'vendedor@gmail.com';
        $user->password = bcrypt('vendedor');
        $user->username = 'vendedor';
        $user->estado = 1;
        $user->save();
        $user->assignRole($vendedorRole); 

        $user = new user;
        $user->name = 'Contador';
        $user->email= 'contador@gmail.com';
        $user->password = bcrypt('contador');
        $user->username = 'contador';
        $user->estado = 1;
        $user->save();
        $user->assignRole($contadorRole);  

    }
}
