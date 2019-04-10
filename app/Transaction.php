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

    /**
     * @param SheetRow|string $from
     * @param SheetRow|string $to
     * @param Money $money
     */
    public static function transfer($from, $to, Money $money)
    {
        $transaction = new static;
        $transaction->type = 'transfer';

        $diffSheets = isset($from->sheet_id, $to->sheet_id) && $from->sheet_id !== $to->sheet_id;

        if ($from instanceof SheetRow) {
            $transaction->from_row_id = $from->id;

            if ($diffSheets) {
                $transaction->from_label = $from->sheet->account->name . ':' . $from->label;
            } else {
                $transaction->from_label = $from->label;
            }
        } else {
            $transaction->from_label = $from;
        }

        if ($to instanceof SheetRow) {
            $transaction->to_row_id = $to->id;

            if ($diffSheets) {
                $transaction->to_label = $to->sheet->account->name . ':' . $to->label;
            } else {
                $transaction->to_label = $to->label;
            }
        } else {
            $transaction->to_label = $to;
        }

        $transaction->amount = $money->getAmount();
        $transaction->save();
    }
}
