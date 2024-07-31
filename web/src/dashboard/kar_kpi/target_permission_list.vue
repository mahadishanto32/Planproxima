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
                      MOS Target modification permission request
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
                  v-if="role_id == 5 || role_id == 6"
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
                  v-if="role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <!-- <select class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.user_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                                                {{ row.employee_id ? row.employee_id + ' : ' : '' }} {{ row.name }}
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
                              <!-- <th>KRA Weightage</th> -->
                              <th>KPI</th>
                              <!-- <th>KPI Weightage</th> -->
                              <th>MOS</th>
                              <!-- <th>MOS Weightage</th>  -->
                              <th style="width: 350px">
                                <div class="row">
                                  <div class="col-12 col-sm-6">
                                    <label class="control-label text-white">
                                      Start Date
                                    </label>
                                    <fieldset class="form-group">
                                      <datepicker
                                        v-model="start_date"
                                        name="start_date"
                                        @closed="startDateClosedFunction"
                                        class="form-control"
                                      >
                                      </datepicker>
                                    </fieldset>
                                  </div>
                                  <div class="col-12 col-sm-6">
                                    <label class="control-label text-white"
                                      >End Date
                                    </label>
                                    <fieldset class="form-group">
                                      <datepicker
                                        v-model="end_date"
                                        name="end_date"
                                        @closed="endDateClosedFunction"
                                        class="form-control"
                                      >
                                      </datepicker>
                                    </fieldset>
                                  </div>
                                </div>
                              </th>
                              <th style="width: 100px">
                                Permission
                                <input
                                  v-on:change="AllItem()"
                                  v-model="all_item"
                                  value="1"
                                  type="checkbox"
                                />
                                <!-- <a @click="permission_update()" class="mail_send"> <i
                                                                        style="color: #FFFFFF;"
                                                                        class="bx bx-mail-send"></i> </a> -->
                                <button
                                  type="button"
                                  class="btn btn-primary btn_bottom_fixed add-btn btn-lg d-flex align-items-center"
                                  @click="permission_update()"
                                >
                                  Submit ({{ item_selects.length }})
                                </button>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id">
                                <td
                                  :rowspan="rowVisible(index, item, 'kra')"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id || index == 0
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ''
                                  }}
                                </td>
                                <!-- <td :rowspan="rowVisible(index,item,'kra')"
                                                                    v-if="(items[index > 0 ? index - 1 : 0 ].kra_id != item.kra_id || index ==0)">
                                                                    {{ item.krajoin ? item.krajoin.kra_weight : '' }}
                                                                </td> -->
                                <td
                                  :rowspan="rowVisible(index, item, 'kpi')"
                                  v-if="
                                    items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id || index == 0
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_name : ''
                                  }}
                                </td>
                                <!-- <td :rowspan="rowVisible(index,item,'kpi')"
                                                                v-if="(items[index > 0 ? index - 1 : 0 ].kpi_id != item.kpi_id || index ==0)">
                                                                    {{ item.kpijoin ? item.kpijoin.kpi_weight : '' }}
                                                                </td> -->
                                <td>
                                  {{ item.mos_name }}
                                  <strong>(W : {{ item.weightage }})</strong>
                                </td>
                                <!-- <td>{{ item.weightage }}</td> -->
                                <td>
                                  <div class="row">
                                    <div class="col-12 col-sm-6">
                                      <label class="control-label">
                                        Start Date
                                      </label>
                                      <fieldset class="form-group">
                                        <datepicker
                                          v-model="item.start_date"
                                          name="start_date"
                                          class="form-control"
                                        ></datepicker>
                                      </fieldset>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                      <label class="control-label"
                                        >End Date
                                      </label>
                                      <fieldset class="form-group">
                                        <datepicker
                                          v-model="item.end_date"
                                          name="end_date"
                                          class="form-control"
                                        ></datepicker>
                                      </fieldset>
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <input
                                    v-if="
                                      item.modification_type == 1 &&
                                      item.modification_status == 1
                                    "
                                    v-on:change="selectChange(index)"
                                    v-model="item.checked"
                                    value="row.id"
                                    type="checkbox"
                                  />

                                  <p
                                    style="
                                      border: 1px solid #efefef;
                                      text-align: center;
                                      border-radius: 8px;
                                    "
                                    v-if="item.modification_status == 3"
                                  >
                                    Acknowledged by HOD
                                  </p>
                                  <!-- <p style="border:  1px solid #efefef; text-align:  center; border-radius: 8px;"
                                                                        v-if="item.modification_type == 1 && item.modification_status == 1">
                                                                        Request Pending</p>
                                                                    <p style="border:  1px solid #efefef; text-align:  center; border-radius: 8px;"
                                                                        v-if="item.modification_type == 1 && item.modification_status == 2">
                                                                        {{ item.start_date }} - {{ item.end_date }}</p> -->
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
                <th colspan="2" class="text-center">
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
                <th colspan="2" class="text-center">
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
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">Mail CC1</label>
                    <div class="controls">
                      <input
                        v-model="permission_mailForm.mailcc1"
                        placeholder="example1@gmail.com"
                        class="form-control"
                        type="text"
                      />
                    </div>
                  </div>
                </th>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">Mail CC3</label>
                    <div class="controls">
                      <input
                        v-model="permission_mailForm.mailcc2"
                        placeholder="example2@gmail.com"
                        class="form-control"
                        type="text"
                      />
                    </div>
                  </div>
                </th>
                <th class="text-center">
                  <div class="form-group">
                    <label for="Profession">Mail CC3</label>
                    <div class="controls">
                      <input
                        v-model="permission_mailForm.mailcc3"
                        placeholder="example3@gmail.com"
                        class="form-control"
                        type="text"
                      />
                    </div>
                  </div>
                </th>
                <th class="text-center">
                  <button @click="monthly_permission()" class="btn btn-success">
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

