<?php

namespace App;

use Money\Money;
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

    /**
     * @param SheetRow $row
     * @param Money $money
     */
    public static function payment(SheetRow $row, Money $money)
    {
        $transaction = new static;
        $transaction->type = 'payment';
        $transaction->from_row_id = $row->id;
        $transaction->from_label = $row->label;
        $transaction->amount = $money->getAmount();
        $transaction->save();
    }
}
