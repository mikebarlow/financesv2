<?php

namespace App\Http\Controllers\Accounts\MassTransfers;

use App\Budget;
use App\Account;
use App\MassTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;

class EditController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     * @param int $transferId
     */
    public function __invoke(Request $request, int $id, int $transferId)
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

        $transfer = MassTransfer::where('id', $transferId)
            ->first();

        return view(
            'accounts.masstransfers.edit',
            [
                'account' => $account,
                'allAccounts' => $allAccounts,
                'transfer' => $transfer,
                'budgetRows' => Budget::outputRows($account->budget->sheet_rows),
            ]
        );
    }
}
