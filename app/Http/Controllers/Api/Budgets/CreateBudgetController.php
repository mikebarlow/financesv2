<?php

namespace App\Http\Controllers\Api\Budgets;

use App\Budget;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBudgetRequest;

class CreateBudgetController extends Controller
{
    /**
     * @param CreateBudgetRequest $request
     */
    public function __invoke(CreateBudgetRequest $request)
    {
        $data = $request->request->get('budget');
        $user = $request->user();

        $budget = new Budget([
            'name' => $data['name'],
            'sheet_rows' => \GuzzleHttp\json_encode($data['rows']),
        ]);

        try {
            $user->budgets()
                ->save($budget);
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
                    'msg' => 'Budget created, redirecting...',
                    'redirect' => route('budgets.list'),
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
