<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    protected $table = 'purchase_request_details';
    protected $fillable = [
        'charge',
        'concept',
        'movementType',
        'observation',
        'totalAmount',
        'paymentAmount',
        'balance',
    ];
}
