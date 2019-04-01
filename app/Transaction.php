<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'from_row_id',
        'from_label',
        'to_row_id',
        'to_label',
        'amount',
    ];

    public function fromRow()
    {
        return $this->belongsTo(SheetRow::class, 'from_row_id');
    }

    public function toRow()
    {
        return $this->belongsTo(SheetRow::class, 'to_row_id');
    }
}
