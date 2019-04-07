<?php

namespace App\Http\Resources;

use Money\Money;
use App\Money\Parser;
use Illuminate\Http\Resources\Json\JsonResource;

class SheetResource extends JsonResource
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

        $sheet = parent::toArray($request);
        $sheet['totals'] = [
            'budget'          => Money::GBP(0),
            'broughtForward'  => Money::GBP(0),
            'payments'        => Money::GBP(0),
            'transIn'         => Money::GBP(0),
            'transOut'        => Money::GBP(0),
            'totAcross'       => Money::GBP(0),
            'totDown'         => Money::GBP(0),
        ];

        $sheet['rows'] = $this->sheetRows
            ->map(
                function ($row) use ($parser, &$sheet) {
                    $budget = Money::GBP($row->budget);
                    $broughtForward = Money::GBP($row->brought_forward);
                    $payments = Money::GBP($row->payments);
                    $transIn = Money::GBP($row->transfer_in);
                    $transOut = Money::GBP($row->transfer_out);

                    $in = $budget->add($broughtForward, $transIn);
                    $out = $payments->add($transOut);
                    $total = $in->subtract($out);

                    $sheet['totals']['budget'] = $sheet['totals']['budget']->add($budget);
                    $sheet['totals']['broughtForward'] = $sheet['totals']['broughtForward']->add($broughtForward);
                    $sheet['totals']['payments'] = $sheet['totals']['payments']->add($payments);
                    $sheet['totals']['transIn'] = $sheet['totals']['transIn']->add($transIn);
                    $sheet['totals']['transOut'] = $sheet['totals']['transOut']->add($transOut);
                    $sheet['totals']['totDown'] = $sheet['totals']['totDown']->add($total);

                    return [
                        'id' => $row->id,
                        'budget_id' => $row->budget_id,
                        'label' => $row->label,
                        'budget' => $parser->moneyToOutput($budget),
                        'broughtForward' => $parser->moneyToOutput($broughtForward),
                        'payments' => $parser->moneyToOutput($payments),
                        'transIn' => $parser->moneyToOutput($transIn),
                        'transOut' => $parser->moneyToOutput($transOut),
                        'total' => $parser->moneyToOutput($total),
                    ];
                }
            );

        $totIn = $sheet['totals']['budget']->add(
            $sheet['totals']['broughtForward'],
            $sheet['totals']['transIn']
        );
        $totOut = $sheet['totals']['payments']->add($sheet['totals']['transOut']);
        $sheet['totals']['totAcross'] = $totIn->subtract($totOut);

        $sheet['totalsMatch'] = $sheet['totals']['totAcross']->equals($sheet['totals']['totDown']);

        $sheet['totals'] = collect($sheet['totals'])
            ->mapwithKeys(
                function ($item, $key) use ($parser) {
                    return [$key => $parser->moneyToOutput($item)];
                }
            )
            ->toArray();

        return $sheet;
    }
}
