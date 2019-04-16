<?php

namespace App\Http\Controllers\Api\MassTransfers;

use App\MassTransfer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransferResource;

class GetTransferController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $transfer = MassTransfer::where('id', $id)
            ->first();

        return response()
            ->json(
                [
                    'transfer' => new TransferResource($transfer),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
