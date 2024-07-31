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
                      <router-link :to="{ path: '/' }"
                        ><i class="bx bx-home-alt"></i
                      ></router-link>
                    </li>
                    <li class="breadcrumb-item active">Feedback</li>
                  </ol>
                </div>
              </div>
              <div class="col-sm-3">
                <router-link
                  class="btn btn-primary add-btn"
                  :to="{
                    path:
                      '/bpt_report?quarter=' +
                      quarter +
                      '&month=' +
                      month +
                      '&dept_id=' +
                      dept_id +
                      '&kra_id=' +
                      kra_id +
                      '&kpi_id=' +
                      id,
                  }"
                >
                  Back
                </router-link>
                <!-- <a class="btn btn-primary add-btn" @click="back()" > <i class="bx bx-add-alt"></i>Back</a> -->
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="table-responsive">
                        <table
                          class="table table-bordered table-striped table-sm"
                        >
                          <!-- <tbody>
                                            <tr>
                                                <th class="text-left" width="15%"><strong>KRA</strong></th>
                                                <th class="text-left"><strong>{{ mos_item.krajoin ? mos_item.krajoin.kra_name : '' }}</strong></th>
                                                <th class="text-left" width="15%"><strong>KRA WEIGHTAGE</strong></th>
                                                <th class="text-left"><strong>{{ mos_item.krajoin ? mos_item.krajoin.kra_weight : '' }}</strong></th>
                                            </tr>
                                            <tr>
                                                <th class="text-left"><strong>KPI</strong></th>
                                                <th class="text-left"><strong>{{mos_item.kpi_name}}</strong>
                                                </th>
                                                <th class="text-left"><strong>KPI WEIGHTAGE</strong></th>
                                                <th class="text-left"><strong>{{ mos_item.kpi_weight }}</strong></th>
                                            </tr>
                                        </tbody> -->
                        </table>
                        <table class="table table-bordered table-sm">
                          <!-- <template  v-for="mos_item  in item.mosjoin"> -->
                          <tr class="thead-dark" v-bind:key="mos_item.id">
                            <th colspan="26" class="text-center">
                              {{ mos_item.mos_name }}
                            </th>
                          </tr>

                          <tr :key="mos_item.id">
                            <td></td>
                            <td
                              v-if="month_off.jan"
                              colspan="2"
                              class="text-center"
                            >
                              Jan
                            </td>
                            <td
                              v-if="month_off.feb"
                              colspan="2"
                              class="text-center"
                            >
                              Feb
                            </td>
                            <td
                              v-if="month_off.mar"
                              colspan="2"
                              class="text-center"
                            >
                              Mar
                            </td>
                            <td
                              v-if="month_off.apr"
                              colspan="2"
                              class="text-center"
                            >
                              Apr
                            </td>
                            <td
                              v-if="month_off.may"
                              colspan="2"
                              class="text-center"
                            >
                              May
                            </td>
                            <td
                              v-if="month_off.jun"
                              colspan="2"
                              class="text-center"
                            >
                              Jun
                            </td>
                            <td
                              v-if="month_off.jul"
                              colspan="2"
                              class="text-center"
                            >
                              Jul
                            </td>
                            <td
                              v-if="month_off.aug"
                              colspan="2"
                              class="text-center"
                            >
                              Aug
                            </td>
                            <td
                              v-if="month_off.sep"
                              colspan="2"
                              class="text-center"
                            >
                              Sep
                            </td>
                            <td
                              v-if="month_off.oct"
                              colspan="2"
                              class="text-center"
                            >
                              Oct
                            </td>
                            <td
                              v-if="month_off.nov"
                              colspan="2"
                              class="text-center"
                            >
                              Nov
                            </td>
                            <td
                              v-if="month_off.dec"
                              colspan="2"
                              class="text-center"
                            >
                              Dec
                            </td>
                          </tr>
                          <tr :key="mos_item.id">
                            <td></td>
                            <td v-if="month_off.jan">Target</td>
                            <td v-if="month_off.jan">Achievement</td>
                            <td v-if="month_off.feb">Target</td>
                            <td v-if="month_off.feb">Achievement</td>
                            <td v-if="month_off.mar">Target</td>
                            <td v-if="month_off.mar">Achievement</td>
                            <td v-if="month_off.apr">Target</td>
                            <td v-if="month_off.apr">Achievement</td>
                            <td v-if="month_off.may">Target</td>
                            <td v-if="month_off.may">Achievement</td>
                            <td v-if="month_off.jun">Target</td>
                            <td v-if="month_off.jun">Achievement</td>
                            <td v-if="month_off.jul">Target</td>
                            <td v-if="month_off.jul">Achievement</td>
                            <td v-if="month_off.aug">Target</td>
                            <td v-if="month_off.aug">Achievement</td>
                            <td v-if="month_off.sep">Target</td>
                            <td v-if="month_off.sep">Achievement</td>
                            <td v-if="month_off.oct">Target</td>
                            <td v-if="month_off.oct">Achievement</td>
                            <td v-if="month_off.nov">Target</td>
                            <td v-if="month_off.nov">Achievement</td>
                            <td v-if="month_off.dec">Target</td>
                            <td v-if="month_off.dec">Achievement</td>
                          </tr>
                          <tr :key="mos_item.id">
                            <td>
                              <label>{{ mos_item.mos_name }}</label>
                            </td>
                            <td v-if="month_off.jan">
                              <input type="text" 
                              class="form-control" 
                              @keypress="onlyNumber"
                              v-model="mos_item.mostargetjoin.january" />
                            </td>
                            <td v-if="month_off.jan">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.january"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['january_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.feb">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.february"
                                type="text"
                                class="form-control"
                              />                              
                            </td>
                            <td v-if="month_off.feb">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.february"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['february_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.mar">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.march"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.mar">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.march"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['march_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.apr">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.april"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.apr">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.april"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['april_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.may">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.may"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.may">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.may"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['may_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.jun">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.jun"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.jun">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.june"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['june_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.jul">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.july"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.jul">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.july"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['july_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.aug">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.august"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.aug">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.august"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['august_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.sep">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.september"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.sep">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.september"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['september_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.oct">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.october"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.oct">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.october"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['october_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.nov">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.november"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.nov">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.november"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['november_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                            <td v-if="month_off.dec">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.december"
                                type="text"
                                class="form-control"
                              />  
                            </td>
                            <td v-if="month_off.dec">
                              <input
                                @keypress="onlyNumber"
                                v-model="mos_item.mosachievementjoin.december"
                                type="text"
                                class="form-control"
                              />
                              <h5>Feedback</h5>
                              <textarea
                                v-model="feedback['december_' + mos_item.id]"
                                class="form-control"
                              ></textarea>
                            </td>
                          </tr>
                          <!-- month wise end user mos target and achievement -->
                          <tr :key="mos_item.id" v-if="mos_item.mos_kpi.length>0">
                            <td>
                              <label>MOS Target and Achievement</label>
                            </td>
                            <!-- january -->
                            <td v-if="month_off.jan" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.january}}</td>
                                        <td>{{user_mos.mosachievementjoin.january}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>
                            <!-- february -->
                            <td v-if="month_off.feb" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.february}}</td>
                                        <td>{{user_mos.mosachievementjoin.february}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- March  -->
                            <td v-if="month_off.mar" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.march}}</td>
                                        <td>{{user_mos.mosachievementjoin.march}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- April   -->
                            <td v-if="month_off.apr" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.april}}</td>
                                        <td>{{user_mos.mosachievementjoin.april}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>  
                            <!-- May  -->
                             <td v-if="month_off.may" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.may}}</td>
                                        <td>{{user_mos.mosachievementjoin.may}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- Jun -->
                             <td v-if="month_off.jun" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.jun}}</td>
                                        <td>{{user_mos.mosachievementjoin.jun}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>   
                            <!-- july -->
                             <td v-if="month_off.jul" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.july}}</td>
                                        <td>{{user_mos.mosachievementjoin.july}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- August -->
                             <td v-if="month_off.aug" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.august}}</td>
                                        <td>{{user_mos.mosachievementjoin.august}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>        
                            <!-- september -->
                             <td v-if="month_off.sep" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.september}}</td>
                                        <td>{{user_mos.mosachievementjoin.september}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- october -->   
                             <td v-if="month_off.oct" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.october}}</td>
                                        <td>{{user_mos.mosachievementjoin.october}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>     
                            <!-- november  --> 
                             <td v-if="month_off.nov" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.november}}</td>
                                        <td>{{user_mos.mosachievementjoin.november}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td> 
                            <!-- december-->
                             <td v-if="month_off.dec" colspan="2">
                                <span  v-for="user_kpi in mos_item.mos_kpi"
                                  :key="user_kpi.id">
                                    <table class="table table-bordered table-sm">
                                      <tr >
                                        <th>MOS</th>
                                        <th>Target</th>
                                        <th>Achievement</th>
                                      </tr>  
                                      <tr v-for="user_mos in user_kpi.mosjoin"
                                      :key="user_mos.id">
                                        <td>{{user_mos.mos_name}}</td>
                                        <td>{{user_mos.mostargetjoin.december}}</td>
                                        <td>{{user_mos.mosachievementjoin.december}}</td>
                                      </tr>  
                                    </table>
                                </span> 
                            </td>                                                                                                                                                               
                          </tr> 
                          <!-- month wise end user mos target and achievement -->                         
                        </table>
                        <div class="row">
                          <div class="col-12 text-right">
                            <button
                              @click="updateMOS()"
                              class="btn btn-success"
                            >
                              Update
                            </button>
                          </div>
                          <br />
                          <br />
                        </div>


                        <table class="table table-bordered table-sm">
                          <tr class="thead-dark">
                            <th colspan="5" class="text-center">End User KPI MOS</th>
                          </tr>
                          <tr>
                            <th>SL</th>
                            <th>User</th>
                            <th>KPI</th>
                            <th>KPI Weightage</th>
                            <th>MOS</th>
                          </tr>
                          <tr></tr>
                          <tr
                            v-for="(user_kpi, index) in mos_item.mos_kpi"
                            :key="user_kpi.id"
                          >  
                            <td>{{index+1}}</td>
                            <td>{{user_kpi.employee.name}}-({{user_kpi.employee.employee_id}})</td>
                            <td>{{user_kpi.kpi_name}}</td>
                            <td>{{user_kpi.kpi_weight}}</td>
                            <td>
                              <table class="table table-bordered table-sm">
                                <tr >
                                  <th>SL</th>
                                  <th>User</th>
                                  <th>Weightage</th>
                                </tr>  
                                <tr v-for="(user_mos, mos_index) in user_kpi.mosjoin"
                                :key="user_mos.id">
                                  <td>{{mos_index+1}}</td>
                                  <td>{{user_mos.mos_name}}</td>
                                  <td>{{user_mos.weightage}}</td>
                                </tr>  
                              </table>                            
                            </td>
                          </tr>
                        </table>   


                        <table class="table table-bordered table-sm">
                          <tr class="thead-dark">
                            <th colspan="5" class="text-center">Feedback</th>
                          </tr>
                          <tr>
                            <th>SI</th>
                            <th>Month</th>
                            <th>Feedback</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                          <tr></tr>
                          <tr
                            v-for="(fee, intex2) in mos_item.feadback"
                            :key="fee.id"
                          >
                            <td colspan="1">{{ intex2 + 1 }}</td>
                            <td colspan="1">
                              <p v-if="fee.fmonth > 0">
                                {{ months[fee.fmonth - 1].name }}
                              </p>
                            </td>
                            <!-- <td colspan="1">{{fee.msg}}</td> -->
                            <td colspan="1" v-html="fee.msg"></td>
                            <td colspan="1">{{ format_Date(fee.date) }}</td>
                            <td colspan="2" class="text-center">
                              <i
                                class="bx bx-edit-alt"
                                @click="show_pop_feedback(fee)"
                              ></i>
                              <i
                                class="bx bx-trash-alt"
                                @click="delete_feedback(fee)"
                              ></i>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <modal width="60%" height="30%" style="padding: 50px" name="feedback">
            <i @click="hide_pop_feedback()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <table class="table table-bordered table-striped table-sm">
                  <tbody>
                    <tr>
                      <th colspan="2" class="text-center" width="15%">
                        <strong>Feedback Update</strong>
                      </th>
                    </tr>

                    <tr>
                      <th class="text-center">
                        <input
                          class="form-control text-center"
                          v-model="feedback_singel.msg"
                        />
                      </th>
                      <th class="text-center">
                        <button
                          @click="feedbackUpdate()"
                          class="btn btn-success"
                        >
                          Save
                        </button>
                      </th>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </modal>

          <modal
            width="60%"
            height="70%"
            style="padding: 50px"
            name="popup-singel"
          >
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <table class="table table-bordered table-striped table-sm">
                  <tbody>
                    <tr>
                      <th colspan="2" class="text-center" width="15%">
                        <strong>KRA</strong>
                      </th>
                    </tr>
                    <tr>
                      <th class="text-center" width="15%">
                        <strong>Name</strong>
                      </th>
                      <th class="text-center" width="15%">
                        <strong>Weightage</strong>
                      </th>
                    </tr>
                    <tr>
                      <th class="text-center">
                        {{ item.krajoin ? item.krajoin.kra_name : "" }}
                      </th>
                      <th class="text-center">
                        {{ item.krajoin ? item.krajoin.kra_weight : "" }}
                      </th>
                    </tr>
                    <tr>
                      <th colspan="2" class="text-center" width="15%">
                        <strong>KPI</strong>
                      </th>
                    </tr>
                    <tr>
                      <th class="text-center">{{ item.kpi_name }}</th>
                      <th class="text-center">{{ item.kpi_weight }}</th>
                    </tr>
                    <tr>
                      <th colspan="2" class="text-center" width="15%">
                        <strong>KPI</strong>
                      </th>
                    </tr>
                    <tr>
                      <th class="text-center">{{ item.kpi_name }}</th>
                      <th class="text-center">{{ item.kpi_weight }}</th>
                    </tr>
                    <tr>
                      <th colspan="2" class="text-center" width="15%">
                        <strong>MOS</strong>
                      </th>
                    </tr>
                    <tr>
                      <th class="text-center">
                        <input
                          class="form-control text-center"
                          placeholder="MOS Name"
                          v-model="addForm.mos_name"
                        />
                      </th>
                      <th class="text-center">
                        <button @click="addMOS()" class="btn btn-success">
                          Save
                        </button>
                      </th>
                    </tr>
                  </tbody>
                </table>
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
import { Form } from "vform";
export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      kpi_id: this.$route.params.id,
      quarter: this.$route.query.quarter,
      month: this.$route.query.month,
      dept_id: this.$route.query.dept_id,
      kra_id: this.$route.query.kra_id,
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      item: [],
      mos_item: [],
      feedback_singel: {},
      feedback: {},
      status: "",
      month_off: [],
      updateForm: new Form({
        arrayData: {
          mosjoin: [],
        },
        feedback: {},
        year_get: this.year,        
      }),
      addForm: new Form({
        mos_name: "",
        weightage: 0,
      }),
      updateFeed: new Form({}),

       
    };
  },
  created() {
    this.role_id = this.user_data.role_id;
    if (this.role_id == 5 || this.role_id == 6 || this.role_id == 7 || this.role_id == 10) {
      this.getDepartment().then(({ data }) => {
        if (data.success) {
          this.month_off = data.data.setting;
          console.log(this.month_off);
        }
      });
    }
    this.getItems();
  },
  methods: {
    back() {
      this.$router.go(-1);
    },
    hide_pop_feedback() {
      this.$modal.hide("feedback");
    },
    show_pop_feedback(feed) {
      this.feedback_singel = feed;
      this.$modal.show("feedback");
    },
    hide_pop() {
      this.$modal.hide("popup-singel");
    },
    show_pop() {
      this.$modal.show("popup-singel");
    },
    target(mos_item) {
      return (
        Number(mos_item.january) +
        Number(mos_item.february) +
        Number(mos_item.march) +
        Number(mos_item.april) +
        Number(mos_item.may) +
        Number(mos_item.june) +
        Number(mos_item.july) +
        Number(mos_item.august) +
        Number(mos_item.september) +
        Number(mos_item.october) +
        Number(mos_item.november) +
        Number(mos_item.december)
      );
    },
    onlyNumber($event) {
      //console.log($event.keyCode); //keyCodes value
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
        // 46 is dot
        $event.preventDefault();
      }
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
    async delete_feedback(item) {
      let loader = this.$loading.show();
      try {
        await axios
          .delete(this.api_url + "mos_feadbacks/" + item.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ res }) => {
            this.getItems();
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    feedbackUpdate() {
      try {
        let loader = this.$loading.show();
        //`mos_id`, `user_id`, `dept_id`, `date`, `msg`, `month`, `fmonth`, `status`
        this.updateFeed.msg = this.feedback_singel.msg;
        this.updateFeed.mos_id = this.feedback_singel.mos_id;
        this.updateFeed
          .put(this.api_url + "mos_feadbacks/" + this.feedback_singel.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(
            (res) => {
              console.log(res);
              this.hide_pop_feedback();
              if (res.data.success) {
                this.$toasted.show(res.data.message, {
                  theme: "bubble",
                  duration: 5000,
                  position: "bottom-right",
                });
              }
              loader.hide();
              //this.$router.push('/daily_work');
            },
            (error) => {
              console.log(error);
              loader.hide();
            }
          );
      } catch (error) {
        // loader.hide();
        console.log(error);
      }
    },
    updateMOS() {

      // try {
      let loader = this.$loading.show();
      this.updateForm.arrayData.mosjoin[0] = this.mos_item;
      this.updateForm.feedback = this.feedback;
      this.updateForm
        .post(this.api_url + "mos_update", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          console.log(res);
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
          loader.hide();
        });
      // } catch (error) {
      // console.log(error);
      // }
      console.log(this.item);
    },
    addMOS() {
      try {
        let loader = this.$loading.show();
        this.addForm.kra_id = this.item.krajoin.id;
        this.addForm.kpi_id = this.item.id;
        this.addForm.dept_id = this.item.dept_id;
        this.addForm
          .post(this.api_url + "m_o_s", {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then((res) => {
            console.log(res);
            if (res.data.success) {
              this.hide_pop();
              this.getItems();
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }
            loader.hide();
          });
      } catch (error) {
        console.log(error);
      }
      console.log(this.item);
    },
    async getItems() {
      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "m_o_s/" + this.kpi_id + "?year=" + this.year, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.mos_item = data.data;
              this.target_achivement(this.mos_item);
            }
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    target_achivement(mos_item){
      let jan_target = 0;
      let jan_achivement = 0;

      let fab_target = 0;
      let fab_achivement = 0;

      let mar_target = 0;
      let mar_achivement = 0;  

      let apr_target = 0;
      let apr_achivement = 0;  
      
      let may_target = 0;
      let may_achivement = 0;      

      let jun_target = 0;
      let jun_achivement = 0; 

      let jul_target = 0;
      let jul_achivement = 0;   

      let aug_target = 0;
      let aug_achivement = 0;  

      let sep_target = 0;
      let sep_achivement = 0;     
      
      let oct_target = 0;
      let oct_achivement = 0;
      
      let nov_target = 0;
      let nov_achivement = 0;   

      let dec_target = 0;
      let dec_achivement = 0;   
      mos_item.mos_kpi.forEach(kpi_mos => {
        kpi_mos.mosjoin.forEach(mos => {
          jan_target+= mos.mostargetjoin.january;
          jan_achivement+= mos.mosachievementjoin.january;

          fab_target+=mos.mostargetjoin.february;
          fab_achivement+=mos.mosachievementjoin.february;

          mar_target+=mos.mostargetjoin.march;
          mar_achivement+=mos.mosachievementjoin.march;  
          
          apr_target+=mos.mostargetjoin.april;
          apr_achivement+=mos.mosachievementjoin.april;    
          
          may_target+=mos.mostargetjoin.may;
          may_achivement+=mos.mosachievementjoin.may;  
          
          jun_target+=mos.mostargetjoin.june;
          jun_achivement+=mos.mosachievementjoin.june;  
          
          jul_target+=mos.mostargetjoin.july;
          jul_achivement+=mos.mosachievementjoin.july; 
          
          aug_target+=mos.mostargetjoin.august;
          aug_achivement+=mos.mosachievementjoin.august;   
          
          sep_target+=mos.mostargetjoin.september;
          sep_achivement+=mos.mosachievementjoin.september;        
          
          oct_target+=mos.mostargetjoin.october;
          oct_achivement+=mos.mosachievementjoin.october;
          
          nov_target+=mos.mostargetjoin.november;
          nov_achivement+=mos.mosachievementjoin.november;     
          
          dec_target+=mos.mostargetjoin.december;
          dec_achivement+=mos.mosachievementjoin.december;           
        });
      });
      mos_item.mostargetjoin.january==0?mos_item.mostargetjoin.january = jan_target : mos_item.mostargetjoin.january;
      mos_item.mosachievementjoin.january==0?mos_item.mosachievementjoin.january = jan_achivement : mos_item.mosachievementjoin.january;

      mos_item.mostargetjoin.february==0?mos_item.mostargetjoin.february=fab_target:mos_item.mostargetjoin.february;
      mos_item.mosachievementjoin.february==0?mos_item.mosachievementjoin.february=fab_achivement:mos_item.mosachievementjoin.february;

      mos_item.mostargetjoin.march==0?mos_item.mostargetjoin.march=mar_target:mos_item.mostargetjoin.march;
      mos_item.mosachievementjoin.march==0?mos_item.mosachievementjoin.march=mar_achivement:mos_item.mosachievementjoin.march;  
      
      mos_item.mostargetjoin.april==0?mos_item.mostargetjoin.april=apr_target:mos_item.mostargetjoin.april;
      mos_item.mosachievementjoin.april==0?mos_item.mosachievementjoin.april=apr_achivement:mos_item.mosachievementjoin.april;    
      
      mos_item.mostargetjoin.may==0?mos_item.mostargetjoin.may=may_target:mos_item.mostargetjoin.may;
      mos_item.mosachievementjoin.may==0?mos_item.mosachievementjoin.may=may_achivement:mos_item.mosachievementjoin.may;  
      
      mos_item.mostargetjoin.june==0?mos_item.mostargetjoin.june=jun_target:mos_item.mostargetjoin.june;
      mos_item.mosachievementjoin.june==0?mos_item.mosachievementjoin.june=jun_achivement:mos_item.mosachievementjoin.june;  
      
      mos_item.mostargetjoin.july==0?mos_item.mostargetjoin.july=jul_target:mos_item.mostargetjoin.july;
      mos_item.mosachievementjoin.july==0?mos_item.mosachievementjoin.july=jul_achivement:mos_item.mosachievementjoin.july; 
      
      mos_item.mostargetjoin.august==0?mos_item.mostargetjoin.august=aug_target:mos_item.mostargetjoin.august;
      mos_item.mosachievementjoin.august==0?mos_item.mosachievementjoin.august=aug_achivement:mos_item.mosachievementjoin.august;   
      
      mos_item.mostargetjoin.september==0?mos_item.mostargetjoin.september=sep_target:mos_item.mostargetjoin.september;
      mos_item.mosachievementjoin.september==0?mos_item.mosachievementjoin.september=sep_achivement:mos_item.mosachievementjoin.september;        
      
      mos_item.mostargetjoin.october==0?mos_item.mostargetjoin.october=oct_target:mos_item.mostargetjoin.october;
      mos_item.mosachievementjoin.october==0?mos_item.mosachievementjoin.october=oct_achivement:mos_item.mosachievementjoin.october;
      
      mos_item.mostargetjoin.november==0?mos_item.mostargetjoin.november=nov_target:mos_item.mostargetjoin.november;
      mos_item.mosachievementjoin.november==0?mos_item.mosachievementjoin.november=nov_achivement:mos_item.mosachievementjoin.november;     
      
      mos_item.mostargetjoin.december==0?mos_item.mostargetjoin.december=dec_target:mos_item.mostargetjoin.december;
      mos_item.mosachievementjoin.december==0?mos_item.mosachievementjoin.december=dec_achivement:mos_item.mosachievementjoin.december;       
    }

  },
  computed: {},
};
</script>
 
 