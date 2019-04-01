<?php

namespace App\Http\Controllers\Accounts;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateAccountController extends Controller
{
    /**
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        return view(
            'accounts.create',
            [
                'budgets' => $request->user()
                    ->budgets()
                    ->orderBy('name', 'asc')
                    ->get(),
            ]
        );
    }
}
