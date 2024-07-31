<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
class AchievementHistoryResource extends JsonResource
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
            "id" => $this->id , 
            "january" => $this->january, 
            "february" => $this->february, 
            "march" => $this->march, 
            "april" => $this->april, 
            "may" => $this->may, 
            "june" => $this->june, 
            "july" => $this->july, 
            "august" => $this->august, 
            "september" => $this->september, 
            "october" => $this->october, 
            "november" => $this->november, 
            "december" => $this->december, 
            "userjoin" => $this->userjoin ,
            "created_at" => $this->created_at ,
        ];
    }
}
