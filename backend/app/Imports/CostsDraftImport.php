<?php
namespace App\Imports;
use App\Models\CostsDraft; 
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class CostsDraftImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {  
        $data = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['date']))->format('Y-m-d') ;
        //echo  $data ;
        if($row['cost']){
            return new CostsDraft([   
                'cost'  => $row['cost'],  
                'factory_code'  => $row['factory_code'],  
                'cost_center'  => $row['cost_center'],  
                'gl_code'  => $row['gl_code'],  
                'date' => $data , 
                // 'date' => date('Y-m-d' , strtotime($row['date'])), 
                'remarks' => $row['remarks'], 
                'created_by' => Auth::user()->id ,  
                'updated_by' => Auth::user()->id ,
             ]);
        }        
    }  
}
