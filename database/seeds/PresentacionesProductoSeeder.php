<?php

use App\PresentacionProducto;
use Illuminate\Database\Seeder;

class PresentacionesProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $pp = new PresentacionProducto();
        $pp->presentacion = 'Ampolla';
        $pp->save();

        $pp = new PresentacionProducto();
        $pp->presentacion = 'Vial';
        $pp->save();

        $pp = new PresentacionProducto();
        $pp->presentacion = 'Unidad';
        $pp->save();
        
    }
}
