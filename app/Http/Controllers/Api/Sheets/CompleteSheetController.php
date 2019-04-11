<?php

namespace App\Http\Controllers\Api\Sheets;

use App\Budget;
use App\Account;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteSheetRequest;

class CompleteSheetController extends Controller
{
    /**
     *
     * @param CompleteSheetRequest $request
     * @param int $id
     */
    public function __invoke(CompleteSheetRequest $request, int $id)
    {
        $account = Account::where('id', $id)
            ->with('latestSheet')
            ->whereHas(
                'users',
                function ($query) use ($request) {
                    $query->where('users.id', $request->user()->id);
                }
            )->first();

        try {
            $sheet = $account->latestSheet;

            $sheet->complete(
                \Carbon\Carbon::parse(
                    $request->request->get('end_date')
                )
            );
        } catch (\Exception $e) {
            return response()
                ->json(
                    [
                        'error' => [
                            'There was a problem completing the sheet',
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
                    'msg' => 'Sheet Completed, redirecting...',
                    'redirect' => route('accounts.start', ['id' => $sheet->account->id]),
                ]
            )
            ->setStatusCode(
                Response::HTTP_OK
            );
    }
}
