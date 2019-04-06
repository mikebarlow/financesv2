<?php

namespace App\Http\Controllers\Api\Sheets;

use DB;
use App\Sheet;
use App\Account;
use App\SheetRow;
use App\Money\Parser;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSheetRequest;

class CreateSheetController extends Controller
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
     * @param CreateSheetRequest $request
     */
    public function __invoke(CreateSheetRequest $request)
    {
        $data = $request->request->get('sheet');

        $account = Account::where('id', $data['account_id'])
            ->whereHas('users', function ($query) use ($request) {
                $query->where('users.id', $request->user()->id);
            })->first();

        if ($account === null) {
            return response()
                ->json(
                    [
                        'error' => [
                            'You do not have access to this account.',
                        ]
                    ]
                )
                ->setStatusCode(
                    Response::HTTP_FORBIDDEN
                );
        }

        try {
            DB::transaction(function () use ($data, $account) {
                $sheet = new Sheet([
                    'start_date' => $data['start_date'],
                ]);

                $sheet = $account->sheets()
                    ->save($sheet);

                $sheetRows = collect($data['budget'])
                    ->map(
                        function ($row, $key) use ($data) {
                            $budget = $this->moneyParser
                                ->convertToMoney($row['amount'])
                                ->getAmount();

                            if (isset($data['brought_forward'][$key]['amount'])) {
                                $broughtForward = $this->moneyParser
                                    ->convertToMoney($data['brought_forward'][$key]['amount'])
                                    ->getAmount();
                            } else {
                                $broughtForward = '0';
                            }

                            return new SheetRow([
                                'budget_id'       => $row['id'],
                                'label'           => $row['name'],
                                'budget'          => $budget,
                                'brought_forward' => $broughtForward,
                                'payments'        => '0',
                                'transfer_in'    => '0',
                                'transfer_out'   => '0',
                            ]);
                        }
                    );

                $sheet->sheetRows()
                    ->saveMany($sheetRows);
            });
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving the sheet',
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
                    'msg' => 'Sheet created, redirecting...',
                    'redirect' => route('accounts.view', ['id' => $data['account_id']]),
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
