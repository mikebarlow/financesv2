<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetRow extends Model
{
    protected $fillable = [
        'budget_id',
        'label',
        'budget',
        'brought_forward',
        'payments',
        'transfer_in',
        'transfer_out',
    ];

    public function sheet()
    {
        return $this->belongsTo(Sheet::class);
    }
}
