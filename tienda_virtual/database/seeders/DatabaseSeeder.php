<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desactivar claves foráneas
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('carritos')->truncate();
        DB::table('ordens')->truncate();
        DB::table('productos')->truncate();
        DB::table('users')->truncate();
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Usuarios de ejemplo
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@tienda.com',
            'is_admin' => true,
            'password' => bcrypt('admin123'),
        ]);
        User::factory()->create([
            'name' => 'Cliente Ejemplo',
            'email' => 'cliente@correo.com',
            'is_admin' => false,
            'password' => bcrypt('cliente123'),
        ]);

        // Crear productos realistas y coherentes
        Producto::factory(30)->create();
    }
}
