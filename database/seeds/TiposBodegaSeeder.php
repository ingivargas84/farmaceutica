<?php

use Illuminate\Database\Seeder;
use App\TipoBodega;

class TiposBodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tb = new TipoBodega();
        $tb->tipo = 'Principal';
        $tb->save();

        $tb = new TipoBodega();
        $tb->tipo = 'Secundaria';
        $tb->save();
    }
}
