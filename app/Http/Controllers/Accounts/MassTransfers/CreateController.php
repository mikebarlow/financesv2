<?php

namespace App\Http\Controllers\Accounts\MassTransfers;

use App\Budget;
use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;

class CreateController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $accountId
     */
    public function __invoke(Request $request, int $id)
    {
        $account = Account::with(['budget', 'latestSheet'])
            ->where('id', $id)
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        $allAccounts = $request->user()
            ->accounts()
            ->with([
                'budget',
                'latestSheet',
            ])
            ->orderBy('name', 'asc')
            ->get();

        return view(
            'accounts.masstransfers.create',
            [
                'account' => $account,
                'allAccounts' => $allAccounts,
                'budgetRows' => Budget::outputRows($account->budget->sheet_rows),
            ]
        );
    }
}
