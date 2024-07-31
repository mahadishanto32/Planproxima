<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SummeryReportUpdateResource extends JsonResource
{
    private $filter;

    public function __construct($resource, $filter ) {
        // Ensure we call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->filter = $filter; // $apple param passed
    }    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id , 
            "name" => $this->name , 
            "status" => $this->monthly_report($this->id,$request->year,$request->month?$request->month:date('m')  ),
        ];
    }
}
