<?php

namespace App\Http\Controllers\Accounts;

use App\Sheet;
use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewOldSheetController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     * @param int $sheetId
     */
    public function __invoke(Request $request, int $id, int $sheetId)
    {
        $account = Account::with('latestSheet')
            ->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        if ($account->latestSheet->id == $sheetId) {
            return redirect()
                ->route('accounts.view', ['id' => $account->id]);
        }

        $sheet = Sheet::where('id', $sheetId)
            ->first();

        return view(
            'accounts.old-sheet',
            [
                'account' => $account,
                'sheet' => $sheet,
            ]
        );
    }
}
