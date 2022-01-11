<?php

use App\FormaPago;
use Illuminate\Database\Seeder;

class FormasPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $fp = new FormaPago();
        $fp->nombre = 'Efectivo';
        $fp->save();

        $fp = new FormaPago();
        $fp->nombre = 'Depósito';
        $fp->save();

        $fp = new FormaPago();
        $fp->nombre = 'Cheque';
        $fp->save();

        $fp = new FormaPago();
        $fp->nombre = 'Crédito';
        $fp->save();
    }
}
