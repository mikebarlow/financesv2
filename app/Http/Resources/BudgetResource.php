<?php

namespace App\Http\Resources;

use Money\Money;
use App\Money\Parser;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
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

        $budget = parent::toArray($request);
        $rows = collect(
            \GuzzleHttp\json_decode(
                $budget['sheet_rows'],
                true
            )
        );

        $budget['rows'] = $rows
            ->mapWithKeys(
                function ($row) use ($parser) {
                    $row['amount'] = $parser->moneyToOutput(
                        Money::GBP($row['amount'])
                    );
                    $row['name'] = ucfirst($row['name']);

                    return [$row['id'] => $row];
                }
            )
            ->sortBy(
                function ($item, $key) {
                    return $item['name'];
                }
            )
            ->toArray();

        return $budget;
    }
}
