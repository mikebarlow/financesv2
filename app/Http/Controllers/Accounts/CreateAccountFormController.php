<?php

namespace App\Http\Controllers\Accounts;

use App\User;
use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateAccountFormController extends Controller
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
                'users' => User::get()
                    ->reject(
                        function ($user) use ($request) {
                            return ($user->id === $request->user()->id);
                        }
                    ),
            ]
        );
    }
}
