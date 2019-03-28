<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'sheet_rows',
    ];

    public function users()
    {
        return $this->belongsToMany(\App\User::class);
    }
}
