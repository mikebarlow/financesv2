<?php

namespace App;

use Money\Money;
use App\Money\Parser;
use Illuminate\Database\Eloquent\Model;

class MassTransfer extends Model
{
    protected $fillable = [
        'name',
        'transfers',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function outputRows(string $transfers)
    {
        $parser = app(Parser::class);

        $rows = collect(
            \GuzzleHttp\json_decode(
                $transfers,
                true
            )
        );

        return $rows
            ->map(
                function ($row) use ($parser) {
                    $row['amount'] = $parser->moneyToOutput(
                        Money::GBP($row['amount'])
                    );

                    return $row;
                }
            )
            ->toArray();
    }
}
