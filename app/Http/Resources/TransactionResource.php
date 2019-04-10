<?php

namespace App\Http\Resources;

use Money\Money;
use App\Money\Parser;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $parser = app(Parser::class);

        if ($this->type == 'payment') {
            $label = 'Payment From ' . $this->from_label;
        } elseif ($this->type == 'transfer') {
            $label = 'Transfer from ' . $this->from_label . ' to ' . $this->to_label;
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $label,
            'amount' => $parser->moneyToOutput(Money::GBP($this->amount))
        ];
    }
}
