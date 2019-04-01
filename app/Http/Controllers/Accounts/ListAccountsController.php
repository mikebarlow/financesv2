<?php

namespace App\Http\Controllers\Accounts;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListAccountsController extends Controller
{
    /**
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        return view(
            'accounts.list',
            [
                'accounts' => $request->user()
                    ->accounts()
                    ->with('budget')
                    ->orderBy('name', 'asc')
                    ->get(),
            ]
        );
    }
}
