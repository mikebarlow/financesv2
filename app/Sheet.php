<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'budget_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function sheetRows()
    {
        return $this->hasMany(SheetRow::class);
    }
}
