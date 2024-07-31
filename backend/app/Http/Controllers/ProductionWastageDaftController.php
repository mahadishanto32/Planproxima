<?php

namespace App\Http\Controllers; 
use App\Models\ProductionWastageDaft;
use App\Models\Product;
use App\Models\CostCenter;
use App\Models\Consumtion;
use App\Models\Ordergroup;
use App\Models\Ordertype;
use App\Models\Wastage;
use App\Models\Factory;
use App\Models\SummaryGroup;
use App\Models\WastageSummaryGroup;
use App\Models\Wastege_relation;
use App\Models\Consumption_relation;  
use App\Models\Cost;
use App\Models\Delivery;
use App\Models\Manufacturer;
use App\Models\Materialgroup;
use App\Models\SapFiles;    
use App\Models\WastegeConsumptionRelation;
use App\Imports\ProductionWastageDaftImport;
use Illuminate\Http\Request;
use App\Http\Resources\ProductionWastageDaftResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use Auth,DB ;
use App\Http\Controllers\AppBaseController;
class ProductionWastageDaftController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
             //$items = ProductionWastageDaft::orderBy('id', 'desc')->get();  
        $items = ProductionWastageDaft::orderBy('id', 'desc');
        
        if($request->comp_code){
                $items->where('comp_code',$request->comp_code);
        }
        if($request->plant){
                $items->where('plant',$request->plant);
        }
        if($request->type){
             $items->where('type',$request->type);
        }
        if($request->month){
           
            $items->where('date','LIKE','%'.$request->month.'.'.$request->year );
        }
        if($request->sync_type){
             if($request->sync_type ==  'approved'){
                 $items->where('status' ,1);
             }else{
                 $items->where('status','!=',1);
             }
         } 
        $items->skip(0);
        if($request->limit){
            $items->take($request->limit) ;
        } 
        $data = $items->get();  
        $items = ProductionWastageDaftResource::collection($data);     
        return $this->sendResponse($items, 'SAP Data retrieved successfully'); 

    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $file_name = $request->file('csvFile')->getClientOriginalName();
            SapFiles::where('file_name',  $file_name );
            if (SapFiles::where('file_name', '=', $file_name )->exists()) {
                return $this->sendResponse(0, 'This file already exist'); 
               // return redirect()->back()->with('error', 'This file already exist');
            }else{ 
                $file_name_url = Storage::disk('public')->put('production_file', $request->csvFile);
                chmod(storage_path('app/public/').$file_name_url, 0755);
                $sap_data = SapFiles::create([
                    'file_name' =>  $file_name ,
                    'file_name_url' =>  $file_name_url ,
                    'created_by' => Auth::user()->id ,  
                    'updated_by' => Auth::user()->id ,
                    'date' => Now()
                ]);
                $additional_data = [
                    'sap_file_id' => $sap_data->id 
                ];

                $data = Excel::import(new ProductionWastageDaftImport($additional_data), request()->file('csvFile')); 
                return $this->sendResponse($data, 'SAP Data retrieved successfully'); 
            }
        }else{ 
            return $this->sendResponse(0, 'Error');
        } 
    }

    public function sync(){
        $items = ProductionWastageDaft::where('status',0)
        //->whereNull('error_note') 
        ->orderBy('id', 'desc')
        ->get()->toArray();   
 
        foreach ($items as $key => $value) { 
            $factory = Factory::where('fac_code', $value['plant'] )->where('status',1)->first(); 
            if(empty($factory)){
                return $value['plant'];
            }else{
                $product = Product::where('material_code',$value['product_code'])
                ->where('plant_id',$factory->id)
                ->orderBy('id','ASC')
                ->first();  
    
                if($product){  
                    $materialgroup = Materialgroup::where( 'id', $product->material_group_id)->first();    
                    //if($materialgroup){
                    if(($value['type'] ==  'PRODUCTION' ) ){ 
                        if($factory->id!='' && $product->summary_group_id!=''){  
                            $data =  Manufacturer::create([
                                'product_id'     => $product->id ,    
                                'summary_group_id' =>  (isset($product->summary_group_id) && $product->summary_group_id!=''  ?  $product->summary_group_id  :  0),
                                'pg_code' =>  $materialgroup ?  $materialgroup->material_group : 0 ,   
                                'date' =>  date('Y-m-d H:i:s', strtotime($value['date'])) ,
                                'factory_id' => $factory ? $factory->id :  0,   // plant code
                                'production_quantity_gnh' => $value['production_quantity_gnh'], 
                                'production_quantity_oth' => $value['production_quantity_oth'], 
                                'remarks' => $value['remarks'], 
                                'sap_file_id' =>  $value['sap_file_id'] , 
                                'darft_id' => $value['id'], 
                                'created_by' => Auth::user()->id ,  
                                'updated_by' => Auth::user()->id ,  
                            ]);
    
                            if($data){
                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                                ->update(['status' => 1]); 
                            }
                        }elseif($factory->id!='' && $product->summary_group_id==''){
                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 3 ,'error_note' => 'Summery group not match']);
                        }else{ 
                            DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 3 ,'error_note' => 'Factory not connected']); 
                        } 
                    }else if(($value['type'] == 'DELIVERY') ){ 
                        if($factory->id!='' && $product->summary_group_id!=''){  
                            $data =  Delivery::create([
                                'product_id'     => $product->id ,  
                                'pg_code' => $materialgroup ? $materialgroup->material_group : '' ,    
                                'delivery_qty' => $value['delivery_qty'],  
                                'date' =>  date('Y-m-d H:i:s', strtotime($value['date'])) ,
                                'factory_id' => $factory ? $factory->id : 0,  
                                'summary_group_id' =>  $product->summary_group_id  ?  $product->summary_group_id  : 0,
                                'remarks' => $value['remarks'] ? $value['remarks'] : 0,    
                                'created_by' => Auth::user()->id , 
                                'darft_id' => $value['id'],  
                                'sap_file_id' =>  $value['sap_file_id'] , 
                                'updated_by' => Auth::user()->id ,  
                            ]);   
                            if($data){
                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                                ->update(['status' => 1]); 
                            }
                        }elseif($factory->id!='' && $product->summary_group_id==''){
                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 3 ,'error_note' => 'Summery group not match']); 
                        }else{
                            // dd($factory);
                            DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 3 ,'error_note' => 'Factory not connected']); 
                        }
                    }else if(($value['type'] == 'WASTAGE') ){   
                        if( $value['unit_code']){ 
                            $ordertype  = Ordertype::where('order_type', $value['unit_code'])->first() ; 
                            $cost_center = CostCenter::find( $ordertype ?  $ordertype->cost_center_id : 0); 
                            if($ordertype){ 
                                $wastege_relation = Wastege_relation::where('plant_id',$factory->id)->where('product_id',$product->id)->where('summary_group_id',$ordertype->summary_id)->first() ; 
                                if($wastege_relation){
                                
                                    $data =  Wastage::create([
                                        'product_id'     => $product->id ? $product->id : 0 , 
                                        'pg_code' => $materialgroup ? $materialgroup->material_group : 0 ,    
                                        'actual_wastage'  => $value['wastage'], 
                                        'wastage_value'  => $value['wastage_value'], 
                                        'date' =>  date('Y-m-d H:i:s', strtotime($value['date'])) , 
                                        'factory_id' => $factory ? $factory->id : 0,
                                        'wastage_summary_group_id' => $wastege_relation->wastage_summary_group_id ,  
                                        'order_group_id' => $ordertype->id ,
                                        'remarks' => $value['remarks'], 
                                        'darft_id' => $value['id'],    
                                        'created_by' => Auth::user()->id,
                                        'updated_by' => Auth::user()->id,
                                        'sap_file_id' =>  $value['sap_file_id'] , 
                                        'summary_group_id' =>  $ordertype  ?  $ordertype->summary_id  : 0,
                                    ]);  
                                    if($data){
                                        DB::table('productionwastagedafts')->where('id',$value['id']) 
                                        ->update(['status' => 1]); 
                                    } 
                                
                                }else{
                                    DB::table('productionwastagedafts')->where('id',$value['id']) 
                                    ->update(['status'=> 4 ,'error_note' => 'Wastege relation not found (summary_group_id ='.$ordertype->summary_id.')']); 
                                } 
                            }else{
                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                                ->update(['status'=> 3 ,'error_note' => 'Order type not found']); 
                            }   
                        }else{
                            DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 3 ,'error_note' => 'Unit code not found']); 
                        }
    
                    }else if(($value['type'] == 'CONSUMTION') ){
    
                        if( $value['unit_code']){  
                            $unit_code =  $value['unit_code'] ;
                            $costCenter = CostCenter::select('plant_code')->join('order_types','order_types.cost_center_id','cost_centers.id')->where('order_types.order_type',$unit_code)->first();
                            
                                $ordertype  = Ordertype::where('order_type', $value['unit_code'])->first() ; 
                                
                                if($ordertype){
                                        $consumption_relation = Consumption_relation::where('plant_id',$factory->id)->where('product_id',$product->id)->where('summary_group_id',$ordertype->summary_id)->first() ; 
                                        if($consumption_relation){
                                                
                                            $cost_center = CostCenter::find( $ordertype ?  $ordertype->cost_center_id : 0);
                                            $data =  Consumtion::create([
                                                'product_id'     => $product->id ? $product->id : 0 , 
                                                'pg_code' => $materialgroup ?  $materialgroup->material_group : 0,  
                                                'cost_code_id' =>   $cost_center  ?  $cost_center->id  : 0,      
                                                'consumtion' => $value['consumtion'],  
                                                'consumtion_value' => $value['consumtion_value'],  
                                                'date' =>  date('Y-m-d H:i:s', strtotime($value['date'])) ,
                                                'factory_code' =>   $factory ? $factory->fac_code : 0 ,   // plant code
                                                'factory_id' => $factory ? $factory->id : 0,   // plant code
                                                'remarks' => $value['remarks'],      
                                                'created_by' => Auth::user()->id ,  
                                                'updated_by' => Auth::user()->id ,  
                                                'darft_id' => $value['id'], 
                                                'wastage_summary_group_id' => $consumption_relation->wastage_summary_group_id ,  
                                                'sap_file_id' =>  $value['sap_file_id'] , 
                                                'order_group_id' => $ordertype->id ,
                                                'summary_group_id' =>  $ordertype  ?  $ordertype->summary_id  : 0,
                                            ]);  
                                            if($data){
                                                DB::table('productionwastagedafts')->where('id',$value['id']) 
                                                ->update(['status' => 1]); 
                                            }
                                        }else{ 
                                            DB::table('productionwastagedafts')->where('id',$value['id']) 
                                            ->update(['status'=> 5 ,'error_note' => 'Consumption relation not found (summary_group_id ='.$ordertype->summary_id.')']); 
    
                                        }
                                }else{
                                    DB::table('productionwastagedafts')->where('id',$value['id']) 
                                    ->update(['status'=> 3 ,'error_note' => 'Order type not found']);  
                                }
                                
                        }else{
                            DB::table('productionwastagedafts')->where('id',$value['id']) 
                            ->update(['status'=> 8 ,'error_note' => 'Unit code not found']); 
                        }
                    }else if(($value['type'] == 'RETURN') ){
                        DB::table('productionwastagedafts')->where('id',$value['id']) 
                        ->update(['status'=> 7 ,'error_note' => 'RETURN']);
                    }   
                }else{
                    DB::table('productionwastagedafts')->where('id',$value['id']) 
                    ->update(['status'=> 2 ,'error_note' => 'Material not found']); 
                }                 
            }
        } 
        //exit();
        return $this->sendResponse(1, 'SAP Data sync retrieved successfully'); 
        //exit();
        //return redirect('/production_wastage_daft');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function show(Cost $cost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function edit(Cost $cost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cost $cost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cost $cost)
    {
        //
    }
}
