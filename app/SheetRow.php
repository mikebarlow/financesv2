<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetRow extends Model
{
    protected $fillable = [
        'label',
        'budget',
        'brought_forward',
        'payments',
        'transfers_in',
        'transfers_out',
    ];

    public function sheet()
    {
        return $this->belongsTo(Sheet::class);
    }
}
