<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"
                        ><i class="bx bx-home-alt"></i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/daily_work' }">
                        Daily Work
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">Add daily work</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section class="input-validation">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body">
                      <form @submit.prevent="create()">
                        <div class="row mb-2">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="Profession">Date</label>
                              <div class="controls">
                                <datepicker
                                  v-model="addForm.date"
                                  name="date"
                                  :disabled-dates="state.disabledDates"
                                  class="form-control"
                                ></datepicker>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="Profession">KRA</label>
                              <div class="controls">
                                <select
                                  id="Profession"
                                  name="kra_id"
                                  @change="getKpi()"
                                  v-model="addForm.kra_id"
                                  :class="{
                                    'is-invalid': addForm.errors.has('kra_id'),
                                  }"
                                  class="form-control"
                                >
                                  <option value="">Select one</option>
                                  <option
                                    v-for="row in kraItem"
                                    :key="row.id"
                                    :value="row.id"
                                  >
                                    {{ row.kra_name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="Profession">KPI</label>
                              <div class="controls">
                                <select
                                  id="Profession"
                                  name="kpi_id"
                                  @change="getMos()"
                                  v-model="addForm.kpi_id"
                                  :class="{
                                    'is-invalid': addForm.errors.has('kpi_id'),
                                  }"
                                  class="form-control"
                                >
                                  <option value="">Select one</option>
                                  <option
                                    v-for="row in kpiItem"
                                    :key="row.id"
                                    :value="row.id"
                                  >
                                    {{ row.kpi_name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="Profession">MOS</label>
                              <div class="controls">
                                <select
                                  id="Profession"
                                  name="mos_id"
                                  v-model="addForm.mos_id"
                                  :class="{
                                    'is-invalid': addForm.errors.has('mos_id'),
                                  }"
                                  class="form-control"
                                >
                                  <option value="">Select one</option>
                                  <option
                                    v-for="row in mosItem"
                                    :key="row.id"
                                    :value="row.id"
                                  >
                                    {{ row.mos_name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th style="width: 4px">No</th>
                              <th>Work Details</th>
                              <th style="width: 130px">Working Type</th>
                              <th
                                style="width: 130px"
                                v-if="Object.keys(DepartmentsItems).length > 1"
                              >
                                Department
                              </th>
                              <th style="width: 20px">Start Time</th>
                              <th style="width: 20px">End Time</th>
                              <th style="width: 20px">Working Time</th>
                              <th style="width: 100px">Top Priority</th>
                              <th style="width: 80px">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template
                              v-for="(schedule, index) in addForm.tasks"
                            >
                              <tr :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                  <textarea
                                    rows="1"
                                    v-model="addForm.tasks[index].task"
                                    class="form-control"
                                  ></textarea>
                                </td>
                                <td>
                                  <select
                                    id="Profession"
                                    name="mos_id"
                                    v-model="
                                      addForm.tasks[index].schedule_type_id
                                    "
                                    :class="{
                                      'is-invalid':
                                        addForm.errors.has('mos_id'),
                                    }"
                                    class="form-control"
                                  >
                                    <option value="">Select one</option>
                                    <option
                                      v-for="row in scheduleTypes"
                                      :key="row.id"
                                      :value="row.id"
                                    >
                                      {{ row.name }}
                                    </option>
                                  </select>
                                </td>
                                <td
                                  v-if="
                                    Object.keys(DepartmentsItems).length > 1
                                  "
                                >
                                  <fieldset class="form-group mb-0">
                                    <Select2
                                      v-model="addForm.tasks[index].department"
                                      placeholder="Select One"
                                      :options="DepartmentsItems"
                                    />
                                  </fieldset>
                                </td>
                                <td>
                                  <vue-timepicker
                                    format="hh:mm A"
                                    close-on-complete
                                    manual-input
                                    v-model="addForm.tasks[index].start_time"
                                    :minute-interval="15"
                                  ></vue-timepicker>
                                </td>
                                <td>
                                  <vue-timepicker
                                    format="hh:mm A"
                                    close-on-complete
                                    manual-input
                                    v-model="addForm.tasks[index].end_time"
                                    :minute-interval="15"
                                  ></vue-timepicker>
                                </td>
                                <td>{{ timeCalculation(index) }}</td>
                                <td>
                                  <input
                                    class="form-control custom_checkbox"
                                    v-model="addForm.tasks[index].top_priority"
                                    style="
                                      border: 1px solid #efefef;
                                      border-radius: 10px;
                                    "
                                    type="checkbox"
                                    name="top_priority"
                                    value="1"
                                  /><label
                                    style="padding-left: 5px"
                                    for="Profession"
                                  ></label>
                                </td>
                                <td>
                                  <button
                                    class="btn-success"
                                    type="button"
                                    @click="item_add()"
                                    v-if="addForm.tasks.length - 1 == index"
                                  >
                                    <i class="bx bx-plus"></i>
                                  </button>
                                  <button
                                    class="btn-danger"
                                    type="button"
                                    @click="item_removes(index)"
                                  >
                                    <i class="bx bx-trash"></i>
                                  </button>
                                </td>
                              </tr>
                            </template>
                            <template v-for="(type, index2) in scheduleTypes">
                              <tr :key="index2">
                                <td
                                  colspan="2"
                                  v-if="index2 == 0"
                                  :rowspan="scheduleTypes.length"
                                ></td>
                                <td colspan="3" class="text-right">
                                  {{ type.name }}
                                </td>
                                <td>{{ timeConvert(getTypeTime(type.id)) }}</td>
                                <td>{{ getTypeTopPriority(type.id) }}</td>
                              </tr>
                            </template>
                            <tr>
                              <th colspan="4"></th>
                              <th class="text-right">Total</th>
                              <th>{{ timeConvert(getTypeTotalTime()) }}</th>
                              <th>{{ getTypeTopTotalPriority() }}</th>
                            </tr>
                            <!-- <tr> 
                                                            <td colspan="3">Function</td>
                                                            <td colspan="2">1h</td>
                                                        </tr> -->
                          </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">
                          Submit
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- Input Validation end -->
        </div>
      </div>
    </div>
    <div>
      <!-- <quasar-tiptap v-bind="options" @update="onUpdate" /> -->
    </div>
  </div>
</template>

<script>
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import axios from '../../axios_instance'

import Select2 from 'v-select2-component'
import { Form } from 'vform'
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Datepicker from 'vuejs-datepicker'
import Prioroty_task from '../priority_task/priority_task.vue'

export default {
  props: {},
  components: {
    Prioroty_task,
    VueTimepicker,
    Datepicker,
    Select2: Select2,
  },
  data() {
    return {
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : new Date().getFullYear(),
      base_url: window.base_url,
      api_url: window.api_url,
      DepartmentsItems: [],
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      scheduleTypes: [],
      scheduleTypes_allow: false,

      addForm: new Form({
        //task: "",
        dept_id: '',
        kra_id: '',
        kpi_id: '',
        mos_id: '',
        top_priority: 0,
        date: new Date(),
        start_time: '08:30:00',
        end_time: '17:15:00',
        user_id: 1,
        tasks: [
          {
            schedule_type_id: 1,
            task: '',
            start_time: '08:30 AM',
            end_time: '09:30 AM',
            duration: 0,
            department: 0,
            top_priority: false,
          },
        ],
      }),
      status: 0,
      kraItem: [],
      kpiItem: [],
      mosItem: [],
      state: {
        disabledDates: {
          to: new Date(this.getToDate()),
          //from: new Date(this.getFromDate()),
        },
      },
    }
  },

  created() {
    this.role_id = this.user_data.role_id
    this.getKRA()
    this.dept()
    this.dailyScheduleTypes()
  },
  methods: {
    timeCalculation(index) {
      let start_time = this.addForm.tasks[index].start_time
      let end_time = this.addForm.tasks[index].end_time
      if (start_time && end_time) {
        let total = this.timeToMin(end_time) - this.timeToMin(start_time)
        console.log(end_time, start_time)
        console.log(total)
        this.addForm.tasks[index].duration = total
        return this.timeConvert(total)
      } else {
        return '0m'
      }
    },
    timeConvert(n) {
      var num = n
      var hours = Math.floor(num / 60)
      var minutes = num % 60

      var formattedTime = ''

      if (hours > 0) {
        formattedTime += hours + 'h '
      }

      if (minutes > 0) {
        formattedTime += minutes + 'm'
      }

      return formattedTime || '0m'
    },
    timeToMin(time) {
      let timeArray = time.split(':')
      let h = Number(timeArray[0])
      let m = Number(timeArray[1].split(' ')[0])
      let am_pm = timeArray[1].split(' ')[1]

      if (am_pm.toLowerCase() === 'pm' && h !== 12) {
        h += 12
      }

      m = h * 60 + m
      return m
    },
    getToDate() {
      var date = new Date()
      date.setDate(date.getDate() - 3)
      var finalDate =
        date.getFullYear() +
        ', ' +
        (date.getMonth() + 1) +
        ', ' +
        date.getDate()
      return finalDate
    },
    getTypeTime(id) {
      let totalTime = 0
      this.addForm.tasks.forEach((element) => {
        if (element.schedule_type_id == id) {
          totalTime = totalTime + Number(element.duration)
        }
      })
      return totalTime
    },
    getTypeTotalTime() {
      let totalTime = 0
      this.addForm.tasks.forEach((element) => {
        totalTime = totalTime + Number(element.duration)
      })
      return totalTime
    },
    getTypeTopPriority(id) {
      let top_priority = 0
      this.addForm.tasks.forEach((element) => {
        if (element.schedule_type_id == id && element.top_priority == true) {
          top_priority = top_priority + 1
        }
      })
      return top_priority ? top_priority : ''
    },
    getTypeTopTotalPriority() {
      let top_priority = 0
      this.addForm.tasks.forEach((element) => {
        if (element.top_priority == true) {
          top_priority = top_priority + 1
        }
      })
      return top_priority ? top_priority : ''
    },
    //end
    async dailyScheduleTypes() {
      console.log('======================================oooooooooooooooooo')
      await axios
        .get(this.api_url + 'daily_schedule_types', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.scheduleTypes = data.data
          console.log(this.scheduleTypes)
        })
    },
    scheduleTypesName(id) {
      for (let index = 0; index < this.scheduleTypes.length; index++) {
        if (this.scheduleTypes[index].id == id) {
          return this.scheduleTypes[index].name
        }
      }
    },
    item_add() {
      let newItem = {
        schedule_type_id: '',
        schedule_details: '',
        task: '',
        start_time: '',
        end_time: '',
        duration: 0,
        department: 0,
        top_priority: false,
      }
      this.addForm.tasks.push(newItem)
    },
    item_removes(id) {
      console.log(id)
      this.$swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this item!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.addForm.tasks.splice(id, 1)
          // this.$swal("Your item has been deleted!", {
          // icon: "success",
          // });
        }
      })
    },
    getFromDate() {
      var date = new Date()
      date.setDate(date.getDate() + 1)
      var finalDate =
        date.getFullYear() +
        ', ' +
        (date.getMonth() + 1) +
        ', ' +
        date.getDate()
      return finalDate
    },
    onUpdate({ getJSON, getHTML }) {
      this.json = getJSON()
      this.html = getHTML()
      console.log('html', this.html)
    },
    create() {
      try {
        let loader = this.$loading.show()
        // this.addForm.task = this.$refs.editor.getContent();
        this.addForm
          .post(this.api_url + 'daily_schedules', {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(
            (res) => {
              console.log(res)
              console.log(console.log(res.headers))
              if (res.data.success) {
                this.$toasted.show(res.data.message, {
                  theme: 'bubble',
                  duration: 5000,
                  position: 'bottom-right',
                })
              }
              loader.hide()
              this.$router.push('/task')
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
    async dept() {
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          this.DepartmentsItems = data.data
          if (this.DepartmentsItems.length > 1) {
            this.DepartmentsItems.push({ id: 'all', text: 'All Department' })
          }
        }
      })
    },
    async getKRA() {
      await axios
        .get(this.api_url + 'k_r_a_s?year=' + this.year, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.kraItem = data.data
        })
    },
    async getKpi() {
      console.log(this.addForm.kra_id)
      await axios
        .get(this.api_url + 'k_p_i_s?kra_id=' + this.addForm.kra_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.kpiItem = data.data
          console.log(this.roles)
        })
    },
    async getMos() {
      await axios
        .get(this.api_url + 'm_o_s?kpi_id=' + this.addForm.kpi_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.mosItem = data.data
          console.log(this.roles)
        })
    },
    async getRole() {
      await axios
        .get(this.api_url + 'role', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.roles = data.data
          console.log(this.roles)
        })
    },
  },
  computed: {},
}
</script>
<style>
.number_2 {
  position: absolute;
  top: 2px !important;
  right: 2px !important;
  color: white;
  border-radius: 17px;
  width: 20px;
  height: 22px;
  font-size: 15px;
  align-content: center;
  text-align: center;
}

.task-table .thead-dark th {
  background: #e65e0c !important;
  border-color: 1px solid #dfe3e7 !important;
}
</style>
