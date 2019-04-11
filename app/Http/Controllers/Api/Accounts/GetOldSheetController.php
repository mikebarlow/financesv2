<?php

namespace App\Http\Controllers\Api\Accounts;

use App\Budget;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OldAccountResource;

class GetOldSheetController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     * @param int $sheetId
     */
    public function __invoke(Request $request, int $id, int $sheetId)
    {
        $account = Account::with(
            [
                'sheets' => function ($query) use ($sheetId) {
                    return $query->where('id', $sheetId)
                        ->with('sheetRows');
                },
            ]
        )->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        return response()
            ->json(
                [
                    'account' => new OldAccountResource($account),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
