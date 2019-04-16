<?php

namespace App\Http\Controllers\Api\MassTransfers;

use App\Sheet;
use App\Account;
use App\MassTransfer;
use App\Money\Parser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class UpdateTransferController extends Controller
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
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
    {
        $data = $request->request->get('transfer');
        $sheetId = $request->request->get('sheetId');
        $account = Sheet::with('account')
            ->where('id', $sheetId)
            ->first()->account;

        $transfer = MassTransfer::where('id', $id)
            ->first();

        $transfer->name = $data['name'];

        $rows = collect($data['rows'])
            ->map(
                function ($row) {
                    $row['amount'] = $this->moneyParser
                        ->convertToMoney($row['amount'])
                        ->getAmount();

                    return [
                        'from_id' => $row['from_id'],
                        'from_label' => $row['from_label'],
                        'to_account' => $row['to_account'],
                        'to_account_lbl' => $row['to_account_lbl'],
                        'to_row' => $row['to_row'],
                        'to_label' => $row['to_label'],
                        'amount' => $row['amount'],
                    ];
                }
            )
            ->toArray();

        $transfer->transfers = \GuzzleHttp\json_encode($rows);

        try {
            $transfer->save();
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving the transfer',
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
                    'msg' => 'Mass Transfer updated, redirecting...',
                    'redirect' => route('accounts.view', ['id' => $account->id]),
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
