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
                  <!-- <div class="card-header">
                                        <h4 class="card-title">Add daily work</h4>
                                    </div> -->
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

                          <div
                            class="col-md-3"
                            v-if="role_id == 5 || role_id == 6 || role_id == 7"
                          >
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
                          <div
                            class="col-md-3"
                            v-if="role_id == 5 || role_id == 6 || role_id == 7"
                          >
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

                          <div
                            class="col-md-3"
                            v-if="role_id == 5 || role_id == 6 || role_id == 7"
                          >
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

                        <!-- <div class="row schedule_row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label>Work Details</label>
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="task" ref="editor" 
                                                                v-model="addForm.task"></ckeditor> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" v-if=" role_id == 5 ">
                                                        <div class="form-group"> 
                                                            <label for="Profession"></label>
                                                            <div class="controls custom_controls">
                                                               
                                                               <input v-model="addForm.top_priority" style="border: 1px solid #efefef ; border-radius:  10px;" type="checkbox"
                                                                    name="top_priority" value="1" ><label style="padding-left:5px ;" for="Profession">Top priority </label> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->

                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th style="width: 4px">No</th>
                              <th style="width: 500px">Work Details</th>
                              <th style="width: 250px">Project</th>
                              <th style="width: 20px">Start Time</th>
                              <th style="width: 20px">End Time</th>
                              <th style="width: 20px">Working Time</th>
                              <th style="width: 100px">Unplanned</th>
                              <th style="width: 100px">Non-Opt</th>
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
                                    v-model="addForm.tasks[index].task"
                                    class="form-control"
                                    required
                                  >
                                  </textarea>
                                  <!-- <input placeholder="Work task" style="width: 100%;" type="text" value=""
                                                                        v-model="addForm.tasks[index].schedule_details" /> -->
                                </td>
                                <td style="widows: 100%">
                                  <!-- <select id="Profession" name="mos_id"
                                                                        v-model="addForm.tasks[index].project_id"
                                                                        :class="{  'is-invalid': addForm.errors.has('mos_id'),  }"
                                                                        class="form-control" required>
                                                                        <option value="">Select Projects</option>
                                                                        <option v-for="row in projectsItem" :key="row.id"
                                                                            :value="row.id">{{ row.name}}</option>
                                                                    </select> -->
                                  <Select2
                                    placeholder="Select Project"
                                    v-model="addForm.tasks[index].project_id"
                                    :options="projectsItem"
                                  />
                                </td>
                                <td>
                                  <vue-timepicker
                                    format="hh:mm a"
                                    close-on-complete
                                    manual-input
                                    v-model="addForm.tasks[index].start_time"
                                    :minute-interval="15"
                                  ></vue-timepicker>
                                </td>
                                <td>
                                  <vue-timepicker
                                    format="hh:mm a"
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
                                    v-model="addForm.tasks[index].work_type"
                                    style="
                                      border: 1px solid #efefef;
                                      border-radius: 10px;
                                    "
                                    type="checkbox"
                                    name="top_priority"
                                    value="1"
                                  />
                                  <label
                                    style="padding-left: 5px"
                                    for="Profession"
                                  ></label>
                                </td>
                                <td>
                                  <input
                                    class="form-control custom_checkbox"
                                    v-model="addForm.tasks[index].task_type"
                                    style="
                                      border: 1px solid #efefef;
                                      border-radius: 10px;
                                    "
                                    type="checkbox"
                                    name="top_priority"
                                    value="1"
                                  />
                                  <label
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
import Select2 from 'v-select2-component'
import { Form } from 'vform'
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import axios from '../../axios_instance'
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Datepicker from 'vuejs-datepicker'
export default {
  props: {},
  components: {
    Select2: Select2,
    VueTimepicker,
    Datepicker,
  },
  data() {
    return {
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : new Date().getFullYear(),
      base_url: window.base_url,
      api_url: window.api_url,
      DepartmentsItems: [],
      startTimeHere: '08:30 am',
      endTimeHere: '09:30 am',
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
            task_type: 0,
            work_type: 0,
            schedule_type_id: 1,
            project_id: '',
            task: '',
            start_time: '08:30 am',
            end_time: '09:30 am',
            duration: 0,
            top_priority: false,
          },
        ],
      }),
      filterForm: new Form({
        wing_id: '',
      }),
      kraItem: [],
      kpiItem: [],
      mosItem: [],
      projectsItem: {},
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
    this.getProjects()
    this.dept()
    if (
      this.role_id == 1 ||
      this.role_id == 2 ||
      this.role_id == 3 ||
      (this.role_id == 4) | (this.role_id == 5)
    ) {
      this.scheduleTypes_allow = true
      this.dailyScheduleTypes()
    }
  },
  methods: {
    async getProjects() {
      let where = '?1=1'
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      await axios
        .get(this.api_url + 'projects' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.projectsItem = data.data
          // console.log('ddddddd' , this.projectsItem);
        })
    },
    //start
    timeCalculation(index) {
      let start_time = this.addForm.tasks[index].start_time
      let end_time = this.addForm.tasks[index].end_time
      if (start_time && end_time) {
        let total = this.timeToMin(end_time) - this.timeToMin(start_time)
        this.addForm.tasks[index].duration = total
        return this.timeConvert(total)
      } else {
        return '0m'
      }
    },
    timeConvert(n) {
      var num = n
      var hours = num / 60
      var rhours = Math.floor(hours)
      var minutes = (hours - rhours) * 60
      var rminutes = Math.round(minutes)
      return (rhours ? rhours + 'h ' : '') + (rminutes ? rminutes + 'm' : '')
    },
    timeToMin(start_time) {
      let start_time_end = start_time.split(' ')
      let am_pm = start_time_end[1]
      let timeArray = start_time_end[0].split(':')
      let h = Number(timeArray[0])
      let m = Number(timeArray[1])
      if (am_pm == 'pm' && h != 12) {
        h = h + 12
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
      var startDate = new Date() // Get current date
      var startTime = new Date(startDate.toDateString() + this.startTimeHere)
      var endTime = new Date(startDate.toDateString() + this.endTimeHere)

      startTime.setHours(startTime.getHours() + 1)
      endTime.setHours(endTime.getHours() + 1)

      this.startTimeHere = startTime.toLocaleTimeString()
      this.endTimeHere = endTime.toLocaleTimeString()

      let newItem = {
        schedule_type_id: '',
        schedule_details: '',
        task: '',
        start_time: this.startTimeHere,
        end_time: this.endTimeHere,
        duration: 0,
        top_priority: false,
      }
      this.addForm.tasks.push(newItem)
      // console.log('this.startTimeHere' , this.startTimeHere);
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
        // console.log('this.addForm' , this.addForm);
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
              // console.log(console.log(res.headers));
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
      this.getDepartments().then(({ data }) => {
        if (data.success) {
          this.DepartmentsItems = data.data
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
<style></style>
