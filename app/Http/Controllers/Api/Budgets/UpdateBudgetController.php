<?php

namespace App\Http\Controllers\Api\Budgets;

use App\Budget;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBudgetRequest;

class UpdateBudgetController extends Controller
{
    /**
     * @param UpdateBudgetRequest $request
     * @param int $budgetId
     */
    public function __invoke(UpdateBudgetRequest $request, int $budgetId)
    {
        $data = $request->request->get('budget');

        $budget = $request->user()
            ->budgets()
            ->where('budgets.id', $budgetId)
            ->first();

        $budget->name = $data['name'];
        $budget->sheet_rows = \GuzzleHttp\json_encode($data['rows']);

        try {
            $budget->save();
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving the budget',
                            $e->getMessage(),
                        ]
                    ]
                )
                ->setStatusCode(
                    Response::HTTP_BAD_REQUEST
                );
        }

        // success return
        return response()
            ->json(
                [
                    'msg' => 'Budget updated, redirecting...',
                    'redirect' => route('budgets.list'),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
