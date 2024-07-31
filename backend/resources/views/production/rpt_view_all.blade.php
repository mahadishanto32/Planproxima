<div class="page-wrapper">

  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-bordered table-sm myfont">
              <thead class="thead-dark">
                @php
                if($request->month=="1"){
                $previousYear = $request->year-1;
                $previousMonth =12;
                }else{
                $previousYear = $request->year;
                $previousMonth = $request->month-1;
                }

                @endphp
                @if(sizeof($productionSectionOT)>0 || sizeof($productionSectionGH)>0)
                <tr>
                  <td>
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped" width="100%">


                      <tr>
                        <td colspan="10" align="center">
                          <h4> Production analysis : <?php echo $request->year; ?>-<?php echo $monthName = date('F', mktime(0, 0, 0, $request->month, 10)); ?> <?php if ($request->week != '') echo "(Week-" . $request->week . ")"; ?></h4>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="1" rowspan="2" align="center" style="vertical-align: middle;">Description</td>
                        <td colspan="1" rowspan="2" style="vertical-align: middle;">UoM</td>
                        <td colspan="4" align="center" bgcolor='#e6e61b'>
                          <h4>{{-- Current month --}} {{date('M', mktime(0, 0, 0, $request->month, 10)).' - '.$request->year}}</h4>
                        </td>
                        <td colspan="2" align="center" bgcolor='#faeeaa'>
                          <h4> {{-- Previous month --}} {{date('M', mktime(0, 0, 0, $previousMonth, 10)).' - '.$previousYear}}</h4>
                        </td>
                        <td colspan="1" align="center" bgcolor='#A9DBE1' rowspan="2" style="vertical-align: middle;">
                          <h4> AVG. Production </h4><br>Jan - <?php echo date('M', mktime(0, 0, 0, $request->month, 10)) ?>
                        </td>
                        <td colspan="1" align="center" bgcolor='#A9DBE1' rowspan="2" style="vertical-align: middle;">
                          <h4> AVG. Delivery</h4><br>Jan - <?php echo date('M', mktime(0, 0, 0, $request->month, 10)) ?>
                        </td>
                        <td colspan="1" align="center" rowspan="2" style="vertical-align: middle;" bgcolor='#dafdd9'>
                          <h4>Production Plan</h4>
                        </td>
                        <td colspan="1" align="center" rowspan="2" style="vertical-align: middle;" bgcolor='#dafdd9'>
                          <h4>Factory Capacity</h4>
                        </td>

                      </tr>
                      <tr>
                        <td colspan="1" align="center" bgcolor='#f0e68c'>
                          <h6>T.Production</h6>
                        </td>
                        <td colspan="1" align="center" bgcolor='#f0e68c'>
                          <h6>A.Production</h6>
                        </td>
                        <td colspan="1" align="center" bgcolor='#f0e68c'>
                          <h6>Comparison</h6>
                        </td>
                        <td colspan="1" align="center" bgcolor='#f0e68c'>
                          <h6>A.Delivery</h6>
                        </td>
                        <td colspan="1" align="center" bgcolor='#f0e68b'>
                          <h6>A.Production</h6>
                        </td>
                        <td colspan="1" align="center" bgcolor='#f0e68b'>
                          <h6>A.delivery</h6>
                        </td>
                        <td colspan="1" align="center"></td>
                        <td colspan="1" align="center"></td>

                      </tr>
                      @php
                      $con='';
                      $currentMonthTarget = 0;
                      $currentMonthActual = 0;
                      $currentMonthDelivery = 0;
                      $preMonthActual = 0;
                      $preMonthDelivery = 0;
                      $pstandard = 0;
                      $factoryamount = 0;
                      $avgelivery = 0;
                      $pavg = 0;
                      $monthtargetsecHO_iproductactual = 0;
                      $premonthtargetsecHO_iproductactual = 0;
                      $avgmonthtargetsecHO_iproductactual = 0;

                      $GHcurrentMonthTarget = 0;
                      $GHcurrentMonthActual = 0;
                      $GHper = 0;
                      $GHcurrentMonthDelivery = 0;
                      $GHpreMonthActual = 0;
                      $GHpreMonthDelivery = 0;
                      $GHpavg = 0;
                      $GHavgelivery = 0;
                      $GHfactoryamount = 0;
                      $GHpstandard = 0;




                      if($request->week!='')
                      {
                      $con=$con ." and t.iweek='".$request->week."'";
                      }

                      $storeArr=array();
                      @endphp

                      @if(sizeof($productionSectionGH)>0)

                      @foreach($productionSectionGH as $section)

                      @php


                      $monthtargetsec= DB::select("select t.iproduct,
                      t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                      sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                      from tbl_production_target t
                      inner join tbl_production_section sname on sname.iid = t.isection
                      left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                      where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$section->standard_year."' and t.imonth='".$request->month."'
                      and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");
                      // dd($monthtargetsec);



                      $premonthtargetsec= DB::select("select t.iproduct,
                      t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                      sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                      from tbl_production_target t
                      inner join tbl_production_section sname on sname.iid = t.isection
                      left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                      where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$previousYear."' and t.imonth='".$previousMonth."'
                      and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");




                      $avgmonthtargetsec= DB::select("select t.iproduct,
                      t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                      sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                      from tbl_production_target t
                      inner join tbl_production_section sname on sname.iid = t.isection
                      left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                      where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$request->year."' and t.imonth<='".$request->month."' and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");

                        $avgideliveryactual= DB::select("select t.iproduct,
                        t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                        sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                        from tbl_production_target t
                        inner join tbl_production_section sname on sname.iid = t.isection
                        left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                        where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$section->standard_year."' and t.imonth<='".$request->month."'
                          and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");


                          if(sizeof($monthtargetsec)==0){
                          $pstandard += (isset($section->istandardproduction)?$section->istandardproduction:0);

                          $factoryamount += (isset($section->factoryamount)?$section->factoryamount:0);
                          }
                          @endphp
                          @foreach($monthtargetsec as $key => $monthtargetsecdata)
                          @php

                          if(sizeof($monthtargetsec)>0)
                          {

                          $storeArr[2] = (isset($storeArr[2])?$storeArr[2]+$monthtargetsec[$key]->iproductactual:$monthtargetsec[$key]->iproductactual);

                          }
                          if(sizeof($premonthtargetsec)>0)
                          {


                          $prestoreArr[2] = (isset($prestoreArr[2])?$prestoreArr[2]+(isset($premonthtargetsec[$key]->iproductactual)?$premonthtargetsec[$key]->iproductactual:0):(isset($premonthtargetsec[$key]->iproductactual)?$premonthtargetsec[$key]->iproductactual:0));

                          }
                          if(sizeof($avgmonthtargetsec)>0)
                          {
                          $avgstoreArr[2] = (isset($avgstoreArr[2])?$avgstoreArr[2]+$avgmonthtargetsec[$key]->iproductactual:$avgmonthtargetsec[$key]->iproductactual);
                          }
                          if(sizeof($avgideliveryactual)>0)
                          {
                          $avgsdeltoreArr[2] = (isset($avgsdeltoreArr[2])?$avgsdeltoreArr[2]+$avgideliveryactual[$key]->ideliveryactual:$avgideliveryactual[$key]->ideliveryactual);
                          }
                          @endphp

                          <tr>
                            <td colspan="1" align="center">{{( isset($monthtargetsecdata) && $monthtargetsecdata->sub_group_name!='' ?$monthtargetsecdata->sub_group_name:'')}}</td>
                            <td colspan="1" align="center">{{( isset($monthtargetsecdata) && $monthtargetsecdata->uom!='' ?$monthtargetsecdata->uom:'')}}</td>
                            {{-- <td colspan="1" align="center">{{$section->vsection_name}}
                  </td> --}}
                  <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->iproducttarget,0)}} @else {{ '0' }}@endif</td>
                  <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->iproductactual,0)}} @else {{ '0' }}@endif</td>

                  <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format(($monthtargetsecdata->iproductactual/($monthtargetsecdata->iproducttarget?$monthtargetsecdata->iproducttarget:1))*100,0)}} @else {{ '0' }}@endif %</td>

                  <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->ideliveryactual,0)}} @else {{ '0' }}@endif</td>

                  <td colspan="1" align="center">@if(!empty($premonthtargetsec[$key])){{ number_format($premonthtargetsec[$key]->iproductactual,0)}} @else {{ '0' }}@endif</td>
                  <td colspan="1" align="center">@if(!empty($premonthtargetsec[$key])){{ number_format($premonthtargetsec[$key]->ideliveryactual,0)}} @else {{ '0' }}@endif</td>
                  <td colspan="1" align="center">@if(!empty($avgmonthtargetsec[$key])){{ number_format($avgmonthtargetsec[$key]->iproductactual/$request->month,0)}} @else {{ '0' }}@endif</td>
                  <td colspan="1" align="center">@if(!empty($avgideliveryactual[$key])){{ number_format($avgideliveryactual[$key]->ideliveryactual/$request->month,0)}} @else {{ '0' }}@endif</td>
                  <td colspan="1" align="center">{{number_format($section->istandardproduction,0)}}</td>
                  <td colspan="1" align="center">{{number_format($section->factoryamount,0)}}</td>

                </tr>

                @php
                $currentMonthTarget += (!empty($monthtargetsec[$key])?$monthtargetsecdata->iproducttarget:0);


                $currentMonthActual += (!empty($monthtargetsec[$key])?$monthtargetsecdata->iproductactual:0);

                $currentMonthDelivery += (!empty($monthtargetsec[$key])?$monthtargetsecdata->ideliveryactual:0);


                $preMonthActual += (!empty($premonthtargetsec[$key])?$premonthtargetsec[$key]->iproductactual:0);

                $preMonthDelivery += (!empty($premonthtargetsec[$key])?$premonthtargetsec[$key]->ideliveryactual:0);

                $pstandard += (isset($section->istandardproduction)?$section->istandardproduction:0);

                $factoryamount += (isset($section->factoryamount)?$section->factoryamount:0);

                $pavg += (!empty($avgmonthtargetsec[$key])?$avgmonthtargetsec[$key]->iproductactual/$request->month:0);

                $avgelivery += (!empty($avgideliveryactual[$key])?$avgideliveryactual[$key]->ideliveryactual/$request->month:0);


                @endphp

                @endforeach
                @endforeach

                @php
                @endphp



                <tr style="background-color: #8d9788bf">
                  <td colspan="1" align="center">GENERAL HOURS</td>
                  <td colspan="1" align="center"></td>
                  <td colspan="1" align="center">{{number_format($GHcurrentMonthTarget = $currentMonthTarget,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHcurrentMonthActual = $currentMonthActual,0)}}</td>
                  <td colspan="1" align="center">@if($currentMonthActual>0 && $currentMonthTarget>0){{ number_format($GHper = ($currentMonthActual/$currentMonthTarget)*100,0)}} @else 0 @endif %</td>
                  <td colspan="1" align="center">{{number_format($GHcurrentMonthDelivery = $currentMonthDelivery,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHpreMonthActual = $preMonthActual,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHpreMonthDelivery = $preMonthDelivery,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHpavg = $pavg,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHavgelivery = $avgelivery,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHpstandard = $pstandard,0)}}</td>
                  <td colspan="1" align="center">{{number_format($GHfactoryamount = $factoryamount,0)}}</td>

                </tr>
                @endif

                @php
                $con='';
                $currentMonthTarget = 0;
                $currentMonthActual = 0;
                $currentMonthDelivery = 0;
                $preMonthActual = 0;
                $preMonthDelivery = 0;
                $pstandard = 0;
                $factoryamount = 0;
                $avgelivery = 0;
                $pavg = 0;
                $monthtargetsecOT_iproductactual = 0;
                $premonthtargetsecOT_iproductactual = 0;
                $avgmonthtargetsecOT_iproductactual = 0;



                if($request->week!='')
                {
                $con=$con ." and t.iweek='".$request->week."'";
                }

                @endphp

                @if(sizeof($productionSectionOT)>0)

                @foreach($productionSectionOT as $key => $section)

                @php


                $monthtargetsec= DB::select("select t.iproduct,
                t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                from tbl_production_target t
                inner join tbl_production_section sname on sname.iid = t.isection
                left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$section->standard_year."' and t.imonth='".$request->month."'
                and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");
                // dd($monthtargetsec);



                $premonthtargetsec= DB::select("select t.iproduct,
                t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                from tbl_production_target t
                inner join tbl_production_section sname on sname.iid = t.isection
                left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$previousYear."' and t.imonth='".$previousMonth."'
                and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");




                $avgmonthtargetsec= DB::select("select t.iproduct,
                t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                from tbl_production_target t
                inner join tbl_production_section sname on sname.iid = t.isection
                left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$request->year."' and t.imonth<='".$request->month."' and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");

                  $avgideliveryactual= DB::select("select t.iproduct,
                  t.isection,sum(t.iproducttarget) as iproducttarget,sum(t.iproductactual) as iproductactual,sum(t.ideliveryfc) as ideliveryfc,
                  sum(t.ideliveryactual) as ideliveryactual, sub_group.name as sub_group_name, sub_group.uom as uom
                  from tbl_production_target t
                  inner join tbl_production_section sname on sname.iid = t.isection
                  left join tbl_production_product_sub_group as sub_group on sub_group.id=t.isubproduct
                  where t.iactive ='0' and t.iproduct='".$request->products."' and t.iyear='".$section->standard_year."' and t.imonth<='".$request->month."'
                    and t.isection='".$section->isection_id."' $con group by t.isection, t.iproduct, sub_group_name,uom");

                    if(sizeof($monthtargetsec)==0){
                    $pstandard += (isset($section->istandardproduction)?$section->istandardproduction:0);

                    $factoryamount += (isset($section->factoryamount)?$section->factoryamount:0);
                    }

                    @endphp
                    @foreach($monthtargetsec as $key => $monthtargetsecdata)
                    @php



                    if(sizeof($monthtargetsec)>0)
                    {
                    $storeArr[1] = (isset($storeArr[1])?$storeArr[1]+$monthtargetsec[$key]->iproductactual:$monthtargetsec[$key]->iproductactual);

                    }
                    if(sizeof($premonthtargetsec)>0)
                    {


                    $prestoreArr[1] = (isset($prestoreArr[1])?$prestoreArr[1]+(isset($premonthtargetsec[$key]->iproductactual)?$premonthtargetsec[$key]->iproductactual:0):(isset($premonthtargetsec[$key]->iproductactual)?$premonthtargetsec[$key]->iproductactual:0));

                    }
                    if(sizeof($avgmonthtargetsec)>0)
                    {
                    $avgstoreArr[1] = (isset($avgstoreArr[1])?$avgstoreArr[1]+$avgmonthtargetsec[$key]->iproductactual:$avgmonthtargetsec[$key]->iproductactual);

                    }
                    if(sizeof($avgideliveryactual)>0)
                    {
                    $avgsdeltoreArr[2] = (isset($avgsdeltoreArr[2])?$avgsdeltoreArr[2]+$avgideliveryactual[$key]->ideliveryactual:$avgideliveryactual[$key]->ideliveryactual);
                    }
                    @endphp

                    <tr>
                      <td colspan="1" align="center">{{( isset($monthtargetsecdata) && $monthtargetsecdata->sub_group_name!='' ?$monthtargetsecdata->sub_group_name:'')}}</td>
                      <td colspan="1" align="center">{{( isset($monthtargetsecdata) && $monthtargetsecdata->uom!='' ?$monthtargetsecdata->uom:'')}}</td>
                      {{-- <td colspan="1" align="center">{{$section->vsection_name}}</td> --}}
                      <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->iproducttarget,0)}} @else {{ '0' }}@endif</td>
                      <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->iproductactual,0)}} @else {{ '0' }}@endif</td>

                      <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format(($monthtargetsecdata->iproductactual/($monthtargetsecdata->iproducttarget?$monthtargetsecdata->iproducttarget:1))*100,0)}} @else {{ '0' }}@endif %</td>

                      <td colspan="1" align="center">@if(isset($monthtargetsec[$key])){{ number_format($monthtargetsecdata->ideliveryactual,0)}} @else {{ '0' }}@endif</td>

                      <td colspan="1" align="center">@if(!empty($premonthtargetsec[$key])){{ number_format($premonthtargetsec[$key]->iproductactual,0)}} @else {{ '0' }}@endif</td>
                      <td colspan="1" align="center">@if(!empty($premonthtargetsec[$key])){{ number_format($premonthtargetsec[$key]->ideliveryactual,0)}} @else {{ '0' }}@endif</td>
                      <td colspan="1" align="center">@if(!empty($avgmonthtargetsec[$key])){{ number_format($avgmonthtargetsec[$key]->iproductactual/$request->month,0)}} @else {{ '0' }}@endif</td>
                      <td colspan="1" align="center">@if(!empty($avgideliveryactual[$key])){{ number_format($avgideliveryactual[$key]->ideliveryactual/$request->month,0)}} @else {{ '0' }}@endif</td>

                      <td colspan="1" align="center">{{number_format($section->istandardproduction,0)}}</td>
                      <td colspan="1" align="center">{{number_format($section->factoryamount,0)}}</td>



                    </tr>

                    @php
                    $currentMonthTarget += (!empty($monthtargetsec[$key])?$monthtargetsecdata->iproducttarget:0);


                    $currentMonthActual += (!empty($monthtargetsec[$key])?$monthtargetsecdata->iproductactual:0);

                    $currentMonthDelivery += (!empty($monthtargetsec[$key])?$monthtargetsecdata->ideliveryactual:0);


                    $preMonthActual += (!empty($premonthtargetsec[$key])?$premonthtargetsec[$key]->iproductactual:0);

                    $preMonthDelivery += (!empty($premonthtargetsec[$key])?$premonthtargetsec[$key]->ideliveryactual:0);

                    $pstandard += (isset($section->istandardproduction)?$section->istandardproduction:0);

                    $factoryamount += (isset($section->factoryamount)?$section->factoryamount:0);

                    $pavg += (!empty($avgmonthtargetsec[$key])?$avgmonthtargetsec[$key]->iproductactual/$request->month:0);
                    $avgelivery += (!empty($avgideliveryactual[$key])?$avgideliveryactual[$key]->ideliveryactual/$request->month:0);


                    @endphp

                    @endforeach

                    @endforeach

                    @php
                    // if(sizeof($monthtargetsec)>0)
                    // {
                    // $storeArr[1] = $monthtargetsecOT_iproductactual;
                    // $storeArr[2] = $monthtargetsecHO_iproductactual;
                    // }
                    // if(sizeof($premonthtargetsec)>0)
                    // {

                    // $prestoreArr[1] = $premonthtargetsecOT_iproductactual;
                    // $prestoreArr[2] = $premonthtargetsecHO_iproductactual;

                    // }
                    // if(sizeof($avgmonthtargetsec)>0)
                    // {
                    // $avgstoreArr[1] = $avgmonthtargetsecOT_iproductactual;
                    // $avgstoreArr[2] = $avgmonthtargetsecHO_iproductactual;

                    // }
                    // print_r($storeArr);
                    @endphp


                    @endif

                    <tr style="background-color: #8d9788bf">
                      <td colspan="1" align="center">OT HOURS</td>
                      <td colspan="1" align="center"></td>
                      <td colspan="1" align="center">{{number_format($currentMonthTarget,0)}}</td>
                      <td colspan="1" align="center">{{number_format($currentMonthActual,0)}}</td>
                      <td colspan="1" align="center">@if($currentMonthActual>0 && $currentMonthTarget>0){{ number_format($OTper = ($currentMonthActual/$currentMonthTarget)*100,0)}} @else 0 @endif %</td>
                      <td colspan="1" align="center">{{number_format($currentMonthDelivery,0)}}</td>
                      <td colspan="1" align="center">{{number_format($preMonthActual,0)}}</td>
                      <td colspan="1" align="center">{{number_format($preMonthDelivery,0)}}</td>
                      <td colspan="1" align="center">{{number_format($pavg,0)}}</td>
                      <td colspan="1" align="center">{{number_format($avgelivery,0)}}</td>
                      <td colspan="1" align="center">{{number_format($pstandard,0)}}</td>
                      <td colspan="1" align="center">{{number_format($factoryamount,0)}}</td>

                    </tr>


                    <tr style="background-color: #7c9370bf">
                      @php
                      $total_currentMonthActual = $currentMonthActual+$GHcurrentMonthActual;
                      $total_currentMonthTarget = $currentMonthTarget+$GHcurrentMonthTarget;
                      $t_t_pro = $currentMonthTarget+(int)$GHcurrentMonthTarget;
                      $t_a_pro = $currentMonthActual+(int)$GHcurrentMonthActual;
                      $t_d_pro = $currentMonthDelivery+(int)$GHcurrentMonthDelivery;
                      $t_pre_a_pro = $preMonthActual+(int)$GHpreMonthActual;
                      $t_pre_d_pro = $preMonthDelivery+(int)$GHpreMonthDelivery;
                      $t_avg_pro = $pavg+(int)$GHpavg;
                      $t_avg_del = $avgelivery+(int)$GHavgelivery;
                      $t_p_plan = $pstandard+(int)$GHpstandard;
                      $t_f_plan = $factoryamount+(int)$GHfactoryamount;

                      @endphp
                      <td colspan="1" align="center">Total</td>
                      <td colspan="1" align="center"></td>
                      <td colspan="1" align="center">{{number_format($t_t_pro,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_a_pro,0)}}</td>
                      <td colspan="1" align="center">@if($total_currentMonthActual>0 && $total_currentMonthTarget>0){{ number_format(($total_currentMonthActual/$total_currentMonthTarget)*100,0)}} @else 0 @endif %</td>

                      <td colspan="1" align="center">{{number_format($t_d_pro,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_pre_a_pro,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_pre_d_pro,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_avg_pro,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_avg_del,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_p_plan,0)}}</td>
                      <td colspan="1" align="center">{{number_format($t_f_plan,0)}}</td>

                    </tr>

                    <tr style="background-color: #a47b7bb5">

                      <td colspan="1" align="center">Capacity Utilization</td>
                      <td colspan="1" align="center"></td>
                      <td colspan="1" align="center">{{number_format(($t_t_pro/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>
                      <td colspan="1" align="center">{{number_format(($t_a_pro/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>
                      <td colspan="1" align="center"></td>
                      <td colspan="1" align="center">{{-- {{number_format(($t_d_pro/($t_f_plan?$t_f_plan:1))*100,0)}}% --}}</td>
                      <td colspan="1" align="center">{{number_format(($t_pre_a_pro/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>
                      <td colspan="1" align="center">{{-- {{number_format(($t_pre_d_pro/($t_f_plan?$t_f_plan:1))*100,0)}}% --}}</td>
                      <td colspan="1" align="center">{{number_format(($t_avg_pro/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>
                      <td colspan="1" align="center">{{-- {{number_format(($t_avg_del/($t_f_plan?$t_f_plan:1))*100,0)}}% --}}</td>
                      <td colspan="1" align="center">{{number_format(($t_p_plan/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>
                      <td colspan="1" align="center">{{number_format(($t_f_plan/($t_f_plan?$t_f_plan:1))*100,0)}}%</td>

                    </tr>

            </table>
            </td>
            </tr>
            <tr>
              <td colspan="11" align="center"><a href="#" class="btn btn-success" onclick="window.open('pro_feedback_type/{{$request->factory}}/{{$request->products}}/{{$request->year}}/{{$request->month}}/1','srcPic','channelmode=1,scrollbars=1,status=0,titlebar=0,toolbar=0,resizable=1,width=500,height=600')">Production Feedback</a></td>
            </tr>

            @endif

            @if(sizeof($wastageQuery)>0)
            <tr>
              <td>
                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped" width="100%">

                  <tr>
                    <td colspan="9" align="center">
                      <h4> Wastage Report : <?php echo $request->year; ?>-<?php echo $monthName = date('F', mktime(0, 0, 0, $request->month, 10)); ?> <?php if ($request->week != '') echo "(Week-" . $request->week . ")"; ?></h4>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="1" rowspan="2" style="vertical-align: middle;" align="center"><b>Raw Material</b></td>
                    <td rowspan="2" style="vertical-align: middle;" colspan="1" align="center"><b>Type</b></td>

                    <td colspan="1" rowspan="2" style="vertical-align: middle;" align="center"><b>{{-- Standard % --}}Average {{$previousYear}}</b></td>
                    <td colspan="3" align="center" bgcolor='#e6e61b'>{{-- Current month --}}{{date('M', mktime(0, 0, 0, $request->month, 10)).' - '.$request->year}}</td>
                    <td colspan="2" align="center" bgcolor='#faeeaa'>{{-- Previous month --}}{{date('M', mktime(0, 0, 0, $previousMonth, 10)).' - '.$previousYear}}</td>
                    <td rowspan="2" style="vertical-align: middle;" colspan="1" align="center"><b>Year's Average</b></td>
                  </tr>
                  <tr>


                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Actual %</b> </td>

                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Variance</b></td>
                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Variance %</b></td>
                    <td colspan="1" align="center" bgcolor='#FAEEAA'><b>Actual</b> </td>
                    <td colspan="1" align="center" bgcolor='#FAEEAA'><b>Variance %</b></td>

                  </tr>


                  <?php
                  $remarks_array = array();
                  ?>
                  @foreach($wastageQuery as $wasSql)

                  @php


                  $wastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth',$request->month)
                  ->where('iproduct',$request->products)
                  ->where('iactive',0)
                  ->where('w5blub',0);

                  $prewastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$previousYear)
                  ->where('imonth',$previousMonth)
                  ->where('iproduct',$request->products)
                  ->where('iactive',0)
                  ->where('w5blub',0);

                  $allfwastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth','<=',$request->month)
                    ->where('iproduct',$request->products)
                    ->where('iactive',0)
                    ->where('w5blub',0);

                    if($request->week!='')
                    {
                    $value = $wastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    $prevalue = $prewastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    $fullvalue = $allfwastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    }
                    else
                    {
                    $value = $wastageValue->avg('iactualwastage');
                    $prevalue = $prewastageValue->avg('iactualwastage');
                    $fullvalue = $allfwastageValue->avg('iactualwastage');
                    }

                    $wastageRemarks = DB::table('tbl_production_wastage')
                    ->select('vremarks')
                    ->where('isection',$wasSql->iid)
                    ->where('iyear',$request->year)
                    ->where('imonth',$request->month)
                    ->where('iproduct',$request->products)
                    ->where('iactive',0)
                    ->where('w5blub',0)
                    ->where('vremarks','!=', '');
                    if($request->week!=''){
                    $wastageRemarks->where('iweek',$request->week);
                    }
                    $allRemarks = $wastageRemarks->get();
                    foreach($allRemarks as $Remark){
                    $aa[0] = $Remark;
                    $aa[1] = $wasSql->vcategory;
                    array_push($remarks_array,$aa);
                    }

                    $a = $wasSql->iamount - $value;
                    $b = $wasSql->iamount - $prevalue;

                    @endphp

                    <tr>
                      <td>
                        <h5>{{$wasSql->vcategory}}</h5>
                      </td>
                      <td colspan="1" align="center">{{$wasSql->vvaluetype}}</td>

                      <td colspan="1" align="center">{{$wasSql->iamount}} %</td>
                      <td colspan="1" align="center">{{number_format($value,2)}} %</td>

                      <td colspan="1" align="center">{{number_format($wasSql->iamount - $value,3)}}</td>
                      <td colspan="1" align="center">{{number_format($a/$wasSql->iamount*100,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($prevalue,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($b/$wasSql->iamount*100,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($fullvalue,2)}}%</td>

                    </tr>

                    @endforeach
                    <tr>
                      <td colspan="9" style="text-align: center;">
                        Wastage Remarks
                      </td>
                    </tr>
                    <?php
                    foreach ($remarks_array as $key => $value) { ?>
                      <tr>
                        <td>
                          <?= $value[1]; ?>

                        </td>
                        <td colspan="8">
                          <?= $value[0]->vremarks; ?>
                        </td>
                      </tr>
                    <?php }
                    ?>



                </table>
              </td>
            </tr>
            <tr>

              <td colspan="11" align="center"><a href="#" class="btn btn-success" onclick="window.open('pro_feedback_type/{{$request->factory}}/{{$request->products}}/{{$request->year}}/{{$request->month}}/2','srcPic','channelmode=1,scrollbars=1,status=0,titlebar=0,toolbar=0,resizable=1,width=500,height=600')">Wastage Feedback</a></td>

            </tr>
            @endif

            @if(sizeof($wastageQuery)>0 and $request->products==18)
            <tr>
              <td>
                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped" width="100%">

                  <tr>
                    <td colspan="9" align="center">
                      <h4> Wastage Report (5 watt) : <?php echo $request->year; ?>-<?php echo $monthName = date('F', mktime(0, 0, 0, $request->month, 10)); ?> <?php if ($request->week != '') echo "(Week-" . $request->week . ")"; ?></h4>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="1" rowspan="2" style="vertical-align: middle;" align="center"><b>Raw Material</b></td>
                    <td rowspan="2" style="vertical-align: middle;" colspan="1" align="center"><b>Type</b></td>

                    <td colspan="1" rowspan="2" style="vertical-align: middle;" align="center"><b>{{-- Standard % --}}Average {{$previousYear}}</b></td>
                    <td colspan="3" align="center" bgcolor='#e6e61b'>{{-- Current month --}}{{date('M', mktime(0, 0, 0, $request->month, 10)).' - '.$request->year}}</td>
                    <td colspan="2" align="center" bgcolor='#faeeaa'>{{-- Previous month --}}{{date('M', mktime(0, 0, 0, $previousMonth, 10)).' - '.$previousYear}}</td>
                    <td rowspan="2" style="vertical-align: middle;" colspan="1" align="center"><b>Year's Average</b></td>
                  </tr>
                  <tr>


                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Actual %</b> </td>

                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Variance</b></td>
                    <td colspan="1" align="center" bgcolor='#f0e68c'><b>Variance %</b></td>
                    <td colspan="1" align="center" bgcolor='#FAEEAA'><b>Actual</b> </td>
                    <td colspan="1" align="center" bgcolor='#FAEEAA'><b>Variance %</b></td>

                  </tr>


                  <?php
                  $remarks_array = array();
                  ?>
                  @foreach($wastageQuery as $wasSql)

                  @php


                  $wastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth',$request->month)
                  ->where('iproduct',$request->products)
                  ->where('iactive',0)
                  ->where('w5blub',1);

                  $prewastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$previousYear)
                  ->where('imonth',$previousMonth)
                  ->where('iproduct',$request->products)
                  ->where('iactive',0)
                  ->where('w5blub',1);

                  $allfwastageValue = DB::table('tbl_production_wastage')
                  ->where('isection',$wasSql->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth','<=',$request->month)
                    ->where('iproduct',$request->products)
                    ->where('iactive',0)
                    ->where('w5blub',1);

                    if($request->week!='')
                    {
                    $value = $wastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    $prevalue = $prewastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    $fullvalue = $allfwastageValue->where('iweek',$request->week)->sum('iactualwastage');
                    }
                    else
                    {
                    $value = $wastageValue->avg('iactualwastage');
                    $prevalue = $prewastageValue->avg('iactualwastage');
                    $fullvalue = $allfwastageValue->avg('iactualwastage');
                    }

                    $wastageRemarks = DB::table('tbl_production_wastage')
                    ->select('vremarks')
                    ->where('isection',$wasSql->iid)
                    ->where('iyear',$request->year)
                    ->where('imonth',$request->month)
                    ->where('iproduct',$request->products)
                    ->where('iactive',0)
                    ->where('w5blub',1)
                    ->where('vremarks','!=', '');
                    if($request->week!=''){
                    $wastageRemarks->where('iweek',$request->week);
                    }
                    $allRemarks = $wastageRemarks->get();
                    foreach($allRemarks as $Remark){
                    $aa[0] = $Remark;
                    $aa[1] = $wasSql->vcategory;
                    array_push($remarks_array,$aa);
                    }

                    $a = $wasSql->iamount - $value;
                    $b = $wasSql->iamount - $prevalue;

                    @endphp

                    <tr>
                      <td>
                        <h5>{{$wasSql->vcategory}}</h5>
                      </td>
                      <td colspan="1" align="center">{{$wasSql->vvaluetype}}</td>

                      <td colspan="1" align="center">{{$wasSql->iamount}} %</td>
                      <td colspan="1" align="center">{{number_format($value,2)}} %</td>

                      <td colspan="1" align="center">{{number_format($wasSql->iamount - $value,3)}}</td>
                      <td colspan="1" align="center">{{number_format($a/$wasSql->iamount*100,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($prevalue,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($b/$wasSql->iamount*100,2)}}%</td>
                      <td colspan="1" align="center">{{number_format($fullvalue,2)}}%</td>

                    </tr>

                    @endforeach
                    <tr>
                      <td colspan="9" style="text-align: center;">
                        Wastage Remarks
                      </td>
                    </tr>
                    <?php
                    foreach ($remarks_array as $key => $value) { ?>
                      <tr>
                        <td>
                          <?= $value[1]; ?>

                        </td>
                        <td colspan="8">
                          <?= $value[0]->vremarks; ?>
                        </td>
                      </tr>
                    <?php }
                    ?>



                </table>
              </td>
            </tr>
            <tr>

              <td colspan="11" align="center"><a href="#" class="btn btn-success" onclick="window.open('pro_feedback_type/{{$request->factory}}/{{$request->products}}/{{$request->year}}/{{$request->month}}/2','srcPic','channelmode=1,scrollbars=1,status=0,titlebar=0,toolbar=0,resizable=1,width=500,height=600')">Wastage Feedback</a></td>

            </tr>
            @endif

            @if(sizeof($details)>0 || sizeof($empquery)>0)
            <tr>
              <td>
                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped" width="100%">

                  <tr>
                    <td colspan="9" align="center">
                      <h4> Details Report : <?php echo $request->year; ?>-<?php echo $monthName = date('F', mktime(0, 0, 0, $request->month, 10)); ?> <?php if ($request->week != '') echo "(Week-" . $request->week . ")"; ?></h4>
                    </td>
                  </tr>



                  @foreach($details as $detRow)

                  <tr>
                    <td>
                      <h5>{{$detRow->vcategory}}</h5>
                    </td>
                    <td colspan="8" style="text-align: left;">{!! strip_tags(str_replace('\r\n','',$detRow->tdetails)) !!}</td>

                  </tr>

                  @endforeach

                  @foreach($empquery as $empRow)

                  <tr>
                    <td colspan="1">Employee Turnover</td>
                    <td colspan="8">{{ number_format($empRow->resign/(($empRow->begining + $empRow->ending)/2)*100,2) }} %</td>
                  </tr>

                  @endforeach

                </table>
              </td>
            </tr>

            @endif
            @if(sizeof($costArea)>0 || sizeof($costAreaOt)>0)
            <tr>
              <td>
                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped" width="100%">

                  <tr>
                    <td colspan="10" align="center">
                      <h4> COST ANALYSIS : <?php echo $request->year; ?>-<?php echo $monthName = date('F', mktime(0, 0, 0, $request->month, 10)); ?> <?php if ($request->week != '') echo "(Week-" . $request->week . ")"; ?></h4>
                    </td>
                  </tr>

                  <tr>
                    <td align="center"><b>GENERAL EXPENSE HEAD</b></td>
                    <td align="center" title="CURRENT MONTH"><b>AMOUNT[BDT]</b></br>({{date('M', mktime(0, 0, 0, $request->month, 10)).' - '.$request->year}}) </td>
                    <td align="center"><b>PER UNIT</b></br>({{date('M', mktime(0, 0, 0, $request->month, 10)).' - '.$request->year}})</td>
                    <td align="center" title="PREVIOUS MONTH"><b>AMOUNT[BDT]</b></br>({{date('M', mktime(0, 0, 0, $previousMonth, 10)).' - '.$previousYear}})</td>
                    <td align="center" title="PREVIOUS MONTH"><b>PER UNIT</b></br>({{date('M', mktime(0, 0, 0, $previousMonth, 10)).' - '.$previousYear}})</td>
                    <td align="center"><b>AVERAGE COST <?php $preYearbanch = $request->year; ?></b><br>Jan - <?php echo date('M', mktime(0, 0, 0, $request->month, 10)) ?></td>
                    <td align="center"><b>AVERAGE</b></br>PER UNIT</b></td>
                    <td align="center"><b>AMOUNT</b></br>(Standard)</td>
                    <td align="right"><b>PER&nbsp;UNIT </b></br>(Standard)</td>
                    <td align="right"><b>Status</td>
                  </tr>

                  @php

                  $gtotalamount =0;
                  $gtotalunit =0;
                  $gtotalpreamount =0;
                  $gtotalpreunit =0;
                  $gtotaltavg =0;
                  $gtotalavgunit =0;
                  $gtotalstandard =0;
                  $gtotalstandardunit =0;


                  @endphp

                  @foreach($costArea as $costRow)
                  @php
                  $totalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costRow->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth',$request->month)
                  ->where('iproduct',$request->products)
                  ->first();

                  $pretotalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costRow->iid)
                  ->where('iyear',$previousYear)
                  ->where('imonth',$previousMonth)
                  ->where('iproduct',$request->products)
                  ->first();

                  $avgtotalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costRow->iid)
                  ->where('iyear',$previousYear)
                  ->where('iproduct',$request->products)
                  ->first();

                  if(isset($storeArr[$costRow->icosttype])){
                  $cus_data = $storeArr[$costRow->icosttype];
                  }else{
                  $cus_data = 0;
                  }

                  if(isset($prestoreArr[$costRow->icosttype])){
                  $pre_cus_data = $prestoreArr[$costRow->icosttype];
                  }else{
                  $pre_cus_data = 0;
                  }

                  if(isset($avgstoreArr[$costRow->icosttype])){
                  $avg_cus_data = $avgstoreArr[$costRow->icosttype];
                  }else{
                  $avg_cus_data = 0;
                  }


                  @endphp
                  <tr>
                    <td>
                      <h6>{{$costRow->vcategory}}</h6>
                    </td>
                    <td colspan="1" align="right">{{number_format($totalValue->total,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($cus_data==0){{'0.00'}}@else{{number_format($totalValue->total/$cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($pretotalValue->total,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($pre_cus_data==0){{'0.00'}}@else{{number_format($pretotalValue->total/$pre_cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($avgtotalValue->total/$request->month,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($avg_cus_data==0){{'0.00'}}@else{{number_format(($avgtotalValue->total)/$avg_cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($costRow->iamount,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">{{number_format($costRow->iperunit,2)}}</td>
                    <td colspan="1" align="right"></td>

                  </tr>

                  @php

                  $gtotalamount +=$totalValue->total;
                  $gtotalunit +=($cus_data==0?0:$totalValue->total/$cus_data);
                  $gtotalpreamount +=$pretotalValue->total;
                  $gtotalpreunit +=($pre_cus_data==0?0:$pretotalValue->total/$pre_cus_data);
                  $gtotaltavg +=$avgtotalValue->total/$request->month;
                  $gtotalavgunit +=($avg_cus_data==0?0:($avgtotalValue->total)/$avg_cus_data);
                  $gtotalstandard +=$costRow->iamount;
                  $gtotalstandardunit +=$costRow->iperunit;

                  @endphp

                  @endforeach

                  <tr>
                    <td>
                      <h5><b>GENERAL COST</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($gtotalamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($gtotalunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($gtotalpreamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($gtotalpreunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($gtotaltavg,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($gtotalavgunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($gtotalstandard,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($gtotalstandardunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right" style="color:#ffffff;" @if($gtotalstandardunit>=$gtotalunit) bgcolor='#3BC82A' @else bgcolor='#FF1818' @endif ><h5>@if($gtotalstandardunit>=$gtotalunit){{'Cost Down. '. '[' .number_format($gtotalstandardunit-$gtotalunit,2).']' .' TK'}} @else {{'Cost Up. '. '[' .number_format($gtotalunit-$gtotalstandardunit,2).']' .' TK'}} @endif</h5>
                    </td>

                  </tr>

                  <tr>
                    <td colspan="10"></td>
                  </tr>
                  <tr>
                    <td colspan="10" align="center"></td>

                  </tr>

                  @php

                  $ototalamount =0;
                  $ototalunit =0;
                  $ototalpreamount =0;
                  $ototalpreunit =0;
                  $ototaltavg =0;
                  $ototalavgunit =0;
                  $ototalstandard =0;
                  $ototalstandardunit =0;

                  @endphp

                  @foreach($costAreaOt as $costOtRow)
                  @php
                  $totalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costOtRow->iid)
                  ->where('iyear',$request->year)
                  ->where('imonth',$request->month)
                  ->where('iproduct',$request->products)
                  ->first();

                  $pretotalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costOtRow->iid)
                  ->where('iyear',$previousYear)
                  ->where('imonth',$previousMonth)
                  ->where('iproduct',$request->products)
                  ->first();

                  $avgtotalValue = DB::table('tbl_production_cost_cost')
                  ->select(DB::raw('SUM(icost) AS total'),DB::raw('SUM(iunit) AS unit'))
                  ->where('icat',$costOtRow->iid)
                  ->where('iyear',$previousYear)
                  ->where('iproduct',$request->products)
                  ->first();

                  if(isset($storeArr[$costOtRow->icosttype])){
                  $cus_data = $storeArr[$costOtRow->icosttype];

                  }else{
                  $cus_data = 0;
                  }

                  if(isset($prestoreArr[$costOtRow->icosttype])){
                  $pre_cus_data = $prestoreArr[$costOtRow->icosttype];
                  }else{
                  $pre_cus_data = 0;
                  }

                  if(isset($avgstoreArr[$costOtRow->icosttype])){
                  $avg_cus_data = $avgstoreArr[$costOtRow->icosttype];
                  }else{
                  $avg_cus_data = 0;
                  }



                  @endphp
                  <tr>
                    <td>
                      <h6>{{$costOtRow->vcategory}}</h6>
                    </td>
                    <td colspan="1" align="right">{{number_format($totalValue->total,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($cus_data==0){{'0.00'}}@else{{number_format($totalValue->total/$cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($pretotalValue->total,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($pre_cus_data==0){{'0.00'}}@else{{number_format($pretotalValue->total/$pre_cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($avgtotalValue->total/$request->month,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">@if($avg_cus_data==0){{'0.00'}}@else{{number_format(($avgtotalValue->total)/$avg_cus_data,2)}}@endif</td>
                    <td colspan="1" align="right">{{number_format($costOtRow->iamount,0)}}</td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">{{number_format($costOtRow->iperunit,2)}}</td>
                    <td colspan="1" align="right"></td>

                  </tr>

                  @php

                  $ototalamount +=$totalValue->total;
                  $ototalunit +=($cus_data==0?0:$totalValue->total/$cus_data);
                  $ototalpreamount +=$pretotalValue->total;
                  $ototalpreunit +=($pre_cus_data==0?0:$pretotalValue->total/$pre_cus_data);
                  $ototaltavg +=$avgtotalValue->total/$request->month;
                  $ototalavgunit +=($avg_cus_data==0?0:($avgtotalValue->total)/$avg_cus_data);
                  $ototalstandard +=$costOtRow->iamount;
                  $ototalstandardunit +=$costOtRow->iperunit;



                  @endphp

                  @endforeach



                  <tr>
                    <td>
                      <h5><b>OT COST</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($ototalunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalpreamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($ototalpreunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototaltavg,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($ototalavgunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalstandard,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #d4d3e7;" align="right">
                      <h5><b>{{number_format($ototalstandardunit,2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right" style="color:#ffffff;" @if($ototalstandardunit>=$ototalunit) bgcolor='#3BC82A' @else bgcolor='#FF1818' @endif ><h5>@if($ototalstandardunit>=$ototalunit){{'Cost Down. '. '[' .number_format($ototalstandardunit-$ototalunit,2).']' .' TK'}} @else {{'Cost Up. '. '[' .number_format($ototalunit-$ototalstandardunit,2).']' .' TK'}} @endif</h5>
                    </td>

                  </tr>
                  <tr>
                    <td colspan="10" height="20">&nbsp;</td>
                  </tr>
                  @php


                  $currentMonthActualmain = ($currentMonthActual?$currentMonthActual:0)+($GHcurrentMonthActual?$GHcurrentMonthActual:0);
                  $preMonthActualmain = ($preMonthActual?$preMonthActual:0)+($GHpreMonthActual?$GHpreMonthActual:0);
                  $pavgmain = ($pavg?$pavg:0)+($GHpavg?$GHpavg:0);

                  $pstandardmain = ($pstandard?$pstandard:0)+($GHpstandard?$GHpstandard:0);
                  $totalamount = $ototalamount + $gtotalamount;
                  $pretotalamount = $ototalpreamount + $gtotalpreamount;
                  $totalstandard = $ototalstandard + $gtotalstandard;
                  $avgtotal = $ototaltavg + $gtotaltavg;

                  $compairstandard = ($pstandardmain==0?0:$totalstandard/$pstandardmain);
                  $compairactual = $totalamount/($currentMonthActualmain?$currentMonthActualmain:1);

                  @endphp
                  <tr>
                    <td>
                      <h5><b>TOTAL PRODUCTION COST</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalamount + $gtotalamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #dddcb8;" align="right">
                      <h5><b>{{number_format($totalamount/($currentMonthActualmain?$currentMonthActualmain:1),2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalpreamount + $gtotalpreamount,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #dddcb8;" align="right">
                      <h5><b>{{number_format($pretotalamount/($preMonthActualmain?$preMonthActualmain:1),2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototaltavg + $gtotaltavg,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #dddcb8;" align="right">
                      <h5><b>{{number_format($avgtotal/($pavgmain?$pavgmain:1),2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right">
                      <h5><b>{{number_format($ototalstandard + $gtotalstandard,0)}}</b></h5>
                    </td>
                    <td colspan="1" style="background-color: #dddcb8;" align="right">
                      <h5><b>{{number_format(($pstandardmain==0?0:($totalstandard/($pstandardmain?$pstandardmain:1))),2)}}</b></h5>
                    </td>
                    <td colspan="1" align="right" style="color:#ffffff;" @if($compairstandard>=$compairactual) bgcolor='#3BC82A' @else bgcolor='#FF1818' @endif ><h5>@if($compairstandard>=$compairactual){{'Cost Down. '. '[' .number_format($compairstandard-$compairactual,2).']' .' TK'}} @else {{'Cost Up. '. '[' .number_format($compairactual-$compairstandard,2).']' .' TK'}} @endif</h5>
                    </td>

                  </tr>
                </table>
              </td>
            </tr>
            @endif
            </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>


</div>