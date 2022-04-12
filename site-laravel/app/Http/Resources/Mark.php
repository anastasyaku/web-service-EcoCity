<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;

class Mark extends JsonResource
{
    private function renderDates(&$obj)
    {
        foreach ($this->resource->toArray() as $field => $_) {
            if ($this->resource[$field] instanceof Carbon) {
                $obj[$field] = $this->resource[$field]->isoFormat("LL LT");
            }
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $obj = parent::toArray($request);
        $obj["type"] = str_replace("App\\", "", $obj["type"]);
        $this->renderDates($obj);
        return $obj;
    }
}
