<?php

namespace App\Http\Controllers\Api\Sheets;

use DB;
use App\SheetRow;
use App\Transaction;
use App\Money\Parser;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\MakeTransferRequest;

class MakeTransferController extends Controller
{
    protected $moneyParser;

    /**
     * @param Parser $money
     */
    public function __construct(Parser $money)
    {
        $this->moneyParser = $money;
    }

    /**
     * @param MakeTransferRequest $request
     */
    public function __invoke(MakeTransferRequest $request)
    {
        $transfer = $request->request->get('transfer');

        $amount = $this->moneyParser->convertToMoney($transfer['amount']);
        $from = null;
        $to = null;

        if ($transfer['from'] > 0) {
            $from = SheetRow::where('id', $transfer['from'])
                ->first();

            // lame sheet to sheet row check
            if ($from->sheet_id != $transfer['fromSheet']) {
                return response()
                    ->json([
                        'error' => [
                            'The from sheet row does not belong to the sheet!'
                        ]
                    ])
                    ->setStatusCode(422);
            }
        }

        if ($transfer['to'] > 0) {
            $to = SheetRow::where('id', $transfer['to'])
                ->first();

            // lame sheet to sheet row check
            if ($to->sheet_id != $transfer['toSheet']) {
                return response()
                    ->json([
                        'error' => [
                            'The to sheet row does not belong to the sheet!'
                        ]
                    ])
                    ->setStatusCode(422);
            }
        }

        try {
            DB::transaction(function () use ($transfer, $from, $to, $amount) {
                if ($from !== null) {
                    $from->transferOut($amount);
                } else {
                    $from = $transfer['fromLabel'];
                }

                if ($to !== null) {
                    $to->transferIn($amount);
                } else {
                    $to = $transfer['toLabel'];
                }

                Transaction::transfer($from, $to, $amount);
            });
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving the transfer',
                            $e->getMessage(),
                        ]
                    ]
                )
                ->setStatusCode(
                    Response::HTTP_BAD_REQUEST
                );
        }

        // success return
        return response()
            ->json(
                [
                    'msg' => 'Transfer logged',
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
