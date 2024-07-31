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
                    <li class="breadcrumb-item active">KPI Score</li>
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
                <div class="col-12 col-sm-6 col-lg-2" v-if="year >= 2023">
                  <label for="users-list-verified">Quarter </label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="getItems()" v-model="filterForm.quarter"
                      id="users-list-verified">
                      <option value="">All</option>
                      <option v-for="row in quarter_months" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                      <option value="5">1st Half yearly</option>
                      <option value="6">2nd Half yearly</option>
                    </select>
                  </fieldset>
                </div>
                
                <div class="col-12 col-sm-6 col-lg-2" v-if="yearFixed == 2023">
                  <label for="users-list-verified">Year Part </label>
                  <fieldset class="form-group">
                    <select class="form-control" v-model="filterForm.year_part" 
                      id="users-list-verified">
                      <option value="0">All</option>
                      <option value="1">Only 2022</option>
                      <option value="2">Only 2023</option>
                    </select>
                  </fieldset>
                </div> <!-- && deptItems.length > 1-->

                <div class="col-12 col-sm-6 col-lg-2" v-if="year >= 2023">
                  <label for="users-list-verified">Month </label>
                  <fieldset class="form-group">
                    <select class="form-control" v-model="filterForm.month" v-on:change="getItems()"
                      id="users-list-verified">
                      <option value="">All</option>
                      <option v-for="row in months" :key="row.id" :value="row.id">{{
                        row.name
                      }}
                      </option>
                    </select>
                  </fieldset>
                </div> <!-- && deptItems.length > 1-->

                <div class="col-sm-6 col-lg-2" v-if="deptItems.length > 1">
                  <label for="users-list-verified">Department</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="getItems() ,getWing(),changeEmployee()" v-model="filterForm.dept_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in deptItems" :key="row.id" :value="row.id">
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <div class="ccol-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6">
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
                <div class="col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6">
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <select class="form-control" v-on:change="getItems()" v-model="filterForm.user_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                        {{ row.employee_id ? row.employee_id + ' : ' : '' }} {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <!---FILTER-->
                <div class="col-sm-6" v-if="!filterForm.month && filterForm.year_part == 0">
                  <div class="form-inline justify-content-center row" style="padding-top: 10px">
                    <label>Total BPT Score : <strong>
                        <span v-if="yearFixed == 2023">
                          {{ (((((thisYearSchore / thisYearweight) * 50) + parseFloat(previousScore)) / 150) * 100).toFixed(2) }}
                        </span>
                        <span v-else>{{ totalScore() }}</span>
                      </strong>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="table-responsive">
                        <!-- Naote: this table is for bpt Score 2022 and currnt year -->
                        <table class="table table-bordered table-sm" v-if="filterForm.year_part==0 || filterForm.year_part==1">
                          <thead class="thead-dark">
                            <tr>
                              <th v-if="filterForm.show_kra == 1">KRA</th>
                              <th v-if="filterForm.show_kra == 1">KRA Weightage</th>
                              <th v-if="filterForm.show_kpi == 1">KPI</th>
                              <th v-if="filterForm.show_kpi == 1">KPI Weightage</th>
                              <th v-if="filterForm.show_mos == 1">MOS</th>
                              <th v-if="filterForm.show_mos == 1">MOS Weightage</th>
                              <th>Target</th>
                              <th>Achv.</th>
                              <th>Score</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id">
                                <td :rowspan="rowVisible(index, item, 'kra', 'previous')" v-if="filterForm.show_kra == 1 &&
                                  (items[index > 0 ? index - 1 : 0].kra_id !=
                                    item.kra_id ||
                                    index == 0)
                                  ">
                                  {{ item.krajoin ? item.krajoin.kra_name : "" }} 
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kra', 'previous')" v-if="filterForm.show_kra == 1 &&
                                  (items[index > 0 ? index - 1 : 0].kra_id !=
                                    item.kra_id ||
                                    index == 0)
                                  ">
                                  {{ item.krajoin ? item.krajoin.kra_weight : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi', 'previous')" v-if="filterForm.show_kpi == 1 &&
                                  (items[index > 0 ? index - 1 : 0].kpi_id !=
                                    item.kpi_id ||
                                    index == 0)
                                  ">
                                  {{ item.kpijoin ? item.kpijoin.kpi_name : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi', 'previous')" v-if="filterForm.show_kpi == 1 &&
                                  (items[index > 0 ? index - 1 : 0].kpi_id !=
                                    item.kpi_id ||
                                    index == 0)
                                  ">
                                  {{ item.kpijoin ? item.kpijoin.kpi_weight : '' }}
                                </td>
                                <!-- <td v-if="filterForm.show_mos==1">{{ item.mos_name }}  ({{ Number(moduleTotal(item)).toFixed(2) }}) </td> -->
                                <td v-if="filterForm.show_mos == 1">
                                  {{ item.mos_name }}
                                </td>
                                <td class="text-right" v-if="filterForm.show_mos == 1">
                                  {{ item.weightage }}
                                </td>
                                <td class="text-right">{{ Number(TotalTarget('target', item)).toFixed(2) }}</td>
                                <td class="text-right">{{ Number(TotalTarget('achievement', item)).toFixed(2) }}</td>
                                <td class="text-right">{{ Number(TotalTarget('score', item)).toFixed(2) }}</td>
                              </tr>
                            </template>
                             <tr >
                            <!--<span></span>
                              <td >Total BPT Score</td>
                              <td align="right">{{ weightageTotal() }}</td>
                              <td align="right">{{ totalScoreGrand('target') }}</td>
                              <td align="right">{{ totalScoreGrand('achievement') }}</td> -->
                              <td colspan="9" align="right">{{ totalScoreGrand('score') }}</td>
                            </tr>                            
                            <tr v-if="yearFixed == 2023">
                              <td colspan="8" align="right">Total BPT Score (2022)</td>
                              <td>{{ totalScore() }}</td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- Naote: this table is for bpt Score 2023 year only -->
                        <table class="table table-bordered table-sm" v-if="yearFixed == 2023 && (filterForm.year_part==0 || filterForm.year_part==2)">
                          <thead class="thead-dark">
                            <tr>
                              <th v-if="filterForm.show_kra == 1">KRA </th>
                              <th v-if="filterForm.show_kra == 1">KRA Weightage</th>
                              <th v-if="filterForm.show_kpi == 1">KPI</th>
                              <th v-if="filterForm.show_kpi == 1">KPI Weightage</th>
                              <th v-if="filterForm.show_mos == 1">MOS</th>
                              <th v-if="filterForm.show_mos == 1">MOS Weightage</th>
                              <th>Target</th>
                              <th>Achv.</th>
                              <th>Score</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in currntYearItem">
                              <tr :key="item.id">
                                <td :rowspan="rowVisible(index, item, 'kra', 'currnt')" v-if="filterForm.show_kra == 1 &&
                                  (currntYearItem[index > 0 ? index - 1 : 0].kra_id !=
                                    item.kra_id ||
                                    index == 0)
                                  ">
                                  {{ item.krajoin ? item.krajoin.kra_name : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kra', 'currnt')" v-if="filterForm.show_kra == 1 &&
                                  (currntYearItem[index > 0 ? index - 1 : 0].kra_id !=
                                    item.kra_id ||
                                    index == 0)
                                  ">
                                  {{ item.krajoin ? item.krajoin.kra_weight : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi', 'currnt')" v-if="filterForm.show_kpi == 1 &&
                                  (currntYearItem[index > 0 ? index - 1 : 0].kpi_id !=
                                    item.kpi_id ||
                                    index == 0)
                                  ">
                                  {{ item.kpijoin ? item.kpijoin.kpi_name : "" }}
                                </td>
                                <td :rowspan="rowVisible(index, item, 'kpi', 'currnt')" v-if="filterForm.show_kpi == 1 &&
                                  (currntYearItem[index > 0 ? index - 1 : 0].kpi_id !=
                                    item.kpi_id ||
                                    index == 0)
                                  ">
                                  {{ item.kpijoin ? item.kpijoin.kpi_weight : '' }}
                                </td>
                                <!-- <td v-if="filterForm.show_mos==1">  ({{ Number(moduleTotal(item)).toFixed(2) }}) </td> -->
                                <td v-if="filterForm.show_mos == 1">
                                  {{ item.mos_name }}
                                </td>
                                <td class="text-right" v-if="filterForm.show_mos == 1">
                                  <!-- {{ item.weightage }} -->
                                  {{ Number(weightageCalculation(item.weightage, totalTargetNew('target', 'first', item),
                                    TotalTarget('target', item))).toFixed(2) }}
                                  <!-- TotalTarget('target', item) -->
                                  <!-- {{ Number(weightageCalculation(item.weightage,0,0)).toFixed(2) }} -->
                                  <!-- {{ Number(weightageCalculation(item.weightage,0,0)).toFixed(2) }} -->
                                </td>
                                <td class="text-right">
                                  {{ Number(totalTargetNew('target', 'first', item)).toFixed(2) }}
                                </td>
                                <td class="text-right">
                                  {{ Number(totalAchievementNew('achievement', 'first', item)).toFixed(2) }}
                                </td>
                                <td class="text-right">
                                  {{ Number(totalScoreNew(totalTargetNew('target', 'first', item),
                                    totalAchievementNew('achievement', 'first', item), weightageCalculation(item.weightage,
                                      totalTargetNew('target', 'first', item),
                                      TotalTarget('target', item)), item)).toFixed(2) }}
                                </td>
                              </tr>
                            </template>
                            <tr>
                              <span></span>
                              <td colspan="4" align="right">Total BPT Score <span v-if="!filterForm.month">(2023)</span></td>
                              <td align="right">{{ Number(thisYearweight).toFixed(2) }}</td>
                              <td align="right">{{ thisYearTarget }}</td>
                              <td align="right">{{ Number(thisYearAchievement).toFixed(2) }}</td>
                              <td align="right">{{ Number(thisYearSchore).toFixed(2) }}</td>
                            </tr>
                            <!-- this.previousScore -->
                            <tr>
                              <td colspan="8" align="right">Total BPT Score - 2023 (Out of 50)</td>
                              <td align="right">{{ Number((thisYearSchore / thisYearweight) * 50).toFixed(2) }}</td>
                            </tr>
                            <tr v-if="(filterForm.year_part==0 )">
                              <td colspan="8" align="right">Grand Total of BPT Score - 2022 & 2023-June (Out of 150)</td>
                              <td align="right">
                                {{ Number(((thisYearSchore / thisYearweight) * 50) + parseFloat(previousScore)).toFixed(2) }}</td>
                            </tr>
                            <tr v-if="(filterForm.year_part==0 )">
                              <td colspan="8" align="right">Final BPT Score - 2022 & 2023-June (Out of 100):</td>
                              <td align="right">
                                {{ (((((thisYearSchore / thisYearweight) * 50) + parseFloat(previousScore)) / 150) * 100).toFixed(2) }}
                              </td>
                            </tr>
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
  components: {
  },
  data() {
    return {
      previousScore: 0,
      currentYearItem: [],
      thisYearweight: 0,
      thisYearTarget: 0,
      thisYearAchievement: 0,
      thisYearSchore: 0,
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
        }
      ],
      dataItemExel: [],
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: "",
      items: [],
      currntYearItem: [],
      item: [],
      mosachievement: [],
      mosatarget: [],
      deptItems: [],
      ingsItems: [],
      employeeItem: [],
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
        year_part:0,
      }),
      kpiForm: new Form({
        dept_id: "",
      }),
      status: "",
      year: this.$localStorage.get("year"),
      yearFixed: this.$localStorage.get("year"),
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
    this.filterForm.dept_id = this.user_data.dept_id;
    
    if (this.filterForm.dept_id) {
      this.getWing();
      this.filterForm.wing_id = this.user_data.wing_id ? this.user_data.wing_id : "";
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
      this.getItems();
    }
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
    async updateYearlyAchiv() {
      let loader = this.$loading.show();
      // async updateKra() {
      let mosatargetData = new Form({
        total: Number(this.mosatarget.total).toFixed(0),
        type: this.mosatarget.type,
        mos_id: this.mosatarget.mos_id,
        dept_id: this.mosatarget.dept_id,
        january: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        february: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        march: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        april: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        may: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        june: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        july: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        august: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        september: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        october: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        november: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0,
        december: this.mosatarget.total ? Number(this.mosatarget.total / 12).toFixed(2) : 0
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
        january: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        february: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        march: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        april: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        may: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        june: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        july: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        august: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        september: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        october: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        november: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
        december: this.mosachievement.total ? Number(this.mosachievement.total / 12).toFixed(2) : 0,
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
    rowVisible(index, item, type, state) {
      let crount = 0;
      if (state == 'previous') {
        this.items.filter((row) => {
          if (type == "kra") {
            if (row.kra_id === item.kra_id) {
              crount += 1;
            }
          } else if (type == "kpi") {
            if (row.kpi_id === item.kpi_id) {
              crount += 1;
            }
          }
        });
        return crount;
      } else {
        this.currntYearItem.filter((row) => {
          if (type == "kra") {
            if (row.kra_id === item.kra_id) {
              crount += 1;
            }
          } else if (type == "kpi") {
            if (row.kpi_id === item.kpi_id) {
              crount += 1;
            }
          }
        });
        return crount;
      }
    },
    async getWing() {
      await axios.get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
      .then(({ data }) => {
        this.getEmployee();
        this.WingsItems = data.data;
      });
    },
    async changeEmployee() {
      this.getEmployee();
      this.getItems(true);
    },
    async getEmployee() {
      //if (this.filterForm.wing_id) {
      let where = '?1=1';
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id;
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
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

    },
    async getItems(load = false) {
      if (this.filterForm.dept_id != "") {
        let where = "";
        //Note: ADD YEAR PARAM
        where = "?year=" + (this.year ? this.year : new Date().getFullYear());
        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }

        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id;
        }

        if (this.filterForm.user_id) {
          where += '&user_id=' + this.filterForm.user_id;
        }
        
        if (this.filterForm.quarter) {
          where += '&quarter=' + this.filterForm.quarter;
        }

        if (this.filterForm.month) {
          where += '&month=' + this.filterForm.month;
        }    

        let loader;
        if (load) { loader = this.$loading.show(); }
        try {
          await axios
            .get(this.api_url + "kra_kpi_mos_score_list" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then(({ data }) => {
              if (data.success) {
                if (this.year < 2023) {
                  this.items = data.data;
                } else if(this.year = 2023){
                  this.items = data.data.previous;
                  this.currntYearItem = data.data.current;
                  if (this.yearFixed == 2023 && Object.keys(data.data.current).length > 0) {
                    this.grandTotal();
                  }
                }else{
                  this.items = data.data.current; 
                }
                this.dataItemExel = data.data;
              }
              if (load) { loader.hide(); }
            });
        } catch (error) {
          if (load) { loader.hide(); }
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
    totalScoreGrand(type) {
      // console.log('type check ...!!' , type);
      let totalscore = 0;
      for (let index = 0; index < this.items.length; index++) {
        totalscore += Number(this.TotalTarget(type, this.items[index]));
      }
      this.previousScore = totalscore > 100 ? 100 : Number(totalscore).toFixed(2)
      return this.previousScore;
    },    
    totalScore() {
      let totalscore = 0;
      for (let index = 0; index < this.items.length; index++) {
        totalscore += Number(this.TotalTarget('score', this.items[index]));
      }
      this.previousScore = totalscore > 100 ? 100 : Number(totalscore).toFixed(2)
      return this.previousScore;
    },
    weightageTotal(){
      // const sum = this.items.reduce((accumulator, currentItem) => {
      //   return Number(accumulator) + Number(currentItem.weightage);
      // }, 0);
      // // console.log( 'dssdrtdyjugjhrwerdyjgh', sum);
      // return  sum;
    },
    TotalTarget(type, item) {
      if (type == 'target') {
        if (this.year < 2022) {//Note:This is for 2021
          if (item.krajoin.role_id == 5) {
            return item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
          } else {
            return item.mostargetjoin ? item.mostargetjoin.total : 0;
          }
        } else {
          return item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
        }
      } else if (type == 'achievement') {
        if (this.year < 2022) {
          if (item.krajoin.role_id == 5) {
            return item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
          } else {
            // return item.mosachievementjoin ? item.mosachievementjoin.total : 0 ;
            return item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
          }
        } else {
          return item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
        }
      } else {
        let target = 0;
        let achievement = 0;
        if (this.year < 2022) {//Note:This is for 2021
          if (item.krajoin.role_id == 5) {
            target = item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
            achievement = item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
          } else {
            target = item.mostargetjoin ? item.mostargetjoin.total : 0;
            // achievement =  item.mosachievementjoin ? item.mosachievementjoin.total : 0 ;
            achievement = item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
          }
        } else {
          target = item.mostargetjoin ? item.mostargetjoin.monthly_total : 0;
          achievement = item.mosachievementjoin ? item.mosachievementjoin.monthly_total : 0;
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
    weightageCalculation(weightage, firstHalfTarget, totalTarget) {
      if (isNaN(weightage) || isNaN(firstHalfTarget) || isNaN(totalTarget)) {
        return 0;
      }
      if (totalTarget === 0) {
        return 0;
      }
      const weightageCal = (weightage * firstHalfTarget) / totalTarget;
      return Math.round(weightageCal * 100) / 100; // Round to 2 decimal places
    },
    totalTargetNew(type, half, item) {
      const kraData = item.krajoin; // Assuming you have a method to fetch KRA data
      const target = item.mostargetjoin; // Assuming you have a method to fetch target data

      let months;
      if (half == 'first') {
        if (item.dept_id == 1 || item.dept_id == 40 || item.dept_id == 41|| item.dept_id == 42) {
          months = ['january', 'february', 'march', 'april', 'may', 'june'];
        } else {
          months = ['january', 'february', 'march', 'april', 'may'];
        }
      } else {
        if (item.dept_id == 1 || item.dept_id == 40 || item.dept_id == 41 || item.dept_id == 42) {
          months = ['july', 'august', 'september', 'october', 'november', 'december'];
        } else {
          months = ['jun', 'july', 'august', 'september', 'october', 'november', 'december'];
        }
      }

      let targetValue = 0;
      if(this.filterForm.month){
        return target.monthly_total.toFixed(2);
      }
      if (kraData.role_id === 5) {
        for (const value of months) {
          targetValue += target[value];
        }
      } else {
        for (const value of months) {
          targetValue += target[value];
        }
      }
      
      return targetValue.toFixed(2);
    },
    totalAchievementNew(type, half, item) {
      const kraData = item.krajoin; // Assuming you have a method to fetch KRA data
      const achievement = item.mosachievementjoin; // Assuming you have a method to fetch achievement data

      let months;
      if (half == 'first') {
        if (item.dept_id == 1 || item.dept_id == 40 || item.dept_id == 41 || item.dept_id == 42) {
          months = ['january', 'february', 'march', 'april', 'may', 'june'];
        } else {
          months = ['january', 'february', 'march', 'april', 'may'];
        }
      } else {
        if (item.dept_id == 1 || item.dept_id == 40 || item.dept_id == 41 || item.dept_id == 42) {
          months = ['july', 'august', 'september', 'october', 'november', 'december'];
        } else {
          months = ['jun', 'july', 'august', 'september', 'october', 'november', 'december'];
        }
      }

      let achievementValue = 0;
      if(this.filterForm.month){
        return achievement.monthly_total.toFixed(2);
      }      
      if (kraData.role_id === 5) {
        for (const value of months) {
          achievementValue += achievement[value];
        }
      } else {
        for (const value of months) {
          achievementValue += achievement[value];
        }
      }

      return achievementValue.toFixed(2);
    },
    totalScoreNew(target, achievement, weightage, item) {
      let achievementValue = 0;
      let mos_calculation = item.mos_calculation;
      if ((target > 0 && achievement > 0) && weightage > 0) {
        if (mos_calculation == 0) {
          achievementValue = (achievement / target) * weightage;
        } else if (mos_calculation == 1) {
          achievementValue = (target / achievement) * weightage;
        } else if (mos_calculation == 2) {
          achievementValue = (achievement / target) * weightage;
        } else if (mos_calculation == 3) {
          achievementValue = (target / achievement) * weightage;
        } else {
          achievementValue = (achievement / target) * weightage;
        }
      }
      if (achievementValue > weightage) {
        achievementValue = weightage;
      }
      return achievementValue.toFixed(2);
    },
    grandTotal() {
      let data = JSON.parse(JSON.stringify(this.currntYearItem));
      this.thisYearweight = 0;
      this.thisYearTarget = 0;
      this.thisYearAchievement = 0;
      this.thisYearSchore = 0;
      const appendedWords = data.filter((item, index, arr) => {
        let TScalculation = parseFloat(this.weightageCalculation(item.weightage, this.totalTargetNew('target', 'first', item), this.TotalTarget('target', item)));
        this.thisYearweight += TScalculation;
        // console.log('check here ..........' , TScalculation);
        this.thisYearTarget += parseFloat(this.totalTargetNew('target', 'first', item));
        this.thisYearAchievement += parseFloat(this.totalAchievementNew('achievement', 'first', item));
        this.thisYearSchore += parseFloat(this.totalScoreNew(
          this.totalTargetNew('target', 'first', item),
          this.totalAchievementNew('achievement', 'first', item), this.weightageCalculation(item.weightage,
            this.totalTargetNew('target', 'first', item),
            this.TotalTarget('target', item)), item
        ));
        
      });
      // console.log('ddddddddddddd' ,this.thisYearweight  );
    }
  },
  computed: {},
};
</script>