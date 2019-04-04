<?php

namespace App;

use Money\Money;
use App\Money\Parser;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'sheet_rows',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function outputRows(string $sheetRows)
    {
        $parser = app(Parser::class);

        $rows = collect(
            \GuzzleHttp\json_decode(
                $sheetRows,
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
