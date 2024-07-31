<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductDraftAPIRequest;
use App\Http\Requests\API\UpdateProductDraftAPIRequest;
use App\Models\ProductDraft;
use App\Models\Product;   
use App\Models\Materialgroup;  
use App\Models\SummaryGroup ;
use App\Models\Factory;   
use App\Models\WastageSummaryGroup;   
use App\Models\Wastege_relation;   
use App\Models\Consumption_relation;   
use App\Models\WastegeConsumptionRelation;  
use App\Models\ProductionWastageDaft;  
use App\Repositories\ProductDraftRepository;
use Illuminate\Http\Request;
use App\Imports\ProductDraftImport;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use Response;
use DB;
use Auth;

/**
 * Class ProductDraftController
 * @package App\Http\Controllers\API
 */

class ProductDraftAPIController extends AppBaseController
{
    /** @var  ProductDraftRepository */
    private $productDraftRepository;

    public function __construct(ProductDraftRepository $productDraftRepo)
    {
        $this->productDraftRepository = $productDraftRepo;
    }

    /**
     * Display a listing of the ProductDraft.
     * GET|HEAD /productDrafts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $query = ProductDraft::orderBy('id','DESC');
        if($request->plant){
            $query->where('plant',$request->plant);
        }
        if($request->product_type){
            $query->where('product_type',$request->product_type);
        }
         
        // if($request->status==0 || $request->status==1){
        //     $query->where('status',$request->status);
        // }else{
        //     $query->whereNotIn('status',[0,1]);
        // } 
        if($request->material_group){
            $query->where('material_group',$request->material_group);
        }
        if($request->wastage_group){
            $query->where('wastage_group',$request->wastage_group);
        }
        if($request->product_group){
            $query->where('product_group',$request->product_group);
        }
        
        $productDrafts  = $query->get();
        // $productDrafts = $this->productDraftRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        return $this->sendResponse($productDrafts->toArray(), 'Product Drafts retrieved successfully');
    }

    public function product_sync(){
         
        $rows = ProductDraft::where('status',0)
       // ->where('material_code','1291201002')
        ->get();
        foreach ($rows as $key => $row) {  
            if($row['material_code'] !=''){  
                $factory = Factory::where('fac_code',$row['plant'])->first();  
                if($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption' ){
                    $product_info = Product::where('material_code',$row['material_code'])
                    ->whereIn('product_type',['wastage', 'consumption'])
                    ->where('plant',$row['plant'])->first(); 
                    if(!$product_info){
                        $materialgroup = Materialgroup::where('material_group',$row['material_group'])->first();
                            $product_info =  Product::create([
                                'plant'    =>  $row['plant'] , 
                                'plant_id' =>  $factory['id'] ? $factory['id'] : 0 , 
                                'material_code' =>  $row['material_code'] ,   
                                'product_type' =>  $row['product_type'] ,  
                                'description'   =>  $row['description'] ,   
                                'product_group' =>  $row['material_group'] ,  
                                'material_group_id' =>  $materialgroup ? $materialgroup['id'] : 0 ,  
                                'material_type' =>  $row['material_type'] ,  
                                'base_unit_of_measure' =>  $row['base_unit_of_measure'] ,   
                                'created_by' => Auth::user()->id ,  
                                'updated_by' => Auth::user()->id ,
                            ]);


                            DB::table('productionwastagedafts')
                            ->where('plant',$row['plant']) 
                            ->where('product_code',$row['material_code'])  
                            ->update(['status'=> 0 ,'error_note' => '']); 

                        // $wastageSummaryGroup = WastageSummaryGroup::where('group_name',$row['wastage_group'])->where('plant_id',$factory['id'])->first();
                        // if($wastageSummaryGroup){  
                    }
                    if( $product_info){
                        $wastageSummaryGroup = WastageSummaryGroup::where('group_name',$row['wastage_group'])->where('plant_id',$factory['id'])->first();
                        if($wastageSummaryGroup){ 

                            if($row['product_type'] == 'wastage'){
                                if(!Wastege_relation::where('product_id', $product_info['id'])
                                ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
                                ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
                                    $insert_data =   Wastege_relation::create([
                                        'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
                                        'product_id'        =>  $product_info['id'], 
                                        'summary_group_id'     =>  $wastageSummaryGroup['summary_group_id'] ,
                                        'plant_id'        =>   $factory['id'] ,
                                        'created_by' => Auth::user()->id ,     
                                        'updated_by' => Auth::user()->id ,
                                    ]); 
                                    DB::table('product_drafts')->where('id',$row['id']) 
                                    ->update(['status'=> 1 ,'error_note' => '']);  

                                }else{
                                    //wastage material alredy exist 
                                    DB::table('product_drafts')->where('id',$row['id']) 
                                    ->update(['status'=> 3 ,'error_note' => 'Wastage material already  exist']);  

                                }  
                            }else if($row['product_type'] == 'consumption'){

                                if(!Consumption_relation::where('product_id', $product_info['id'])
                                ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
                                ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
                                    $insert_data =   Consumption_relation::create([
                                        'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
                                        'product_id'        =>  $product_info['id'], 
                                        'summary_group_id'  =>  $wastageSummaryGroup['summary_group_id'] ,
                                        'plant_id'        =>   $factory['id'] ,
                                        'created_by' => Auth::user()->id ,     
                                        'updated_by' => Auth::user()->id ,
                                    ]);  
                                    DB::table('product_drafts')->where('id',$row['id']) 
                                    ->update(['status'=> 1 ,'error_note' => '']);  

                                } 
                                else{
                                    //Consumption material alredy exist 
                                    DB::table('product_drafts')->where('id',$row['id']) 
                                    ->update(['status'=> 4 ,'error_note' => 'Consumption material already  exist']);  

                                }
                            }
                        }else{
                            DB::table('product_drafts')->where('id',$row['id']) 
                            ->update(['status'=> 2 ,'error_note' => 'wastage summary group  not found']);
                        }
                    } 
    

                }else{
                    $summary_group = SummaryGroup::where('code',$row['product_group'])->first();   
                    if($summary_group){ 
                        $product_info = Product::where('material_code',$row['material_code'])
                        ->where('plant',$row['plant'])
                        ->where('product_type','production')
                        ->where('summary_group_id',$summary_group['id'])
                        ->first();  
                        if(!$product_info){
                            $materialgroup = Materialgroup::where('material_group',$row['material_group'])->first();
                            $product_info =  Product::create([
                                'plant'    =>  $row['plant'] , 
                                'plant_id' =>  $factory['id'] ? $factory['id'] : 0 , 
                                'material_code' =>  $row['material_code'] ,   
                                'product_type' =>  $row['product_type'] ,  
                                'description'   =>  $row['description'] ,   
                                'product_group' =>  $row['material_group'] ,  
                                'material_group_id' =>  $materialgroup ? $materialgroup['id'] : 0 ,  
                                'material_type' =>  $row['material_type'] ,  
                                'base_unit_of_measure' =>  $row['base_unit_of_measure'] ,  
                                'summary_group_id' =>  $summary_group ? $summary_group['id'] : 0,    
                                'created_by' => Auth::user()->id ,  
                                'updated_by' => Auth::user()->id ,
                            ]); 
                            DB::table('productionwastagedafts')
                            ->where('plant',$row['plant']) 
                            ->where('product_code',$row['material_code'])  
                            ->update(['status'=> 0 ]); 

                            DB::table('product_drafts')->where('id',$row['id']) 
                                    ->update(['status'=> 1 ,'error_note' => '']); 

                        }else{
                            DB::table('product_drafts')->where('id',$row['id']) 
                            ->update(['status'=> 2 ,'error_note' => 'Production material already exist']);  
                        }

                    }else{

                        DB::table('product_drafts')->where('id',$row['id']) 
                        ->update(['status'=> 2 ,'error_note' => 'Product group not found']);  

                    }
                    
                }
            }else{
                DB::table('product_drafts')->where('id',$row['id']) 
                ->update(['status'=> 2 ,'error_note' => 'Product code is empty']);  
            }
        }
            // print_r($product_info);

            // exit();

            // return $this->sendResponse($product_info, 'test'); 

            
        //     $factory = Factory::where('fac_code',$row['plant'])->first();  
        //     if($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption' ){

        //         $wastageSummaryGroup = WastageSummaryGroup::where('group_name',$row['wastage_group'])->where('plant_id',$factory['id'])->first(); 

        //     }else{
        //         if( $summary_group ){
        //             $wastageSummaryGroup = WastageSummaryGroup::where('group_name',$row['wastage_group'])->where('plant_id',$factory['id'])->where('summary_group_id',$summary_group['id'])->first(); 
        //         }  
        //     }
          
        //     if(!$product_info){

        //      $materialgroup = Materialgroup::where('material_group',$row['material_group'])->first();
            
        //     // if(($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption') || ($row['product_type'] == 'production' &&  $wastageSummaryGroup )){
        //     if($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption' ){

        //         $product_info =  Product::create([
        //             'plant'    =>  $row['plant'] , 
        //             'plant_id' =>  $factory['id'] ? $factory['id'] : 0 , 
        //             'material_code' =>  $row['material_code'] ,   
        //             'product_type' =>  $row['product_type'] ,  
        //             'description'   =>  $row['description'] ,   
        //             'product_group' =>  $row['material_group'] ,  
        //             'material_group_id' =>  $materialgroup ? $materialgroup['id'] : 0 ,  
        //             'material_type' =>  $row['material_type'] ,  
        //             'base_unit_of_measure' =>  $row['base_unit_of_measure'] ,   
        //             'created_by' => Auth::user()->id ,  
        //             'updated_by' => Auth::user()->id ,
        //         ]);
        //         DB::table('product_drafts')->where('id',$row['id']) 
        //                 ->update(['status'=> 1 ,'error_note' => '']); 
        //     }else{
        //         if($wastageSummaryGroup){
        //             $product_info =  Product::create([
        //                 'plant'    =>  $row['plant'] , 
        //                 'plant_id' =>  $factory['id'] ? $factory['id'] : 0 , 
        //                 'material_code' =>  $row['material_code'] ,   
        //                 'product_type' =>  $row['product_type'] ,  
        //                 'description'   =>  $row['description'] ,   
        //                 'product_group' =>  $row['material_group'] ,  
        //                 'material_group_id' =>  $materialgroup ? $materialgroup['id'] : 0 ,  
        //                 'material_type' =>  $row['material_type'] ,  
        //                 'base_unit_of_measure' =>  $row['base_unit_of_measure'] ,  
        //                 'summary_group_id' =>  $summary_group ? $summary_group['id'] : 0,    
        //                 'created_by' => Auth::user()->id ,  
        //                 'updated_by' => Auth::user()->id ,
        //             ]);
        //             DB::table('product_drafts')->where('id',$row['id']) 
        //                     ->update(['status'=> 1 ,'error_note' => '']); 

        //         }

        //     }
        //      // }
               
        //     }else{ 
        //         if($row['product_type'] == 'production'){
        //             DB::table('product_drafts')->where('id',$row['id']) 
        //             ->update(['status'=> 2 ,'error_note' => 'Production material already exist']);  
        //         }

        //     } 

        // if($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption' ){   
        //     if($wastageSummaryGroup && $product_info){ 
        //     //if($wastageSummaryGroup && $product_info){   
        //         if($row['product_type'] == 'wastage'){
        //             if(!Wastege_relation::where('product_id', $product_info['id'])
        //             ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
        //             ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
        //                 $insert_data =   Wastege_relation::create([
        //                     'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
        //                     'product_id'        =>  $product_info['id'], 
        //                     'summary_group_id'     =>  $wastageSummaryGroup['summary_group_id'] ,
        //                     'plant_id'        =>   $factory['id'] ,
        //                     'created_by' => Auth::user()->id ,     
        //                     'updated_by' => Auth::user()->id ,
        //                 ]); 
        //                 DB::table('product_drafts')->where('id',$row['id']) 
        //                 ->update(['status'=> 1 ,'error_note' => '']);  

        //             }else{
        //                 //wastage material alredy exist 
        //                 DB::table('product_drafts')->where('id',$row['id']) 
        //                 ->update(['status'=> 3 ,'error_note' => 'Wastage material already  exist']);  

        //             }  
        //         }else if($row['product_type'] == 'consumption'){

        //             if(!Consumption_relation::where('product_id', $product_info['id'])
        //             ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
        //             ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
        //                 $insert_data =   Consumption_relation::create([
        //                     'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
        //                     'product_id'        =>  $product_info['id'], 
        //                     'summary_group_id'  =>  $wastageSummaryGroup['summary_group_id'] ,
        //                     'plant_id'        =>   $factory['id'] ,
        //                     'created_by' => Auth::user()->id ,     
        //                     'updated_by' => Auth::user()->id ,
        //                 ]);  
        //                 DB::table('product_drafts')->where('id',$row['id']) 
        //                 ->update(['status'=> 1 ,'error_note' => '']);  

        //             } 
        //             else{
        //                 //Consumption material alredy exist 
        //                 DB::table('product_drafts')->where('id',$row['id']) 
        //                 ->update(['status'=> 4 ,'error_note' => 'Consumption material already  exist']);  

        //             }
        //         }  
                
                
        //     }else{
        //         //wastage summary group  not found 
        //         if($row['product_type'] == 'wastage' || $row['product_type'] == 'consumption' ){
        //             DB::table('product_drafts')->where('id',$row['id']) 
        //             ->update(['status'=> 2 ,'error_note' => 'wastage summary group  not found']);   
        //         }
        //     }
        // }
        // }

            return $this->sendResponse(1, 'Product sync successfully'); 
    }  
    
 

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
           // return $this->sendResponse($request , 'Product retrieved failed'); 
            Storage::disk('public')->put('product', $request->csvFile);

            $data = Excel::import(new ProductDraftImport, request()->file('csvFile')); 

             return $this->sendResponse($data , 'Product Upload successfully'); 
 
        }else{
             return $this->sendResponse(0, 'Product Upload failed'); 

        } 
    }

    /**
     * Store a newly created ProductDraft in storage.
     * POST /productDrafts
     *
     * @param CreateProductDraftAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductDraftAPIRequest $request)
    {
        $input = $request->all();

        $productDraft = $this->productDraftRepository->create($input);

        return $this->sendResponse($productDraft->toArray(), 'Product Draft saved successfully');
    }

    /**
     * Display the specified ProductDraft.
     * GET|HEAD /productDrafts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductDraft $productDraft */
        $productDraft = $this->productDraftRepository->find($id);

        if (empty($productDraft)) {
            return $this->sendError('Product Draft not found');
        }

        return $this->sendResponse($productDraft->toArray(), 'Product Draft retrieved successfully');
    }

    /**
     * Update the specified ProductDraft in storage.
     * PUT/PATCH /productDrafts/{id}
     *
     * @param int $id
     * @param UpdateProductDraftAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductDraftAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProductDraft $productDraft */
        $productDraft = $this->productDraftRepository->find($id);

        if (empty($productDraft)) {
            return $this->sendError('Product Draft not found');
        }

        $productDraft = $this->productDraftRepository->update($input, $id);

        return $this->sendResponse($productDraft->toArray(), 'ProductDraft updated successfully');
    }

    /**
     * Remove the specified ProductDraft from storage.
     * DELETE /productDrafts/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ProductDraft $productDraft */
        $productDraft = $this->productDraftRepository->find($id);

        if (empty($productDraft)) {
            return $this->sendError('Product Draft not found');
        }

        $productDraft->delete();

        return $this->sendSuccess('Product Draft deleted successfully');
    }
}
