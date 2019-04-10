<?php

namespace App\Http\Controllers\Api\Sheets;

use DB;
use App\SheetRow;
use App\Transaction;
use App\Money\Parser;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\MakePaymentRequest;

class MakePaymentController extends Controller
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
     * @param MakePaymentRequest $request
     */
    public function __invoke(MakePaymentRequest $request)
    {
        $payment = $request->request->get('payment');

        $amount = $this->moneyParser->convertToMoney($payment['amount']);
        $row = SheetRow::where('id', $payment['row'])
            ->first();

        // lame sheet to sheet row check
        if ($row->sheet_id != $request->request->get('sheet_id')) {
            return response()
                ->json([
                    'The sheet row does not belong to the sheet!'
                ])
                ->setStatusCode(422);
        }

        try {
            DB::transaction(function () use ($row, $amount) {
                $row->pay($amount);

                Transaction::payment($row, $amount);
            });
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving the payment',
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
                    'msg' => 'Payment logged',
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
