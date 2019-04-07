<?php

namespace App\Http\Controllers\Api\Accounts;

use App\Budget;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;

class GetLatestSheetController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $account = Account::with('latestSheet.sheetRows')
            ->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        return response()
            ->json(
                [
                    'account' => new AccountResource($account),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
