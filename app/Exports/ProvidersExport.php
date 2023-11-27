<?php

namespace App\Exports;

use App\Models\Administration\Provider;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProvidersExport implements FromCollection, WithHeadings
{
    protected $filters;
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $name = $this->filters['name'];
        $contact = $this->filters['contact'];
        $rfc = $this->filters['rfc'];

        return Provider::select('id', 'name', 'contact', 'rfc', 'address', 'phone', 'email')
            ->when($name, function ($query) use ($name) {
                return $query->where('name',  'like', '%' . $name . '%');
            })->when($contact, function ($query) use ($contact) {
                return $query->where('contact',  'like', '%' . $contact . '%');
            })->when($rfc, function ($query) use ($rfc) {
                return $query->where('rfc', 'like', '%' . $rfc . '%');
            })->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["#", "Proveedor", "Contacto", "RFC", "Direccion", "Teléfono", "Correo electrónico"];
    }
}
