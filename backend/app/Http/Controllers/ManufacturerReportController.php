<?php

namespace App\Http\Controllers;

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

class ManufacturerReportController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       return 0;
    }

    public function production_report_api(Request $request){
        $ob = new Request();
        $ob->end_date = "2022-05-31";
        $obj->end_date_previous = "2022-04-30";
        $obj->factory_id =  5;
        $obj->start_date = "2022-05-01";
        $obj->start_date_previous = "2022-04-01";
        $obj->summary_group_id = 12;
        $obj->watt5 = 0;    
        return self::production_report($obj);    
    }

    public function production_report(Request $request){
        ini_set('max_execution_time', 0);
        $items = array();
        $delivary  =  array();
    
        $request['start_date'] = $request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date'])) : '';
        $request['end_date'] =  $request['end_date'] ? date('Y-m-d' , strtotime($request['end_date'])) : '';
        $request['start_date_previous'] =$request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date_previous'])) : '';
        $request['end_date_previous'] =  $request['start_date'] ?  date('Y-m-d' , strtotime($request['end_date_previous'])) : '';

        $filter = $request->all();  
        if($request->factory_id){ 
            $query = SummaryGroup::orderBy('id','asc');
            if($request->summary_group_id){
                $query->where('id', $request->summary_group_id);
            }  
            $items = $query->first();  
            $delivary = new ReportDelivaryResource($items ,  $filter ) ; //here  ;
        }   
        $factInfo  =  Factory::find($request['factory_id']);   

        // wasate and consumption 
        $wastage_query = WastageSummaryGroup::where('summary_group_id',$request['summary_group_id']); 
        $wastage_query = $wastage_query->where('status',1);
        $wastage_items = $wastage_query->get(); 
        //Note: This loof is for Api 
        foreach($wastage_items as $key=>$item){
            $wastage_items[$key]->filters = $filter;
        }
        
        $wastage = ReportWastageResource::collection($wastage_items); 
        //Note: COST ANALYSIS General Hour
        $cost_center_gnh = CostCenter::selectRaw("cost_gl.*")
        ->where('summary_group_id',$request->summary_group_id)
        ->join('factory_standards','factory_standards.cost_center' ,'=' ,'cost_centers.cost_code')
        ->join('cost_gl','cost_gl.gl_code' ,'=' ,'factory_standards.gl_code')
        ->where('cost_gl.type',1)
        ->where('factory_standards.year',date('Y',strtotime($request['start_date'])))
        ->groupBy('cost_gl.gl_code')
        ->get();
        
        //Note: This loof is for Api 
        foreach($cost_center_gnh as $key=>$item){
            $cost_center_gnh[$key]->filters = $filter;
        }         
        $cost_center_gnh = CostReportResource::collection($cost_center_gnh , $filter );
        
        //Note : This Part is for avobe 2022
        if(date('Y' , strtotime($request['start_date'])) > 2021){
            $cost_center_gnh = self::cost_analysis($cost_center_gnh,1);
        }
        //Note : This Part is for avobe 2022
        
        $cost_center_oth = CostCenter::selectRaw("cost_gl.*")
        ->where('cost_centers.summary_group_id',$request->summary_group_id)
        ->join('factory_standards','factory_standards.cost_center' ,'=' ,'cost_centers.cost_code')
        ->join('cost_gl','cost_gl.gl_code' ,'=' ,'factory_standards.gl_code')
        ->where('factory_standards.year',date('Y',strtotime($request['start_date'])))
        ->where('cost_gl.type',2)
        ->groupBy('cost_gl.gl_code')
        ->get(); 
        //Note: This loof is for Api 
        foreach($cost_center_oth as $key=>$item){
            $cost_center_oth[$key]->filters = $filter;
        }          
        $cost_center_oth = CostReportResource::collection($cost_center_oth , $filter );  
        //Note : This Part is for avobe 2022
        if(date('Y' , strtotime($request['start_date'])) > 2021){
            $cost_center_oth = self::cost_analysis($cost_center_oth,2);
        }
        //Note : This Part is for avobe 2022

        $return_data =  array(
            'cost_center_gnh' => $cost_center_gnh ,
            'cost_center_oth' => $cost_center_oth ,
            'delivary' => array($delivary),
            'wastage' => $wastage,
            ) ;
        return $this->sendResponse($return_data, 'PrReposroduct retrieved successfully');

    }

    public function cost_analysis($cost_center_gnh,$type){
        $CostGLGroup_idghh = collect($cost_center_gnh)->pluck('group_id')->unique()->values()->all();
        $cost_center_gnh_group = $cost_center_gnh->groupBy('group_id');
        $CostGLGroup = CostGLGroup::whereIn('id',$CostGLGroup_idghh)->get();
        $cost_center_arr=[];
        foreach($CostGLGroup as $key=>$CostGLGroups){
            $CostGLGroups_data = isset($cost_center_gnh_group[$CostGLGroups->id])?$cost_center_gnh_group[$CostGLGroups->id]:[];
            $cost_center_arr[$key]['id'] = $key; 
            $cost_center_arr[$key]['gl_name'] = $CostGLGroups['name'];
            $cost_center_arr[$key]['gl_code'] = '';      
            
            $average_cost = 0;
            $cost = 0;
            $cost_previous = 0;
            $standard_gnh = 0;
            $standard_oth = 0;
            foreach($CostGLGroups_data as $CostGLGroup){
                $CostGLGroup_arry = collect($CostGLGroup)->toArray();
                $average_cost+= $CostGLGroup_arry['average_cost'];
                $cost+= $CostGLGroup_arry['cost'];
                $cost_previous+= $CostGLGroup_arry['cost_previous'];
                $standard_gnh+= $CostGLGroup_arry['standard_gnh'];
                $standard_oth+= $CostGLGroup_arry['standard_oth'];
            } 
            $cost_center_arr[$key]['average_cost'] = $average_cost;
            $cost_center_arr[$key]['cost'] = $cost;
            $cost_center_arr[$key]['cost_previous'] = $cost_previous;
            $cost_center_arr[$key]['standard_gnh'] = $standard_gnh;
            $cost_center_arr[$key]['standard_oth'] = $standard_oth;
            $cost_center_arr[$key]['type'] = $type;       
        }
        return $cost_center_arr;
    }

    public function production_report_delivary(Request $request){
        $items = array();
        $delivary  =  array();
    
        $request['start_date'] = $request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date'])) : '';
        $request['end_date'] =  $request['end_date'] ? date('Y-m-d' , strtotime($request['end_date'])) : '';
        $request['start_date_previous'] =$request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date_previous'])) : '';
        $request['end_date_previous'] =  $request['start_date'] ?  date('Y-m-d' , strtotime($request['end_date_previous'])) : '';

        $filter  = $request->all();  
        if($request->factory_id){ 
            $query = SummaryGroup::orderBy('id','asc');
            if($request->summary_group_id){
                $query->where('id', $request->summary_group_id);
            } 
            $items = $query->first();  
            $delivary = new ReportDelivaryResource($items ,  $filter ) ; //here  ;
        }    
        $return_data =  array( 
            'delivary' => array($delivary )  
            ) ;
        return $this->sendResponse($return_data, 'PrReposroduct retrieved successfully');

    }

    public function production_report_wastage(Request $request){
          
        $request['start_date'] = $request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date'])) : '';
        $request['end_date'] =  $request['end_date'] ? date('Y-m-d' , strtotime($request['end_date'])) : '';
        $request['start_date_previous'] =$request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date_previous'])) : '';
        $request['end_date_previous'] =  $request['start_date'] ?  date('Y-m-d' , strtotime($request['end_date_previous'])) : '';

        $filter  = $request->all();  
       
        $factInfo  =  Factory::find($request['factory_id']);  


        $wastage_query = WastageSummaryGroup::selectRaw("SUM(wastages.actual_wastage) as actual_wastage ")   
        ->selectRaw(" wastage_summary_group.group_name")
        ->selectRaw(" wastage_summary_group.uom")
        ->selectRaw(" wastage_summary_group.grouping_id") 
        ->join('products', 'wastage_summary_group.scrap_material', '=', 'products.material_code') 
        ->join('wastages', 'wastages.product_id', '=', 'products.id'); 
        if($request['start_date']  && $request['end_date']){
            $wastage_query->whereBetween('wastages.date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        }
        if($request['summary_group_id']){
            $wastage_query->where('wastages.summary_group_id',$request['summary_group_id']);
        } 
        if($factInfo){
            $wastage_query->where('wastage_summary_group.plant',  $factInfo->fac_code );
        } 
        $wastage_query->groupBy('wastage_summary_group.uom'); 
        $wastage_query->groupBy('wastage_summary_group.group_name'); 
        $wastage_query->groupBy('wastage_summary_group.grouping_id');  
        $wastage_items = $wastage_query->get(); 
        if($request['summary_group_id'] == 3){
            // 5watt 
            $wastage_query->where('wastages.order_group_id','!=', 51 );
        }  
        $wastage = ReportWastageResource::collection($wastage_items); 
        $return_data =  array( 
            'wastage' => $wastage
            ) ;
        return $this->sendResponse($return_data, 'PrReposroduct retrieved successfully');

    }

    public function production_report_5watt(Request $request){
        $items = array();
        $delivary  =  array();
    
        $request['start_date'] = $request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date'])) : '';
        $request['end_date'] =  $request['end_date'] ? date('Y-m-d' , strtotime($request['end_date'])) : '';
        $request['start_date_previous'] =$request['start_date'] ?  date('Y-m-d' , strtotime($request['start_date_previous'])) : '';
        $request['end_date_previous'] =  $request['start_date'] ?  date('Y-m-d' , strtotime($request['end_date_previous'])) : '';

        $filter  = $request->all();  

        if($request->factory_id){ 
            $query = SummaryGroup::orderBy('id','asc');
            if($request->summary_group_id){
                $query->where('id', $request->summary_group_id);
            } 
            $items = $query->first();  
            $delivary = new ReportDelivaryResource($items ,  $filter ) ; //here  ;
        }   
        $factInfo  =  Factory::find($request['factory_id']);  


        // $wastage_query = WastageSummaryGroup::selectRaw("SUM(wastages.actual_wastage) as actual_wastage ")   
        // ->selectRaw(" wastage_summary_group.group_name")
        // ->selectRaw(" wastage_summary_group.uom")
        // ->selectRaw(" wastage_summary_group.grouping_id") 
        // ->join('products', 'wastage_summary_group.scrap_material', '=', 'products.material_code') 
        // ->join('wastages', 'wastages.product_id', '=', 'products.id'); 
        // if($request['start_date']  && $request['end_date']){
        //     $wastage_query->whereBetween('wastages.date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        // }
        // if($request['summary_group_id']){
        //     $wastage_query->where('wastages.summary_group_id',$request['summary_group_id']);
        // }
        // if($request['summary_group_id'] == 3){
        //     // 5 watt
        //     $wastage_query->where('wastages.order_group_id', 51 );
        // }  
        // if($factInfo){
        //     $wastage_query->where('wastage_summary_group.plant',  $factInfo->fac_code );
        // } 
        // $wastage_query->groupBy('wastage_summary_group.uom'); 
        // $wastage_query->groupBy('wastage_summary_group.group_name'); 
        // $wastage_query->groupBy('wastage_summary_group.grouping_id'); 
        
        $wastage_query = WastageSummaryGroup::where('summary_group_id',$request['summary_group_id']);
        $wastage_items = $wastage_query->get(); 
        $filter = $request->all();  

        foreach($wastage_items as $key=>$item){
            $wastage_items[$key]->filters = $filter;
        }         
        
        $wastage = ReportWastageResource::collection($wastage_items);
         


        //$wastage_items = $wastage_query->get();   
        //$wastage = ReportWastageResource::collection($wastage_items); 
        $return_data =  array( 
            'watt5' => $wastage
            ) ;
        return $this->sendResponse($return_data, 'PrReposroduct retrieved successfully');

    }
    public function  yearly_report(Request $request){
        return view('manufacturer/yearly_report', [ 
            'request' => $request 
        ]);

    }

    public function get_summary_list(Request $request)
    {
        $items = CostCenter::select('summary_group.id','summary_group.code')->join('summary_group','summary_group.id','cost_centers.summary_group_id')->where('cost_centers.factory_id',$request->factory_id)->groupBy('summary_group.id')->groupBy('summary_group.code')->get();

        $html = '<option value="">Select Product group</option>';
        foreach($items as $item){
            $html .= '<option value="'.$item->id.'">'.$item->code.'</option>';
        }   
        return $html;
    }

    
    public function summaryList(Request $request)
    {
        $items = CostCenter::select('summary_group.id','summary_group.code' ,'summary_group.description')
        ->join('summary_group','summary_group.id','cost_centers.summary_group_id')
        ->where('cost_centers.factory_id',$request->factory_id)
        ->groupBy('summary_group.id')
        ->groupBy('summary_group.code')
        ->groupBy('summary_group.description')->get();
        return $this->sendResponse($items, 'Summary successfully');
        // $html = '<option value="">Select Product group</option>';
        // foreach($items as $item){
        //     $html .= '<option value="'.$item->id.'">'.$item->code.'</option>';
        // }   
        // return $html;
    }

}
