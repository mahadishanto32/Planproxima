<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

class ProductionReportController extends Controller
{
    /**
     *
     * Created by Sazzadul islam
     * Date : 09/05/2019
     *
     **/
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rpt_view_all(Request $request)
    {
        $week     =  $request->get('week');
        $month    =  $request->get('month');
        $year     =  $request->get('year');
        $products =  $request->get('products');
        $factory  =  $request->get('factory');
        $user_data = Auth::user();
        $productionSectionOT = DB::table('tbl_production_section_cost')
            ->join('tbl_production_section', 'tbl_production_section_cost.isection_id', '=', 'tbl_production_section.iid')
            ->where('tbl_production_section_cost.standard_year', $year)
            ->where('tbl_production_section_cost.iproduct_id', $products)
            ->where('tbl_production_section_cost.iactive', 0)
            ->where('tbl_production_section_cost.isection_id', 1)
            ->orderBy('tbl_production_section_cost.isection_id', 'DESC')
            ->get();

        $productionSectionGH = DB::table('tbl_production_section_cost')
            ->join('tbl_production_section', 'tbl_production_section_cost.isection_id', '=', 'tbl_production_section.iid')
            ->where('tbl_production_section_cost.standard_year', $year)
            ->where('tbl_production_section_cost.iproduct_id', $products)
            ->where('tbl_production_section_cost.iactive', 0)
            ->where('tbl_production_section_cost.isection_id', 2)
            ->orderBy('tbl_production_section_cost.isection_id', 'DESC')
            ->get();
        // dd($year);

        $wastageQuery = DB::table('tbl_production_category_cost')
            ->where('entry_year', $year)
            ->where('ifactory', $factory)
            ->where('iproductid', $products)
            ->where('itype', 3)
            ->where('iactive', 0)
            ->get();

        $detailsQuery = DB::table('tbl_production_details')
            ->join('tbl_production_category_cost', 'tbl_production_details.icat', '=', 'tbl_production_category_cost.iid')
            ->where('tbl_production_details.iyear', $year)
            ->where('tbl_production_details.imonth', $month)
            ->where('tbl_production_details.ifactory', $factory)
            ->where('tbl_production_details.iproduct', $products)
            ->where('tbl_production_category_cost.itype', 0)
            ->where('tbl_production_details.iactive', 0);


        if ($request->get('week') != '') {
            $details = $detailsQuery->where('tbl_production_details.iweek', $request->get('week'))->get();
        } else {
            $details = $detailsQuery->where('tbl_production_details.iweek', 4)->get();
        }
        $costArea = DB::table('tbl_production_category_cost')
            ->where('entry_year', $year)
            ->where('ifactory', $factory)
            ->where('iproductid', $products)
            ->where('itype', 1)
            ->where('icosttype', 2)
            ->where('iactive', 0)
            ->orderBy(DB::raw("vcategory = 'Factory-Salary & Allowance [Management]'"), 'DESC')
            ->orderBy(DB::raw("vcategory='Factory-Salary & Allowance [Production]'"), 'DESC')
            // ->orderBy(DB::raw("vcategory='Factory-Lunch & Snacks'"),'DESC')
            ->orderBy('iid')
            // ->orderBy('iid')
            // ->orderBy('vcategory','Factory-Salary & Allowance [Management]')
            // ->orderBy('vcategory','Factory-Salary & Allowance [Production]')
            // ->orderByRaw("FIELD(vcategory , 'Factory-Salary & Allowance [Management]','Factory-Salary & Allowance [Production]') ASC")
            ->get();

        $costAreaOt = DB::table('tbl_production_category_cost')
            ->where('entry_year', $year)
            ->where('ifactory', $factory)
            ->where('iproductid', $products)
            ->where('itype', 1)
            ->where('icosttype', 1)
            ->where('iactive', 0)
            ->orderBy('iid')
            // ->orderByRaw("IF(status = 'announced', accouncement_date, date_start) DESC")
            ->get();

        $empsql = DB::table('tbl_production_emp')
            ->select('iweek', DB::raw('sum(inumber_of_resig) as resign'), DB::raw('sum(inumber_of_join) as ajoin'), DB::raw('sum(iending_emp) as ending'), DB::raw('sum(ibegining_emp) as begining'))
            ->where('iyear', $year)
            ->where('imonth', $month)
            ->where('ifactory', $factory)
            ->where('iproduct', $products)
            ->where('iactive', 0);


        if ($request->get('week') != '') {
            $empquery = $empsql->where('tbl_production_emp.iweek', $request->get('week'))->groupBy('tbl_production_emp.iweek')->get();
        } else {
            $empquery = $empsql->groupBy('tbl_production_emp.iweek')->get();
        }



        //dd($details);
        // $result['view'] = view('production/rpt_view_all', compact('productionSectionOT', 'productionSectionGH', 'wastageQuery', 'details', 'costArea', 'costAreaOt', 'empquery', 'request'));
        // return Response::json(ResponseUtil::makeResponse('ok', $result));

        return view('production/rpt_view_all', compact('productionSectionOT', 'productionSectionGH', 'wastageQuery', 'details', 'costArea', 'costAreaOt', 'empquery', 'request'));
    }