<script>
import Select2 from 'v-select2-component'
import { Form } from 'vform'
import { VueEditor } from 'vue2-editor'
import Datepicker from 'vuejs-datepicker'
import axios from '../../axios_instance'
export default {
  props: {},
  components: {
    Select2: Select2,
    Datepicker,
    VueEditor,
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      all_item: 0,
      base_url: window.base_url,
      api_url: window.api_url,
      permission_mailForm: new Form({
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
      start_date: '',
      end_date: '',
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : new Date().getFullYear(),
      filterForm: new Form({
        dept_id: '',
        wing_id: '',
        user_id: '',
      }),
      item_selects: [],
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

    if (this.$route.query.dept_id || this.$route.query.user_id) {
      this.filterForm.user_id = this.$route.query.user_id
        ? this.$route.query.user_id
        : ''
      this.filterForm.dept_id = this.$route.query.dept_id
        ? this.$route.query.dept_id
        : this.filterForm.dept_id
      this.getItems()
    }
  },
  methods: {
    rowVisible(index, item, type) {
      let crount = 0
      this.items.filter((row) => {
        if (type == 'kra') {
          if (row.kra_id === item.kra_id) {
            crount += 1
          }
        } else if (type == 'kpi') {
          if (row.kpi_id === item.kpi_id) {
            crount += 1
          }
        }
      })
      return crount
    },
    startDateClosedFunction() {
      for (let index = 0; index < this.items.length; index++) {
        this.items[index].start_date = this.start_date
      }
    },
    endDateClosedFunction() {
      for (let index = 0; index < this.items.length; index++) {
        this.items[index].end_date = this.end_date
      }
    },
    AllItem() {
      console.log(this.all_item)
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
    permission_update() {
      try {
        let loader = this.$loading.show()
        let ids = []
        this.item_selects.forEach((element) => {
          if (element.checked) {
            ids.push(element)
          }
        })
        this.permission_mailForm.items = this.items
        this.permission_mailForm.ids = ids
        this.permission_mailForm.user_id = this.filterForm.user_id
        //mos_modification_permission_acknowledge
        var url = this.api_url

        // if (this.role_id == 1 || (this.user_data.dept_id ) ) {
        //     url = url + 'mos_modification_permission_approved';
        // } else {

        // }
        if (
          this.user_data.dept_id == 1 ||
          this.user_data.dept_id == 40 ||
          this.user_data.dept_id == 41
        ) {
          url = url + 'mos_modification_permission_acknowledge'
        } else {
          url = url + 'mos_modification_permission_approved'
        }
        this.permission_mailForm
          .post(url, {
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
              this.getItems()
              loader.hide()
              // this.$router.push('/daily_work');
            },
            (error) => {
              console.log(error)
              loader.hide()
            }
          )
      } catch (error) {
        // loader.hide();
        console.log(error)
      }
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
          console.log(this.WingsItems)
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

          console.log(this.WingsUser)
        })
      // }
    },
    async getItems(load = false) {
      //this.getWing();
      if (this.filterForm.dept_id != '' || this.filterForm.params.user_id) {
        let where =
          '?year=' + (this.year ? this.year : new Date().getFullYear())
        if (this.filterForm.dept_id) {
          where += '&dept_id=' + this.filterForm.dept_id
        }
        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id
        }
        if (this.filterForm.user_id) {
          where += '&user_id=' + this.filterForm.user_id
        }
        if (this.$route.query.dept_id) {
          where += '&dept_id=' + this.$route.query.dept_id
        }
        if (this.$route.query.user_id) {
          where += '&user_id=' + this.$route.query.user_id
        }

        let loader
        if (load) {
          loader = this.$loading.show()
        }

        try {
          await axios
            .get(this.api_url + 'target_permission_list' + where, {
              headers: {
                'Content-Type': 'application/json',
                Authorization: this.token ? `Bearer ${this.token}` : '',
              },
            })
            .then(({ data }) => {
              if (data.success) {
                this.items = data.data
                console.log('this.items', this.items)
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
