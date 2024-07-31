<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Auth;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

class ProductionController extends Controller
{
    /**
     *
     * Created by Sazzadul islam
     * Date : 09/05/2019
     *
     **/
    public function __construct()
    {
        // $globla_config = GlobalSettingsController::global_configs();
        // $this->middleware('auth');
        // $this->fac_start_date = $globla_config['productioon_start_date'];
        // $this->fac_end_date = $globla_config['productioon_end_date'];
    }
    public function pro_target_entry(Request $request)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_target as t')
            ->select(DB::raw('t.iid iid,t.iweek,t.imonth,t.iyear as year,sname.vsection_name,t.iproducttarget,t.iproductactual,t.ideliveryfc,t.ideliveryactual,departments.name,tbl_production_product_name_cost.vproduct_name,subg.name as product_sub_name,t.vremarks,t.dcreatedate as entrydate, t.iactive'))
            ->leftjoin('departments', 't.ifactory', 'departments.id')
            ->leftjoin('tbl_production_product_name_cost', 't.iproduct', 'tbl_production_product_name_cost.iid')
            ->leftjoin('tbl_production_section as sname', 'sname.iid', 't.isection')
            ->leftjoin('tbl_production_product_sub_group as subg', 'subg.id', 't.isubproduct');
        if ($request->year != '' && $request->month != '') {
            $data_bilder->where('departments.id', $user_data->dept)
                ->where('t.iyear', $request->year)
                ->where('t.imonth', $request->month);
            if ($request->week != '') {
                $data_bilder->where('t.iweek', $request->week);
            }
        }
        $data['target_data'] = $data_bilder->where('t.iactive', 0)->orderBy('t.iid', 'DESC')->paginate(100);


