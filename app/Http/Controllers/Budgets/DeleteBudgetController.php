<?php

namespace App\Http\Controllers\Budgets;

use App\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteBudgetController extends Controller
{
    /**
     * @todo restrict budgets
     *
     * @param Request $request
     * @param int $budgetId
     */
    public function __invoke(Request $request, int $budgetId)
    {
        try {
            $request->user()
                ->budgets()
                ->detach($budgetId);

            $budget = Budget::where('id', $budgetId)
                ->delete();
        } catch (\Exception $e) {
            return back()
                ->with('errorMsg', 'There was a problem deleting the budget');
        }

        return back()
            ->with('successMsg', 'Budget has been deleted, any sheets using it will no longer be able to complete');
    }
}
