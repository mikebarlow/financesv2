<?php

namespace App\Http\Controllers\Api\Budgets;

use App\Budget;
use App\Money\Parser;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBudgetRequest;

class UpdateBudgetController extends Controller
{
    protected $moneyParser;

    /**
     * @param Parser $money
     */
    public function __construct(Parser $money)
    {
        $this->moneyParser = $money;
    }

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

        $rows = collect($data['rows'])
            ->map(
                function ($row) {
                    $row['amount'] = $this->moneyParser
                        ->convertToMoney($row['amount'])
                        ->getAmount();

                    return $row;
                }
            )
            ->toArray();


        $budget->sheet_rows = \GuzzleHttp\json_encode($rows);

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
