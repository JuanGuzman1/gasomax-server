<?php

namespace Database\Seeders;

use App\Imports\QuoteConceptsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Seeder;

class QuoteConceptsExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('resources/imports/cuentas_contables.xlsx'); // Ruta al archivo Excel
        Excel::import(new QuoteConceptsImport, $file);
    }
}
