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

    /**
     * @param Money $amount
     * @return void
     */
    public function refund(Money $amount)
    {
        $payments = Money::GBP($this->payments);

        $this->payments = $payments->subtract($amount)->getAmount();

        $this->save();
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function transferOut(Money $amount)
    {
        $transOut = Money::GBP($this->transfer_out);

        $this->transfer_out = $transOut->add($amount)->getAmount();

        $this->save();
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function undoTransferOut(Money $amount)
    {
        $transOut = Money::GBP($this->transfer_out);

        $this->transfer_out = $transOut->subtract($amount)->getAmount();

        $this->save();
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function transferIn(Money $amount)
    {
        $transIn = Money::GBP($this->transfer_in);

        $this->transfer_in = $transIn->add($amount)->getAmount();

        $this->save();
    }

    /**
     * @param Money $amount
     * @return void
     */
    public function undoTransferIn(Money $amount)
    {
        $transIn = Money::GBP($this->transfer_in);

        $this->transfer_in = $transIn->subtract($amount)->getAmount();

        $this->save();
    }
}
