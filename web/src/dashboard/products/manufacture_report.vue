<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-sm-9">
                <div class="breadcrumb-wrapper col-9">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active"> Production Report
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">

          <!-- NEW REPORT -->
          <section id="basic-datatable">
            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Factory</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="summaryList($event)" v-model="filterForm.factory_id"
                            id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in itemsFactorys" :key="row.id" :value="row.id">
                        {{ row.dis_name }}
                      </option>
                    </select>

                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Product group</label>
                  <fieldset class="form-group">
                    <select required="" class="form-control" v-model="filterForm.summary_group_id">
                      <option value="">Select Product group</option>
                      <option v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id">{{
                          row.description
                        }}
                      </option>

                    </select>

                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label">From</label>
                  <fieldset class="form-group">
                    <datepicker v-model="filterForm.start_date" name="start_date" class="form-control"
                                @closed="datepickerClosedFunction"></datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">To </label>
                  <fieldset class="form-group">
                    <datepicker v-model="filterForm.end_date" name="end_date" class="form-control"
                                 @closed="previous_to_date"></datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label">From (Previous)</label>
                  <fieldset class="form-group">
                    <datepicker v-model="filterForm.start_date_previous" name="start_date_previous"
                                class="form-control">
                    </datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label">To (Previous)</label>
                  <fieldset class="form-group">
                    <datepicker v-model="filterForm.end_date_previous" name="end_date_previous"
                                class="form-control"></datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-1_5">
                  <fieldset class="form-group">

                    <!-- GET NEW REPORT -->
                    <button type="submit" @click="finter(1)" class="btn btn-primary mb-2 float-left">Submit</button>

                    <!-- GET OLD REPORT -->
                    <button type="submit" @click="getOldReport()" class="btn btn-primary mb-2 float-right">Old Report</button>
                  </fieldset>

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">

                      <!-- OLD REPORT LINK -->
