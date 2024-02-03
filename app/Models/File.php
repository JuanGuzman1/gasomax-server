<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'localName',
        'name',
        'tag',
        'extension',
        'size',
        'path',
        'fileable_id',
        'fileable_type'
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
