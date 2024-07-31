<?php
namespace App\Imports;
use App\Models\FactoryStandard; 
use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class FactoryStandardImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {     
        $cost_center = CostCenter::where('cost_code',$row['cost_center'])->first();

        if($cost_center){ 
            $FactoryStandard = FactoryStandard::where('gl_code',$row['gl_code'])
            ->where('year',$row['year'])
            ->where('type',strtolower($row['type']))
            ->where('report_type',strtolower($row['report_type']))
            ->where('cost_center_id',$cost_center->id)
            ->get();

            if(sizeof($FactoryStandard)>0){
                if(count($FactoryStandard) > 1){
                    FactoryStandard::where('gl_code',$row['gl_code'])
                    ->where('year',$row['year'])
                    ->where('type',strtolower($row['type']))
                    ->where('report_type',strtolower($row['report_type']))
                    ->where('cost_center_id',$cost_center->id)
                    ->forceDelete();

                    return new FactoryStandard([  
                        'cost_center_id' =>  $cost_center->id,
                        'cost_center' => $row['cost_center'], 
                        'gl_text' => $row['gl_text'],
                        'gl_code' => $row['gl_code'],
                        'jan' => $row['jan'],
                        'feb' => $row['feb'],
                        'mar' => $row['mar'], 
                        'apr' => $row['apr'],
                        'may' => $row['may'],
                        'jun' => $row['jun'],
                        'jul' => $row['jul'],
                        'aug' => $row['aug'],
                        'sep' => $row['sep'],
                        'oct' => $row['oct'],
                        'nov' => $row['nov'],
                        'dec' => $row['dec'], 
                        'cost_amount' => $row['report_type'] == 'yearly' ?   $row['cost'] ?   $row['cost']  : 0 :   $row['jan'] + $row['feb'] + $row['mar'] +  $row['apr'] + $row['may'] + $row['jun'] + $row['jul'] + $row['aug'] + $row['sep'] + $row['oct'] + $row['nov'] + $row['dec'] ,  // total cost yearly 
                        //'cost_amount' =>$row['cost'] ?  $row['cost']  :  $row['jan'] + $row['feb'] + $row['mar'] +  $row['apr'] + $row['may'] + $row['jun'] + $row['jul'] + $row['aug'] + $row['sep'] + $row['oct'] + $row['nov'] + $row['dec'] , // total cost yearly 
                        'year' => $row['year'],
                        'type' => strtolower($row['type']),
                        'report_type' =>  strtolower($row['report_type']),
                    ]);
                }else{
                    $FactoryStandard = FactoryStandard::where('gl_code',$row['gl_code'])
                    ->where('year',$row['year'])
                    ->where('type',strtolower($row['type']))
                    ->where('report_type',strtolower($row['report_type']))
                    ->where('cost_center_id',$cost_center->id) 
                    ->first(); 
                    if($FactoryStandard){ 
                        $FactoryStandard->jan = $row['jan'];
                        $FactoryStandard->feb = $row['feb'];
                        $FactoryStandard->mar = $row['mar']; 
                        $FactoryStandard->apr = $row['apr'];
                        $FactoryStandard->may = $row['may'];
                        $FactoryStandard->jun = $row['jun'];
                        $FactoryStandard->jul = $row['jul'];
                        $FactoryStandard->aug = $row['aug'];
                        $FactoryStandard->sep = $row['sep'];
                        $FactoryStandard->oct = $row['oct'];
                        $FactoryStandard->nov = $row['nov'];
                        $FactoryStandard->dec = $row['dec']; 
                        $FactoryStandard->cost_amount = $row['report_type'] == 'yearly' ?  $row['cost']  ?   $row['cost']  : 0 :   $row['jan'] + $row['feb'] + $row['mar'] +  $row['apr'] + $row['may'] + $row['jun'] + $row['jul'] + $row['aug'] + $row['sep'] + $row['oct'] + $row['nov'] + $row['dec'] ;
                        $FactoryStandard->save();  
                    }     
                }
            }else{
                return new FactoryStandard([  
                    'cost_center_id' =>  $cost_center->id,
                    'cost_center' => $row['cost_center'], 
                    'gl_text' => $row['gl_text'],
                    'gl_code' => $row['gl_code'],
                    'jan' => $row['jan'],
                    'feb' => $row['feb'],
                    'mar' => $row['mar'], 
                    'apr' => $row['apr'],
                    'may' => $row['may'],
                    'jun' => $row['jun'],
                    'jul' => $row['jul'],
                    'aug' => $row['aug'],
                    'sep' => $row['sep'],
                    'oct' => $row['oct'],
                    'nov' => $row['nov'],
                    'dec' => $row['dec'], 
                    'cost_amount' =>$row['report_type'] == 'yearly' ?  $row['cost']  ?   $row['cost']  : 0 :   $row['jan'] + $row['feb'] + $row['mar'] +  $row['apr'] + $row['may'] + $row['jun'] + $row['jul'] + $row['aug'] + $row['sep'] + $row['oct'] + $row['nov'] + $row['dec'] ,  // total cost yearly 
                    'year' => $row['year'],
                    'type' => strtolower($row['type']),
                    'report_type' =>  strtolower($row['report_type']),
                ]);
            }

        } 
    }  
  
}
