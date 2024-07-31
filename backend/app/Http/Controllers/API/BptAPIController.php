<?php

namespace App\Http\Controllers\API;
use App\Models\Factory;
use App\Models\Product;
use App\Models\Manufacturer;
use App\Models\Ordergroup;
use App\Models\SummaryGroup;
use App\Models\WastageSummaryGroup;
use App\Models\CostCenter;
use App\Models\CostGLGroup;
use App\Models\Wastage; 
use App\Models\Cost; 
use App\Http\Resources\ManufacturerReportResource;
use App\Http\Resources\ReportDelivaryResource;
use App\Http\Resources\ReportWastageResource;
use App\Http\Resources\ManufacturerResource;
use App\Http\Resources\CostReportResource;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\ManufacturerReportController;

class BptAPIController extends AppBaseController
{
    public function production_report_api($date){ 
        ini_set('max_execution_time', 0);
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST'); //default METHOD
        $obj = [];
        
        $obj['end_date'] = date('Y-m-t',strtotime($date));
        $obj['end_date_previous'] = date("Y-m-t",strtotime ( '-1 month' , strtotime ( $date ) ));
        $obj['start_date'] = date('Y-m-01',strtotime($date));
        $obj['start_date_previous'] = date("Y-m-01",strtotime ( '-1 month' , strtotime ( $date ) ));
        $obj['watt5'] = 0;
        $SummaryGroup = SummaryGroup::orderBy('id','asc')->get();
        $data = [];
        foreach($SummaryGroup as $key=>$item){
            $obj['factory_id'] = $item->factory_id;
            $obj['summary_group_id'] = $item->id;
            $myRequest->replace($obj);
            $controller = new ManufacturerReportController();
            $functionRes = $controller->production_report($myRequest);
            $responce = json_decode(json_encode($functionRes), true);


            $responce = $responce['original']['data'];  
            $delivery = $responce['delivary'][0];
            $production = [];
            // Note: Production Capacity 
            $production['capacity']['monthly_amount'] = (isset($delivery['gnh_capacity'])?$delivery['gnh_capacity']:0)+(isset($delivery['oth_capacity'])?$delivery['oth_capacity']:0);
            $production['capacity']['avg_amount'] = $production['capacity']['monthly_amount'];
            $production['capacity']['monthly_per'] = 100; 
            $production['capacity']['avg_per'] = 100;

            // Note: Production Plan 
            $production['production_plan']['monthly_amount'] = (isset($delivery['gnh_production_plan'])?$delivery['gnh_production_plan']:0)+(isset($delivery['oth_production_plan'])?$delivery['oth_production_plan']:0);
            $production['production_plan']['avg_amount'] = (isset($delivery['gnh_actual_production_avg'])?$delivery['gnh_actual_production_avg']:0)+(isset($delivery['oth_actual_production_avg'])?$delivery['oth_actual_production_avg']:0);
            $production['production_plan']['monthly_per'] = (!isset($production['capacity']['monthly_amount']) || ($production['capacity']['monthly_amount']==0))?0:($production['production_plan']['monthly_amount'] / $production['capacity']['monthly_amount']) * 100;
            $production['production_plan']['avg_per'] = (!isset($production['capacity']['monthly_amount']) || ($production['capacity']['monthly_amount']==0))?0:(($delivery['gnh_actual_production_avg']+$delivery['oth_actual_production_avg'])/$production['capacity']['monthly_amount']) * 100; 

            // Note: Production target
            $production['target']['monthly_amount'] = (isset($delivery['gnh_production_target'])?$delivery['gnh_production_target']:0)
            +(isset($delivery['oth_production_target'])?$delivery['oth_production_target']:0);
            $production['target']['monthly_per'] = ((!isset($production['capacity']['monthly_amount']) || $production['capacity']['monthly_amount']==0))?0:($production['target']['monthly_amount'] / $production['capacity']['monthly_amount']) * 100;

            // Note: Actual Production
            $production['aproduction']['monthly_amount'] = (isset($delivery['gnh_actual_production'])?$delivery['gnh_actual_production']:0)+(isset($delivery['oth_actual_production'])?$delivery['oth_actual_production']:0);
            $production['aproduction']['avg_amount'] = (isset($delivery['gnh_actual_production_avg'])?$delivery['gnh_actual_production_avg']:0)+(isset($delivery['oth_actual_production_avg'])?$delivery['oth_actual_production_avg']:0);
            
            $production['aproduction']['monthly_per'] = (!isset($production['capacity']['monthly_amount']) || ($production['capacity']['monthly_amount']==0))?0:(($delivery['gnh_actual_production']+$delivery['oth_actual_production'])/$production['capacity']['monthly_amount'])*100;
            $production['production_plan']['avg_per'] = ((!isset($production['capacity']['monthly_amount']) || $production['capacity']['monthly_amount']==0))?0:(($delivery['gnh_actual_production_avg']+$delivery['oth_actual_production_avg'])/$production['capacity']['monthly_amount']) * 100; 

            // Note: Actual Wastage 
            $wastage = [];
            $wastageAry = $responce['wastage'];
            foreach($wastageAry as $key=>$ary){
                $wastage[] = [
                    'group_name' => $ary['group_name'],
                    'std' => $ary['previous_year_average'],
                    'month_wastage' => (!isset($ary['consumtion']) || ($ary['consumtion']==0))?0:(($ary['actual_wastage'] / $ary['consumtion']) * 100),
                    'avg' => $ary['current_year_average'],
                ];
            }
            //Note: Cost Analusis Gth
            $costAry_gnh = $responce['cost_center_gnh'];
            $costAry_oth = $responce['cost_center_oth'];
            $cost_gnh = [];
            $cost_oth = [];

            $costAmount_gn = 0;
            $costPerUnit_gn = 0;

            $standardAmount_gn = 0;
            $standardPerUnit_gn = 0;            
            foreach($costAry_gnh as $key=>$item){
                $costAmount_gn+= $item['cost'];
                $costPerUnit_gn+= $delivery['gnh_actual_production']==0?0:$item['cost']/$delivery['gnh_actual_production'];

                $standardAmount_gn+= $item['standard_gnh'];
                $standardPerUnit_gn+= $delivery['gnh_production_plan']==0?0:$item['standard_gnh']/$delivery['gnh_production_plan']; 
            }
            $cost_gnh = [
                'amount' => $costAmount_gn,
                'per_unit' => $costPerUnit_gn,
                'samount' => $standardAmount_gn,
                'sper_unit' => $standardPerUnit_gn,
            ];   
            //Note: Cost Analusis oth 
            $costAmount_oth = 0;
            $costPerUnit_oth = 0;

            $standardAmount_oth = 0;
            $standardPerUnit_oth = 0;                    
            foreach($costAry_oth as $key=>$item){
                $costAmount_oth+= $item['cost'];
                $costPerUnit_oth += (!isset($item['oth_actual_production']) || ($item['oth_actual_production']==0))?0:($item['cost']/$delivery['oth_actual_production']);
                $standardAmount_oth+= $item['standard_oth'];
                $standardPerUnit_oth+= $delivery['oth_production_plan']==0?0:$item['standard_oth']/$delivery['oth_production_plan'];
            }   
            $cost_oth = [
             'amount' => $costAmount_oth,
             'per_unit' => $costPerUnit_oth,
             'samount' => $standardAmount_oth,
             'sper_unit' => $standardPerUnit_oth,
            ];                        
            $data[] = [
                'production' => $production,
                'wastage' => $wastage,
                'cost_gnh' => $cost_gnh,
                'cost_oth' => $cost_oth,
                'filter' => $obj
            ];             
                  
        }
        return $this->sendResponse($data, 'PrReposroduct retrieved successfully');
    }
}
