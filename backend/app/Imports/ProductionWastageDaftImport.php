<?php
namespace App\Imports;
use App\Models\ProductionWastageDaft; 
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class ProductionWastageDaftImport implements ToModel, WithHeadingRow
{ 
    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }
    public function model(array $row)
    {  

       
        return new ProductionWastageDaft([ 
            'comp_code' => $row['comp_code'],
            'plant' => $row['plant'] ? (($row['plant']==1214)?1201:$row['plant']) : 0,
            'unit_code' => $row['order_type'],
            'product_code'  => $row['product_code'] ? $row['product_code'] : '--', 
            'target_qty' => $row['target_qty'],
            'production_quantity_gnh' => $row['production_quantity_gnh'] ? $row['production_quantity_gnh'] : 0,
            'production_quantity_oth' => $row['production_quantity_oth'] ? $row['production_quantity_oth'] : 0,
            'delivery_qty' => $row['delivery_qty'],
            'date' => $row['date'],
            'consumtion' => $row['consumtion'],
            'consumtion_value' => $row['consumtion_value'],
            'wastage_value' => $row['wastage_value'],
            'type' => $row['type'] ? $row['type'] : 'No',
            'wastage' => $row['wastage'], 
            'return' => $row['return'], 
            //'remarks' => $row['remarks'] ? $row['remarks'] : '--', 
            'remarks' => '', 
            'status' => 0, 
            'sap_file_id' => $this->data ?  $this->data['sap_file_id'] : 0 , 
            'created_by' => Auth::user()->id ,  
            'updated_by' => Auth::user()->id ,  
            ]);
    }  
}
