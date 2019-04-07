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

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function sheetRows()
    {
        return $this->hasMany(SheetRow::class)
            ->orderBy('label', 'asc');
    }
}
