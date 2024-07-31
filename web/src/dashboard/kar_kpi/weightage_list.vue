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
                        ><i class="bx bx-home-alt"></i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">
                      KRA, KPI and MOS Weightage List
                    </li>
                  </ol>
                </div>
              </div>
              <!-- <div class=" col-sm-3">
                                <router-link class="btn btn-primary add-btn" :to="{ path: '/add_daily_work' }"> <i class="bx bx-add-alt"></i> Add daily work </router-link>
                            </div> -->
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div
                  v-if="deptItems.length > 1"
                  class="col-12 col-sm-6 col-lg-2"
                >
                  <label for="users-list-verified">Department</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="deptChange()"
                      v-model="filterForm.dept_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in deptItems"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="role_id == 1 || role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Wings</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="changeEmployee()"
                      v-model="filterForm.wing_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in WingsItems"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.wing_title }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="role_id == 1 || role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <!-- <select class="form-control" v-on:change="getItems()" v-model="filterForm.user_id"
                      id="users-list-verified">
                      <option value="">Select One</option>
                      <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                        {{ row.employee_id ? row.employee_id + " : " : "" }}
                        {{ row.name }}
                      </option>
                    </select> -->

                    <Select2
                      placeholder="Select One"
                      v-on:change="getItems()"
                      v-model="filterForm.user_id"
                      :options="employeeItem"
                    />
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <div class="dwn_btn">
                    <label class="mb-2 mr-sm-2 col-1">
                      Download
                      <vue-excel-xlsx
                        :data="dataItemExel"
                        :columns="columns"
                        :fileName="filename"
                        :sheetName="sheetName"
                      >
                        <p class="bx bxs-cloud-download"></p>
                      </vue-excel-xlsx>
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
                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th>KRA</th>
                              <th>KRA Weightage</th>
                              <th>KPI</th>
                              <th>KPI Weightage</th>
                              <th>MOS</th>
                              <th>MOS Weightage</th>
                              <th v-if="role_id == 5 || role_id == 6">
                                MOS System ID
                              </th>
                              <th style="width: 200px">
                                Permission
                                <input
                                  v-on:change="AllItem()"
                                  v-model="all_item"
                                  value="1"
                                  type="checkbox"
                                />
                                <!-- <a @click="show_pop_permission()" class="mail_send"> <i style="color: #FFFFFF;" class="bx bx-mail-send"></i> </a> -->
                                <button
                                  type="button"
                                  class="btn btn-primary btn_bottom_fixed add-btn btn-lg d-flex align-items-center"
                                  @click="show_pop_permission()"
                                >
                                  Permission Request ({{ item_selects.length }})
                                </button>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id">
                                <td
                                  :rowspan="item.kra_count"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id || index == 0
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ''
                                  }}
                                </td>
                                <td
                                  :rowspan="item.kra_count"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id || index == 0
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_weight : ''
                                  }}
                                </td>
                                <td
                                  :rowspan="item.kpi_count"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id || index == 0
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_name : ''
                                  }}
                                  <i
                                    class="relation_kpi"
                                    v-if="
                                      item.kpijoin.rep_id != '' &&
                                      item.kpijoin.rep_id != 0 &&
                                      (role_id == 7 ||
                                        role_id == 6 ||
                                        role_id == 5)
                                    "
                                    >Relation</i
                                  >
                                </td>
                                <td
                                  :rowspan="item.kpi_count"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id || index == 0
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_weight : ''
                                  }}
                                </td>
                                <td>
                                  {{ item.mos_name }}
                                  <span v-if="item.share_per > 0">
                                    <i class="relation_kpi">
                                      Share Weightage
                                      {{ item.share_per }}%
                                    </i>
                                  </span>
                                  <br /><br />
                                  <!-- Note : KPI Rep Share  -->
                                  <span
                                    v-if="item.working_memberJoin.length > 0"
                                  >
                                    <span
                                      v-for="(user, index) in mosViewRelation(
                                        item
                                      )"
                                    >
                                      <i class="relation_kpi">
                                        {{ user.name }} - ({{
                                          user['rep_per'] > 0
                                            ? user['rep_per']
                                            : 0
                                        }}<span v-if="user['rep_per'] > 0"
                                          >%</span
                                        >),
                                      </i>
                                    </span>
                                  </span>

                                  <!-- Note: MOS Rep Share  -->
                                  <span
                                    v-if="
                                      item.mos_working_memberJoin.length > 0 &&
                                      (role_id == 7 ||
                                        role_id == 6 ||
                                        role_id == 5)
                                    "
                                  >
                                    <span
                                      v-for="(
                                        user, index
                                      ) in working_memberJoinCal(item)"
                                    >
                                      <i class="relation_kpi">
                                        {{ user.name }}- ({{
                                          user['rep_per'] > 0
                                            ? user['rep_per']
                                            : 0
                                        }}<span v-if="user['rep_per'] > 0"
                                          >%</span
                                        >),
                                      </i>
                                    </span>
                                  </span>
                                  <!-- Note: MOS ONly HOD Panel  -->
                                  <span
                                    v-if="
                                      item.mos_working_memberJoin.length == 0 &&
                                      item.working_memberJoin.length == 0 &&
                                      role_id == 5
                                    "
                                  >
                                    <i class="relation_kpi">Department</i>
                                  </span>
                                  <span
                                    v-if="
                                      item.mos_working_memberJoin.length == 0 &&
                                      item.working_memberJoin.length == 0 &&
                                      role_id == 6
                                    "
                                  >
                                    <i class="relation_kpi">Wings</i>
                                  </span>
                                </td>
                                <td>{{ item.weightage }}</td>
                                <td v-if="role_id == 5 || role_id == 6">
                                  {{ item.id }}
                                </td>
                                <td>
                                  <p v-if="item.modification_type == 1">
                                    <input
                                      v-if="
                                        item.modification_status == 0 ||
                                        !dateValidation(item.end_date)
                                      "
                                      v-on:change="selectChange(index)"
                                      v-model="item.checked"
                                      value="row.id"
                                      type="checkbox"
                                    />
                                  </p>

                                  <p
                                    style="
                                      border: 1px solid #efefef;
                                      text-align: center;
                                      border-radius: 8px;
                                    "
                                    v-if="
                                      item.modification_status == 1 &&
                                      dateValidation(item.end_date)
                                    "
                                  >
                                    Request Pending
                                  </p>

                                  <p
                                    style="
                                      border: 1px solid #efefef;
                                      text-align: center;
                                      border-radius: 8px;
                                    "
                                    v-if="
                                      item.modification_status == 3 &&
                                      dateValidation(item.end_date)
                                    "
                                  >
                                    Acknowledged by HOD
                                  </p>
                                  <!-- <p style="border:  1px solid #efefef; text-align:  center; border-radius: 8px;"  v-if="(item.modification_status == 2 && dateValidation(item.end_date))">{{ item.start_date }} - {{ item.end_date }}</p> -->
                                  <p
                                    class="permission_date"
                                    v-if="
                                      item.modification_status == 2 &&
                                      dateValidation(item.end_date)
                                    "
                                  >
                                    {{ item.start_date }} - {{ item.end_date }}
                                  </p>
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
    <modal
      width="75%"
      height="80%"
      style="padding: 50px"
      name="popup-permission"
    >
      <i @click="hide_pop_permission()" class="bx bx-x-circle x-circle"></i>
      <div class="app-content">
        <h3>KRA KPI Target modification permission request</h3>
        <div class="card">
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">TO Date</label>
                    <div class="controls">
                      <datepicker
                        v-model="permission_mailForm.start_date"
                        name="to_date"
                        :disabled-dates="state.disabledDates"
                        class="form-control"
                      ></datepicker>
                    </div>
                  </div>
                </th>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">From Date</label>
                    <div class="controls">
                      <datepicker
                        v-model="permission_mailForm.end_date"
                        name="from_date"
                        :disabled-dates="state.disabledDates"
                        class="form-control"
                      ></datepicker>
                    </div>
                  </div>
                </th>
                <th colspan="2" class="text-center">
                  <div class="form-group">
                    <label for="Profession">Months</label>
                    <div class="controls">
                      <multiselect
                        v-model="select_months"
                        :options="months"
                        :multiple="true"
                        placeholder="Select(Months)"
                        :label="'name'"
                        track-by="id"
                        :searchable="true"
                        :close-on-select="false"
                        :show-labels="false"
                      >
                        <template
                          slot="selection"
                          slot-scope="{ values, isOpen }"
                          ><span
                            class="multiselect__single"
                            v-if="values.length &amp;&amp; !isOpen"
                            >{{ values.length }} options selected</span
                          ></template
                        >
                      </multiselect>
                    </div>
                  </div>
                </th>
              </tr>
              <tr>
                <th colspan="4" class="text-center">
                  <vue-editor
                    v-model="permission_mailForm.note"
                    name="note"
                    placeholder="Note...."
                  >
                  </vue-editor>
                </th>
              </tr>
              <tr>
                <th colspan="2"></th>

                <th class="text-center">
                  <button
                    @click="monthly_permission()"
                    class="btn btn-success"
                    :disabled="isDisabled"
                  >
                    Send
                  </button>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>
  </div>
