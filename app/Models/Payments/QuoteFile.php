<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Model;

class QuoteFile extends Model
{
    protected $table = 'quote_files';
    protected $fillable = [
        'localName',
        'name',
        'tag',
        'description',
        'extension',
        'size',
        'path',
        'quote_id',
        'provider',
        'amount',
        'deliveryDate',
        'selectedQuoteFile'
    ];
}
