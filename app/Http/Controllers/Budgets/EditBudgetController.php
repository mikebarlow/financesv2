<?php

namespace App\Http\Controllers\Budgets;

use App\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditBudgetController extends Controller
{
    /**
     * @todo restrict budgets
     *
     * @param Request $request
     * @param int $budgetId
     */
    public function __invoke(Request $request, int $budgetId)
    {
        return view(
            'budgets.edit',
            [
                'budgetId' => $budgetId
            ]
        );
    }
}
