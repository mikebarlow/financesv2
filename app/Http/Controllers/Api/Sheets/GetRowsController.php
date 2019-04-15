<?php

namespace App\Http\Controllers\Api\Sheets;

use App\Sheet;
use App\SheetRow;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\SheetRowResource;

class GetRowsController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $rows = SheetRow::where('sheet_id', $id)
            ->get();

        $sheet = Sheet::with('account')
            ->where('id', $id)
            ->first();

        return response()
            ->json(
                [
                    'rows' => SheetRowResource::collection($rows),
                    'accountName' => $sheet->account->name,
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
