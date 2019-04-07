<?php

namespace App\Http\Controllers\Api\Sheets;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class GetTransactionsController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {






        return response()
            ->json(
                [
                    'transactions' => Transaction::collection($transactions),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
