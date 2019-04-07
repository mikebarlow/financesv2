<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'budget_id' => $this->budget_id,
            'budget' => $this->whenLoaded(
                'budget',
                new BudgetResource($this->budget)
            ),
            'latest' => $this->whenLoaded(
                'latestSheet',
                new SheetResource($this->latestSheet)
            ),
        ];
    }
}
