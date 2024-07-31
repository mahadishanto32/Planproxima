<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MosdataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $monthNames = array(
            "jan" => "january",
            "feb" => "february",
            "mar" => "march",
            "apr" => "april",
            "may" => "may",
            "jun" => "june",
            "jul" => "july",
            "aug" => "august",
            "sep" => "september",
            "oct" => "october",
            "nov" => "november",
            "dec" => "december"
        );
        $check = 'Nothing';
        if($request->quarter){
            if($request->quarter == 1){
                $monthlyAmount = $this->july + $this->august + $this->september;
            }elseif($request->quarter == 2){
                $monthlyAmount = $this->october + $this->november + $this->december ;
            }elseif($request->quarter == 3){
                $monthlyAmount = $this->january + $this->february + $this->march;
            }elseif($request->quarter == 4){
                $monthlyAmount = $this->april + $this->may + $this->june;
            }elseif($request->quarter == 5){
                $monthlyAmount = $this->july + $this->august + $this->september + $this->october + $this->november + $this->december ;
            }else{
                $monthlyAmount = $this->january + $this->february + $this->march + $this->april + $this->may + $this->june;
            }
        }elseif($request->month){
            
            $fullMonthName = isset($monthNames[$request->month]) ? $monthNames[$request->month] : "january";
            
            $monthlyAmount = $this->$fullMonthName ;
            $check = $monthlyAmount;
            
        }else{
            
            $monthlyAmount = ( $this->january + $this->february + $this->march + $this->april + $this->may + $this->june + $this->july + $this->august + $this->september + $this->october + $this->november + $this->december );
        }
        //$total  =  ( $this->january + $this->february + $this->march + $this->april + $this->may + $this->june + $this->july + $this->august + $this->september + $this->october + $this->november + $this->december ) ;
        return [
            "id" =>  $this->id,
            "mos_id" =>  $this->mos_id,
            "type" =>  $this->type,
            "january" =>  $this->january,
            "january_status" => $this->january_status,
            "february" =>  $this->february,
            "february_status" => $this->february_status,
            "march" =>  $this->march,
            "march_status" => $this->march_status,
            "april" =>  $this->april,
            "april_status" => $this->april_status,
            "may" =>  $this->may,
            "may_status" => $this->may_status,
            "june" =>  $this->june,
            "june_status" => $this->june_status,
            "july" =>  $this->july,
            "july_status" => $this->july_status,
            "august" =>  $this->august,
            "august_status" => $this->august_status,
            "september" =>  $this->september,
            "september_status" => $this->september_status,
            "october" =>  $this->october,
            "october_status" => $this->october_status,
            "november" =>  $this->november,
            "november_status" => $this->november_status,
            "december" =>  $this->december,
            "december_status" => $this->december_status,
            //'january_status', 'february_status', 'march_status', 'april_status', 'may_status', 'june_status', 'july_status', 'august_status', 'september_status', 'october_status', 'november_status', 'december_status'
            "total" => $this->total,
            "monthly_total" => $monthlyAmount,
            "dept_id" =>  $this->dept_id,
            "created_at" =>  $this->created_at,
            "updated_at" =>  $this->updated_at, 
            "request" =>  $request->all(), 
            "check" =>  $check, 
        ];
    }
}
