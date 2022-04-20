<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'salary_gross' => $this->salary_gross,
            'iit' => $this->iit,
            'cpc' => $this->cpc,
            'cmshi' => $this->cmshi,
            'mshi' => $this->mshi,
            'sd' => $this->sd,
            'salary_net' => $this->salary_net,
        ];
    }
}
