<?php
namespace App\Imports;
use App\Models\FactoryCapacity; 
use App\Models\SummaryGroup;
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class FactoryCapacityImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {    
        $productGroup = SummaryGroup::where('code',$row['product_group'])->first();
        
        $deletedRows = FactoryCapacity::where('summary_group_id', $productGroup['id'])->where('year',$row['year'])->where('type', strtolower($row['type']))->delete(); 
        return new FactoryCapacity([ 
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
            'summary_group_id' => $productGroup ? $productGroup['id'] : 0, 
            'year' => $row['year'],
            'type' => strtolower($row['type']),
            'total_capacity' =>  $this->totat($row)  
            ]);
    }  
    public function totat($row){
        return ( $row['jan'] + $row['feb'] +  $row['mar'] +  $row['apr'] +  $row['may'] + $row['jun'] + $row['jul'] + $row['aug']  + $row['sep'] + $row['oct']  + $row['nov']  + $row['dec'] ); 
        //return 200 ;
    }
}
