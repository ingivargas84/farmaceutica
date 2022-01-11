<?php

use Illuminate\Database\Seeder;
use App\Cliente;
use App\Proveedor;
use App\territorios;

class DistribuidoraAngelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $t = territorios::create([
          'territorio' => 'Distribuidora',
          'descripcion' => 'Distribuidora'
        ]);

        $t = territorios::findOrFail($t->id);
        $t->id = 0;
        $t->save();

        $cli = Cliente::create([
              'nit' => '5297559',
              'nombre_cliente' => 'Distribuidora el Angel',
              'dias_credito' => 0,
              'direccion' => '4 Avenida A, 6C-23 APTO. 33, Zona 9 Cerezos Quetzaltenango, Quetzaltenango',
              'territorio' => 0
            ]);

          $cliente = Cliente::findOrFail($cli->id);
          $cliente->id = 0;
          $cliente->save();

        $pro = Proveedor::create([
              'nit' => '5297559',
              'nombre_comercial' => 'Distribuidora el Angel',
              'nombre_legal' => 'Distribuidora el Angel',
              'direccion' => '4 Avenida A, 6C-23 APTO. 33, Zona 9 Cerezos Quetzaltenango, Quetzaltenango',
              'dias_credito' => 0,
            ]);

        $proveedor = Proveedor::findOrFail($pro->id);
        $proveedor->id = 0;
        $proveedor->save();



    }
}