<!--                      <div class="btn-group mb-1" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                          <router-link target="_blank" class="text-white" :to="{ path: '/rpt_view_all' }"><i
                              class="bx bx-add-alt"></i> Product wise report( OLD )
                          </router-link>
                        </button>
                      </div>-->

                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead class="thead-dark">
                          <tr>
                            <td>
                              <table id="dataTable"
                                     class="table table-bordered table-condensed table-hover table-striped"
                                     width="100%">
                                <thead class="thead-dark">
                                <tr>
                                  <th colspan="14" class="text-center" style="font-size: 18px">
                                    Production analysis
                                  </th>
                                </tr>
                                <tr>
                                  <td colspan="1" rowspan="2">Description</td>
                                  <td colspan="4" class="text-center">{{ filterForm.start_date | formatDate }} -
                                    {{ filterForm.end_date | formatDate }}
                                  </td>
                                  <td colspan="4" class="text-center"> {{ filterForm.start_date_previous | formatDate }}
                                    - {{ filterForm.end_date_previous | formatDate }}
                                  </td>
                                  <td colspan="1" rowspan="2"> AVG. Production <br>Jan -
                                    {{ filterForm.end_date | formatMonth }}
                                  </td>
                                  <td colspan="1" rowspan="2"> AVG. Delivery <br>Jan -
                                    {{ filterForm.end_date | formatMonth }}
                                  </td>
                                  <td colspan="1" rowspan="2"> Production Plan (as per sales projection)</td>
                                  <td colspan="1" rowspan="2"> Factory Capacity</td>
                                </tr>
                                <tr>
                                  <td colspan="1"> T.Production</td>
                                  <td colspan="1"> A.Production</td>
                                  <td colspan="1"> Comparison</td>
                                  <td colspan="1"> A.Delivery</td>
                                  <td colspan="1"> T.Production</td>
                                  <td colspan="1"> A.Production</td>
                                  <td colspan="1"> Comparison</td>
                                  <td colspan="1"> A.Delivery</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                  <td colspan="14" class="text-center"> General Hour</td>
                                </tr>
                                <tr v-for="(item , index ) in deliveris" :key="index">
                                  <td>
                                    <router-link target="_blank"
                                                 :to="{ path: '/manufacturer_search/'+filterForm.factory_id+'/'+filterForm.summary_group_id+'/'+filterForm.start_date+'/'+filterForm.end_date }">
                                      {{ item.code }}
                                    </router-link>
                                  </td>
                                  <!-- current -->
                                  <td class="current text-right">{{ formatPrice(item.gnh_production_target) }}</td>
                                  <td class="current text-right">{{ formatPrice(item.gnh_actual_production) }}</td>
                                  <td class="current text-right">{{
                                      Number(item.gnh_production_target > 0 ? (item.gnh_actual_production / item.gnh_production_target) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="current text-right">{{ formatPrice(item.gnh_actual_delivery) }}</td>
                                  <!-- Previous -->
                                  <td class="previous text-right">{{
                                      formatPrice(item.gnh_production_target_previous)
                                    }}
                                  </td>
                                  <td class="previous text-right">{{
                                      formatPrice(item.gnh_actual_production_previous)
                                    }}
                                  </td>
                                  <td class="previous text-right">{{
                                      Number(item.gnh_production_target_previous > 0 ? (item.gnh_actual_production_previous / item.gnh_production_target_previous) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="previous text-right">{{
                                      formatPrice(item.gnh_actual_delivery_previous)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(item.gnh_actual_production_avg) }}</td>
                                  <td class="text-right">{{ formatPrice(item.gnh_actual_delivery_avg) }}</td>
                                  <td class="text-right">{{
                                      formatPrice(Number(item.gnh_production_plan).toFixed(2))
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(Number(item.gnh_capacity).toFixed(2)) }}</td>
                                </tr>
                                <tr>
                                  <td colspan="14" class="text-center"> OT Hour</td>
                                </tr>
                                <tr v-for="(item , index ) in deliveris" :key="'a'+index">
                                  <td>
                                    <router-link target="_blank"
                                                 :to="{ path: '/manufacturer_search/'+filterForm.factory_id+'/'+filterForm.summary_group_id+'/'+filterForm.start_date+'/'+filterForm.end_date }">
                                      {{ item.code }}
                                    </router-link>
                                  </td>
                                  <!-- current -->
                                  <td class="current text-right">{{ formatPrice(item.oth_production_target) }}</td>
                                  <td class="current text-right">{{ formatPrice(item.oth_actual_production) }}</td>
                                  <td class="current text-right">{{
                                      Number(item.oth_production_target > 0 ? (item.oth_actual_production / item.oth_production_target) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="current text-right">--</td>
                                  <!-- Previous -->
                                  <td class="previous text-right">{{
                                      formatPrice(item.oth_production_target_previous)
                                    }}
                                  </td>
                                  <td class="previous text-right">{{
                                      formatPrice(item.oth_actual_production_previous)
                                    }}
                                  </td>
                                  <!-- <td class="previous text-right">{{ Number(item.oth_production_target_previous > 0 ? (item.oth_actual_production_previous / item.oth_production_target_previous ) * item.oth_production_target_previous : 0).toFixed(2) }}%</td> -->
                                  <td class="previous text-right">{{
                                      Number(item.oth_production_target_previous > 0 ? (item.oth_actual_production_previous / item.oth_production_target_previous) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="previous text-right">--</td>
                                  <td class="text-right">{{ formatPrice(item.oth_actual_production_avg) }}</td>
                                  <td class="text-right">-</td>
                                  <td class="text-right">{{
                                      formatPrice(Number(item.oth_production_plan).toFixed(2))
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(Number(item.oth_capacity).toFixed(2)) }}</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr v-if="deliveris.length > 0" class="sub_total">
                                  <td>Total</td>
                                  <!-- current -->
                                  <td class="text-right">{{ formatPrice(total_production_target) }}</td>
                                  <td class="text-right">{{ formatPrice(total_actual_production) }}</td>
                                  <td class="text-right"></td>
                                  <td class="text-right">{{ formatPrice(total_actual_delivery) }}</td>
                                  <!-- previous -->
                                  <td class="text-right">{{ formatPrice(total_production_target_previous) }}</td>
                                  <td class="text-right">{{ formatPrice(total_actual_production_previous) }}</td>
                                  <td class="text-right"></td>
                                  <td class="text-right">{{ formatPrice(total_actual_delivery_previous) }}</td>
                                  <td class="text-right">{{ formatPrice(total_actual_production_avg) }}</td>
                                  <td class="text-right">{{ formatPrice(total_actual_delivery_avg) }}</td>
                                  <td class="text-right">{{ formatPrice(total_production_plan) }}</td>
                                  <td class="text-right">{{ formatPrice(total_capacity) }}</td>
                                </tr>
                                <tr v-if="deliveris.length > 0" class="total">
                                  <td>Capacity Utilization</td>
                                  <!-- current -->
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_production_target / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_actual_production / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right"></td>
                                  <td class="text-right"></td>
                                  <!-- previous -->
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_production_target_previous / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_actual_production_previous / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right"></td>
                                  <td class="text-right"></td>
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_actual_production_avg / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right"></td>
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_production_plan / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <td class="text-right">{{
                                      Number(total_capacity ? (total_capacity / total_capacity) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                </tr>
                                <tr v-if="deliveris.length > 0"  class="text-center">
                                  <td colspan="13" class="text-center">

                                    <!-- NEW COMMENT -->
                                    <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center text-center" style="margin-right: 90px;">
                                      <a class="text-white text-center text-bold-900" style="color: #efefef;"
                                         @click="show_pop('production')"> <i class="bx bx-add-alt"></i><strong>Remarks</strong></a>
                                    </button>
                                  </td>
                                </tr>
                                </tfoot>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table id="dataTable"
                                     class="table table-bordered table-condensed table-hover table-striped"
                                     width="100%">
                                <tbody class="thead-dark">
                                <tr>
                                  <th class="text-center" colspan="11" style="font-size: 18px;">
                                    Wastage Report
                                  </th>
                                </tr>
                                <tr>
                                  <td colspan="1" rowspan="2"><b>Raw Material </b></td>
                                  <td rowspan="2" colspan="1"><b>UoM</b></td>
                                  <td colspan="1" rowspan="2"><b>Standard<br>wastage %</b></td>
                                  <td colspan="4" class="current text-center">{{ filterForm.start_date | formatDate }} -
                                    {{ filterForm.end_date | formatDate }}
                                  </td>
                                  <td colspan="3" class="previous text-center">
                                    {{ filterForm.start_date_previous | formatDate }} -
                                    {{ filterForm.end_date_previous | formatDate }}
                                  </td>
                                  <td rowspan="2" colspan="1"><b>Year's Average <br> ({{ new Date().getFullYear() }})</b></td>
                                </tr>
                                <tr>
                                  <!-- current  -->
                                  <td colspan="1" class="current"><b>Wastage(Qty)</b></td>
                                  <td colspan="1" class="current"><b>Consumption(Qty)</b></td>
                                  <td colspan="1" class="current"><b>Actual %</b></td>
                                  <td colspan="1" class="current"><b>Variance %</b></td>
                                  <!-- previous  -->
                                  <td colspan="1" class="previous"><b>Wastage(Qty)</b></td>
                                  <td colspan="1" class="previous"><b>Consumption(Qty)</b></td>
                                  <td colspan="1" class="previous"><b>Actual %</b></td>
                                </tr>
                                <tr v-for="(item , index ) in wastage" :key="index">
                                  <td>{{ item.group_name }}  </td>
                                  <td class="text-center">{{ item.uom }}</td>
                                  <td class="text-right">{{ Number(item.previous_year_average).toFixed(2) }}%</td>
                                  <td class="current text-right">{{ formatPrice(item.actual_wastage) }}</td>
                                  <td class="current text-right">{{ formatPrice(item.consumtion) }}</td>
                                  <td class="current text-right">{{
                                      Number(item.consumtion ? (item.actual_wastage / item.consumtion) * 100 : 0).toFixed(2)
                                    }} %
                                  </td>
                                  <td class="text-right">{{
                                      Number(item.consumtion ? (item.actual_wastage / item.consumtion)* 100 - item.previous_year_average  : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <!-- previous  -->
                                  <td class="previous text-right">{{ formatPrice(item.actual_wastage_previous) }}</td>
                                  <td class="previous text-right">{{ formatPrice(item.consumtion_previous) }}</td>
                                  <td class="previous text-right">{{
                                      Number(item.consumtion_previous ? (item.actual_wastage_previous / item.consumtion_previous) * 100 : 0).toFixed(2)
                                    }} %
                                  </td>
                                  <td class=" text-right">{{ Number(item.current_year_average).toFixed(2) }}%</td>
                                </tr>
                                <tr v-if="wastage.length > 0"  class="text-center">
                                  <td colspan="11" class="text-center">

                                    <!-- NEW COMMENT -->
                                    <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center text-center" style="margin-right: 90px;">
                                      <a class="text-white text-white text-bold-900" style="color: #efefef;"
                                         @click="show_pop('wastage')"> <i class="bx bx-add-alt"></i><strong>Remarks</strong></a>
                                    </button>

                                  </td>
                                </tr>
                                </tbody>

                              </table>
                            </td>
                          </tr>


                          <tr v-if="filterForm.summary_group_id == 3">
                            <td>
                              <table id="dataTable"
                                     class="table table-bordered table-condensed table-hover table-striped"
                                     width="100%">
                                <thead>
                                <tr>
                                  <th class="text-center" colspan="11">
                                    Wastage Report (5 Watt)
                                    <i v-if="watt5_show == 1 " @click="watt5Show()" style="float: right ;"
                                       class="bx bx-chevron-up  mr-1 watt5_show"></i>
                                    <i v-if="watt5_show == 0 " @click="watt5Show()" style="float: right ;"
                                       class="bx bx-chevron-down  mr-1 watt5_show"></i>
                                  </th>
                                </tr>
                                </thead>
                                <tbody class="thead-dark" v-if="watt5_show == 1 ">

                                <tr>
                                  <td colspan="1" rowspan="2"><b>Raw Material</b></td>
                                  <td rowspan="2" colspan="1"><b>UoM</b></td>
                                  <td colspan="1" rowspan="2"><b>Standard<br>wastage %</b></td>
                                  <td colspan="4" class="current text-center">{{ filterForm.start_date | formatDate }} -
                                    {{ filterForm.end_date | formatDate }}
                                  </td>
                                  <td colspan="3" class="previous text-center">
                                    {{ filterForm.start_date_previous | formatDate }} -
                                    {{ filterForm.end_date_previous | formatDate }}
                                  </td>
                                  <td rowspan="2" colspan="1"><b>Year's Average <br> ({{ new Date().getFullYear() }})</b></td>
                                </tr>
                                <tr>
                                  <!-- current  -->
                                  <td colspan="1" class="current"><b>Wastage(Qty)</b></td>
                                  <td colspan="1" class="current"><b>Consumption(Qty)</b></td>
                                  <td colspan="1" class="current"><b>Actual %</b></td>
                                  <td colspan="1" class="current"><b>Variance %</b></td>
                                  <!-- previous  -->
                                  <td colspan="1" class="previous"><b>Wastage(Qty)</b></td>
                                  <td colspan="1" class="previous"><b>Consumption(Qty)</b></td>
                                  <td colspan="1" class="previous"><b>Actual %</b></td>
                                </tr>
                                <tr v-for="(item , index ) in watt5" :key="index">
                                  <td>{{ item.group_name }}</td>
                                  <td class="text-center">{{ item.uom }}</td>
                                  <td class="text-right">{{ Number(item.previous_year_average).toFixed(2) }}%</td>
                                  <td class="current text-right">{{ formatPrice(item.actual_wastage) }}</td>
                                  <td class="current text-right">{{ formatPrice(item.consumtion) }}</td>
                                  <td class="current text-right">{{
                                      Number(item.consumtion ? (item.actual_wastage / item.consumtion) * 100 : 0).toFixed(2)
                                    }} %
                                  </td>
                                  <td class="text-right">{{
                                      Number(item.consumtion ? item.previous_year_average - (item.actual_wastage / item.consumtion) * 100 : 0).toFixed(2)
                                    }}%
                                  </td>
                                  <!-- previous  -->
                                  <td class="previous text-right">{{ formatPrice(item.actual_wastage_previous) }}</td>
                                  <td class="previous text-right">{{ formatPrice(item.consumtion_previous) }}</td>
                                  <td class="previous text-right">{{
                                      Number(item.consumtion_previous ? (item.actual_wastage_previous / item.consumtion_previous) * 100 : 0).toFixed(2)
                                    }} %
                                  </td>
                                  <td class=" text-right">{{ Number(item.current_year_average).toFixed(2) }}%</td>
                                </tr>
                                <tr v-if="wastage.length > 0 && watt5_show == 1 " class="text-center">
                                  <td colspan="11" class="text-center">

                                    <!-- NEW COMMENT -->
                                    <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center text-center" style="margin-right: 90px;">
                                      <a class="text-white text-center text-bold-900" style="color: #efefef;"
                                         @click="show_pop('wastage')"> <i class="bx bx-add-alt"></i><strong>Remarks</strong></a>
                                    </button>
                                  </td>
                                </tr>
                                </tbody>

                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table id="dataTable"
                                     class="table table-bordered table-condensed table-hover table-striped">
                                <tbody class="thead-dark">
                                <tr class="text-center">
                                  <th colspan="10" style="font-size: 18px;">
                                    COST ANALYSIS
                                  </th>
                                </tr>
                                <tr>
                                  <td rowspan="2" class="text-center"><b> EXPENSE HEAD</b></td>
                                  <td colspan="2" class="text-current">{{ filterForm.start_date | formatDate }} -
                                    {{ filterForm.end_date | formatDate }}
                                  </td>
                                  <td colspan="2" class="previous text-center">
                                    {{ filterForm.start_date_previous | formatDate }} -
                                    {{ filterForm.end_date_previous | formatDate }}
                                  </td>
                                  <td colspan="2" class="text-center"> AVG. <br>Jan -
                                    {{ filterForm.end_date | formatMonth }}
                                  </td>
                                  <td rowspan="2" class="text-center"><b>AMOUNT</b><br>(Standard)</td>
                                  <td rowspan="2" class="text-right"><b>PER&nbsp;UNIT </b><br>(Standard)</td>
                                  <td rowspan="2" class="text-right"><b>Status</b></td>
                                </tr>
                                <tr>
                                  <td title="CURRENT MONTH" class="text-center"><b>AMOUNT[BDT]</b>
                                  </td>
                                  <td class="text-center"><b>PER UNIT</b>
                                  </td>
                                  <td class="previous text-center" title="PREVIOUS MONTH"><b>AMOUNT[BDT]</b>
                                  </td>
                                  <td class="previous text-center" title="PREVIOUS MONTH"><b>PER UNIT</b>
                                  </td>

                                  <td class="text-center"><b>AVG COST</b>
                                  </td>
                                  <td class="text-center"><b>AVG/UNIT</b>
                                  </td>
                                </tr>
                                <tr class="text-center">
                                  <td colspan="10">
                                    <h5>General Hour</h5>
                                  </td>
                                </tr>
                                <tr v-for="(item , index ) in cost_center_gnh" :key="'cost_gng'+index">
                                  <td>{{ item.gl_name }}</td>
                                  <td class="text-right">{{ formatPrice(item.cost) }}</td>
                                  <td class="text-right">{{
                                      (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="previous text-right">{{ formatPrice(item.cost_previous) }}</td>
                                  <td class="previous text-right">{{
                                      (total_actual_production_gnh_previous ? item.cost_previous / total_actual_production_gnh_previous : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right"> {{ formatPrice(item.average_cost ? item.average_cost : 0) }}
                                  </td>
                                  <td class="text-right">{{
                                      Number(total_gnh_actual_production_avg ? item.average_cost / total_gnh_actual_production_avg : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(item.standard_gnh) }}</td>
                                  <td class="text-right"> {{
                                      Number(total_production_plan_gnh ? item.standard_gnh / total_production_plan_gnh : 0).toFixed(2)
                                    }}
                                  </td>
                                  <!-- <td class="text-right"
                                      v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) > 0"
                                      bgcolor="#e1ffde" align="right"> Cost
                                    Down.[{{
                                      Number((total_production_plan_gnh ? item.standard_gnh / total_production_plan_gnh : 0) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0)).toFixed(2)
                                    }}]TK
                                  </td>
                                  <td class="text-right"
                                      v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) == 0"
                                      bgcolor="#e1ffde" align="right"> 00
                                  </td>
                                  <td class="text-right"
                                      v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) < 0"
                                      bgcolor="#ffd6d6" align="right"> Cost
                                    Up.[{{
                                      Number((total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0) - (total_production_plan_gnh ? item.standard_gnh / total_production_plan_gnh : 0)).toFixed(2)
                                    }}]TK
                                  </td> -->
                                  <td class="text-right"
                                    v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) > 0"
                                    bgcolor="#e1ffde" align="right"> 
                                    {{
                                      Number((total_production_plan_gnh ? item.standard_gnh / total_production_plan_gnh : 0) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right"
                                    v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) == 0"
                                    bgcolor="#e1ffde" align="right"> 00
                                  </td>
                                  <td class="text-right"
                                    v-if="((total_production_plan_gnh ?  item.standard_gnh / total_production_plan_gnh : 0 ) - (total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0 )) < 0"
                                    bgcolor="#ffd6d6" align="right"> 
                                    {{
                                      Number((total_actual_production_gnh ? item.cost / total_actual_production_gnh : 0) - (total_production_plan_gnh ? item.standard_gnh / total_production_plan_gnh : 0)).toFixed(2)
                                    }}
                                  </td>
                                </tr>

                                <tr class="sub_total summary_text_tr">
                                  <td>GNH Total</td>
                                  <td class="text-right">{{ formatPrice(total_gnh_cost) }}</td>
                                  <td class="text-right">
                                    {{ Number(gnh_total_per_unit(total_actual_production_gnh)).toFixed(2) }}
                                  </td>
                                  <td class="previous text-right">{{ formatPrice(total_gnh_cost_previous) }}</td>
                                  <td class="previous text-right">{{
                                      Number(gnh_total_per_unit_previous(total_actual_production_gnh_previous)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(total_gnh_average_cost) }}</td>
                                  <td class="text-right"> 
                                    {{
                                      Number(total_gnh_actual_production_avg ? (total_gnh_average_cost / total_gnh_actual_production_avg) : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(total_standard_gnh) }}</td>
                                  <td class="text-right">{{
                                      (total_production_plan_gnh ? total_standard_gnh / total_production_plan_gnh : 0).toFixed(2)
                                    }}
                                  </td>

                                  <!-- <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) > 0"
                                      bgcolor="#3BC82A85" align="right"> Cost Down.[{{
                                      Number((total_production_plan_gnh ? total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)).toFixed(2)
                                    }}]TK
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) < 0"
                                      bgcolor="#FF1818" align="right"> Cost Up.[{{
                                      Number(gnh_total_per_unit(total_actual_production_gnh) - (total_production_plan_gnh ? total_standard_gnh / total_production_plan_gnh : 0)).toFixed(2)
                                    }}]TK
                                  </td> -->

                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) > 0"
                                      bgcolor="#3BC82A85" align="right"> {{
                                      Number((total_production_plan_gnh ? total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_gnh ?  total_standard_gnh / total_production_plan_gnh : 0) - gnh_total_per_unit(total_actual_production_gnh)) < 0"
                                      bgcolor="#FF1818" align="right"> {{
                                      Number(gnh_total_per_unit(total_actual_production_gnh) - (total_production_plan_gnh ? total_standard_gnh / total_production_plan_gnh : 0)).toFixed(2)
                                    }}
                                  </td>                                  
                                </tr>
                                <tr class="text-center">
                                  <td colspan="10">
                                    <h5>OT Hour</h5>
                                  </td>
                                </tr>
                                <tr v-for="(item , index ) in cost_center_oth" :key="'cost_oth'+index">
                                  <td>{{ item.gl_name }}</td>
                                  <td class="text-right">{{ formatPrice(item.cost) }}</td>
                                  <td class="text-right">{{
                                      (total_actual_production_oth ? item.cost / total_actual_production_oth : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="previous text-right">{{ formatPrice(item.cost_previous) }}</td>
                                  <td class="previous text-right">{{
                                      Number(total_actual_production_oth_previous ? item.cost_previous / total_actual_production_oth_previous : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{
                                    formatPrice(item.average_cost ? item.average_cost : 0)
                                    }}
                                  </td>
                                  <td class="text-right">{{
                                      Number(total_oth_actual_production_avg ? item.average_cost / total_oth_actual_production_avg : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{
                                      formatPrice(item.standard_oth ? item.standard_oth : 0)
                                    }}
                                  </td>
                                  <td class="text-right"> {{
                                      Number(total_production_plan_oth ? item.standard_oth / total_production_plan_oth : 0).toFixed(2)
                                    }}
                                  </td>

                                  <!-- <td class="text-right"
                                      v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) > 0"
                                      bgcolor="#e1ffde" align="right"> Cost
                                    Down.[{{
                                      Number((total_production_plan_oth ? item.standard_oth / total_production_plan_oth : 0) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0)).toFixed(2)
                                    }}]TK
                                  </td>
                                  <td class="text-right"
                                      v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) == 0"
                                      bgcolor="#e1ffde" align="right"> 00
                                  </td>
                                  <td class="text-right"
                                      v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) < 0"
                                      bgcolor="#ffd6d6" align="right"> Cost
                                    Up.[{{
                                      Number((total_actual_production_oth ? item.cost / total_actual_production_oth : 0) - (total_production_plan_oth ? item.standard_oth / total_production_plan_oth : 0)).toFixed(2)
                                    }}]TK
                                  </td> -->
                                  <td class="text-right"
                                      v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) > 0"
                                      bgcolor="#e1ffde" align="right"> 
                                    {{
                                      Number((total_production_plan_oth ? item.standard_oth / total_production_plan_oth : 0) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right"
                                      v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) == 0"
                                      bgcolor="#e1ffde" align="right"> 00
                                  </td>
                                  <td class="text-right"
                                    v-if="((total_production_plan_oth ?  item.standard_oth  / total_production_plan_oth : 0 ) - (total_actual_production_oth ? item.cost / total_actual_production_oth : 0 )) < 0"
                                    bgcolor="#ffd6d6" align="right"> 
                                    {{
                                      Number((total_actual_production_oth ? item.cost / total_actual_production_oth : 0) - (total_production_plan_oth ? item.standard_oth / total_production_plan_oth : 0)).toFixed(2)
                                    }}
                                  </td>                                  
                                </tr>

                                <tr class="sub_total summary_text_tr">
                                  <td>OTH Total</td>
                                  <td class="text-right">{{ formatPrice(total_oth_cost) }}</td>
                                  <td class="text-right">
                                    {{ Number(oth_total_per_unit(total_actual_production_oth)).toFixed(2) }}
                                  </td>
                                  <td class="previous text-right">{{ formatPrice(total_oth_cost_previous) }}</td>
                                  <td class="previous text-right">{{
                                      Number(oth_total_per_unit_previous(total_actual_production_oth_previous)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(total_oth_average_cost) }}</td>
                                  <td class="text-right"> 
                                    {{
                                      Number(total_oth_actual_production_avg ? (total_oth_average_cost / total_oth_actual_production_avg) : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(total_standard_oth) }}</td>
                                  <td class="text-right">{{
                                      (total_production_plan_oth ? total_standard_oth / total_production_plan_oth : 0).toFixed(2)
                                    }}
                                  </td>
                                  <!-- <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) > 0"
                                      bgcolor="#3BC82A85" align="right"> Cost Down.[{{
                                      Number((total_production_plan_oth ? total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)).toFixed(2)
                                    }}]TK
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) < 0"
                                      bgcolor="#FF1818" align="right"> Cost Up.[{{
                                      Number(oth_total_per_unit(total_actual_production_oth) - (total_production_plan_oth ? total_standard_oth / total_production_plan_oth : 0)).toFixed(2)
                                    }}]TK
                                  </td> -->
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) > 0"
                                      bgcolor="#3BC82A85" align="right"> {{
                                      Number((total_production_plan_oth ? total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_production_plan_oth ?  total_standard_oth / total_production_plan_oth : 0) - oth_total_per_unit(total_actual_production_oth)) < 0"
                                      bgcolor="#FF1818" align="right"> {{
                                      Number(oth_total_per_unit(total_actual_production_oth) - (total_production_plan_oth ? total_standard_oth / total_production_plan_oth : 0)).toFixed(2)
                                    }}
                                  </td>
                                </tr>
                                <tr class="total summary_text_tr">
                                  <td>Grand Total(GNH and OTH )</td>
                                  <td class="text-right">{{ formatPrice(total_gnh_cost + total_oth_cost) }}</td>
                                  <td class="text-right">
                                    {{ Number((total_gnh_cost + total_oth_cost) / total_actual_production).toFixed(2) }}
                                  </td>
                                  <td class="previous text-right ">
                                    {{ formatPrice(total_gnh_cost_previous + total_oth_cost_previous) }}
                                  </td>
                                  <td class="previous text-right">{{
                                      Number((total_gnh_cost_previous + total_oth_cost_previous) / total_actual_production_previous).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right"> 
                                    {{ formatPrice(total_gnh_average_cost + total_oth_average_cost) }}
                                  </td>
                                  <td class="text-right">
                                    {{
                                      Number(total_actual_production_avg ? ((total_gnh_average_cost + total_oth_average_cost) / total_actual_production_avg) : 0).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right">{{ formatPrice(total_standard_gnh + total_standard_oth) }}</td>
                                  <td class="text-right">{{
                                      (total_production_plan_gnh ? (total_standard_gnh + total_standard_oth) / (total_production_plan_gnh + total_production_plan_oth) : 0).toFixed(2)
                                    }}
                                  </td>


                                  <!-- <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) > 0"
                                      bgcolor="#3BC82A85" align="right"> Cost Down.[{{
                                      Number((total_standard_gnh + total_standard_oth) / (total_production_plan_gnh + total_production_plan_oth) - ((total_gnh_cost + total_oth_cost) / total_actual_production)).toFixed(2)
                                    }}]TK
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) < 0"
                                      bgcolor="#FF1818" align="right"> Cost Up.[{{
                                      Number(((total_gnh_cost + total_oth_cost) / total_actual_production) - (total_standard_gnh + total_standard_oth) / (total_production_plan_gnh + total_production_plan_oth)).toFixed(2)
                                    }}]TK
                                  </td> -->
                                  <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) > 0"
                                      bgcolor="#3BC82A85" align="right"> {{
                                      Number((total_standard_gnh + total_standard_oth) / (total_production_plan_gnh + total_production_plan_oth) - ((total_gnh_cost + total_oth_cost) / total_actual_production)).toFixed(2)
                                    }}
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) == 0"
                                      bgcolor="#3BC82A85" align="right"> 00
                                  </td>
                                  <td class="text-right summary_text"
                                      v-if="((total_standard_gnh +  total_standard_oth)/ (total_production_plan_gnh + total_production_plan_oth ) - ((total_gnh_cost + total_oth_cost) / total_actual_production)) < 0"
                                      bgcolor="#FF1818" align="right"> {{
                                      Number(((total_gnh_cost + total_oth_cost) / total_actual_production) - (total_standard_gnh + total_standard_oth) / (total_production_plan_gnh + total_production_plan_oth)).toFixed(2)
                                    }}
                                  </td>
                                     
                                </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>



          <!---COMMENT LIST AND NEW COMMENT MODAL--->
          <modal width="75%" height="80%" style="padding:50px" name="popup-singel">
            <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
            <div class="app-content ">
              <div class="card">
                <section id="dashboard-analytics">
                  <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <a @click="tabs('list')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link"
                         data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab"
                         v-bind:class="{ active: comment_active  == 'list' }">
                        Remark list
                      </a>
                      <a @click="tabs('add')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link"
                         data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab"
                         v-bind:class="{ active: comment_active  == 'add' }">
                        New Remark
                      </a>
                    </div>
                  </nav>
                </section>
                <form @submit.prevent="product_feadback()">
                  <table class="table table-bordered table-striped table-sm">

                    <!---COMMENT LIST--->
                    <tbody v-if="comment_active =='list'">
                    <tr class="text-center">
                      <th>Type</th>
                      <th>Section</th>
                      <th>Remark</th>
                      <th>Remark By</th>
                      <th>Date</th>
                    </tr>

                    <tr :key="index" class="text-center" v-for="(feed, index) in feedbacks">
                      <td>{{ feed.production_type }}</td>
                      <td>{{ feed.section_name }}</td>
                      <td><p v-html="feed.comments"></p></td>
                      <td>{{ !feed.user_info ? 'NA' : feed.user_info.name }}</td>
                      <td>{{ format_Date(feed.created_at) }}</td>
                    </tr>
                    </tbody>

                    <!---NEW COMMENT FORM--->
                    <tbody v-if="comment_active =='add'">
                    <tr>
                      <th colspan="2" class="text-center">
                        <div class="form-group has-success">
                          <label class="control-label">PRODUCTION</label>
                          <select v-if="feedbackType =='production'" name="production" id="production"
                                  v-model="production_feadback_mailForm.production_type"
                                  class="form-control chzn-select">
                            <option value="">SELECT</option>
                            <option value="T.PRODUCTION">T.PRODUCTION</option>
                            <option value="A.PRODUCTION">A.PRODUCTION</option>
                            <option value="D.PRODUCTION">D.PRODUCTION</option>
                          </select>
                          <select v-if="feedbackType =='wastage'" name="production" id="production"
                                  v-model="production_feadback_mailForm.production_type"
                                  class="form-control chzn-select">
                            <option value="">SELECT</option>
                            <option value="Actual">Actual</option>
                            <option value="Standard">Standard</option>
                          </select>
                        </div>
                      </th>
                      <th colspan="2" class="text-center">
                        <div class="form-group has-success">
                          <label class="control-label">SECTION</label>
                          <select v-if="feedbackType =='production'" name="section" id="section"
                                  v-model="production_feadback_mailForm.section_name" class="form-control chzn-select">
                            <option value="">SELECT</option>
                            <option value="GENERAL HOUR">GENERAL HOUR</option>
                            <option value="OT HOUR">OT HOUR</option>
                          </select>
                          <select v-if="feedbackType =='wastage'" name="production" id="production"
                                  v-model="production_feadback_mailForm.section_name" class="form-control chzn-select">
                            <option value="">SELECT</option>
                            <option v-for="row in itemsSummaryGroup" :key="row.description" :value="row.id">
                              {{ row.description }}
                            </option>
                          </select>
                        </div>
                      </th>
                    </tr>

                    <tr>
                      <th colspan="4" class="text-center">
                        <vue-editor v-model="production_feadback_mailForm.comments" name="task"
                                    placeholder="Remark...."></vue-editor>
                      </th>
                    </tr>

                    <tr>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC1</label>
                          <div class="controls">
                            <input v-model="production_feadback_mailForm.mailcc1" placeholder="example1@gmail.com"
                                   class="form-control" type="text"/>
                          </div>
                        </div>
                      </th>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC3</label>
                          <div class="controls">
                            <input v-model="production_feadback_mailForm.mailcc2" placeholder="example2@gmail.com"
                                   class="form-control" type="text"/>
                          </div>
                        </div>

                      </th>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC3</label>
                          <div class="controls">
                            <input v-model="production_feadback_mailForm.mailcc3" placeholder="example3@gmail.com"
                                   class="form-control" type="text"/>
                          </div>
                        </div>

                      </th>
                      <th class="text-center">
                        <button class="btn btn-success">Save</button>
                      </th>
                    </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "../../axios_instance";
import {Form} from "vform";
import Datepicker from 'vuejs-datepicker';
import {VueEditor} from "vue2-editor";
import moment from "moment";

export default {
  props: {},
  components: {
    Datepicker,
    VueEditor
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      items: [],
      watt5_show: 0,
      itemsFactorys: [],
      itemsSummaryGroup: [],
      cost_center_gnh: [],
      cost_center_oth: [],
      deliveris: [],
      wastage: [],
      watt5: [],
      feadbacks: [],
      feedbacks: [],
      status: '',
      feedbackType: '',
      comment_active: 'list',
      filterForm: new Form({
        factory_id: "",
        summary_group_id: "",
        start_date: new Date(),
        end_date: new Date(),
        start_date_previous: null,
        end_date_previous: null
      }),
      production_feadback_mailForm: new Form({
        mailcc1: "",
        mailcc2: "",
        mailcc3: "",
        section_name: "",
        production_type: "",
        start_date: new Date(),
        end_date: new Date(),
        factory_id: "",
        comments: "",
        summary_group_id: ""
      }),
      // total_capacity : 0 ,
      // total_production_plan : 0 ,

    };
  },
  created() {
    let start_date = new Date();
    var start_date_previous = new Date(start_date.setMonth(start_date.getMonth() - 1));
    this.filterForm.start_date_previous = start_date_previous;
    this.filterForm.end_date_previous = start_date_previous;//this.filterForm.start_date;

    this.getFactorys();
  },
  methods: {
    datepickerClosedFunction() {
      //Note: current date to previous month same date.
      var start_date_previous_set = new Date(this.filterForm.start_date);
      start_date_previous_set.setMonth(start_date_previous_set.getMonth() - 1);
      //Note: current date to previous month same date.
      this.filterForm.start_date_previous = new Date(start_date_previous_set);

      var start_dateConvert = new Date(this.filterForm.start_date);
      this.filterForm.end_date = new Date(start_dateConvert.getFullYear(), start_dateConvert.getMonth()+1, 0);
      this.filterForm.end_date_previous = new Date(this.filterForm.start_date_previous.getFullYear(), this.filterForm.start_date_previous.getMonth()+1, 0);
      // new Date(this.filterForm.end_date_previous.setMonth(this.filterForm.start_date_previous.getMonth() + 1, 0));
    },
    watt5Show() {
      if (this.watt5_show == 1) {
        this.watt5_show = 0;
      } else {
        this.watt5_show = 1;
      }
    },
    tabs(i) {
      this.comment_active = i;
    },
    previous_from_date() {
      console.log(this.filterForm.start_date);
      this.filterForm.start_date_previous = this.filterForm.start_date;
    },
    previous_to_date() {
      //Note: current date to previous month last date same date.
      var end_date_previous_set = new Date(this.filterForm.end_date);
      end_date_previous_set.setMonth(end_date_previous_set.getMonth() - 1);
      //Note: current date to previous month  last date same date.
      console.log('sddsfdsfdsf',new Date(end_date_previous_set));
      this.filterForm.end_date_previous = end_date_previous_set;
    },
    product_feadback() {
      try {
        let loader = this.$loading.show();
        // let loader = this.$loading.show();
        this.production_feadback_mailForm.start_date = this.filterForm.start_date ? this.format_Date(this.filterForm.start_date) : '';
        this.production_feadback_mailForm.end_date = this.filterForm.end_date ? this.format_Date(this.filterForm.end_date) : '';
        this.production_feadback_mailForm.summary_group_id = this.filterForm.summary_group_id;
        this.production_feadback_mailForm.factory_id = this.filterForm.factory_id;
        this.production_feadback_mailForm.type = this.feedbackType;
        this.production_feadback_mailForm.post(this.api_url + "production_feedbacks", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          if (res.data.success) {

            //HIDE MODAL
            this.hide_pop();

            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });

            //SHOW MODAL WITH UPDATED DATA
            this.show_pop(this.feedbackType);

          }
          loader.hide();
          // this.$router.push('/daily_work');
        }, (error) => {
          console.log(error);
          loader.hide();
        })
      } catch (error) {
        // loader.hide();
        console.log(error);
      }
    },

    //OLD METHOD
    async finter() {

      let loader = this.$loading.show();
      this.filterForm.watt5 = 0;
      this.filterForm.start_date = this.filterForm.start_date ? this.format_Date(this.filterForm.start_date) : '';
      this.filterForm.end_date = this.filterForm.end_date ? this.format_Date(this.filterForm.end_date) : '';
      this.filterForm.start_date_previous = this.filterForm.start_date_previous ? this.format_Date(this.filterForm.start_date_previous) : '';
      this.filterForm.end_date_previous = this.filterForm.end_date_previous ? this.format_Date(this.filterForm.end_date_previous) : '';
      this.filterForm.post(this.api_url + "production_report", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      }).then((res) => {
        loader.hide();
        this.deliveris = res.data.data.delivary;
        this.wastage = res.data.data.wastage;
        this.cost_center_gnh = res.data.data.cost_center_gnh;
        this.cost_center_oth = res.data.data.cost_center_oth;


      }, (error) => {
        loader.hide();
        console.log(error);
      })

      if (this.filterForm.summary_group_id == 3) {
        this.filterForm.watt5 = 1;
        this.filterForm.post(this.api_url + "production_report_5watt", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          loader.hide();
          this.watt5 = res.data.data.watt5;

        }, (error) => {
          loader.hide();
          console.log(error);
        })
      }

      this.filterForm.get(this.api_url + "production_feedbacks?summary_group_id=" + this.filterForm.summary_group_id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      }).then((res) => {
        this.feedbacks = res.data.data;

        //console.log('res.data.data', res.data.data)
      })


    },


    async getItems() {
      let where = '?';
      let loader = this.$loading.show();
      try {
        await axios
            .get(this.api_url + "products" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
              },
            })
            .then(({
                     data
                   }) => {
              if (data.success) {
                this.items = data.data
              }
              loader.hide();
            });
      } catch (error) {
        loader.hide();
      }
    },

    //GET PRODUCT BY FACTORY ID
    async summaryList() {
      //summary_list
      this.filterForm.post(this.api_url + "summary_list", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      }).then((res) => {
        console.log(res);
        this.itemsSummaryGroup = res.data.data;

      }, (error) => {
        console.log(error);
      })
    },
    async getFactorys() {
      let where = '?';
      await axios
          .get(this.api_url + "factorys" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
                   data
                 }) => {
            if (data.success) {
              this.itemsFactorys = data.data
            }

          });

    },
    gnh_total_per_unit(production_gnh) {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(production_gnh ? value.cost / production_gnh : 0);
      }, 0);
    },
    oth_total_per_unit(production_oth) {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(production_oth ? value.cost / production_oth : 0);
      }, 0);
    },
    gnh_total_per_unit_previous(production_gnh) {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(production_gnh ? value.cost_previous / production_gnh : 0);
      }, 0);
    },
    oth_total_per_unit_previous(production_oth) {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(production_oth ? value.cost_previous / production_oth : 0);
      }, 0);
    },
    colorCheck() {
      return 1;
    },
    hide_pop() {
      this.$modal.hide("popup-singel");
    },

    //ONCLICK COMMENT BUTTON GET ALL COMMENT LIST AND RENDER IN COMMENT LIST TAB
    show_pop(type) {
      this.feedbackType = type;
      if (type == 'wastage') {
        this.production_feadback_mailForm.production_type == 'Actual';
      } else {
        this.production_feadback_mailForm.production_type == 'A.PRODUCTION';
      }

      //GET PRODUCT FEEDBACK DATA LIST
      axios.get(this.api_url + "production_feedbacks?summary_group_id=" + this.filterForm.summary_group_id + "&startDate=" + this.filterForm.start_date + "&endDate=" + this.filterForm.end_date, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      }).then((res) => {
        this.feedbacks = res.data.data;
        console.log('this.feedbacks', this.feedbacks)
      })

      this.$modal.show("popup-singel");
    },

    //GET OLD REPORT
    getOldReport(){
      //GET FILTER INPUT
      //GET OLD FACTORY ID
      let newFactoryId =  this.filterForm.factory_id;

      var objectVal = this.itemsFactorys.filter(function(elem){
        if(elem.id == newFactoryId) return elem.old_id;
      });

      let factory = objectVal[0].old_id;
      let product = this.filterForm.summary_group_id;
      let year = moment(String(this.filterForm.start_date)).format('YYYY');
      let month = moment(String(this.filterForm.start_date)).format('M');
      let week = this.filterForm.week;

      //REDIRECT URL GENERATE WITH PARAMS
      let routeData = this.$router.resolve({ path: 'rpt_view_all', query: { factory: factory, product: product, year:year, month:month, week: week } });

      //OPEN NEW TAB
      window.open(routeData.href, '_blank');

    }

  },
  computed: {
    //  total_capacity = this.deliveris.reduce((acc, item) => acc + item.gnh_capacity, 0);
    //  total_production_plan = this.deliveris.reduce((acc, item) => acc + item.gnh_production_plan, 0);

    total_capacity: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_capacity) + Number(value.oth_capacity);
      }, 0);
    },
    total_production_plan: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_production_plan) + Number(value.oth_production_plan);
      }, 0);
    },

    total_production_plan_gnh: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_production_plan);
      }, 0);
    },

    total_production_plan_oth: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.oth_production_plan);
      }, 0);
    },

    total_production_target: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_production_target) + Number(value.oth_production_target);
      }, 0);
    },
    total_production_target_previous: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_production_target_previous) + Number(value.oth_production_target_previous);
      }, 0);
    },
    total_actual_production_gnh: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production);
      }, 0);
    },
    total_actual_production_oth: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.oth_actual_production);
      }, 0);
    },

    total_actual_production_oth_previous: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.oth_actual_production_previous);
      }, 0);
    },
    total_actual_production: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production) + Number(value.oth_actual_production);
      }, 0);
    },
    //total_actual_Production_gnh
    total_actual_production_previous: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production_previous) + Number(value.oth_actual_production_previous);
      }, 0);
    },
    total_actual_production_gnh_previous: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production_previous);
      }, 0);
    },
    total_actual_delivery: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_delivery);
      }, 0);
    },
    total_actual_delivery_previous: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_delivery_previous);
      }, 0);
    },
    total_actual_production_avg: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production_avg) + Number(value.oth_actual_production_avg);
      }, 0);
    },

    total_gnh_actual_production_avg: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_production_avg);
      }, 0);
    },

    total_oth_actual_production_avg: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.oth_actual_production_avg);
      }, 0);
    },


    total_actual_delivery_avg: function () {
      if (!this.deliveris) {
        return 0;
      }

      return this.deliveris.reduce(function (total, value) {
        return total + Number(value.gnh_actual_delivery_avg ? value.gnh_actual_delivery_avg : 0);
      }, 0);
    },

    // wastage

    total_actual_wastage: function () {
      if (!this.wastage) {
        return 0;
      }

      return this.wastage.reduce(function (total, value) {
        return total + Number(value.actual_wastage);
      }, 0);
    },
    total_consumtion: function () {
      if (!this.wastage) {
        return 0;
      }

      return this.wastage.reduce(function (total, value) {
        return total + Number(value.consumtion);
      }, 0);
    },
    total_gnh_cost: function () {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(value.cost);
      }, 0);
    },
    total_gnh_average_cost: function () {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(value.average_cost);
      }, 0);
    },
    total_oth_cost: function () {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(value.cost);
      }, 0);
    },
    total_oth_average_cost: function () {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(value.average_cost);
      }, 0);
    },
    total_gnh_cost_previous: function () {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(value.cost_previous);
      }, 0);
    },
    total_oth_cost_previous: function () {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(value.cost_previous);
      }, 0);
    },

    total_standard_gnh: function () {
      if (!this.cost_center_gnh) {
        return 0;
      }

      return this.cost_center_gnh.reduce(function (total, value) {
        return total + Number(value.standard_gnh);
      }, 0);
    },

    total_standard_oth: function () {
      if (!this.cost_center_oth) {
        return 0;
      }

      return this.cost_center_oth.reduce(function (total, value) {
        return total + Number(value.standard_oth ? value.standard_oth : 0);
      }, 0);
    },
    // standard_per_unit_gnh_total : function(){
    //     if (!this.cost_center_gnh) {
    //         return 0;
    //     }

    //     return this.cost_center_gnh.reduce(function (total, value) {
    //         return total + Number(value.standard_gnh ? value.standard_gnh : 0) ;
    //     }, 0);
    // }


  }
};
</script>
<style>
.previous {
  background: #faeeaa;
}

.thead-light th {
  background-color: lightgrey;
}

td {
  text-transform: none;
}

.total {
  background: #efefef;
  font-weight: bold;
}

.watt5_show {
  background: #ededed;
  border-radius: 20%;
  padding: 2px;
}
</style>
    