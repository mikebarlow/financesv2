<?php

namespace App;

use Money\Money;
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

    /**
     * @param Money $amount
     * @return void
     */
    public function pay(Money $amount)
    {
        $payments = Money::GBP($this->payments);

        $this->payments = $payments->add($amount)->getAmount();

        $this->save();
    }
}