    public function get_products_list(Request $request)
    {

        $factory = $request->factory_id;

        $productsList = DB::table('tbl_production_product_name_cost')
            ->where('ifactory', $factory)
            ->where('iactive', 0)
            ->get();

        return Response::json(ResponseUtil::makeResponse('ok', $productsList));
        // return $productsList;

        // return view('production/get_products_list',compact('productsList'));
    }

    public function get_sub_products_list(Request $request)
    {
        $products_group_id = $request->get('products_group_id');

        $productsSubList = DB::table('tbl_production_product_sub_group')
            ->where('product_group_id', $products_group_id)
            ->where('iactive', 0)
            ->get();
        return Response::json(ResponseUtil::makeResponse('ok', $productsSubList));

        // return view('production/get_products_sub_list', compact('productsSubList'));
    }

    public function pro_feedback_type(Request $request)
    {

        $data = DB::table('tbl_production_feedback')->get();

        return view('production/pro_feedback_type', $data);
    }


    public function pro_feedback_comments(Request $request)
    {
        $user_data = Auth::user();

        DB::table('tbl_production_feedback')->insert(
            [
                'iyear' => date('Y'),
                'imonth' => date('m'),
                'ifactory' =>  $request->factoryid,
                'iproduct' =>  $request->productid,
                'tfeedback' =>  $request->activity,
                'itype' =>  $request->itype,
                'production' =>  $request->production,
                'section' =>  $request->section,
                'vuser' => $user_data->email,
                'vscreenname' => $user_data->screenname,
                'dcreatedate' => date('Y-m-d'),
                'iactive' => 0
            ]
        );


        if ($request->activity != "") {
            die();
            if ($request->itype == 1) {
                $type = 'Production';
            } else {
                $type = 'Wastage';
            }
            $phpMail = new PHPMailer();
            $message = "";

            $to = "<b> ATENTION: </b> ";

            $queryDataShow = DB::select(DB::raw("SELECT * FROM tbl_department
            WHERE tbl_department.id='" . $request->factoryid . "'"));

            // $phpMail->AddAddress("sazzadul.islam@ssgbd.com","IT");
            $phpMail->AddAddress($queryDataShow[0]->hod_email, $queryDataShow[0]->hod_name);
            //$phpMail->AddCC("khushbu@ssgbd.com", "Khushbu Moni Lopa");
            $phpMail->AddCC('shahidul.alam@ssgbd.com', "System CC");
            //$phpMail->AddCC("management.desk@ssgbd.com", "Management DeskOffice");
            $phpMail->AddCC("management.desk@ssgbd.com", "Management Desk");
            $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
            // $phpMail->AddCC("sazzadul.islam@ssgbd.com","Management DeskOffice");

            if ($request->mailcc1 != "") {
                $phpMail->AddCC($request->mailcc1, "System CC");
            }
            if ($request->mailcc2 != "") {
                $phpMail->AddCC($request->mailcc2, "System CC");
            }
            if ($request->mailcc3 != "") {
                $phpMail->AddCC($request->mailcc3, "System CC");
            }
            $comments = $request->activity;
            $to_user = $queryDataShow[0]->hod_name;

            $message = view('production/mail_comm', compact('comments', 'to_user', 'type'));



            $user = "Management Desk's Office";
            $user_email = "management.desk@ssgbd.com";


            //$phpMail->AddCC("management.desk@ssgbd.com","Management DeskOffice");// CEO OFFICE
            $phpMail->AddReplyTo("management.desk@ssgbd.com", "Management Desk");

            $msg = $message;

            $phpMail->FromName = $user;
            $phpMail->From = "management.desk@ssgbd.com";

            //////////////


            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = $type . " Report Feedback";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;

            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
                exit;
            }

            $phpMail->ClearAddresses();
            $phpMail->ClearAttachments();
        }


        return Redirect::back()->with('success', 'Data insert successfully');
    }
}
