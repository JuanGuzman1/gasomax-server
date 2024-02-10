<?php

namespace App\Imports;

use App\Models\Payments\QuoteConcept;
use App\Models\Users\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuoteConceptsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $charge = $row['cargos'];
            $concept = $row['conceptos'];

            $quoteConcept = new QuoteConcept([
                'charge' => $charge,
                'concept' => $concept
            ]);

            if ($row['departamento']) {
                $department = Department::where('name', $row['departamento'])->firstOrFail();
                $quoteConcept->department()->associate($department);
            }

            $quoteConcept->save();
        }
    }
}