        return Response::json(ResponseUtil::makeResponse('ok', $data));
        // return view('production/pro_target_entry', $data);
    }

    public function pro_target_insert(Request $request)
    {
        $user_data = Auth::user();

        $id = DB::table('tbl_production_target')->insert(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'isubproduct' => $request->sub_products,
                'isection' => $request->section,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'iproducttarget' => $request->target,
                'iproductactual' => $request->actual,
                'ideliveryfc' => $request->delivery_fc,
                'ideliveryactual' => $request->delivery_actual,
                'vremarks' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        if ($id) {
            return Response::json([
                'success' => true,
                'message' => 'Success'
            ], 200);
        }
        return Response::json(ResponseUtil::makeError('error'), 400);
        // return Redirect::back()->with('success', 'Data insert successfully');
    }

    public function pro_target_edit($id)
    {
        $user_data = Auth::user();
        $data_bilder = DB::table('tbl_production_target as t')
            ->select(DB::raw('t.iid as iid,t.iweek,t.imonth,t.iyear as year,sname.vsection_name,t.iproducttarget,t.iproductactual,t.ideliveryfc,t.ideliveryactual,tbl_department.department_name,tbl_production_product_name_cost.vproduct_name,t.isubproduct as isubproduct_id,t.vremarks,t.dcreatedate as entrydate, t.iactive, t.iproduct, t.isection, t.ifactory'))
            ->leftjoin('tbl_department', 't.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name_cost', 't.iproduct', 'tbl_production_product_name_cost.iid')
            ->leftjoin('tbl_production_section as sname', 'sname.iid', 't.isection');

        $data['target_data'] = $data_bilder->where('t.iactive', 0)->where('t.iid', $id)->orderBy('t.iid', 'DESC')->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();

        $data['productsSubList'] = DB::table('tbl_production_product_sub_group')
            ->where('product_group_id', $data['target_data']->iproduct)
            ->where('iactive', 0)
            ->get();
        // dd($data['productsSubList']);

        $data['week'] = DB::table('tbl_production_week')->get();
        return view('production/pro_target_edit', $data);
    }

    public function pro_target_update(Request $request)
    {
        DB::table('tbl_production_target')->where('iid', $request->iid)->update(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'isubproduct' => $request->sub_products,
                'isection' => $request->section,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'iproducttarget' => $request->target,
                'iproductactual' => $request->actual,
                'ideliveryfc' => $request->delivery_fc,
                'ideliveryactual' => $request->delivery_actual,
                'vremarks' => $request->remarks,
            ]
        );
        return Redirect::back()->with('success', 'Data insert successfully');
    }

    public function pro_entry_bill_cost(Request $request)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_cost_cost as d')
            ->select(DB::raw('d.iid diid,d.ifactory,tbl_production_category_cost.vcategory as vcategory,tbl_department.department_name as dname,d.icost icost,
           d.iunit iunit,d.iyear as year, d.imonth as imonth, d.iweek as iweek,d.fbanch_mark as fbanch_mark,d.tremarks as tremarks,pname.vproduct_name, d.iactive, d.iid '))
            ->leftjoin('tbl_department', 'd.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name_cost as pname', 'd.iproduct', 'pname.iid')
            ->leftjoin('tbl_production_category_cost', 'd.icat', 'tbl_production_category_cost.iid');
        if ($request->year != '' && $request->month != '' && $request->factory != '' && $request->products != '') {
            $data_bilder->where('tbl_department.id', $request->factory)
                ->where('d.iyear', $request->year)
                ->where('d.imonth', $request->month)
                ->where('d.iproduct', $request->products);
            if ($request->week != '') {
                $data_bilder->where('d.iweek', $request->week);
            }
        }
        $data['cost_data'] = $data_bilder->where('d.iactive', 0)->orderBy('d.iid', 'DESC')->paginate();


        return Response::json(ResponseUtil::makeResponse('ok', $data));


        // return view('production/pro_entry_bill_cost', $data);
    }
    public function select_area_data(Request $request)
    {
        $products = $request->get('products_id');

        $productsList = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('entry_year', 2021)
            ->where('itype', $request->get('itype'))
            ->where('iproductid', $products)
            ->orderBy('iid', 'ASC')
            ->get();

        return Response::json(ResponseUtil::makeResponse('ok', $productsList));


        // $html = '<option value="">Select Area</option>';
        // foreach ($productsList as $key => $value) {
        //     $html .= '<option value="' . $value->iid . '">' . $value->vcategory . '</option>';
        // }
        // return $html;
    }

    public function select_area_data_cost(Request $request)
    {
        $products = $request->get('products_id');

        $productsList = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('entry_year', $request->get('yearselect'))
            ->where('itype', $request->get('itype'))
            ->where('ifactory', $request->get('fact'))
            ->where('iproductid', $products)
            // ->where('icosttype', 2)
            ->orderBy('iid', 'ASC')
            ->get();

        return Response::json(ResponseUtil::makeResponse('ok', $productsList));

        // $html = '<option value="">Select Area</option>';
        // foreach ($productsList as $key => $value) {
        //     $html .= '<option value="' . $value->iid . '">' . $value->vcategory . '</option>';
        // }
        // return $html;
    }

    public function get_iending_emp(Request $request)
    {
        $products = $request->get('products_id');

        $productsList = DB::table('tbl_production_emp')
            ->where('iactive', 0)
            ->where('iproduct', $products)
            ->orderBy('iid', 'DESC')
            ->first();

        if (isset($productsList->iending_emp)) {
            return $productsList->iending_emp;
        } else {
            return 0;
        }
    }
    public function pro_cost_entry(Request $request)
    {

        $user_data = Auth::user();
        $id = DB::table('tbl_production_cost_cost')->insert(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'icat' => $request->cat,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'icost' => $request->cost,
                'iunit' => $request->unit,
                'tremarks' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        if ($id) {
            return Response::json([
                'success' => true,
                'message' => 'Success'
            ], 200);
        }
        return Response::json(ResponseUtil::makeError('error'), 400);
        // return Redirect::back()->with('success', 'Data insert successfully');
    }

    public function pro_cost_edit($id)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_cost_cost');

        $data['cost_data'] = $data_bilder->where('iid', $id)->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();

        return view('production/pro_cost_edit', $data);
    }

    public function pro_cost_update(Request $request)
    {
        $user_data = Auth::user();
        DB::table('tbl_production_cost_cost')->where('iid', $request->iid)->update(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'icat' => $request->cat,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'icost' => $request->cost,
                'iunit' => $request->unit,
                'tremarks' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data Update successfully');
    }

    public function pro_wastage_entry(Request $request)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_wastage as d')
            ->select(DB::raw('d.iid diid,d.ifactory,tbl_production_category_cost.vcategory as vcategory,tbl_department.department_name as dname,d.iactualwastage icost,d.iyear as year, d.imonth imonth, d.iweek iweek,d.vremarks tremarks,pname.vproduct_name, d.iactive, d.iid'))
            ->leftjoin('tbl_department', 'd.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name_cost as pname', 'd.iproduct', 'pname.iid')
            ->leftjoin('tbl_production_category_cost', 'd.isection', 'tbl_production_category_cost.iid');
        if ($request->year != '' && $request->month != '' && $request->factory) {
            $data_bilder->where('tbl_department.id', $request->factory)
                ->where('d.iyear', $request->year)
                ->where('d.imonth', $request->month);
            if ($request->week != '') {
                $data_bilder->where('d.iweek', $request->week);
            }
        }
        $data['wastage_data'] = $data_bilder->where('d.iactive', 0)->orderBy('d.iid', 'DESC')->paginate(15);
        
        return Response::json(ResponseUtil::makeResponse('ok', $data));

        // return view('production/pro_wastage_entry', $data);
    }
    public function pro_wastage_insert(Request $request)
    {
        // dd($request);
        $user_data = Auth::user();
        $data = array(
            'ifactory' => $request->factory,
            'iproduct' => $request->products,
            'isection' => $request->cat,
            'iyear' => $request->year,
            'imonth' => $request->month,
            'iweek' => $request->week,
            'iactualwastage' => $request->actual_wastage,
            'vremarks' => $request->remarks,
            'vuser' => $user_data->email,
        );
        if (isset($request->w5blub) and $request->w5blub == 1) {
            $data['w5blub'] = 1;
        }

        $id = DB::table('tbl_production_wastage')->insert($data);

        if ($id) {
            return Response::json([
                'success' => true,
                'message' => 'Success'
            ], 200);
        }
        return Response::json(ResponseUtil::makeError('error'), 400);

        // return Redirect::back()->with('success', 'Data insert successfully');
    }
    public function pro_wastage_edit($id)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_wastage');

        $data['cost_data'] = $data_bilder->where('iid', $id)->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();

        return view('production/pro_wastage_edit', $data);
    }
    public function pro_wastage_update(Request $request)
    {
        $user_data = Auth::user();
        DB::table('tbl_production_wastage')->where('iid', $request->iid)->update(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'isection' => $request->cat,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'iactualwastage' => $request->actual_wastage,
                'vremarks' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data Update successfully');
    }

    /////////////
    public function pro_details_entry(Request $request)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_details as d')
            ->select(DB::raw('d.iid diid,d.ifactory,tbl_production_category_cost.vcategory as vcategory,tbl_department.department_name as dname,d.iyear as year, d.imonth imonth, d.iweek iweek,d.tdetails as tremarks,pname.vproduct_name, d.iactive, d.iid'))
            ->leftjoin('tbl_department', 'd.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name_cost as pname', 'd.iproduct', 'pname.iid')
            ->leftjoin('tbl_production_category_cost', 'd.icat', 'tbl_production_category_cost.iid')
            ->where('d.vuser', $user_data->email);
        if ($request->year != '' && $request->month != '' && $request->factory) {
            $data_bilder->where('tbl_department.id', $request->factory)
                ->where('d.iyear', $request->year)
                ->where('d.imonth', $request->month);
            if ($request->week != '') {
                $data_bilder->where('d.iweek', $request->week);
            }
        }
        $data['cost_data'] = $data_bilder->where('d.iactive', 0)->orderBy('d.iid', 'DESC')->paginate(15);

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();
        $data['request'] = $request;


        $data['areaList'] = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('entry_year', 2021)
            ->where('itype', 0)
            ->where('ifactory', $user_data->dept)
            ->orderBy('iid', 'ASC')
            ->get();

        $data['fac_start_date'] = $this->fac_start_date;
        $data['fac_end_date'] = $this->fac_end_date;

        return view('production/pro_details_entry', $data);
    }
    public function pro_details_insert(Request $request)
    {
        // dd($request);
        $user_data = Auth::user();
        DB::table('tbl_production_details')->insert(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'icat' => $request->category,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'tdetails' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data insert successfully');
    }
    public function pro_details_edit($id)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_details');

        $data['cost_data'] = $data_bilder->where('iid', $id)->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();

        $data['areaList'] = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('entry_year', 2021)
            ->where('itype', 0)
            ->where('ifactory', $user_data->dept)
            ->orderBy('iid', 'ASC')
            ->get();

        return view('production/pro_details_edit', $data);
    }
    public function pro_details_update(Request $request)
    {
        $user_data = Auth::user();
        DB::table('tbl_production_details')->where('iid', $request->iid)->update(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'icat' => $request->category,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'tdetails' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data Update successfully');
    }
    //////////////////
    public function pro_emp_turnover_entry(Request $request)
    {
        $user_data = Auth::user();





        $data_bilder = DB::table('tbl_production_emp as d')
            ->select(DB::raw('d.ibegining_emp, d.inumber_of_resig, d.inumber_of_join, d.iending_emp,d.iid diid,d.ifactory,tbl_department.department_name as dname,d.iyear as year, d.imonth as imonth, d.iweek as iweek,d.vremarks as tremarks,pname.vproduct_name, d.iactive, d.iid'))
            ->leftjoin('tbl_department', 'd.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name as pname', 'd.iproduct', 'pname.iid')
            ->where('tbl_department.id', $user_data->dept);
        if ($request->year != '' && $request->month != '') {
            $data_bilder->where('d.iyear', $request->year)
                ->where('d.imonth', $request->month);
            if ($request->week != '') {
                $data_bilder->where('d.iweek', $request->week);
            }
        }
        $data['cost_data'] = $data_bilder->where('d.iactive', 0)->orderBy('d.iid', 'DESC')->paginate(15);

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();
        $data['request'] = $request;

        return view('production/pro_emp_turnover_entry', $data);
    }
    public function pro_emp_turnover_insert(Request $request)
    {


        $user_data = Auth::user();
        DB::table('tbl_production_emp')->insert(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'inumber_of_join' => $request->new_join,
                'inumber_of_resig' => $request->resign,
                'ibegining_emp' => $request->begin_emp,
                'iending_emp' => $request->end_emp,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'vremarks' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data insert successfully');
    }
    public function pro_emp_turnover_edit($id)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_emp');

        $data['cost_data'] = $data_bilder->where('iid', $id)->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name')
            ->where('ifactory', $user_data->dept)
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();
        $data['week'] = DB::table('tbl_production_week')->get();

        return view('production/pro_emp_turnover_edit', $data);
    }
    public function pro_emp_turnover_update(Request $request)
    {
        $user_data = Auth::user();
        DB::table('tbl_production_emp')->where('iid', $request->iid)->update(
            [
                'ifactory' => $request->factory,
                'iproduct' => $request->products,
                'icat' => $request->cat,
                'iyear' => $request->year,
                'imonth' => $request->month,
                'iweek' => $request->week,
                'iactualwastage' => $request->actual_wastage,
                'tdetails' => $request->remarks,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data Update successfully');
    }

    ////////////////////
    public function pro_category_entry(Request $request)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_category_cost as c')
            ->select(DB::raw('c.iid,c.entry_year,c.ifactory,tbl_production_product_name_cost.vproduct_name,tbl_department.id,tbl_department.department_name,c.vcategory,c.iamount,c.iperunit,c.icosttype,c.iactive'))
            ->leftjoin('tbl_department', 'c.ifactory', 'tbl_department.id')
            ->leftjoin('tbl_production_product_name_cost', 'c.iproductid', 'tbl_production_product_name_cost.iid')
            ->where('c.iactive', 0)
            ->where('c.vuser', $user_data->email);
        if ($request->year != '' && $request->production_type != '' && $request->factory) {
            $data_bilder->where('tbl_department.id', $request->factory)
                ->where('c.entry_year', $request->year)
                ->where('c.ifactory', $request->factory)
                ->where('c.icosttype', $request->production_type);
        }
        $data['cost_data'] = $data_bilder->orderBy('c.iid', 'DESC')->paginate(50);

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();

        $data['request'] = $request;


        $data['areaList'] = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('itype', 0)
            ->where('ifactory', $user_data->dept)
            ->orderBy('iid', 'ASC')
            ->get();

        return view('production/pro_category_entry', $data);
    }
    public function pro_category_insert(Request $request)
    {


        // dd($request);
        $user_data = Auth::user();
        DB::table('tbl_production_category_cost')->insert(
            [
                'entry_year' => $request->year,
                'vcategory' => $request->category,
                'ifactory' => $request->factory,
                'iproductid' => $request->products,
                'itype' => 1,
                'icosttype' => $request->production_type,
                'iamount' => $request->amount,
                'iperunit' => $request->unit,
                'vuser' => $user_data->email,
            ]
        );
        return Redirect::back()->with('success', 'Data insert successfully');
    }
    public function pro_category_edit($id)
    {
        $user_data = Auth::user();

        $data_bilder = DB::table('tbl_production_category_cost');

        $data['cost_data'] = $data_bilder->where('iid', $id)->first();

        $department_bilder = DB::table('tbl_department')->where('ifactory', 1);
        if ($user_data->usertype == 'A' || $user_data->email == 'cost') {
            $data['department'] = $department_bilder->orderBy('department_name', 'ASC')->get();
        } else {
            $data['department'] = $department_bilder->where('id', $user_data->dept)->orderBy('department_name', 'ASC')->get();
        }
        $data['section'] = DB::table('tbl_production_section')
            ->where('iactive', 0)
            ->orderBy('vsection_name', 'ASC')
            ->get();

        $data['production_product'] = DB::table('tbl_production_product_name_cost')
            ->where('iactive', 0)
            ->orderBy('vproduct_name', 'ASC')
            ->get();

        $data['areaList'] = DB::table('tbl_production_category_cost')
            ->where('iactive', 0)
            ->where('itype', 0)
            ->where('ifactory', $user_data->dept)
            ->orderBy('iid', 'ASC')
            ->get();

        return view('production/pro_category_edit', $data);
    }
    public function pro_category_update(Request $request)
    {
        $user_data = Auth::user();

        DB::table('tbl_production_category_cost')->where('iid', $request->iid)->update(
            [
                'entry_year' => $request->year,
                'vcategory' => $request->category,
                'ifactory' => $request->factory,
                'iproductid' => $request->products,
                'itype' => 1,
                'icosttype' => $request->production_type,
                'iamount' => $request->amount,
                'iperunit' => $request->unit,
                'vuser' => $user_data->email,
            ]
        );

        return Redirect::back()->with('success', 'Data Update successfully');
    }
    //////////////
}
