<?php

namespace App\Http\Controllers\Budgets;

use App\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListBudgetsController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        return view(
            'budgets.list',
            [
                'budgets' => $request->user()->budgets()->orderBy('name', 'asc')->get(),
            ]
        );
    }
}
