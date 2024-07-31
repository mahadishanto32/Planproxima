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
                    <li class="breadcrumb-item active">Performance Report</li>
                  </ol>
                </div>
              </div>
              <div class="col-sm-3">
                <!-- <router-link class="btn btn-primary add-btn" :to="{ path: '/add_daily_work' }"> <i class="bx bx-add-alt"></i> Add daily work </router-link> -->
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

                    <label class="mb-2 mr-sm-2 col-1">Show With<strong> Zero</strong> <input type="checkbox" value="1"
                        v-model="filterForm.show_zero" v-on:change="monthChange()"></label>
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Quarter </label>
                  <fieldset class="form-group">
                    <select class="form-control" v-model="filterForm.quarter" id="users-list-verified" v-on:change="monthChange()">
                      <option value="">All</option>
                      <option v-for="row in quarter_months" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                      <option value="5">1st Half yearly</option>
                      <option value="6">2nd Half yearly</option>
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
                <div v-if="(deptItems.length > 1) || (user_data.dept_id == 6)" class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Department</label>
                  <fieldset class="form-group">
                    <select v-on:change="getWing()" class="form-control" v-model="filterForm.dept_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in deptItems" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6">
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
                </div>
                <div class="col-12 col-sm-6 col-lg-2"
                  v-if="employeeItem.length > 0 && ( role_id == 5 || role_id == 6 )">
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="changeEmployee()" v-model="filterForm.user_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <div class="col-sm-6 col-lg-2">
                  <label for="users-list-verified">Achievement Range</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="rangeSearch()" v-model="filterForm.achievement_range"
                      id="users-list-verified">
                      <option value="10000">All</option>
                      <option value="90">Less than 90</option>
                      <option value="80">Less than 80</option>
                      <option value="70">Less than 70</option>
                      <option value="60">Less than 60</option>
                      <option value="50">Less than 50</option>
                      <option value="40">Less than 40</option>
                      <option value="30">Less than 30</option>
                    </select>
                  </fieldset>
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
                              <th v-if="filterForm.show_kra == 1" rowspan="2">
                                Department name
                              </th>
                              <th v-if="filterForm.wing_id" rowspan="2">
                                Employee Name
                              </th>
                              <th v-if="filterForm.show_kra == 1" colspan="3">
                                KRA
                              </th>
                              <th v-if="filterForm.show_kpi == 1" colspan="3">
                                KPI
                              </th>
                              <th v-if="filterForm.show_mos == 1" colspan="6">
                                MOS
                              </th>
                            </tr>
                            <tr>
                              <th v-if="filterForm.show_kra == 1">Name</th>
                              <th v-if="filterForm.show_kra == 1">
                                KRA Weightage
                              </th>
                              <th v-if="filterForm.show_kra == 1">Score</th>

                              <th v-if="filterForm.show_kpi == 1">Name</th>
                              <th v-if="filterForm.show_kpi == 1">
                                KPI Weightage
                              </th>
                              <th v-if="filterForm.show_kpi == 1">Score</th>

                              <th v-if="filterForm.show_mos == 1">Name</th>
                              <th v-if="filterForm.show_mos == 1">
                                MOS Weightage
                              </th>
                              <th v-if="filterForm.show_mos == 1">Target</th>
                              <th v-if="filterForm.show_mos == 1">Achv.</th>
                              <th v-if="filterForm.show_mos == 1">Achv.%</th>
                              <th v-if="filterForm.show_mos == 1">Score</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id">
                                <td :rowspan="rowVisible(index, item, 'dep')" v-if="
                                    items[index > 0 ? index - 1 : 0].dept_id !=
                                      item.dept_id || index == 0
                                  ">
                                  {{ item.dep_name ? item.dep_name : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'user')" v-if="
                                    ((items[index > 0 ? index - 1 : 0].user_id !=
                                      item.user_id ||
                                      index == 0)) && filterForm.wing_id
                                  ">
                                  {{
                                  item.user_name ? item.user_name : ""
                                  }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kra')" v-if="
                                    filterForm.show_kra == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id ||
                                      index == 0)
                                  ">
                                  {{
                                  item.krajoin ? item.krajoin.kra_name : ""
                                  }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kra')" v-if="
                                    filterForm.show_kra == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id ||
                                      index == 0)
                                  ">
                                  {{
                                  item.krajoin ? item.krajoin.kra_weight : ""
                                  }}
                                </td>

                                <td :rowspan="rowVisible(index, item, 'kra')" v-if="
                                    filterForm.show_kra == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id ||
                                      index == 0)
                                  ">
                                  {{
                                  Number(kra_kpiScore("kra", item)).toFixed(2)
                                  }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi')" v-if="
                                    filterForm.show_kpi == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id ||
                                      index == 0)
                                  ">
                                  {{
                                  item.kpijoin ? item.kpijoin.kpi_name : ""
                                  }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi')" v-if="
                                    filterForm.show_kpi == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id ||
                                      index == 0)
                                  ">
                                  {{
                                  item.kpijoin ? item.kpijoin.kpi_weight : ""
                                  }}
                                </td>

                                <td :rowspan="rowVisible(index, item, 'kpi')" v-if="
                                    filterForm.show_kpi == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id ||
                                      index == 0)
                                  ">
                                  {{
                                  Number(kra_kpiScore("kpi", item)).toFixed(2)
                                  }}
                                </td>
                                <td
                                  v-if="filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range ">
                                  {{ item.mos_name }}
                                </td>
                                <td class="text-right"
                                  v-if="filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range ">
                                  {{ item.weightage }}
                                </td>
                                <td class="text-right"
                                  v-if="filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range ">
                                  {{ targetTotal(item) }}
                                  <!-- {{
                                    Number(TotalTarget("target", item)).toFixed(
                                      2
                                    )
                                  }} -->
                                </td>
                                <td class="text-right"
                                  v-if="filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range ">
                                  <!-- {{
                                    Number(
                                      TotalTarget("achievement", item)
                                    ).toFixed(2)
                                  }} -->
                                  {{Number(achievementjoinTotal(item)).toFixed(2)}}
                                </td>
                                <td class="text-right"
                                  v-if="filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range ">
                                  {{ Number(percentCalculator(item)).toFixed(2)}}%
                                </td>
                                <td class="text-right"
                                  v-if=" filterForm.show_mos == 1 && percentCalculator(item) < filterForm.achievement_range">
                                  <!-- {{
                                    Number(TotalTarget("score", item)).toFixed(
                                      2
                                    )
                                  }} -->

                                  {{ Number(scoreCalculator(item)).toFixed(2) }}
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
    components: {},
    data() {
      return {
        columns: [
          {
            label: "KRA",
            field: "krajoin",
            dataFormat: this.kraName,
            rowspan: 4,
          },
          {
            label: "KRA Weightage",
            field: "krajoin",
            dataFormat: this.weightageValue,
          },
          {
            label: "KPI",
            field: "kpijoin",
            dataFormat: this.kpiName,
          },
          {
            label: "KPI Weightage",
            field: "kpijoin",
            dataFormat: this.kpiweightageValue,
          },
          {
            label: "MOS",
            field: "mos_name",
          },
          {
            label: "MOS Weightage",
            field: "weightage",
          },
        ],
        dataItemExel: [],
        base_url: window.base_url,
        api_url: window.api_url,
        token: this.$localStorage.get("d_token"),
        user_data: JSON.parse(this.$localStorage.get("user")),
        role_id: "",
        items: [],
        allItems: [],
        item: [],
        mosachievement: [],
        mosatarget: [],
        deptItems: [],
        ingsItems: [],
        employeeItem: [],
        WingsItems: [],
        filterForm: new Form({
          dept_id: this.$route.query.dept_id ? this.$route.query.dept_id : "",
          wing_id: "",
          user_id: "",
          kra_id: this.$route.query.kra_id ? this.$route.query.kra_id : "",
          kpi_id: "",
          quarter: this.$route.query.quarter ? this.$route.query.quarter : "",
          month: this.$route.query.month ? this.$route.query.month : "",
          show_kra: 1,
          show_kpi: 1,
          show_mos: 1,
          show_yachi: 1,
          achievement_range: 10000
        }),
        kpiForm: new Form({
          dept_id: "",
        }),

        status: "",

        // year: this.$localStorage.get("year"),

        //KRA INITIAL DATA
        kra_id: "",
        kra_name: "",
        kra_weight: "",
        editKraForm: new Form({}),

        //KPOI INITIAL DATA
        kpi_id: "",
        kpi_name: "",
        kpi_weight: "",
        editKpiForm: new Form({}),
        addKpiForm: new Form({}),

        //MOS INITIAL DATA
        mos_id: "",
        mos_name: "",
        mos_weight: "",
        editMosForm: new Form({}),
        addMosForm: new Form({}),
      };
    },
    created() {
      this.role_id = this.user_data.role_id;
      if (this.role_id > 1) {
        this.filterForm.dept_id = this.user_data.dept_id;
      }
      if (this.filterForm.dept_id) {
        this.getWing();
        this.filterForm.wing_id = this.user_data.wing_id
          ? this.user_data.wing_id
          : "";
      }
      if (
        this.role_id == 1 ||
        this.role_id == 2 ||
        this.role_id == 3 ||
        this.role_id == 5 ||
        this.role_id == 4
      ) {
        //this.getDept();
        this.getEmployee();
        this.deptChange();
      } else {
        //this.getItems();

        // this.getItems();
      }
      this.getItems(true);
      //this.getDept();
    },
    methods: {
      kpiName(value) {
        return value.kpi_name;
      },
      weightageValue(value) {
        return value.kra_weight;
      },
      kpiweightageValue(value) {
        return value.kpi_weight;
      },
      kraName(value) {
        return value.kra_name;
      },
      //Jan	Feb	Mar	Apr	May	Jun	Jul	Aug	Sep	Oct	Nov	Dec

      hide_pop() {
        this.$modal.hide("popup-singel");
        this.$modal.hide("kraedit");
        this.$modal.hide("kpiedit");
        this.$modal.hide("mosedit");
        this.$modal.hide("kpiadd");
        this.$modal.hide("mosadd");
      },
      add_kpi(item) {
        this.item = item;
        this.$modal.show("popup-singel");
      },
      AchiTarget(item) {
        this.item = item;
        this.mosachievement = item.mosachievementjoin;
        this.mosatarget = item.mostargetjoin;
        this.$modal.show("achi_target");
      },
      AchiTargetHidden() {
        //this.item = item;
        this.$modal.hide("achi_target");
      },
      monthChange() {
        this.items = this.allItems;
        if (this.filterForm.show_zero) {
          return this.items;
        } else {
          let a = this.items.filter(item => { 
            let total = 0; 
            if(item.mostargetjoin){
              var target = item.mostargetjoin;
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
                  total = q1 ;
                } else if (this.filterForm.quarter == 2) { 
                  total = q2 ;
                } else if (this.filterForm.quarter == 3) {
                  total = q3 ;
                } else if (this.filterForm.quarter == 4) {
                  total = q4 ;
                } else if (this.filterForm.quarter == 5) {
                  total = q5 ;
                } else if (this.filterForm.quarter == 6) {
                  total = q6 ;
                }  
              }else{
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
      async updateYearlyAchiv() {
        let loader = this.$loading.show();
        // async updateKra() {
        let mosatargetData = new Form({
          total: Number(this.mosatarget.total).toFixed(0),
          type: this.mosatarget.type,
          mos_id: this.mosatarget.mos_id,
          dept_id: this.mosatarget.dept_id,
          january: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          february: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          march: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          april: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          may: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          june: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          july: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          august: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          september: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          october: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          november: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
          december: this.mosatarget.total
            ? Number(this.mosatarget.total / 12).toFixed(2)
            : 0,
        });
        // mosatargetData.total  =  mosatarget.total ;
        mosatargetData
          .put(this.api_url + "mos_datas/" + this.mosatarget.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then((data) => {
            console.log(data);
          });

        let mosachievementData = new Form({
          total: Number(this.mosachievement.total).toFixed(0),
          type: this.mosachievement.type,
          mos_id: this.mosachievement.mos_id,
          dept_id: this.mosachievement.dept_id,
          january: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          february: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          march: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          april: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          may: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          june: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          july: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          august: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          september: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          october: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          november: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
          december: this.mosachievement.total
            ? Number(this.mosachievement.total / 12).toFixed(2)
            : 0,
        });
        // mosachievementData.total  =  mosachievement.total ;
        mosachievementData
          .put(this.api_url + "mos_datas/" + this.mosachievement.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then((data) => {
            this.$toasted.show(data.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
            this.AchiTargetHidden();
            loader.hide();
            //HIDE MODAL
            // this.$modal.hide("kraedit");
            //DATA RELOAD
            // this.getItems();
          });
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
      rangeSearch() {
        let newItems = [];
        //this.items = this.allItems;
        this.monthChange();
        this.items.filter((row) => {
          let ach = this.percentCalculator(row);
          if ((ach < this.filterForm.achievement_range)) {
            newItems.push(row);
          }

        });
        this.items = newItems;

      },
      rowVisible(index, item, type) {
        let crount = 0;

        this.items.filter((row) => {
          let ach = this.percentCalculator(row);
          if (type == "kra") {
            if (row.kra_id === item.kra_id && (ach < this.filterForm.achievement_range)) {
              crount += 1;
            }
          } else if (type == "kpi") {
            if (row.kpi_id === item.kpi_id && (ach < this.filterForm.achievement_range)) {
              crount += 1;
            }
          } else if (type == "dep") {
            if (row.dept_id === item.dept_id && (ach < this.filterForm.achievement_range)) {
              crount += 1;
            }
          } else if (type == "user") {
            if (row.user_id === item.user_id && (ach < this.filterForm.achievement_range)) {
              crount += 1;
            }
          }


        });
        return crount;
      },
      async getWing() {
        this.getItems(true);
        await axios
          .get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ data }) => {
            this.getEmployee();
            this.WingsItems = data.data;
            console.log(this.WingsItems);
          });
      },
      async changeEmployee() {
        this.getEmployee();
        this.getItems(true);
      },
      async getEmployee() {
        //if (this.filterForm.wing_id) {
        let where = "?1=1";
        if (this.filterForm.wing_id) {
          where += "&wing_id=" + this.filterForm.wing_id;
        }
        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }
        await axios
          .get(this.api_url + "users" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ data }) => {
            this.employeeItem = data.data;
          });
        //}
      },
      async getItems(load = false) {
        let where = "";
        //ADD YEAR PARAM
        where = "?year=" +  this.year ;

        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }
        if (this.filterForm.wing_id) {
          where += "&wing_id=" + this.filterForm.wing_id;
        }
        if (this.filterForm.user_id) {
          where += "&user_id=" + this.filterForm.user_id;
        }
        let loader;
        if (load) {
          loader = this.$loading.show();
        }
        try {
          await axios
            .get(this.api_url + "performance_report" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then(({ data }) => {
              if (data.success) {
                this.items = data.data;
                this.allItems = data.data;
                this.dataItemExel = data.data;
              }
              if (load) {
                loader.hide();
              }
            });
        } catch (error) {
          if (load) {
            loader.hide();
          }
        }
      },
      async deptChange() {
        this.getDept();
        this.getWing();
        this.getItems(true);
      },
      async getDept() {
        let loader = this.$loading.show();
        this.getDepartments(this.status).then(({ data }) => {
          if (data.success) {
            loader.hide();
            this.deptItems = data.data;
          } else {
            loader.hide();
          }
        });
      },
      totalScore() {
        let totalscore = 0;
        for (let index = 0; index < this.items.length; index++) {
          totalscore += Number(this.TotalTarget("score", this.items[index]));
        }
        return totalscore > 100 ? 100 : Number(totalscore).toFixed(2);
      },
      //percentCalculator
      percentCalculator(item) {
        var target = Number(this.targetTotal(item));
        var achievement = Number(this.achievementjoinTotal(item));
        return achievement > 0 ? ((achievement / target) * 100) : 0;
      },
      scoreCalculator(item) {
        var target = Number(this.targetTotal(item));
        var achievement = Number(this.achievementjoinTotal(item));
        let score = 0;
        if (target > 0 && achievement > 0) {
          if (item.mos_calculation == 0) {
            score = ((achievement / target) * item.weightage).toFixed(2);
          } else if (item.mos_calculation == 1) {
            score = ((target / achievement) * item.weightage).toFixed(2);
          } else if (item.mos_calculation == 2) {
            score = ((achievement / target) * item.weightage).toFixed(2);
          } else if (item.mos_calculation == 3) {
            score = ((target / achievement) * item.weightage).toFixed(2);
          } else {
            score = ((achievement / target) * item.weightage).toFixed(2);
          }
        } else {
          score = 0;
        }
        if (Number(score) > Number(item.weightage)) {
          return item.weightage;
        } else {
          return score;
        }
      },
      targetTotal(item) {

        if (item.mostargetjoin) {
          let target = item.mostargetjoin;
          let total = 0;
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
          return total;
        } else {
          return 0;
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
          q1 = achievement.january + achievement.february + achievement.march;
          q2 = achievement.april + achievement.may + achievement.june;
          q3 = achievement.july + achievement.august + achievement.september;
          q4 = achievement.october + achievement.november + achievement.december;
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
          return total;
        } {
          return 0;
        }
      },
      TotalTarget(type, item) {
        //let target;
        if (type == "target") {
          if (this.year < 2022) {
            //Note:This is for 2021
            if (item.krajoin.role_id == 5) {
              return item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
            } else {
              return item.mostargetjoin ? item.mostargetjoin.total : 0;
            }
          } else {
            return item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
          }
        } else if (type == "achievement") {
          if (this.year < 2022) {
            if (item.krajoin.role_id == 5) {
              return item.mosachievementjoin
                ? item.mosachievementjoin.monthly_total
                : 0;
            } else {
              // return item.mosachievementjoin ? item.mosachievementjoin.total : 0;
              return item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
            }
          } else {

            return item.mosachievementjoin
              ? item.mosachievementjoin.monthly_total
              : 0;
          }
        } else {
          let target = 0;
          let achievement = 0;
          if (this.year < 2022) {
            //Note:This is for 2021
            if (item.krajoin.role_id == 5) {
              target = item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
              achievement = item.mosachievementjoin
                ? item.mosachievementjoin.monthly_total
                : 0;
            } else {
              target = item.mostargetjoin ? item.mostargetjoin.total : 0;
              // achievement = item.mosachievementjoin
              //   ? item.mosachievementjoin.total
              //   : 0;
              achievement = item.mosachievementjoin
                ? item.mosachievementjoin.monthly_total
                : 0;
            }
          } else {
            target = item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
            achievement = item.mosachievementjoin
              ? item.mosachievementjoin.monthly_total
              : 0;
          }

          let score = 0;
          if (target > 0 && achievement > 0) {
            if (item.mos_calculation == 0) {
              score = ((achievement / target) * item.weightage).toFixed(2);
            } else if (item.mos_calculation == 1) {
              score = ((target / achievement) * item.weightage).toFixed(2);
            } else if (item.mos_calculation == 2) {
              score = ((achievement / target) * item.weightage).toFixed(2);
            } else if (item.mos_calculation == 3) {
              score = ((target / achievement) * item.weightage).toFixed(2);
            } else {
              score = ((achievement / target) * item.weightage).toFixed(2);
            }
          } else {
            score = 0;
          }
          if (Number(score) > Number(item.weightage)) {
            return item.weightage;
          } else {
            return score;
          }
        }
      },

      kra_kpiScore(type, item) {
        let count = 0;
        this.items.filter((row) => {
          if (type == "kra") {
            if (row.kra_id === item.kra_id) {
              let score = Number(this.scoreCalculator(row));
              count += score;
            }
          } else {
            if (row.kpi_id === item.kpi_id) {
              let score = Number(this.scoreCalculator(row));
              count += score;
            }
          }
        });
        return count;
      },
    },
    computed: {},
  };
</script>