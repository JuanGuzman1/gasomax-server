<?php

namespace App\Models\Payments;

use App\Models\Users\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteConcept extends Model
{
    protected $table = 'quote_concepts';
    protected $fillable = [
        'concept',
        'charge',
        'department_id'
    ];

    /**
     * Get the department of the concept.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
