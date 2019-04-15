<?php

namespace App\Http\Controllers\Api\MassTransfers;

use App\Account;
use App\MassTransfer;
use App\Money\Parser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CreateTransferController extends Controller
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
     */
    public function __invoke(Request $request)
    {
        //
    }
}