</template>

<style>
.dwn_btn {
  margin-top: 15px;
}
</style>

<script>
import Select2 from 'v-select2-component'
import { Form } from 'vform'
import Multiselect from 'vue-multiselect'
import { VueEditor } from 'vue2-editor'
import Datepicker from 'vuejs-datepicker'
import axios from '../../axios_instance'

export default {
  props: {},
  components: {
    Select2: Select2,

    Multiselect,
    Datepicker,
    VueEditor,
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      all_item: 0,
      select_months: [],
      base_url: window.base_url,
      api_url: window.api_url,
      permission_mailForm: new Form({
        select_months: [],
        start_date: new Date().toISOString().slice(0, 10),
        end_date: new Date(new Date(new Date().getTime() + 86400000 * 2))
          .toISOString()
          .slice(0, 10),
      }),
      state: {
        disabledDates: {
          to: new Date(new Date().getTime() - 86400000),
          from: new Date(new Date().getTime() + 86400000 * 2),
        },
      },
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      items: [],
      item: [],
      search: '',
      status: '',
      deptItems: [],
      WingsItems: [],
      employeeItem: [],
      filterForm: new Form({
        dept_id: '',
        wing_id: '',
        user_id: '',
      }),
      item_selects: [],
      filename: 'KPI List',
      sheetName: 'KPI List',
      columns: [
        {
          label: 'KRA',
          field: 'krajoin',
          dataFormat: this.kraName,
          rowspan: 4,
        },
        {
          label: 'KPI',
          field: 'kpijoin',
          dataFormat: this.kpiName,
        },
        {
          label: 'MOS',
          field: 'mos_name',
        },
        {
          label: 'reference_id',
          field: 'id',
        },
      ],
      dataItemExel: [],
      isDisabled: true,
    }
  },
  created() {
    this.filterForm.dept_id = this.user_data.dept_id
    this.role_id = this.user_data.role_id
    if (this.filterForm.dept_id) {
      this.getWing()
      this.filterForm.wing_id = this.user_data.wing_id
        ? this.user_data.wing_id
        : ''
    }
    if (
      this.role_id == 1 ||
      this.role_id == 2 ||
      this.role_id == 3 ||
      this.role_id == 4 ||
      this.role_id == 5
    ) {
      this.getEmployee()
      this.deptChange()
    } else {
      //this.getItems();
      this.getItems()
    }
  },
  methods: {
    kpiName(value) {
      return value.kpi_name
    },
    kraName(value) {
      return value.kra_name
    },
    AllItem() {
      if (this.all_item == 1) {
        this.item_selects = this.items
        for (let index = 0; index < this.items.length; index++) {
          this.item_selects[index].checked = this.all_item == 1 ? true : false
        }
      } else {
        this.item_selects = []
        for (let index = 0; index < this.items.length; index++) {
          this.items[index].checked = false
        }
      }
    },
    selectChange(index) {
      this.items[index].checked = this.items[index].checked ? true : false
      if (this.items[index].checked) {
        this.item_selects.push(this.items[index])
      }
    },
    show_pop_permission() {
      // this.item  = item ;
      this.$modal.show('popup-permission')
    },
    hide_pop_permission() {
      this.$modal.hide('popup-permission')
    },
    achievement(item, month) {
      if (item.mostargetjoin[month] > 0 && item.mosachievementjoin[month] > 0) {
        return (
          (item.mostargetjoin[month] / item.mosachievementjoin[month]) *
          100
        ).toFixed()
      } else {
        return 0
      }
      // (item.mostargetjoin.january / item.mosachievementjoin.january)/100
    },
    monthly_permission() {
      try {
        let loader = this.$loading.show()
        let ids = []
        this.item_selects.forEach((element) => {
          if (element.checked) {
            ids.push(element.id)
          }
        })
        this.permission_mailForm.item = this.item
        this.permission_mailForm.ids = ids
        this.permission_mailForm.select_months = this.select_months
        this.formatDate(this.permission_mailForm.start_date)
        this.formatDate(this.permission_mailForm.end_date)
        this.permission_mailForm
          .post(this.api_url + 'mos_modification_permission', {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(
            (res) => {
              if (res.data.success) {
                this.$toasted.show(res.data.message, {
                  theme: 'bubble',
                  duration: 5000,
                  position: 'bottom-right',
                })
              }
              this.item_selects = []
              this.getItems()
              loader.hide()
              this.hide_pop_permission()

              // this.$router.push('/daily_work');
            },
            (error) => {
              loader.hide()
            }
          )
      } catch (error) {}
    },
    checkConditionKra(length, kpi_index, mos_index) {
      if (kpi_index == 0 && mos_index == 0) {
        return true
      } else {
        return false
      }
    },
    checkConditionKpi(length, mos_index) {
      if (mos_index == 0) {
        return true
      } else {
        return false
      }
    },
    async getWing() {
      await axios
        .get(this.api_url + 'wings?dept_id=' + this.filterForm.dept_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.getEmployee()
          this.WingsItems = data.data
        })
    },
    async changeEmployee() {
      this.getEmployee()
      this.getItems(true)
    },
    async getEmployee() {
      // if(this.filterForm.wing_id){
      let where = '?1=1'
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id
      }
      await axios
        .get(this.api_url + 'users' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.employeeItem = data.data
        })
      // }
    },
    async getItems(load = false) {
      //this.getWing();
      if (this.filterForm.dept_id != '') {
        let where = '?year=' + this.year
        if (this.filterForm.dept_id) {
          this.sheetName = 'Dept KPI KPI MOS'
          this.filename = 'Dept KPI KPI MOS'
          where += '&dept_id=' + this.filterForm.dept_id
        }
        if (this.filterForm.wing_id) {
          this.sheetName = 'Wing KPI KPI MOS'
          this.filename = 'Wing KPI KPI MOS'
          where += '&wing_id=' + this.filterForm.wing_id
        }
        if (this.filterForm.user_id) {
          this.sheetName = 'Employee KPI KPI MOS'
          this.filename = 'Employee KPI KPI MOS'
          where += '&user_id=' + this.filterForm.user_id
        }
        let loader
        if (load) {
          loader = this.$loading.show()
        }

        try {
          await axios
            .get(this.api_url + 'kra_kpi_mos_list' + where, {
              headers: {
                'Content-Type': 'application/json',
                Authorization: this.token ? `Bearer ${this.token}` : '',
              },
            })
            .then(({ data }) => {
              if (data.success) {
                this.items = data.data
                this.dataItemExel = data.data
              }
              if (load) {
                loader.hide()
              }
            })
        } catch (error) {
          if (load) {
            loader.hide()
          }
        }
      }
    },
    async deptChange() {
      this.getDept()
      this.getWing()
      this.getItems(true)
    },
    async getDept() {
      let loader = this.$loading.show()
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          loader.hide()
          this.deptItems = data.data
        } else {
          loader.hide()
        }
      })
    },
    formatDate(date) {
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear()

      if (month.length < 2) month = '0' + month
      if (day.length < 2) day = '0' + day

      return [year, month, day].join('-')
    },
    working_memberJoinCal(item) {
      let nameAry = []
      item.mos_working_memberJoin.filter((data, index) => {
        if (index == 0) {
          let name = data.krajoin.user_join.name
          let sum = 0
          item.mos_working_memberJoin.filter((row, index) => {
            if (row.krajoin.user_join.id == data.krajoin.user_join.id) {
              sum += data.rep_per
            }
          })
          nameAry.push({ name: name, rep_per: sum })
        } else if (
          item.mos_working_memberJoin[index - 1].krajoin.user_join.id !=
          data.krajoin.user_join.id
        ) {
          let name = data.krajoin.user_join.name
          let sum = 0
          item.mos_working_memberJoin.filter((row, index) => {
            if (row.krajoin.user_join.id == data.krajoin.user_join.id) {
              sum += data.rep_per
            }
          })
          nameAry.push({ name: name, rep_per: sum })
        }
      })
      return nameAry
    },
    mosViewRelation(item) {
      let nameAry = []
      item.working_memberJoin.filter((row, index) => {
        let name = row.krajoin.user_join.name
        let sum = 0
        row.mos.filter((singlerow, index) => {
          sum += singlerow.rep_per
        })
        nameAry.push({ name: name, rep_per: sum })
      })
      return nameAry
    },
  },
  beforeUpdate() {
    if (this.select_months.length > 0) {
      this.isDisabled = false
    } else {
      this.isDisabled = true
    }
  },
  computed: {
    filteredItems() {
      return this.items.filter((item) => {
        return (
          item.mos_name.toLowerCase().indexOf(this.search.toLowerCase()) > -1
        )
      })
    },
  },
}
</script>
