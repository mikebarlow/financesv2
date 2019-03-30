<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $budget = parent::toArray($request);
        $budget['rows'] = \GuzzleHttp\json_decode(
            $budget['sheet_rows'],
            true
        );

        return $budget;
    }
}
