<?php

namespace App\Http\Controllers\Budgets;

use App\User;
use App\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateBudgetController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        return view(
            'budgets.create',
            [
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
