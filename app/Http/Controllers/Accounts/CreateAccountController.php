<?php

namespace App\Http\Controllers\Accounts;

use App\User;
use App\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;

class CreateAccountController extends Controller
{
    /**
     *
     * @param CreateAccountRequest $request
     */
    public function __invoke(CreateAccountRequest $request)
    {
        $user = $request->user();
        $share = $request->request->get('share', false);

        $account = new Account([
            'name' => $request->request->get('name'),
            'budget_id' => $request->request->get('budget_id'),
        ]);

        $shareWith = User::where('id', '!=', $user->id)
            ->first();

        try {
            $account = $user->accounts()
                ->save($account);

            if ($share) {
                $shareWith->accounts()
                    ->attach($account->id);
            }
        } catch (\Exception $e) {
            return back()
                ->with([
                    'errorMsg' => $e->getMessage(), //'There was a problem saving the account',
                ]);
        }

        // success return
        return redirect()
            ->route('accounts.list')
            ->with('successMsg', 'Account Created');
    }
}
