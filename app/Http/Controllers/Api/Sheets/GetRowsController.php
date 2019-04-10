<?php

namespace App\Http\Controllers\Api\Sheets;

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

        return response()
            ->json(
                [
                    'rows' => SheetRowResource::collection($rows),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
