<?php

namespace App\Http\Controllers\Accounts;

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

        $account = new Account([
            'name' => $request->request->get('name'),
            'budget_id' => $request->request->get('budget_id'),
        ]);

        try {
            $user->accounts()
                ->save($account);
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
