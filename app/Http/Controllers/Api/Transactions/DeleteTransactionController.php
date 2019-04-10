<?php

namespace App\Http\Controllers\Api\Transactions;

use DB;
use Money\Money;
use App\SheetRow;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class DeleteTransactionController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $transaction = Transaction::where('id', $id)
            ->first();
        if ($transaction === null) {
            return response()
                ->json([
                    'The transaction does not exist',
                ])
                ->setStatusCode(404);
        }

        $amount = Money::GBP($transaction->amount);

        try {
            DB::transaction(function () use (&$transaction, &$from, &$to, $amount) {
                if ($transaction->type == 'payment') {
                    $row = SheetRow::where('id', $transaction->from_row_id)
                        ->first();

                    $row->refund($amount);
                } elseif ($transaction->type == 'transfer') {
                    if ($transaction->from_row_id !== null) {
                        $from = SheetRow::where('id', $transaction->from_row_id)
                            ->first();

                        $from->undoTransferOut($amount);
                    }

                    if ($transaction->to_row_id !== null) {
                        $to = SheetRow::where('id', $transaction->to_row_id)
                            ->first();

                        $to->undoTransferIn($amount);
                    }
                }

                $transaction->delete();
            });
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem deleting the transaction',
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
                    'msg' => 'Transaction Deleted',
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
