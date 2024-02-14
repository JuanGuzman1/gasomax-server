<?php

namespace App\Imports;

use App\Models\Administration\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        foreach ($collection as $row) {
            $line = $row['giro'];
            $unit = $row['unidad'];

            $quoteConcept = new Unit([
                'line' => $line,
                'unit' => $unit
            ]);

            $quoteConcept->save();
        }
    }
}
