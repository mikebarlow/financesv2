<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'budget_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function massTransfers()
    {
        return $this->hasMany(MassTransfer::class);
    }

    public function sheets()
    {
        return $this->hasMany(Sheet::class)
            ->latest();
    }

    public function latestSheet()
    {
        return $this->hasOne(Sheet::class)
            ->latest();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
