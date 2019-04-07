<?php

namespace App\Http\Controllers\Accounts;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewAccountController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $accountId
     */
    public function __invoke(Request $request, int $id)
    {
        $account = Account::with('latestSheet')
            ->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        return view(
            'accounts.view',
            [
                'account' => $account
            ]
        );
    }
}
