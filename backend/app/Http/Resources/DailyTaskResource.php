<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $dep_join = $this->deptjoin;
        $template_name = 'General Task';
        $template_type = 1; 
        if($this->deptjoin){
            // return [$this->deptjoin->id ,$this->deptjoin->is_factory  ,$this->role_id];
            if($this->deptjoin->id == 8 && $this->role_id !=5){
                $template_name = 'Project Wise Task';
                $template_type = 3;
            }else if($this->deptjoin->is_factory != 1 && $this->role_id == 5 ){
                $template_name = 'Hod Task ';
                $template_type = 2;
            }else if($this->deptjoin->is_factory == 1 ){
                $template_name = 'Factory Task ';
                $template_type = 4;
            }else{
                $template_name = 'General Task ';
                $template_type = 1;
            }
        }       
        return [
            "id" => $this->id ,   
            "user_id" => $this->user_id ,
            "userjoin" =>  $this->userjoin, 
            "role_id" => $this->role_id,  
            "deptjoin" => $dep_join?$dep_join:[],  
            "template_name" => $template_name,  
            "template_type" => $template_type,  
            "task" =>  $this->task, 
            "tasks" =>  $this->tasks($request , $this->id  , $this->user_id), 
            "comments" => $this->comments(), 
            "factory_formatjoin" => $this->factory_formatjoin, 
            "date" =>  $this->date, //$this->date->format('Y-m-d H:i:s'), 
            "status" =>  $this->status, 
            "start_time" =>  $this->start_time, 
            "end_time" =>  $this->end_time, 
            "created_at" => $this->created_at , 
            "updated_at" => $this->updated_at  
        ];
    }
}
