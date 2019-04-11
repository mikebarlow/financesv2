<?php

namespace App\Http\Controllers\Api\Budgets;

use App\User;
use App\Budget;
use App\Money\Parser;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBudgetRequest;

class CreateBudgetController extends Controller
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
     * @param CreateBudgetRequest $request
     */
    public function __invoke(CreateBudgetRequest $request)
    {
        $data = $request->request->get('budget');
        $share = $request->request->get('share', false);
        $user = $request->user();

        $rows = collect($data['rows'])
            ->map(
                function ($row) {
                    $row['amount'] = $this->moneyParser
                        ->convertToMoney($row['amount'])
                        ->getAmount();

                    $row['id'] = Str::uuid();

                    return $row;
                }
            )
            ->toArray();

        $budget = new Budget([
            'name' => $data['name'],
            'sheet_rows' => \GuzzleHttp\json_encode($rows),
        ]);

        $shareWith = User::where('id', '!=', $user->id)
            ->first();

        try {
            $budget = $user->budgets()
                ->save($budget);

            if ($share) {
                $shareWith->budgets()
                    ->attach($budget->id);
            }
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
