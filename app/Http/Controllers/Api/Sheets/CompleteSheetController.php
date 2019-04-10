<?php

namespace App\Http\Controllers\Api\Sheets;

use App\Budget;
use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompleteSheetController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id
     */
    public function __invoke(Request $request, int $id)
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

            $sheet->complete($request->request->get('end_date'));
        } catch (\Exception $e) {
            return back()
                ->with('errorMsg', 'There was a problem completing the sheet');
        }

        return redirect()
            ->route('accounts.start', ['id' => $account->id])
            ->with('successMsg', 'Sheet Completed, Start next Sheet');
    }
}
