<?php

namespace Database\Seeders;

use App\Imports\UnitsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class UnitsExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('resources/imports/unidades_negocio.xlsx'); // Ruta al archivo Excel
        Excel::import(new UnitsImport, $file);
    }
}
