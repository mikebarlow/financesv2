<?php

namespace App\Http\Controllers\Api\Budgets;

use App\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BudgetResource;

class GetBudgetController extends Controller
{
    /**
     * @param Request $request
     * @param int $budgetId
     */
    public function __invoke(Request $request, int $budgetId)
    {
        $budget = $request->user()
            ->budgets()
            ->where('budgets.id', $budgetId)
            ->first();

        // success return
        return response()
            ->json(
                [
                    'budget' => new BudgetResource($budget),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
