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
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active"> KRA , KPI and MOS
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link v-if="role_id == 5 || role_id == 6 || role_id == 7"
                  class="btn btn-primary add-btn weightage_list" :to="{ path: '/weightage_list' }"> <i
                    class="bx bx-add-alt"></i>Target Modification Request</router-link>

                <router-link v-if="role_id == 5" class="btn btn-primary add-btn achievement_request"
                  :to="{ path: '/m_o_s_achievement_permissions' }"> <i class="bx bx-add-alt"></i>Achievement Entry
                  Request</router-link>
                <!-- <a v-if="role_id == 5 "
                  class="btn btn-primary add-btn achievement_request " @click="AchievementRequest()"> <i
                    class="bx bx-add-alt"></i>Achievement Entry Request</a> -->
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div class="col-12 col-sm-12 ">
                  <div class="form-inline justify-content-center row" style="padding-top: 10px;">
                    <label class="mb-2 mr-sm-2 col-1">Show <strong>KRA</strong> <input type="checkbox" checked=""
                        value="1" v-model="filterForm.show_kra"></label>
                    <label class="mb-2 mr-sm-2 col-1">Show <strong>KPI</strong> <input type="checkbox" checked=""
                        value="1" v-model="filterForm.show_kpi"></label>
                    <label class="mb-2 mr-sm-2 col-1">Show <strong>MOS</strong> <input type="checkbox" checked=""
                        value="1" v-model="filterForm.show_mos"></label>
                    <label class="mb-2 mr-sm-2 col-1">Show <strong>Y.Achi.%</strong> <input type="checkbox" value="1"
                        v-model="filterForm.show_yachi"></label>
                    <label class="mb-2 mr-sm-2 col-1">Show With<strong> Zero</strong> <input type="checkbox" value="1"
                        v-model="filterForm.show_zero" v-on:change="monthChange()"></label>
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Quarter {{ year }} </label>
                  <fieldset class="form-group">

                    <select v-if="year < 2024" class="form-control" v-on:change="monthChange()" v-model="filterForm.quarter"
                      id="users-list-verified">
                      <option value="">All</option>
                      <option v-for="row in quarter_months" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                      <option value="5">1st Half yearly</option>
                      <option value="6">2nd Half yearly</option>
                    </select>

                    <select  v-if="year > 2023" class="form-control" v-on:change="monthChange()" v-model="filterForm.quarter"
                      id="users-list-verified">
                      <option value="">All</option>
                      <option v-for="row in quarter_months_2nd" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                      <option value="6">1st Half yearly</option>
                      <option value="2">2nd Half yearly</option>
                    </select>

                    
                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Month </label>
                  <fieldset class="form-group">
                    <select class="form-control" v-model="filterForm.month" v-on:change="monthChange()"
                      id="users-list-verified">
                      <option value="">All</option>
                      <option v-for="row in months" :key="row.id" :value="row.id">{{
                        row.name
                      }}
                      </option>
                    </select>
                  </fieldset>
                </div> <!-- && deptItems.length > 1-->

                <div v-if="(deptItems.length > 1 && user_data.email != 'cost') || (user_data.dept_id == 6)"
                  class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Department</label>
                  <fieldset class="form-group">
                    <select v-on:change="getKRA()" class="form-control" v-model="filterForm.dept_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in deptItems" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <!-- <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6">
                  <label for="users-list-verified">Wings</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="changeEmployee()" v-model="filterForm.wing_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in WingsItems" :key="row.id" :value="row.id">
                        {{ row.wing_title }}
                      </option>
                    </select>
                  </fieldset>
                </div> -->

                <div class="ccol-sm-4 col-lg-2" v-if="search_box" >
                <label for="users-list-verified">Employee </label>
                <fieldset class="form-group">

                  <div class="autocomplete">
                  <input
                    v-model="search"
                    @input="getEmployee()"
                    type="text"
                    class="form-control" data-validation-required-message="This field is required" placeholder="Name , Employee ID , Phone , Email"
                  />
                  <ul
                    v-show="isOpen"
                    class="autocomplete-results"
                  >
                    <li @click="changeEmployee(result.id)"
                      v-for="(result, i) in employeeItem"
                      :key="i"
                      class="autocomplete-result"
                    >
                      {{ result.name }} ( {{ result.employee_id }})
                    </li>
                  </ul>
                </div>
                  <!-- <input type="text" name="search"  v-on:keyup="getItems()"  v-model="filterForm.search"  class="form-control" data-validation-required-message="This field is required" placeholder="Name , Employee ID , Phone , Email">  -->
                </fieldset>
              </div>


                

                <div class="col-12 col-sm-6 col-lg-2" v-if="employeeItem.length > 0">
                  <label for="users-list-verified">Employee  </label>
                  <fieldset class="form-group">

                    <Select2 placeholder="Select One" v-on:change="getItems()" v-model="filterForm.user_id"
                      :options="employeeItem" />
                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="Profession">KRA</label>
                  <fieldset class="form-group">
                    <div class="controls">
                      <select id="Profession" name="kra_id" v-on:change="getKpi()" v-model="filterForm.kra_id"
                        class="form-control">
                        <option value="">Select one</option>
                        <option v-for="row in kraItem" :key="row.id" :value="row.id">
                          {{ row.kra_name }}
                        </option>
                      </select>
                    </div>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="Profession">KPI</label>
                  <fieldset class="form-group">
                    <div class="controls">
                      <select id="Profession" name="kpi_id" v-on:change="getItems()" v-model="filterForm.kpi_id"
                        class="form-control">
                        <option value="">Select one</option>
                        <option v-for="row in kpiItem" :key="row.id" :value="row.id">
                          {{ row.kpi_name }}
                        </option>
                      </select>
                    </div>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 6 && filterForm.dept_id == 8">
                  <label for="Profession">Submit Notification TO HOD</label>
                  <fieldset class="form-group">
                    <div class="controls">
                      <button type="submit" class="btn btn-primary mb-2" @click="wings_notification()">Submit</button>
                    </div>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-4" v-if="user_data.dept_id ==  1 ||  user_data.dept_id ==  42 || user_data.dept_id ==  40 ||  user_data.dept_id ==  41 ||  user_data.dept_id ==  124 ">
                  
                  <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                    <a class="text-white" @click="kpiUpload()"> <i class="bx bx-add-alt"></i>Achievement CSV Upload </a>
                  </button>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th v-if="filterForm.show_kra == 1">KRA</th>
                              <th v-if="filterForm.show_kra == 1">Weightage</th>
                              <th v-if="filterForm.show_kpi == 1">KPI</th>
                              <th v-if="filterForm.show_mos == 1">MOS</th>
                              <th>{{
                                filterForm.month != '' ? 'm.Target' : filterForm.quarter == 1 || filterForm.quarter == 2
                                  || filterForm.quarter == 3 || filterForm.quarter == 4 ? 'Q.Target' : filterForm.quarter
                                    == 5 || filterForm.quarter == 6 ? 'H.Target' : 'Y.Target'
                              }}
                              </th>
                              <th>{{
                                filterForm.month != '' ? 'm.Achi' : filterForm.quarter == 1 || filterForm.quarter == 2
                                  || filterForm.quarter == 3 || filterForm.quarter == 4 ? 'Q.Achi' : filterForm.quarter ==
                                    5 || filterForm.quarter == 6 ? 'H.Achi' : 'Y.Achi'
                              }}
                              </th>
                              <th v-if="filterForm.show_yachi == 1">{{
                                filterForm.month != '' ? 'm.Achieve.%' : filterForm.quarter == 1 || filterForm.quarter
                                  == 2 || filterForm.quarter == 3 || filterForm.quarter == 4 ? 'Q.Achieve.%' :
                                filterForm.quarter == 5 || filterForm.quarter == 6 ? 'H.Achieve%' : 'Y.Achieve.%'
                              }}
                              </th>
                              <!-- this condition is for 2024 -->
                              <template v-if="year == 2024">
                                <th v-if="select_months('jul')">Jul</th>
                                <th v-if="select_months('aug')">Aug</th>
                                <th v-if="select_months('sep')">Sep</th>
                                <th v-if="select_months('oct')">Oct</th>
                                <th v-if="select_months('nov')">Nov</th>
                                <th v-if="select_months('dec')">Dec</th>

                                <th v-if="select_months('jan')">Jan</th>
                                <th v-if="select_months('feb')">Feb</th>
                                <th v-if="select_months('mar')">Mar</th>
                                <th v-if="select_months('apr')">Apr</th>
                                <th v-if="select_months('may')">May</th>
                                <th v-if="select_months('jun')">Jun</th>
                                <th>Action</th>
                              </template>
                              <!-- this condition is for before 2023 jun  -->
                              <template v-else>
                                <th v-if="select_months('jan')">Jan</th>
                                <th v-if="select_months('feb')">Feb</th>
                                <th v-if="select_months('mar')">Mar</th>
                                <th v-if="select_months('apr')">Apr</th>
                                <th v-if="select_months('may')">May</th>
                                <th v-if="select_months('jun')">Jun</th>

                                <th v-if="select_months('jul')">Jul</th>
                                <th v-if="select_months('aug')">Aug</th>
                                <th v-if="select_months('sep')">Sep</th>
                                <th v-if="select_months('oct')">Oct</th>
                                <th v-if="select_months('nov')">Nov</th>
                                <th v-if="select_months('dec')">Dec</th>
                              </template>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index ) in items">
                              <tr :key="item.id" :class="index">
                                <td :rowspan="rowVisible(index, item, 'kra')"
                                  v-if="filterForm.show_kra == 1 && (items[index > 0 ? index - 1 : 0].kra_id != item.kra_id || index == 0)">
                                  {{ item.krajoin ? item.krajoin.kra_name : '' }}
                                  <strong>
                                    (T-{{ kraTotalTarget(item.kra_id, 'target') }}/A- {{ kraTotalTarget(item.kra_id,
                                      'achievement') }})
                                  </strong>
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kra')"
                                  v-if="filterForm.show_kra == 1 && (items[index > 0 ? index - 1 : 0].kra_id != item.kra_id || index == 0)">
                                  {{ item.krajoin ? item.krajoin.kra_weight : '' }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi')"
                                  v-if="filterForm.show_kpi == 1 && (items[index > 0 ? index - 1 : 0].kpi_id != item.kpi_id || index == 0)">
                                  {{ item.kpijoin ? item.kpijoin.kpi_name : '' }}
                                  (T-{{
                                    kpiTotalTarget(item.kpi_id, 'target')
                                  }}/A-{{ kpiTotalTarget(item.kpi_id, 'achievement') }})
                                  <i class="relation_kpi"
                                    v-if="(item.kpijoin.rep_id != '') && item.kpijoin.rep_id != 0 && (role_id == 7 || role_id == 6 || role_id == 5)">Relation</i>
                                </td>
                                <td v-if="filterForm.show_mos == 1" @click="mos_rep_pop(item)">
                                  {{ item.mos_name }}
                                  (W-{{ item.weightage }})
                                  (T-{{ mosTotalTarget(item, 'target') }}/A-{{ mosTotalTarget(item, 'achievement') }})
                                  <span v-if="item.share_per > 0">
                                    <i class="relation_kpi">
                                      Share Weightage
                                      {{ item.share_per }}%
                                    </i>
                                  </span>
                                  <br><br>
                                  <!-- Note : KPI Rep Share  -->
                                  <span v-if="item.working_memberJoin.length > 0">
                                    <span v-for="(user, index) in mosViewRelation(item)">
                                      <i class="relation_kpi"> {{ user.name }} -
                                        ({{ user['rep_per'] > 0 ? user['rep_per'] : 0 }}<span
                                          v-if="user['rep_per'] > 0">%</span>),
                                      </i>
                                    </span>
                                  </span>

                                  <!-- Note: MOS Rep Share  -->
                                  <span
                                    v-if="item.mos_working_memberJoin.length > 0 && (role_id == 7 || role_id == 6 || role_id == 5)">
                                    <span v-for="(user, index) in working_memberJoinCal(item)">
                                      <i class="relation_kpi"> {{ user.name }}-
                                        ({{ user['rep_per'] > 0 ? user['rep_per'] : 0 }}<span
                                          v-if="user['rep_per'] > 0">%</span>),
                                      </i>
                                    </span>
                                  </span>
                                  <!-- Note: MOS ONly HOD Panel  -->
                                  <span v-if="item.mos_working_memberJoin.length == 0
                                    && item.working_memberJoin.length == 0 && (role_id == 5)">
                                    <i class="relation_kpi">Department</i>
                                  </span>
                                  <span v-if="item.mos_working_memberJoin.length == 0
                                    && item.working_memberJoin.length == 0
                                    && (role_id == 6)">
                                    <i class="relation_kpi">Wings</i>
                                  </span>
                                </td>
                                <td
                                  v-bind:class="achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 100 ? 'gb_color_green' : achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) < 100 && achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 0 ? 'gb_color_yellow' : ''">
                                  {{
                                    Number(targetTotal(item)).toFixed(2)
                                  }}{{ item.isvalorper == 1 ? '%' : '' }}
                                </td>
                                <td
                                  v-bind:class="achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 100 ? 'gb_color_green' : achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) < 100 && achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 0 ? 'gb_color_yellow' : ''">
                                  {{
                                    Number(achievementjoinTotal(item)).toFixed(2)
                                  }}{{ item.isvalorper == 1 ? '%' : '' }}
                                </td>
                                <td
                                  v-bind:class="achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 100 ? 'gb_color_green' : achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) < 100 && achievementTotal(item, targetTotal(item), achievementjoinTotal(item)) > 0 ? 'gb_color_yellow' : ''"
                                  v-if="filterForm.show_yachi == 1">
                                  {{
                                    achievementTotal(item, targetTotal(item), achievementjoinTotal(item))
                                  }}%
                                </td>
                                <!-- this condition is for 2024 -->
                                <template v-if="year > 2023">
                                  <td v-if="select_months('jul')" @click="mos_show_pop(item, 'july')"
                                    v-bind:class="achievement(item, 'july') > 100 ? 'gb_color_green' : achievement(item, 'july') < 100 && achievement(item, 'july') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'july') > 0 ? achievement(item, 'july') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'july') != 0 &&  item.mosachievementjoin.july_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'july') == 0 && item.mostargetjoin ? item.mostargetjoin.july : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(7) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('aug')" @click="mos_show_pop(item, 'august')"
                                    v-bind:class="achievement(item, 'august') > 100 ? 'gb_color_green' : achievement(item, 'august') < 100 && achievement(item, 'august') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'august') > 0 ? achievement(item, 'august') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'august') != 0 &&  item.mosachievementjoin.august_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'august') == 0 && item.mostargetjoin ? item.mostargetjoin.august : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(8) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('sep')" @click="mos_show_pop(item, 'september')"
                                    v-bind:class="achievement(item, 'september') > 100 ? 'gb_color_green' : achievement(item, 'september') < 100 && achievement(item, 'september') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'september') > 0 ? achievement(item, 'september') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'september') != 0 &&  item.mosachievementjoin.september_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'september') == 0 && item.mostargetjoin ? item.mostargetjoin.september : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(9) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('oct')" @click="mos_show_pop(item, 'october')"
                                    v-bind:class="achievement(item, 'october') > 100 ? 'gb_color_green' : achievement(item, 'october') < 100 && achievement(item, 'october') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'october') > 0 ? achievement(item, 'october') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'october') != 0 &&  item.mosachievementjoin.october_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'october') == 0 && item.mostargetjoin ? item.mostargetjoin.october : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(10) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('nov')" @click="mos_show_pop(item, 'november')"
                                    v-bind:class="achievement(item, 'november') > 100 ? 'gb_color_green' : achievement(item, 'november') < 100 && achievement(item, 'november') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'november') > 0 ? achievement(item, 'november') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'november') != 0 &&  item.mosachievementjoin.november_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'november') == 0 && item.mostargetjoin ? item.mostargetjoin.november : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(11) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('dec')" @click="mos_show_pop(item, 'december')"
                                    v-bind:class="achievement(item, 'december') > 100 ? 'gb_color_green' : achievement(item, 'december') < 100 && achievement(item, 'december') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'december') > 0 ? achievement(item, 'december') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'december') != 0 &&  item.mosachievementjoin.december_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i @click="mos_hide_pop(item, 'december')"
                                      v-if="achievement(item, 'december') == 0 && item.mostargetjoin ? item.mostargetjoin.december : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(12) == 'red' ? 'color_red' : ''"></i>
                                  </td>

                                  <td v-if="select_months('jan')" @click="mos_show_pop(item, 'january')"
                                    v-bind:class="achievement(item, 'january') > 100 ? 'gb_color_green' : achievement(item, 'january') < 100 && achievement(item, 'january') > 0 ? 'gb_color_yellow' : ''">
                                    {{ achievement(item, 'january') > 0 ? achievement(item, 'january') + '%' : '' }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'january') != 0 &&  item.mostargetjoin.january_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'january') == 0 && item.mostargetjoin ? item.mostargetjoin.january : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(1) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('feb')" @click="mos_show_pop(item, 'february')"
                                    v-bind:class="achievement(item, 'february') > 100 ? 'gb_color_green' : achievement(item, 'february') < 100 && achievement(item, 'february') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'february') > 0 ? achievement(item, 'february') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'february') != 0 &&  item.mostargetjoin.february_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'february') == 0 && item.mostargetjoin ? item.mostargetjoin.february : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(2) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('mar')" @click="mos_show_pop(item, 'march')"
                                    v-bind:class="achievement(item, 'march') > 100 ? 'gb_color_green' : achievement(item, 'march') < 100 && achievement(item, 'march') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'march') > 0 ? achievement(item, 'march') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'march') != 0 &&  item.mostargetjoin.march_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P </i> -->
                                    <i v-if="achievement(item, 'march') == 0 && item.mostargetjoin ? item.mostargetjoin.march : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(3) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('apr')" @click="mos_show_pop(item, 'april')"
                                    v-bind:class="achievement(item, 'april') > 100 ? 'gb_color_green' : achievement(item, 'april') < 100 && achievement(item, 'april') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'april') > 0 ? achievement(item, 'april') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'april') != 0 &&  item.mostargetjoin.april_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'april') == 0 && item.mostargetjoin ? item.mostargetjoin.april : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(4) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('may')" @click="mos_show_pop(item, 'may')"
                                    v-bind:class="achievement(item, 'may') > 100 ? 'gb_color_green' : achievement(item, 'may') < 100 && achievement(item, 'may') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'may') > 0 ? achievement(item, 'may') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'may') != 0 &&  item.mosachievementjoin.may_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'may') == 0 && item.mostargetjoin ? item.mostargetjoin.may : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(5) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('jun')" @click="mos_show_pop(item, 'june')"
                                    v-bind:class="achievement(item, 'june') > 100 ? 'gb_color_green' : achievement(item, 'june') < 100 && achievement(item, 'june') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'june') > 0 ? achievement(item, 'june') + '%' : ''
                                    }}

                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'june') != 0 &&  item.mosachievementjoin.june_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'june') == 0 && item.mostargetjoin ? item.mostargetjoin.june : 0 > 0"
                                      class="bx bx-map " v-bind:class="colorCheck(6) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                </template>
                                <!-- this condition is for till 2023 jun -->
                                <template v-else>
                                  <td v-if="select_months('jan')" @click="mos_show_pop(item, 'january')"
                                    v-bind:class="achievement(item, 'january') > 100 ? 'gb_color_green' : achievement(item, 'january') < 100 && achievement(item, 'january') > 0 ? 'gb_color_yellow' : ''">
                                    {{ achievement(item, 'january') > 0 ? achievement(item, 'january') + '%' : '' }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'january') != 0 &&  item.mostargetjoin.january_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'january') == 0 && item.mostargetjoin ? item.mostargetjoin.january : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(1) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('feb')" @click="mos_show_pop(item, 'february')"
                                    v-bind:class="achievement(item, 'february') > 100 ? 'gb_color_green' : achievement(item, 'february') < 100 && achievement(item, 'february') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'february') > 0 ? achievement(item, 'february') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'february') != 0 &&  item.mostargetjoin.february_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'february') == 0 && item.mostargetjoin ? item.mostargetjoin.february : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(2) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('mar')" @click="mos_show_pop(item, 'march')"
                                    v-bind:class="achievement(item, 'march') > 100 ? 'gb_color_green' : achievement(item, 'march') < 100 && achievement(item, 'march') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'march') > 0 ? achievement(item, 'march') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'march') != 0 &&  item.mostargetjoin.march_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P </i> -->
                                    <i v-if="achievement(item, 'march') == 0 && item.mostargetjoin ? item.mostargetjoin.march : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(3) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('apr')" @click="mos_show_pop(item, 'april')"
                                    v-bind:class="achievement(item, 'april') > 100 ? 'gb_color_green' : achievement(item, 'april') < 100 && achievement(item, 'april') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'april') > 0 ? achievement(item, 'april') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'april') != 0 &&  item.mostargetjoin.april_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'april') == 0 && item.mostargetjoin ? item.mostargetjoin.april : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(4) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('may')" @click="mos_show_pop(item, 'may')"
                                    v-bind:class="achievement(item, 'may') > 100 ? 'gb_color_green' : achievement(item, 'may') < 100 && achievement(item, 'may') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'may') > 0 ? achievement(item, 'may') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'may') != 0 &&  item.mosachievementjoin.may_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'may') == 0 && item.mostargetjoin ? item.mostargetjoin.may : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(5) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('jun')" @click="mos_show_pop(item, 'june')"
                                    v-bind:class="achievement(item, 'june') > 100 ? 'gb_color_green' : achievement(item, 'june') < 100 && achievement(item, 'june') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'june') > 0 ? achievement(item, 'june') + '%' : ''
                                    }}

                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'june') != 0 &&  item.mosachievementjoin.june_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'june') == 0 && item.mostargetjoin ? item.mostargetjoin.june : 0 > 0"
                                      class="bx bx-map " v-bind:class="colorCheck(6) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('jul')" @click="mos_show_pop(item, 'july')"
                                    v-bind:class="achievement(item, 'july') > 100 ? 'gb_color_green' : achievement(item, 'july') < 100 && achievement(item, 'july') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'july') > 0 ? achievement(item, 'july') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending" v-if="achievement(item , 'july') != 0 &&  item.mosachievementjoin.july_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )" class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'july') == 0 && item.mostargetjoin ? item.mostargetjoin.july : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(7) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('aug')" @click="mos_show_pop(item, 'august')"
                                    v-bind:class="achievement(item, 'august') > 100 ? 'gb_color_green' : achievement(item, 'august') < 100 && achievement(item, 'august') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'august') > 0 ? achievement(item, 'august') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'august') != 0 &&  item.mosachievementjoin.august_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'august') == 0 && item.mostargetjoin ? item.mostargetjoin.august : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(8) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('sep')" @click="mos_show_pop(item, 'september')"
                                    v-bind:class="achievement(item, 'september') > 100 ? 'gb_color_green' : achievement(item, 'september') < 100 && achievement(item, 'september') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'september') > 0 ? achievement(item, 'september') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'september') != 0 &&  item.mosachievementjoin.september_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'september') == 0 && item.mostargetjoin ? item.mostargetjoin.september : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(9) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('oct')" @click="mos_show_pop(item, 'october')"
                                    v-bind:class="achievement(item, 'october') > 100 ? 'gb_color_green' : achievement(item, 'october') < 100 && achievement(item, 'october') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'october') > 0 ? achievement(item, 'october') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'october') != 0 &&  item.mosachievementjoin.october_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'october') == 0 && item.mostargetjoin ? item.mostargetjoin.october : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(10) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('nov')" @click="mos_show_pop(item, 'november')"
                                    v-bind:class="achievement(item, 'november') > 100 ? 'gb_color_green' : achievement(item, 'november') < 100 && achievement(item, 'november') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'november') > 0 ? achievement(item, 'november') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'november') != 0 &&  item.mosachievementjoin.november_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i v-if="achievement(item, 'november') == 0 && item.mostargetjoin ? item.mostargetjoin.november : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(11) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                  <td v-if="select_months('dec')" @click="mos_show_pop(item, 'december')"
                                    v-bind:class="achievement(item, 'december') > 100 ? 'gb_color_green' : achievement(item, 'december') < 100 && achievement(item, 'december') > 0 ? 'gb_color_yellow' : ''">
                                    {{
                                      achievement(item, 'december') > 0 ? achievement(item, 'december') + '%' : ''
                                    }}
                                    <!-- <i title="Achievement approval pending"
                                    v-if="achievement(item , 'december') != 0 &&  item.mosachievementjoin.december_status == 1 && (item.krajoin.role_id == 6 || item.krajoin.role_id == 7 )"
                                    class="achievment_pending">P</i> -->
                                    <i @click="mos_hide_pop(item, 'december')"
                                      v-if="achievement(item, 'december') == 0 && item.mostargetjoin ? item.mostargetjoin.december : 0 > 0"
                                      class="bx bx-map" v-bind:class="colorCheck(12) == 'red' ? 'color_red' : ''"></i>
                                  </td>
                                </template>
                                <td>
                                  <div class="dropup">
                                    <span
                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <router-link v-if="(role_id == 5 || role_id == 6 || role_id == 7 || role_id == 10)"
                                        target="_blank" class="dropdown-item"
                                        :to="{ path: '/achievement_mos/' + item.id + '?quarter=' + filterForm.quarter + '&month=' + filterForm.month + '&dept_id=' + filterForm.dept_id + '&kra_id=' + filterForm.kra_id + '&kpi_id=' + filterForm.kpi_id }">
                                        <i class="bx bx-edit-alt mr-1"></i>
                                        Achievement
                                      </router-link>
                                      <router-link class="dropdown-item" target="_blank"
                                        :to="{ path: '/bpt_report_details/' + item.kpi_id }">
                                        <i class="bx bx-edit-alt mr-1"></i> Details
                                      </router-link>
                                      <router-link traget="_blank"
                                        v-if="(role_id == 5 || role_id == 6 || role_id == 7 || role_id == 10) && (p_data)"
                                        class="dropdown-item" :to="{ path: '/measure_of_success/' + item.kpi_id }">
                                        <i class="bx bx-edit-alt mr-1"></i> MOS
                                        Edit
                                      </router-link>
                                      <a @click="comment_show(item)" class="dropdown-item">
                                        <i class="bx bx-comment mr-1">
                                        </i>
                                        Comment
                                      </a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                        </table>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!--FEEDBACK MODAL--->
          <modal height="80%" name="comment" style="padding:50px" width="65%">
            <i @click="comment_hidden()" class="bx bx-x-circle x-circle">
            </i>
            <div class="app-content ">
              <div class="card">
                <section id="dashboard-analytics">
                  <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <a @click="tabs('comments')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link"
                        data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab"
                        v-bind:class="{ active: comment_active == 'comments' }">
                        Comments
                      </a>
                      <a @click="tabs('add')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link"
                        data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab"
                        v-bind:class="{ active: comment_active == 'add' }">
                        New Comment
                      </a>
                    </div>
                  </nav>
                </section>
                <form @submit.prevent="task_comment()">
                  <table class="table table-bordered table-striped table-sm">
                    <tbody v-if="comment_active == 'add'">
                      <tr>
                        <th class="text-center" colspan="4">
                          <vue-editor name="task" placeholder="Comment...." v-model="comment_mailForm.msg">
                          </vue-editor>
                        </th>
                      </tr>
                      <tr>
                        <th class="text-center">
                          <div class="form-group">
                            <label for="Profession">
                              Mail CC1
                            </label>
                            <div class="controls">
                              <input class="form-control" placeholder="example1@gmail.com" type="text"
                                v-model="comment_mailForm.mailcc1" />
                            </div>
                          </div>
                        </th>
                        <th class="text-center">
                          <div class="form-group">
                            <label for="Profession">
                              Mail CC3
                            </label>
                            <div class="controls">
                              <input class="form-control" placeholder="example2@gmail.com" type="text"
                                v-model="comment_mailForm.mailcc2" />
                            </div>
                          </div>
                        </th>
                        <th class="text-center">
                          <div class="form-group">
                            <label for="Profession">
                              Mail CC3
                            </label>
                            <div class="controls">
                              <input class="form-control" placeholder="example3@gmail.com" type="text"
                                v-model="comment_mailForm.mailcc3" />
                            </div>
                          </div>
                        </th>
                        <th class="text-center">
                          <button class="btn btn-success">
                            Save
                          </button>
                        </th>
                      </tr>
                    </tbody>
                    <tbody v-if="comment_active == 'comments'">
                      <tr class="text-center">
                        <th>
                          Comment
                        </th>
                        <th>
                          User Name
                        </th>
                        <th>
                          Date
                        </th>
                      </tr>
                      <tr :key="index" class="text-center" v-for="(com, index) in feedback">
                        <th>
                          <p v-html="com.msg">
                          </p>
                        </th>
                        <th>
                          {{ com.feedback_user ? com.feedback_user.name : "" }}
                        </th>
                        <th>
                          {{ format_Date(com.created_at) }}
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
    <modal width="60%" height="70%" style="padding:50px" name="mos-popup-singel">
      <i @click="mos_hide_pop()" class="bx bx-x-circle  x-circle"></i>
      <div class="app-content ">
        <div class="card">
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th colspan="4">{{ achievementItem.mos_name }}<strong>( W: {{ achievementItem.weightage }})</strong></th>
              </tr>
              <tr>
                <th>Achievement Value</th>
                <th>User Name </th>
                <th>Role</th>
                <th>Update Date</th>
              </tr>
              <tr v-for="item in achievementItem.achievement" :key="item.id">
                <td>{{ item[achievementItem.filter_month] }}</td>
                <td>{{ item.userjoin.name }}</td>
                <td>{{ item.userjoin.title }}</td>
                <td>{{ item.created_at | formatDate }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>

    <modal width="80%" height="90%" style="padding:50px" name="mos-rep-pop">
      <i @click="mos_rep_hide()" class="bx bx-x-circle  x-circle"></i>
      <div class="app-content ">
        <div class="card">
          <nav>
            <div id="nav-tab" role="tablist" class="nav nav-tabs">
              <a id="nav-home-tab" data-toggle="tab" @click='checkTab(1)' href="#nav-home" role="tab"
                aria-controls="nav-home" aria-selected="true" class="nav-item nav-link active">Share MOS Percentage</a>
              <a id="nav-home-tab" data-toggle="tab" @click='checkTab(2)' href="#nav-home" role="tab"
                aria-controls="nav-home" aria-selected="false" class="nav-item nav-link">Assign New MOS</a>
            </div>
          </nav>
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th class='text-center' colspan="3">
                  MOS Name: {{ rep_mos.mos_name }}
                  <strong>( W: {{ rep_mos.weightage }})</strong>
                </th>
                <th>
                  Share Weightage {{ rep_mos.share_per }}%
                </th>
              </tr>
              <tr v-if="checkTabList == 1">
                <th>Name</th>
                <th>MOS </th>
                <th>Weightage</th>
                <th colspan="2">Share(%)</th>
              </tr>
              <template v-if="checkTabList == 1" v-for="item in rep_item">
                <tr v-for="item_mos in item.mosjoin" :key="item.id">
                  <td>{{ item.employee.name }} - ({{ item.employee.employee_id }})</td>
                  <td>{{ item_mos.mos_name }}</td>
                  <td>{{ item_mos.weightage }}</td>
                  <td colspan="2"><input type='text' v-model='item_mos.rep_per' /></td>
                </tr>
              </template>
              <tr v-if="checkTabList == 1" class='text-right'>
                <td colspan='5'>
                  <button class='btn btn-success' @click='mos_per_share()'>Submit</button>
                </td>
              </tr>
              <tr v-if="checkTabList == 2">
                <td>Wings</td>
                <td>Employee</td>
                <td>KRA</td>
                <td>KPI</td>
              </tr>
              <tr v-if="checkTabList == 2" class='text-center'>
                <td>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="changeEmployee()" v-model="filterForm.wing_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in WingsItems" :key="row.id" :value="row.id">
                        {{ row.wing_title }}
                      </option>
                    </select>
                  </fieldset>
                </td>
                <td>
                  <fieldset class="form-group">
                    <Select2 placeholder="Select Employee" v-on:change="getKRA()" v-model="filterForm.user_id"
                      :options="employeeItem" />
                  </fieldset>
                </td>
                <td>
                  <fieldset class="form-group">
                    <div class="controls">
                      <select id="Profession" name="kra_id" v-on:change="getKpi_data(1)" v-model="filterForm.kra_id"
                        class="form-control">
                        <option value="">Select KRA</option>
                        <option v-for="row in emp_kra" :key="row.id" :value="row.id">
                          {{ row.kra_name }}
                        </option>
                      </select>
                    </div>
                  </fieldset>
                </td>
                <td>
                  <fieldset class="form-group">
                    <div class="controls">
                      <select id="Profession" name="kpi_id" v-on:change="getMos()" v-model="filterForm.kpi_id"
                        class="form-control">
                        <option value="">Select one</option>
                        <option v-for="row in kpiItem" :key="row.id" :value="row.id">
                          {{ row.kpi_name }}
                        </option>
                      </select>
                    </div>
                  </fieldset>
                </td>
              </tr>
              <tr v-if="checkTabList == 2">
                <td colspan="2">MOS Name</td>
                <td colspan="2">Share Percentage</td>
              </tr>
              <tr v-if="checkTabList == 2" v-for="item_mos in mos_ary" :key="item_mos.id">
                <td colspan="2">{{ item_mos.mos_name }} - {{ item_mos.weightage }} </td>
                <td colspan="2" class="text-center"><input type='text' v-model='item_mos.rep_per' /></td>
              </tr>
              <tr>
                <td v-if="checkTabList == 2" colspan="4" class="text-right">
                  <button class='btn btn-success' @click='assign_mos()'>Submit</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>
    <modal width="75%" height="80%" style="padding: 50px" name="achievement_request">
      <i @click="AchievementRequestHide()" class="bx bx-x-circle x-circle"></i>
      <div class="app-content">
        <h3>KRA KPI Achievement Entry request</h3>
        <div class="card">
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">TO Date</label>
                    <div class="controls">
                      <datepicker v-model="permission_mailForm.start_date" name="to_date" class="form-control">
                      </datepicker>
                    </div>
                  </div>
                </th>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">From Date</label>
                    <div class="controls">
                      <datepicker v-model="permission_mailForm.end_date" name="from_date" class="form-control">
                      </datepicker>
                    </div>
                  </div>
                </th>
                <th colspan="2" class="text-center">
                  <div class="form-group">
                    <label for="Profession">Months</label>
                    <div class="controls">
                      <multiselect v-model="permission_mailForm.select_months" :options="months" :multiple="true"
                        placeholder="Select(Months)" :label="'name'" track-by="id" :searchable="true"
                        :close-on-select="false" :show-labels="false">
                        <template slot="selection" slot-scope="{ values, isOpen }"><span class="multiselect__single"
                            v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options
                            selected</span></template>
                      </multiselect>
                    </div>
                  </div>
                </th>
              </tr>
              <tr>
                <th colspan="4" class="text-center">
                  <vue-editor v-model="permission_mailForm.content" name="note" placeholder="Note....">
                  </vue-editor>
                </th>
              </tr>
              <tr>
                <th colspan="2"></th>

                <th class="text-center">
                  <button @click="achiv_permission()" class="btn btn-success">
                    Send
                  </button>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>

    <modal width="60%" height="250px" style="padding:50px" name="mosPopup">
      <i @click="hiddenkpiUpload()" class="bx bx-x-circle  x-circle"></i>
      <div class="app-content ">
        <div class="card">
          <div class="col-sm-6">
            <a @click="demoDownload()" class="btn-block glow users-list-clear mb-0 download_template">
              Achievement Upload Format</a>
            <br>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <tbody>

              <tr>
                <th class="text-center">
                  <input type="file" accept=".xlsx" class="form-control" ref="file" @change="handleFileObject()" />
                </th>
                <th class="text-center">
                  <button @click="csvUpload()" class="btn btn-success">Save</button>
                </th>
              </tr>
            </tbody>
          </table>

        </div>

      </div>
    </modal>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import Datepicker from "vuejs-datepicker";
import axios from "../../axios_instance";
import { Form } from "vform";
import { VueEditor } from "vue2-editor";
import Select2 from 'v-select2-component';

// import Dropdown from 'vue-simple-search-dropdown';
export default {
  props: {},
  components: {
    'Select2': Select2,
    VueEditor,
    Multiselect,
    Datepicker,
    // Dropdown
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      search: '', 
      isOpen: false,
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: null,
      role_id: '',
      p_data: "",
      rep_item: [],
      rep_itemForm: new Form({
        item: [],
      }),
      item_mosForm: new Form({
        item: [],
        rep_mos: [],
        kpi_id: []
      }),
      mos_ary: [],
      emp_kra: [],
      checkTabList: 1,
      rep_mos: [],
      items: [],
      item: [],
      items_all: [],
      deptItems: [],
      WingsItems: [],
      employeeItem: [],
      templates: [],
      achievementItem: [],
      quarter: this.$route.query.quarter,
      month: this.$route.query.month,
      dept_id: this.$route.query.dept_id,
      kra_id: this.$route.query.kra_id,
      comment_active: 'comments',
      feedback: '',
      filterForm: new Form({
        dept_id: '',
        wing_id: "",
        user_id: "",
        kra_id: this.$route.query.kra_id ? this.$route.query.kra_id : '',
        kpi_id: '',
        quarter: this.$route.query.quarter ? this.$route.query.quarter : '',
        month: this.$route.query.month ? this.$route.query.month : '',
        show_kra: 1,
        show_kpi: 1,
        show_mos: 1,
        show_yachi: 1,
        show_zero: 1,
      }),
      search_box : false ,
      permission_mailForm: new Form({
        select_months: [],
        content: '',
        start_date: new Date().toISOString().slice(0, 10),
        end_date: new Date(new Date(new Date().getTime() + 86400000 * 2))
          .toISOString()
          .slice(0, 10),
      }),
      comment_mailForm: new Form({
        mailcc1: "",
        mailcc2: "",
        mailcc3: "",
        msg: "",
        mos_id: ""
      }),

      status: '',
      kraItem: [],
      kpiItem: [],
      mosItem: [],
 
    };
  },
  created() {
    if(this.$localStorage.get("user")){
      this.user_data = JSON.parse(this.$localStorage.get("user"));
    }else{ 
      this.loginWithToken();
    }
   
    //this.department_templates();
    this.role_id = this.user_data.role_id;
    this.filterForm.dept_id = this.user_data.dept_id;
    //if (this.role_id == 5 || this.role_id == 6 || this.role_id == 7) {
    if (this.filterForm.dept_id) {
      // this.getWing();
      
      this.filterForm.wing_id = this.user_data.wing_id ? this.user_data.wing_id : "";
    }
    if (
      this.role_id == 6 ||
      this.role_id == 7 ||
      this.role_id == 5) {
      this.filterForm.dept_id = this.user_data.dept_id;
      //   this.getEmployee();
      //   this.getKRA();
      //   this.getItems();
    }
    if (this.filterForm.kra_id) {
      this.getKpi();
    }
    this.deptChange();
    this.single_permission();
    this.getKRAItem();
    if(this.user_data.role_id == 1 || this.user_data.dept_id == 1  ||  this.user_data.dept_id == 46 ||  this.user_data.dept_id == 124 || this.user_data.dept_id == 6 || this.user_data.dept_id == 40 || this.user_data.dept_id == 41  || this.user_data.dept_id == 42   ){
      this.search_box = true  ;
      
    }else{
      this.getEmployee();
      this.search_box = false ;

    }
  },
  methods: {
 
    async loginWithToken(){ 
      var  token  = this.$route.query.key ;  

      console.log(token);
      let formData = new FormData();   
      formData.append("token", token);
      await axios.post(this.api_url + 'loginWithToken?', formData,
        { 
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            Authorization: token ? `Bearer ${token}`: ""
          },
        })
        .then(res => {
          if(res.data.status == 1){
            this.$toasted.show(res.data.message, {
              theme: "outline",
              duration: 5000,
              position: "top-right",
            }); 
            this.$localStorage.set("d_token", res.data.access_token);
            this.$localStorage.set("user", JSON.stringify(res.data.user)); 
           
            this.$router.push("/bpt_report_api"); 
            //this.$router.go("/home/l");
          }
        })
      
    },
    AchievementRequest() {
      this.$modal.show("achievement_request");
    },
    AchievementRequestHide() {
      this.$modal.hide("achievement_request");
    },
    async demoDownload() {
      let formData = new FormData();
      formData.append("user_id", this.user_data.id);
      formData.append("year", this.year);
      await axios.post(this.api_url + 'achiv_download_mos_file_format', formData,
        {
          responseType: 'arraybuffer',
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        })
        .then(response => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
          fileLink.href = fileURL;
          fileLink.setAttribute('download', 'Achievement_format.xlsx');
          document.body.appendChild(fileLink);
          fileLink.click();
        })
    },
    csvFile() {
      let file = event.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (event) => {
        this.csvform.csv = event.target.result;
      };
    },
    csvUpload() {
      let formData = new FormData();
      formData.append("csvFile", this.csv);
      let loader = this.$loading.show();
      axios
        .post(this.api_url + "achiv-upload-csv", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          console.log(res.data.message);
          loader.hide();
          this.hiddenkpiUpload();
          this.$modal.hide('file-upload');
          this.$swal({
            title: res.data.message,
            icon: "success",
          });
          this.getItems();
        },
          (err) => {
            loader.hide();
          });


    },
    handleFileObject() {
      this.csv = this.$refs.file.files[0];
      console.log(this.csv);
      this.csvName = this.csv.name;
    },
    achiv_permission() {
      this.permission_mailForm
        .post(this.api_url + "mos_achievement_permission", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(
          (res) => {
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }
            this.AchievementRequestHide();
            this.item_selects = [];

            loader.hide();
            this.AchievementRequestHide();
          },
          (error) => {
            loader.hide();
          }
        );
    },
    kpiUpload() {
      this.$modal.show("mosPopup");
    },
    hiddenkpiUpload() {
      this.$modal.hide("mosPopup");
    },
    mos_hide_pop() {
      this.$modal.hide("mos-popup-singel");
    },
    mos_show_pop(item, month) {
      axios
        .get(this.api_url + "get_achievement?id=" + item.id + '&month=' + month, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {

          this.achievementItem = res.data.data;

        });
      this.$modal.show("mos-popup-singel");
    },
    mos_rep_pop(item) {
      if (this.role_id == 5 || this.role_id == 6) {
        axios
          .get(this.api_url + "get_rep_mos?id=" + item.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then((res) => {
            this.rep_item = res.data.data;
            this.rep_mos = item;
            // console.log('item' , this.rep_item);     
          });
        this.$modal.show("mos-rep-pop");
      }
      // console.log('item',item);
    },
    mos_rep_hide() {
      this.checkTabList = 1;
      this.$modal.hide("mos-rep-pop");
    },
    mos_per_share() {
      let loader = this.$loading.show();
      this.rep_itemForm.item = this.rep_item;
      this.rep_itemForm
        .post(this.api_url + "submit_rep_mos", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          // console.log(res);
          loader.hide();
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
          this.getItems(true);
          this.$modal.hide("mos-rep-pop");
        });
    },
    single_permission() {
      axios
        .get(this.api_url + "single_permission", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          this.p_data = res.data.data[0];
        });
    },
    countRow(index, item) {
      if (this.filterForm.month) {
        return '';
      }
    },
    monthChange() {
      this.items = this.items_all;
      if (this.filterForm.show_zero) {
        return this.items;
      } else {
        let a = this.items.filter(item => {
          //for (let index = 0; index < this.items.length; index++) {
          let target = item.mostargetjoin;
          //this.items.slice(0, index);
          let total = 0;
          if (item.mostargetjoin) {
            let q1;
            let q2;
            let q3;
            let q4;
            let q5;
            let q6;

            q1 = target.january + target.february + target.march;
            q2 = target.april + target.may + target.june;
            q3 = target.july + target.august + target.september;
            q4 = target.october + target.november + target.december;
            q5 = q1 + q2;
            q6 = q3 + q4;

            if (this.filterForm.month == '') {
              if (this.filterForm.quarter == 1) {
                total = q1;
              } else if (this.filterForm.quarter == 2) {
                total = q2;
              } else if (this.filterForm.quarter == 3) {
                total = q3;
              } else if (this.filterForm.quarter == 4) {
                total = q4;
              } else if (this.filterForm.quarter == 5) {
                total = q5;
              } else if (this.filterForm.quarter == 6) {
                total = q6;
              }
            } else {
              if (this.filterForm.month == 'jan') {
                total = target.january;
              } else if (this.filterForm.month == 'feb') {
                total = target.february;
              } else if (this.filterForm.month == 'mar') {
                total = target.march;
              } else if (this.filterForm.month == 'apr') {
                total = target.april;
              } else if (this.filterForm.month == 'may') {
                total = target.may;
              } else if (this.filterForm.month == 'jun') {
                total = target.june;
              } else if (this.filterForm.month == 'jul') {
                total = target.july;
              } else if (this.filterForm.month == 'aug') {
                total = target.august;
              } else if (this.filterForm.month == 'sep') {
                total = target.september;
              } else if (this.filterForm.month == 'oct') {
                total = target.october;
              } else if (this.filterForm.month == 'nov') {
                total = target.november;
              } else if (this.filterForm.month == 'dec') {
                total = target.december;
              } else {
                total = target.total;
              }

            }
          }


          return total > 0;
        });
        this.items = a;
      }
    },
    rowVisible(index, item, type) {
      let crount = 0;
      this.items.filter(row => {
        if (type == 'kra') {
          if (row.kra_id === item.kra_id) {
            crount += 1;
          }
        } else if (type == 'kpi') {
          if (row.kpi_id === item.kpi_id) {
            crount += 1;
          }
        }

      })
      return crount;
    },
    achievementTotal(item, target, achievement) {
      if (target > 0 && achievement > 0) {

        if (item.mos_calculation == 0) {
          return ((achievement / target) * 100).toFixed();
        } else if (item.mos_calculation == 1) {

          return ((target / achievement) * 100).toFixed(2);

        } else if (item.mos_calculation == 2) {

          return ((achievement / target) * 100).toFixed(2);
        } else if (item.mos_calculation == 3) {

          return ((target / achievement) * 100).toFixed(2);
        } else {
          return ((achievement / target) * 100).toFixed(2);
        }

      } else {
        return 0;
      }
    },
    achievement(item, month) {
      if (item.mostargetjoin) {
        let target = item.mostargetjoin[month];
        if (item.mosachievementjoin) {
          let achievement = item.mosachievementjoin[month];
          if (target > 0 && achievement > 0) {
            if (item.mos_calculation == 0) {
              return ((achievement / target) * 100).toFixed();
            } else if (item.mos_calculation == 1) {

              return ((target / achievement) * 100).toFixed(2);

            } else if (item.mos_calculation == 2) {

              return ((achievement / target) * 100).toFixed(2);
            } else if (item.mos_calculation == 3) {

              return ((target / achievement) * 100).toFixed(2);
            } else {
              return ((achievement / target) * 100).toFixed(2);
            }
          } else {
            return 0;
          }
        } else {
          return 0;
        }
      } else {
        return 0;
      }
    },
    colorCheck(month_id) {
      const currentTime = new Date();
      const currentYear = currentTime.getFullYear();
      const currentMonth = currentTime.getMonth() + 1;

      if (month_id < 7) {
        if (currentYear > this.year && month_id < currentMonth) {
          return 'red';
        }
      } else if (currentYear > this.year || month_id < currentMonth) {
        return 'red';
      }

      return false;
    },
    mosTotalTarget(item, type) {
      let g_total = 0;
      //this.items =  this.items_all ;
      //this.items.filter(item => {
      let total = 0;
      let target;

      if (type == 'target') {
        if (item.mostargetjoin) {
          target = item.mostargetjoin;
        } else {
          return 0;
        }

      } else if (type == 'achievement') {
        if (item.mosachievementjoin) {
          target = item.mosachievementjoin;
        } else {
          return 0;
        }

      }

      let q1;
      let q2;
      let q3;
      let q4;
      let q5;
      let q6;
      q1 = target.january + target.february + target.march;
      q2 = target.april + target.may + target.june;
      q3 = target.july + target.august + target.september;
      q4 = target.october + target.november + target.december;
      q5 = q1 + q2;
      q6 = q3 + q4;
      if (this.filterForm.month == '') {
        if (this.filterForm.quarter == 1) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q1;
          } else {
            total = q1 / 3;
          }
        } else if (this.filterForm.quarter == 2) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q2;
          } else {
            total = q2 / 3;
          }
        } else if (this.filterForm.quarter == 3) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q3;
          } else {
            total = q3 / 3;
          }
        } else if (this.filterForm.quarter == 4) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q4;
          } else {
            total = q4 / 3;
          }
        } else if (this.filterForm.quarter == 5) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q5;
          } else {
            total = q5 / 6;
          }

        } else if (this.filterForm.quarter == 6) {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = q6;
          } else {
            total = q6 / 6;
          }
        } else {
          if (item.mos_calculation == 0 || item.mos_calculation == 1) {
            total = (q1 + q2 + q3 + q4);
          } else {
            total = (q1 + q2 + q3 + q4) / 12;
          }

          // total =  q1 + q2 + q3+ q4 ;
        }
      } else {


        if (this.filterForm.month == 'jan') {
          total = target.january;
        } else if (this.filterForm.month == 'feb') {
          total = target.february;
        } else if (this.filterForm.month == 'mar') {
          total = target.march;
        } else if (this.filterForm.month == 'apr') {
          total = target.april;
        } else if (this.filterForm.month == 'may') {
          total = target.may;
        } else if (this.filterForm.month == 'jun') {
          total = target.june;
        } else if (this.filterForm.month == 'jul') {
          total = target.july;
        } else if (this.filterForm.month == 'aug') {
          total = target.august;
        } else if (this.filterForm.month == 'sep') {
          total = target.september;
        } else if (this.filterForm.month == 'oct') {
          total = target.october;
        } else if (this.filterForm.month == 'nov') {
          total = target.november;
        } else if (this.filterForm.month == 'dec') {
          total = target.december;
        }
        // return total > 0 ;
      }
      g_total += total;
      //}
      //});
      return this.amountConvert(g_total, 2);
      //return g_total ;
    },
    kpiTotalTarget(kpi_id, type) {
      let g_total = 0;
      //this.items =  this.items_all ;
      this.items.filter(item => {
        if (item.mostargetjoin && item.mosachievementjoin) {
          let total = 0;
          let target;
          if (item.kpi_id == kpi_id) {
            if (type == 'target') {
              target = item.mostargetjoin;
            } else if (type == 'achievement') {
              target = item.mosachievementjoin;
            }

            let q1;
            let q2;
            let q3;
            let q4;
            let q5;
            let q6;
            q1 = (target ? target.january : 0) + (target ? target.february : 0) + (target ? target.march : 0);
            q2 = (target ? target.april : 0) + (target ? target.may : 0) + (target ? target.june : 0);
            q3 = (target ? target.july : 0) + (target ? target.august : 0) + (target ? target.september : 0);
            q4 = (target ? target.october : 0) + (target ? target.november : 0) + (target ? target.december : 0);
            q5 = q1 + q2;
            q6 = q3 + q4;
            if (this.filterForm.month == '') {
              if (this.filterForm.quarter == 1) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1;
                } else {
                  total = q1 / 3;
                }
              } else if (this.filterForm.quarter == 2) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q2;
                } else {
                  total = q2 / 3;
                }
              } else if (this.filterForm.quarter == 3) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q3;
                } else {
                  total = q3 / 3;
                }
              } else if (this.filterForm.quarter == 4) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q4;
                } else {
                  total = q4 / 3;
                }
              } else if (this.filterForm.quarter == 5) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q5;
                } else {
                  total = q5 / 6;
                }

              } else if (this.filterForm.quarter == 6) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q6;
                } else {
                  total = q6 / 6;
                }
              } else {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = (q1 + q2 + q3 + q4);
                } else {
                  total = (q1 + q2 + q3 + q4) / 12;
                }

                // total =  q1 + q2 + q3+ q4 ;
              }
            } else {


              if (this.filterForm.month == 'jan') {
                total = target.january;
              } else if (this.filterForm.month == 'feb') {
                total = target.february;
              } else if (this.filterForm.month == 'mar') {
                total = target.march;
              } else if (this.filterForm.month == 'apr') {
                total = target.april;
              } else if (this.filterForm.month == 'may') {
                total = target.may;
              } else if (this.filterForm.month == 'jun') {
                total = target.june;
              } else if (this.filterForm.month == 'jul') {
                total = target.july;
              } else if (this.filterForm.month == 'aug') {
                total = target.august;
              } else if (this.filterForm.month == 'sep') {
                total = target.september;
              } else if (this.filterForm.month == 'oct') {
                total = target.october;
              } else if (this.filterForm.month == 'nov') {
                total = target.november;
              } else if (this.filterForm.month == 'dec') {
                total = target.december;
              }
              // return total > 0 ;
            }
            g_total += total;
          }
        }
      });
      return this.amountConvert(g_total, 2);
      //return g_total ;
    },
    kraTotalTarget(kra_id, type) {
      let g_total = 0;
      //this.items =  this.items_all ;

      this.items.filter(item => {
        let total = 0;
        if (item.mostargetjoin && item.mosachievementjoin) {
          let target;
          if (item.kra_id == kra_id) {
            if (type == 'target') {
              target = item.mostargetjoin;
            } else if (type == 'achievement') {
              target = item.mosachievementjoin;
            }

            let q1 = 0;
            let q2 = 0;
            let q3 = 0;
            let q4 = 0;
            let q5 = 0;
            let q6 = 0;
            if (target) {
              q1 = (target ? target.january : 0) + (target ? target.february : 0) + (target ? target.march : 0);
              q2 = (target ? target.april : 0) + (target ? target.may : 0) + (target ? target.june : 0);
              q3 = (target ? target.july : 0) + (target ? target.august : 0) + (target ? target.september : 0);
              q4 = (target ? target.october : 0) + (target ? target.november : 0) + (target ? target.december : 0);
            }
            q5 = q1 + q2;
            q6 = q3 + q4;
            if (this.filterForm.month == '') {
              if (this.filterForm.quarter == 1) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1;
                } else {
                  total = q1 / 3;
                }
              } else if (this.filterForm.quarter == 2) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q2;
                } else {
                  total = q2 / 3;
                }
              } else if (this.filterForm.quarter == 3) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q3;
                } else {
                  total = q3 / 3;
                }
              } else if (this.filterForm.quarter == 4) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q4;
                } else {
                  total = q4 / 3;
                }
              } else if (this.filterForm.quarter == 5) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q5;
                } else {
                  total = q5 / 6;
                }

              } else if (this.filterForm.quarter == 6) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q6;
                } else {
                  total = q6 / 6;
                }
              } else {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = (q1 + q2 + q3 + q4);
                } else {
                  total = (q1 + q2 + q3 + q4) / 12;
                }

                // total =  q1 + q2 + q3+ q4 ;
              }
            } else {


              if (this.filterForm.month == 'jan') {
                total = target.january;
              } else if (this.filterForm.month == 'feb') {
                total = target.february;
              } else if (this.filterForm.month == 'mar') {
                total = target.march;
              } else if (this.filterForm.month == 'apr') {
                total = target.april;
              } else if (this.filterForm.month == 'may') {
                total = target.may;
              } else if (this.filterForm.month == 'jun') {
                total = target.june;
              } else if (this.filterForm.month == 'jul') {
                total = target.july;
              } else if (this.filterForm.month == 'aug') {
                total = target.august;
              } else if (this.filterForm.month == 'sep') {
                total = target.september;
              } else if (this.filterForm.month == 'oct') {
                total = target.october;
              } else if (this.filterForm.month == 'nov') {
                total = target.november;
              } else if (this.filterForm.month == 'dec') {
                total = target.december;
              };
              // return total > 0 ;
            }

            g_total += total;
          }
        }
      });
      return this.amountConvert(g_total, 2);
      // return g_total ;
    },
    targetTotal(item) {

      let total = 0;
      if (item.mostargetjoin) {
        let target = item.mostargetjoin;
        let q1;
        let q2;
        let q3;
        let q4;
        let q5;
        let q6;
        q1 = target.january + target.february + target.march;
        q2 = target.april + target.may + target.june;
        q3 = target.july + target.august + target.september;
        q4 = target.october + target.november + target.december;
        q5 = q1 + q2;
        q6 = q3 + q4;
        if (this.filterForm.month == '') {
          if (this.filterForm.quarter == 1) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1;
            } else {
              total = q1 / 3;
            }
          } else if (this.filterForm.quarter == 2) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q2;
            } else {
              total = q2 / 3;
            }
          } else if (this.filterForm.quarter == 3) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q3;
            } else {
              total = q3 / 3;
            }
          } else if (this.filterForm.quarter == 4) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q4;
            } else {
              total = q4 / 3;
            }
          } else if (this.filterForm.quarter == 5) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q5;
            } else {
              total = q5 / 6;
            }

          } else if (this.filterForm.quarter == 6) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q6;
            } else {
              total = q6 / 6;
            }
          } else {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = (q1 + q2 + q3 + q4);
            } else {
              total = (q1 + q2 + q3 + q4) / 12;
            }

            // total =  q1 + q2 + q3+ q4 ;
          }
        } else {
          if (this.filterForm.month == 'jan') {
            total = target.january;
          } else if (this.filterForm.month == 'feb') {
            total = target.february;
          } else if (this.filterForm.month == 'mar') {
            total = target.march;
          } else if (this.filterForm.month == 'apr') {
            total = target.april;
          } else if (this.filterForm.month == 'may') {
            total = target.may;
          } else if (this.filterForm.month == 'jun') {
            total = target.june;
          } else if (this.filterForm.month == 'jul') {
            total = target.july;
          } else if (this.filterForm.month == 'aug') {
            total = target.august;
          } else if (this.filterForm.month == 'sep') {
            total = target.september;
          } else if (this.filterForm.month == 'oct') {
            total = target.october;
          } else if (this.filterForm.month == 'nov') {
            total = target.november;
          } else if (this.filterForm.month == 'dec') {
            total = target.december;
          } else {
            total = 0;
          }
        }
      }
      return total;
    },
    moduleTotal(item) {
      if (item.mosmodulejoin) {
        let module = item.mosmodulejoin;
        let total = 0;
        let q1;
        let q2;
        let q3;
        let q4;
        let q5;
        let q6;
        q1 = module.january + module.february + module.march;
        q2 = module.april + module.may + module.june;
        q3 = module.july + module.august + module.september;
        q4 = module.october + module.november + module.december;
        q5 = q1 + q2;
        q6 = q3 + q4;
        if (this.filterForm.month == '') {
          if (this.filterForm.quarter == 1) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1;
            } else {
              total = q1 / 3;
            }
          } else if (this.filterForm.quarter == 2) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q2;
            } else {
              total = q2 / 3;
            }
          } else if (this.filterForm.quarter == 3) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q3;
            } else {
              total = q3 / 3;
            }
          } else if (this.filterForm.quarter == 4) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q4;
            } else {
              total = q4 / 3;
            }
          } else if (this.filterForm.quarter == 5) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q5;
            } else {
              total = q5 / 6;
            }

          } else if (this.filterForm.quarter == 6) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q6;
            } else {
              total = q6 / 6;
            }
          } else {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = (q1 + q2 + q3 + q4);
            } else {
              total = (q1 + q2 + q3 + q4) / 12;
            }

            // total =  q1 + q2 + q3+ q4 ;
          }
        } else {
          if (this.filterForm.month == 'jan') {
            total = module.january;
          } else if (this.filterForm.month == 'feb') {
            total = module.february;
          } else if (this.filterForm.month == 'mar') {
            total = module.march;
          } else if (this.filterForm.month == 'apr') {
            total = module.april;
          } else if (this.filterForm.month == 'may') {
            total = module.may;
          } else if (this.filterForm.month == 'jun') {
            total = module.june;
          } else if (this.filterForm.month == 'jul') {
            total = module.july;
          } else if (this.filterForm.month == 'aug') {
            total = module.august;
          } else if (this.filterForm.month == 'sep') {
            total = module.september;
          } else if (this.filterForm.month == 'oct') {
            total = module.october;
          } else if (this.filterForm.month == 'nov') {
            total = module.november;
          } else if (this.filterForm.month == 'dec') {
            total = module.december;
          } else {
            total = 0;
          }
        }
        return total;
      }
    },
    achievementjoinTotal(item) {
      if (item.mosachievementjoin) {
        let achievement = item.mosachievementjoin;
        let total = 0;
        let q1;
        let q2;
        let q3;
        let q4;
        let q5;
        let q6;
        q1 = (achievement ? achievement.january : 0) + (achievement ? achievement.february : 0) + (achievement ? achievement.march : 0);


        q2 = (achievement ? achievement.april : 0) + (achievement ? achievement.may : 0) + (achievement ? achievement.june : 0);
        q3 = (achievement ? achievement.july : 0) + (achievement ? achievement.august : 0) + (achievement ? achievement.september : 0);
        q4 = (achievement ? achievement.october : 0) + (achievement ? achievement.november : 0) + (achievement ? achievement.december : 0);
        q5 = q1 + q2;
        q6 = q3 + q4;
        if (this.filterForm.month == '') {
          if (this.filterForm.quarter == 1) {
            // total =  q1 ;
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1;
            } else {
              total = q1 / 3;
            }
          } else if (this.filterForm.quarter == 2) {
            // total = q2 ;
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q2;
            } else {
              total = q2 / 3;
            }
          } else if (this.filterForm.quarter == 3) {
            // total =  q3;
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q3;
            } else {
              total = q3 / 3;
            }
          } else if (this.filterForm.quarter == 4) {
            // total = q4 ;
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q4;
            } else {
              total = q4 / 3;
            }
          } else if (this.filterForm.quarter == 5) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q5;
            } else {
              total = q5 / 6;
            }
            // if(item.mos_calculation == 1 || item.mos_calculation == 2 || item.mos_calculation == 3){
            //     total =  q5/6;
            // }else{
            //     total =  q5;
            // }

          } else if (this.filterForm.quarter == 6) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q6;
            } else {
              total = q6 / 6;
            }
            // if(item.mos_calculation == 1 || item.mos_calculation == 2 || item.mos_calculation == 3){
            //     total =  q6/6;
            // }else{
            //     total =  q6;
            // }

          } else {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = (q1 + q2 + q3 + q4);
            } else {
              total = (q1 + q2 + q3 + q4) / 12;
            }
          }
        } else {
          if (this.filterForm.month == 'jan') {
            total = achievement.january;
          } else if (this.filterForm.month == 'feb') {
            total = achievement.february;
          } else if (this.filterForm.month == 'mar') {
            total = achievement.march;
          } else if (this.filterForm.month == 'apr') {
            total = achievement.april;
          } else if (this.filterForm.month == 'may') {
            total = achievement.may;
          } else if (this.filterForm.month == 'jun') {
            total = achievement.june;
          } else if (this.filterForm.month == 'jul') {
            total = achievement.july;
          } else if (this.filterForm.month == 'aug') {
            total = achievement.august;
          } else if (this.filterForm.month == 'sep') {
            total = achievement.september;
          } else if (this.filterForm.month == 'oct') {
            total = achievement.october;
          } else if (this.filterForm.month == 'nov') {
            total = achievement.november;
          } else if (this.filterForm.month == 'dec') {
            total = achievement.december;
          } else {
            total = 0;
          }
        }
        return total // total;
      } else {
        return 0;
      }
    },
    select_months(mo) {
      //const d = new Date();
      // if (this.year == 2024) {//Note: This condition is for after 2023 jun
      //   if (this.filterForm.month != '') {
      //     if (this.filterForm.month == mo) {
      //       return true;
      //     } else {
      //       return false;
      //     }
      //   } else {
      //     if (this.filterForm.quarter != '') {
      //       if (this.filterForm.quarter == 1 && (mo == 'jul' || mo == 'aug' || mo == 'sep')) {
      //         return true;
      //       } else if (this.filterForm.quarter == 2 && (mo == 'oct' || mo == 'nov' || mo == 'dec')) {
      //         return true;
      //       } else if (this.filterForm.quarter == 3 && (mo == 'jan' || mo == 'feb' || mo == 'mar')) {
      //         return true;
      //       } else if (this.filterForm.quarter == 4 && (mo == 'apr' || mo == 'may' || mo == 'jun')) {
      //         return true;
      //       } else if (this.filterForm.quarter == 5 && (mo == 'jul' || mo == 'aug' || mo == 'sep' || mo == 'oct' || mo == 'nov' || mo == 'dec')) {
      //         return false;
      //       } else if (this.filterForm.quarter == 6 && (mo == 'jan' || mo == 'feb' || mo == 'mar' || mo == 'apr' || mo == 'may' || mo == 'jun')) {
      //         return false;
      //       } else {
      //         return false;
      //       }
      //     } else {
      //       return true
      //     }
      //   }
      // } else {//Note: This condition is for before 2023 jun
        if (this.filterForm.month != '') {
          if (this.filterForm.month == mo) {
            return true;
          } else {
            return false;
          }
        } else {
          if (this.filterForm.quarter != '') {
            if (this.filterForm.quarter == 1 && (mo == 'jan' || mo == 'feb' || mo == 'mar')) {
              return true;
            } else if (this.filterForm.quarter == 2 && (mo == 'apr' || mo == 'may' || mo == 'jun')) {
              return true;
            } else if (this.filterForm.quarter == 3 && (mo == 'jul' || mo == 'aug' || mo == 'sep')) {
              return true;
            } else if (this.filterForm.quarter == 4 && (mo == 'oct' || mo == 'nov' || mo == 'dec')) {
              return true;
            } else if (this.filterForm.quarter == 5 && (mo == 'jan' || mo == 'feb' || mo == 'mar' || mo == 'apr' || mo == 'may' || mo == 'jun')) {
              return false;
            } else if (this.filterForm.quarter == 6 && (mo == 'jul' || mo == 'aug' || mo == 'sep' || mo == 'oct' || mo == 'nov' || mo == 'dec')) {
              return false;
            } else {
              return false;
            }
          } else {
            return true
          }
        }
     // }


    },
    checkConditionKra(length, kpi_index, mos_index) {
      if (kpi_index == 0 && mos_index == 0) {
        return true;
      } else {
        return false;
      }
    },
    checkConditionKpi(length, mos_index) {
      if (mos_index == 0) {
        return true;
      } else {
        return false;
      }
    },
    // async getWing() {
    //   await axios.get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
    //     headers: {
    //       "Content-Type": "application/json",
    //       Authorization: this.token ? `Bearer ${this.token}` : ""
    //     },
    //   }).then(({ data }) => {
    //     //this.getEmployee();
    //     this.WingsItems = data.data;
    //   });
    // },
    async changeEmployee(id) { 
      this.filterForm.user_id =  id; 
      this.isOpen = false; 
      this.getItems(true);
    },
    getKrsData() {
      let where = '?1=1';
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id;
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }
      if (this.filterForm.user_id) {
        where += '&user_id=' + this.filterForm.user_id;
      }
      axios.get(this.api_url + "users_kra" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.emp_kra = data.data;
        });
    },
    async getEmployee() {
      //if(this.filterForm.wing_id){
      let where = '?limit=100';
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id;
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }
      if(this.search){
        where += '&search_key=' + this.search;
      }
      await axios.get(this.api_url + "users" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.employeeItem = data.data;
        });
      //}

      this.isOpen = true;

    },
    async deptChange() {
      this.getDept();
      //this.getWing();
      this.getItems(true);
    },
    async getDept() {
      //let loader = this.$loading.show();
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          // loader.hide();
          this.deptItems = data.data;
        } else {
          //loader.hide();
        }
      });
    },
    async getKRA() {
      this.getKRAItem();
      this.getItems();
      this.getWing();
      this.getKrsData();
    },
    async getKRAItem() {
      let where = '?year=' +  this.year ;
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id;
      }
      if (this.filterForm.user_id) {
        where += '&user_id=' + this.filterForm.user_id;
      }
      await axios.get(this.api_url + "k_r_a_s?" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.kraItem = data.data;
        });

    },
    getKpi() {
      this.getKpi_data();
      this.getItems();
    },
    async getKpi_data(type = 0) {
      let where = '?1=1';
      if (this.filterForm.kra_id) {
        where += '&kra_id=' + this.filterForm.kra_id;
      }
      if (type) {
        where += '&type=' + type;
      }
      await axios.get(this.api_url + "k_p_i_s" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {

          this.kpiItem = data.data;
        });
    },
    async getItems(load = false) {
      if (this.filterForm.dept_id || this.user_data.dept_id) {
        let where = '?year=' + this.year;
        if (this.filterForm.dept_id) {
          where += '&dept_id=' + (this.filterForm.dept_id ? this.filterForm.dept_id : this.user_data.dept_id);
        }
        if (this.filterForm.kra_id) {
          where += '&kra_id=' + this.filterForm.kra_id;
        }
        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id;
        }
        if (this.filterForm.user_id) {
          where += '&user_id=' + this.filterForm.user_id;
        }
        if (this.filterForm.kpi_id) {
          where += '&kpi_id=' + this.filterForm.kpi_id;
        }
        let loader;
        if (load) { loader = this.$loading.show(); }

        try {
          await axios
            .get(this.api_url + "kra_kpi_mos_list" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
              },
            })
            .then(({
              data
            }) => {
              if (data.success) {
                this.items_all = data.data;
                this.items = data.data;
                this.items.filter(function (item, index) {
                  item['share_per'] = 0;
                  let total = 0;
                  if (item.working_memberJoin) {
                    item.working_memberJoin.filter(function (row, rIndex) {
                      total += row['rep_per'];
                    });
                    item['share_per'] = total;
                  }
                });
              }
              if (load) { loader.hide(); }
            });
        } catch (error) {
          if (load) { loader.hide(); }
        }
      }
    },
    // Note: Department templates 
    department_templates() {
    },
    //SHOW COMMENT MODAL
    comment_show(item) {
      this.item = item;
      //GET COMMENTS
      axios.get(this.api_url + "mos_feadbacks?mos_id=" + item.id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({
          data
        }) => {
          if (data.success) {
            this.feedback = data.data;
          }
        });

      this.$modal.show("comment");
    },
    tabs(i) {
      this.comment_active = i;
    },
    //HIDE COMMENT MODAL
    comment_hidden() {
      this.$modal.hide("comment");
    },
    //MONTHLY REPORT MOS FEEDBACK
    task_comment() {
      try {
        let loader = this.$loading.show();
        this.comment_mailForm.mos_id = this.item.id;
        this.comment_mailForm.dept_id = this.item.dept_id;
        this.comment_mailForm.fmonth = this.filterForm.month;

        this.comment_mailForm.post(this.api_url + "mos_feadbacks", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          if (res.data.success) {
            this.comment_hidden();
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
          loader.hide();
          // this.$router.push('/daily_work');
        }, (error) => {

          loader.hide();
        })
      } catch (error) {
        // loader.hide();

      }
    },
    wings_notification() {
      try {
        let loader = this.$loading.show();
        this.filterForm.post(this.api_url + "achivement_notification", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });

          }
          loader.hide();
          // this.$router.push('/daily_work');
        }, (error) => {

          loader.hide();
        })
      } catch (error) {
        // loader.hide();

      }
    },
    sharePercentage(items) {
      let sum = 0;
      items.filter(function (item, index) {
        sum += parseInt(item['rep_per']);
      })
      return sum;
    },
    checkTab(val) {
      this.checkTabList = val;
    },
    getMos() {
      // m_o_s
      let where = '?year=' +  this.year ;
      if (this.filterForm.kra_id) {
        where += '&kra_id=' + this.filterForm.kra_id;
      }
      if (this.filterForm.user_id) {
        where += '&user_id=' + this.filterForm.user_id;
      }
      if (this.filterForm.kpi_id) {
        where += '&kpi_id=' + this.filterForm.kpi_id;
      }
      axios
        .get(this.api_url + "m_o_s" + where, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        })
        .then(({
          data
        }) => {
          if (data.success) {
            this.mos_ary = data.data;
          }
        });

    },
    assign_mos() {
      this.item_mosForm.item = this.mos_ary;
      this.item_mosForm.rep_mos = this.rep_mos;
      this.item_mosForm.kpi_id = this.filterForm.kpi_id;
      let loader = this.$loading.show();

      this.item_mosForm.post(this.api_url + "assign_mos_submit", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      }).then((res) => {
        if (res.data.success) {
          this.$toasted.show(res.data.message, {
            theme: "bubble",
            duration: 5000,
            position: "bottom-right",
          });
        }
        loader.hide();
        // this.$router.push('/daily_work');
      }, (error) => {

        loader.hide();
      })

    },
    working_memberJoinCal(item) {
      let nameAry = [];
      item.mos_working_memberJoin.filter((data, index) => {
        if (index == 0) {
          let name = data.krajoin.user_join.name
          let sum = 0;
          item.mos_working_memberJoin.filter((row, index) => {
            if (row.krajoin.user_join.id == data.krajoin.user_join.id) {
              sum += data.rep_per
            }
          });
          nameAry.push({ name: name, 'rep_per': sum })
        } else if (item.mos_working_memberJoin[index - 1].krajoin.user_join.id != data.krajoin.user_join.id) {
          let name = data.krajoin.user_join.name
          let sum = 0;
          item.mos_working_memberJoin.filter((row, index) => {
            if (row.krajoin.user_join.id == data.krajoin.user_join.id) {
              sum += data.rep_per
            }
          });
          nameAry.push({ name: name, 'rep_per': sum })
        }
      });
      return nameAry;
    },
    mosViewRelation(item) {
      let nameAry = [];
      item.working_memberJoin.filter((row, index) => {
        let name = row.krajoin.user_join.name;
        let sum = 0;
        row.mos.filter((singlerow, index) => {
          sum += singlerow.rep_per
        })
        nameAry.push({ name: name, 'rep_per': sum });
      });
      return nameAry;
    }
  },
  computed: {
     
  },
};
</script>
<style>
.color_red {
  color: red;
}

.gb_color_green {
  background-color: seagreen;
  color: seashell;
}

.gb_color_yellow {
  background-color: yellow;
  color: black;
}
 
  .autocomplete {
    position: relative;
  }

  .autocomplete-results {
    padding: 0;
    margin: 0;
    border: 1px solid #eeeeee; 
    position: absolute; 
    min-height: 1em;
    max-height: 6em;    
    overflow: auto;
    z-index: 99999;
    width: 100%;

  }

  .autocomplete-result {
    list-style: none;
    text-align: left;
    padding: 4px 2px;
    cursor: pointer;
    background: #fff;
    background-color: rgb(255, 255, 255);
    border-bottom: 1px solid #efefef;
    padding: 5px;
  }

  .autocomplete-result:hover {
    background-color: #4AAE9B;
    color: white;
  } 
</style>