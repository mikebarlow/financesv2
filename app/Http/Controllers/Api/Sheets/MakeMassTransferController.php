<?php

namespace App\Http\Controllers\Api\Sheets;

use DB;
use App\Sheet;
use App\Account;
use Money\Money;
use App\Transaction;
use App\MassTransfer;
use App\Money\Parser;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\MakeMassTransferRequest;

class MakeMassTransferController extends Controller
{
    protected $moneyParser;

    protected $accountCache = [];

    /**
     * @param Parser $money
     */
    public function __construct(Parser $money)
    {
        $this->moneyParser = $money;
    }

    /**
     * @param MakeMassTransferRequest $request
     */
    public function __invoke(MakeMassTransferRequest $request)
    {
        $sheetId = $request->request->get('sheet_id');
        $massTransferId = $request->request->get('mass_transfer_id');

        $massTransfer = MassTransfer::where('id', $massTransferId)
            ->first();

        $sheet = Sheet::with('sheetRows')
            ->where('id', $sheetId)
            ->first();

        try {
            collect(\GuzzleHttp\json_decode($massTransfer->transfers, true))
                ->each(
                    function ($transfer) use ($sheet) {
                        $amount = Money::GBP($transfer['amount']);
                        $from = $sheet->sheetRows->where('budget_id', $transfer['from_id'])->first();
                        $to = null;

                        if ($transfer['to_account'] > 0) {
                            if (! empty($this->accountCache[$transfer['to_account']])) {
                                $account = $this->accountCache[$transfer['to_account']];
                            } else {
                                $sheet = Sheet::with('account.latestSheet.sheetRows')
                                    ->where('id', $transfer['to_account'])
                                    ->first();
                                $account = $this->accountCache[$transfer['to_account']] = $sheet->account;
                            }

                            $toSheet = $account->latestSheet;
                            $to = $toSheet->sheetRows->where('budget_id', $transfer['to_row'])->first();
                        }

                        DB::transaction(function () use ($transfer, $from, $to, $amount) {
                            if ($from !== null) {
                                $from->transferOut($amount);
                            } else {
                                $from = $transfer['from_label'];
                            }

                            if ($to !== null) {
                                $to->transferIn($amount);
                            } else {
                                $to = $transfer['to_label'];
                            }

                            Transaction::transfer($from, $to, $amount);
                        });
                    }
                );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem saving one or more transfers',
                            $e->getMessage(),
                        ]
                    ]
                )
                ->setStatusCode(
                    Response::HTTP_BAD_REQUEST
                );
        }

        return response()
            ->json(
                [
                    'msg' => 'Mass Transfer logged',
                ]
            )
            ->setStatusCode(
                Response::HTTP_CREATED
            );
    }
}
