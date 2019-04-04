<?php

namespace App\Http\Controllers\Accounts;

use App\Budget;
use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StartAccountController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $account = Account::with('budget')
            ->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        return view(
            'accounts.start',
            [
                'account' => $account,
                'budget'  => Budget::outputRows($account->budget->sheet_rows),
            ]
        );
    }
}
