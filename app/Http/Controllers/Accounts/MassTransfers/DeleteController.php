<?php

namespace App\Http\Controllers\Accounts\MassTransfers;

use App\Account;
use App\MassTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;

class DeleteController extends Controller
{
    /**
     *
     * @param Request $request
     * @param int $id,
     * @param int $transferId
     */
    public function __invoke(Request $request, int $id, int $transferId)
    {
        $transfer = MassTransfer::where('id', $transferId)
            ->delete();

        return back()
            ->with('successMsg', 'Mass Transfer Deleted');
    }
}
