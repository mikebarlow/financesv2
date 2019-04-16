<?php

namespace App\Http\Resources;

use Money\Money;
use App\Money\Parser;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
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

        $transfer = parent::toArray($request);
        $rows = collect(
            \GuzzleHttp\json_decode(
                $transfer['transfers'],
                true
            )
        );

        $transfer['rows'] = $rows
            ->map(
                function ($row) use ($parser) {
                    $row['amount'] = $parser->moneyToOutput(
                        Money::GBP($row['amount'])
                    );

                    return $row;
                }
            )
            ->toArray();

        return $transfer;
    }
}
