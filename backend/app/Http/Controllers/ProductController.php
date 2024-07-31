<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Factory; 
use App\Models\Materialgroup;
use App\Models\WastegeConsumptionRelation;
use App\Imports\ProductImport;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use DB ;
class ProductController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $data['title'] =  "Product" ; 
        //summary_group_id
        $q = Product::orderBy('id', 'desc'); 
        
        
        if($request->filter_type ==  2 ||  $request->filter_type ==  3 ){ 
            if($request->filter_type ==  2){
                $pro = collect(DB::table('wastege_relations')
                ->where('wastage_summary_group_id',$request->wastage_summary_group_id) 
                ->select('product_id') 
                ->get())->pluck('product_id')
                ->toArray(); 
               // return $this->sendResponse($pro, ' => Product retrieved successfully');
                $q->where(function($query)use($pro){ 
                    $query->whereIn('id',$pro); 
              });
            }else if($request->filter_type ==  3){
                $pro = collect(DB::table('consumption_relations')
                ->where('wastage_summary_group_id',$request->wastage_summary_group_id) 
                ->select('product_id') 
                ->get())->pluck('product_id')
                ->toArray(); 
               // return $this->sendResponse($pro, ' => Product retrieved successfully');
                $q->where(function($query)use($pro){ 
                    $query->whereIn('id',$pro); 
              });
            }
           
        }else{
            if($request->summary_group_id){
                $q->where('summary_group_id',$request->summary_group_id) ;
            }
        }
        if($request->material_code){
            $q->where('material_code',$request->material_code) ;
        }
        if($request->limit){
            $q->take($request->limit) ;
        } 
        
        $items  = $q->get();  
        $items = ProductResource::collection($items);  
        return $this->sendResponse($items, 'Product retrieved successfully');
        // return view('product/index', [
        //     'items' => $items,
        // ]);

    }

    public function product_singel($id,Request $request){

        $item = Product::find($id);   
       // $items = ProductResource::collection($items);  
        return $this->sendResponse($item, 'Product retrieved successfully'); 

    }
    public function consumption_material($id,Request $request){

        $item = Product::find($id);   
        // $items = ProductResource::collection($items);  
        $consumtions = WastegeConsumptionRelation::select('wastege_consumption_relation.*','products.material_code as consumtion_material_code' ,'wastage_summary_group.group_name','wastage_summary_group.scrap_material')
        ->join('products','products.id','=','wastege_consumption_relation.consumtion_product_code')
        ->join('wastage_summary_group','wastage_summary_group.id','=','wastege_consumption_relation.wastage_summary_group_id')
        ->where('wastege_consumption_relation.wastage_product_code',$item->material_code)
        ->where('products.summary_group_id',$item->summary_group_id)
        ->get();
        return $this->sendResponse($consumtions, 'Product retrieved successfully'); 

    }
    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
           // return $this->sendResponse($request , 'Product retrieved failed'); 
            Storage::disk('public')->put('product', $request->csvFile);

            $data = Excel::import(new ProductImport, request()->file('csvFile')); 

             return $this->sendResponse($data , 'Product Upload successfully'); 
 
        }else{
             return $this->sendResponse(0, 'Product Upload failed'); 

        } 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] =  "Product Add";
        $data['date'] = date("Y-m-d");
        return view('product/add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'material_code' => 'required|max:50', 
            'factory_id' => 'required|max:11',
            'summary_group_id' => 'required|max:11',
        ]);
        if(Product::where('material_code',$request->material_code)
            ->where('plant_id',$request->factory_id)
            ->where('summary_group_id',$request->summary_group_id)->exists()){
          
                return $this->sendResponse(0, 'This material already exist'); 
        }else{
            $data = array();
            $data['material_code'] = $request->material_code;
            $data['plant_id'] = $request->factory_id;
            $data['summary_group_id'] = $request->summary_group_id;
            if($request->factory_id){
                $factory  = Factory::find($request->factory_id) ; 
                $data['plant'] = $factory ?  $factory->fac_code : '';
            } 
            $data['product_group'] = $request->product_group; 
            if($request->product_group){
                $material_group  = Materialgroup::where('material_group',$request->product_group)->first() ; 
                //return $this->sendResponse($material_group, 'Product retrieved successfully');
                if( $material_group){
                    $data['material_group_id'] = $material_group ?  $material_group['id'] : '';
                } 
            }  
            $product_data =  Product::create($data);
            if( $product_data){
                $consumption_material =  $request->consumption_material;
                foreach ($consumption_material as $key => $value) { 
                    $consumption_material = array();
                    $consumption_material['wastage_summary_group_id'] = $request->wastage_summary_sub_id ? $request->wastage_summary_sub_id : $request->wastage_summary_id  ;
                    $consumption_material['wastage_product_code'] =  $data['material_code'] ;  
                    $consumption_material['consumtion_product_code'] = $value['id'];
                    $consumption_material['consumtion_product'] = $value['material_code'];
                    WastegeConsumptionRelation::create($consumption_material);
                } 
            } 
            return $this->sendResponse(1, 'Product retrieved successfully');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
