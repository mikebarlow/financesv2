<?php

namespace App\Http\Controllers\Api\Sheets;

use App\SheetRow;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;

class GetTransactionsController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $rowIds = SheetRow::where('sheet_id', $id)
            ->pluck('id');

        $transactions = Transaction::whereIn('from_row_id', $rowIds)
            ->orWhereIn('to_row_id', $rowIds)
            ->orderBy('id', 'desc')
            ->get();

        return response()
            ->json(
                [
                    'transactions' => TransactionResource::collection($transactions),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
