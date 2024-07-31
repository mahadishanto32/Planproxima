<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
class MosTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$request
        return [
            "id" => $this->id , 
            "user_id" => $this->user_id?$this->user_id:'',
            "user_name" => $this->user_name?$this->user_name:'', 
            "dep_name" => $this->dep_name?$this->dep_name:'',   
            "dept_id" => $this->dept_id, 
            "kra_id" => $this->kra_id, 
            "kra_count" =>$this->count_kra,
            "krajoin" =>$this->krajoin, 
            "kpi_count" =>$this->count_kpi, 
            "kpijoin" =>$this->kpijoin, 
            "kpi_id" => $this->kpi_id, 
            "mos_name" => $this->mos_name, 
            "mos_calculation" => $this->mos_calculation, 
            "weightage" => $this->weightage, 
            "rep_per" => $this->rep_per?$this->rep_per:0, 
            "rep_id" => $this->rep_id?$this->rep_id:0, 
            "isvalorper" => $this->isvalorper,
            "modification_type" => $this->modification_type ,
            "modification_status" => $this->modification_status ,
            "start_date" => $this->start_date ,
            "end_date" => $this->end_date ,
            "modification_months" => json_decode($this->modification_months),
            "checked" => false , 
            
            // "working_member" => $this->working_member(),
            // "mos_working_member" => $this->mos_working_member(),

            "working_memberJoin" => $this->working_memberJoin,
            "mos_working_memberJoin" => $this->mos_working_memberJoin,

            "mosachievementjoin" => New MosdataResource($this->mosachievementjoin($request)),
            "mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            "mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
        ];
    }
}
