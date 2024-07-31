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
                      <router-link :to="{ path: '/' }">
                        <i class="bx bx-home-alt"> </i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">
                      Daily Works
                      <label class="mb-2 mr-sm-2 col-1" @click="demoDownload()">
                      </label>
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1 border rounded py-2 mb-2">
              <div class="row mb-2">
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="deptItems.length > 1"
                >
                  <label for="users-list-verified"> Department </label>
                  <fieldset class="form-group">
                    <Select2
                      v-model="filterForm.dept_id"
                      placeholder="Select One"
                      :options="deptItems"
                      v-on:change="changeDept()"
                    />
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id < 7">
                  <label for="users-list-verified"> Wing </label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      id="users-list-verified"
                      v-model="filterForm.wing_id"
                      v-on:change="changeWing()"
                    >
                      <option value="">Select One</option>
                      <option
                        :key="row.id"
                        :value="row.id"
                        v-for="row in WingsItems"
                      >
                        {{ row.wing_title }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label">From Date </label>
                  <fieldset class="form-group">
                    <datepicker
                      :disabled-dates="state.disabledDates"
                      @closed="datepickerClosedFunction"
                      class="form-control"
                      name="date"
                      v-model="filterForm.date"
                    >
                    </datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label"> TO Date </label>
                  <fieldset class="form-group">
                    <datepicker
                      :disabled-dates="state.disabledDates"
                      @closed="datepickerClosedFunction"
                      class="form-control"
                      name="toDate"
                      v-model="filterForm.toDate"
                    >
                    </datepicker>
                  </fieldset>
                </div>
              </div>
              <div class="row mb-2">
                <div
                  class="col-12 col-sm-6 col-lg-3"
                  v-for="(schedule, index) in scheduleTypes"
                  :key="index"
                >
                  <div>
                    <input
                      type="checkbox"
                      class="from-control"
                      @change="checkTypes($event, schedule.id)"
                      name="dateRange"
                      v-model="filterForm.schedule[schedule.id]"
                    />
                    <label class="control-label" style="padding-left: 5px">
                      {{ schedule.name }}
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
                      <div
                        class="btn-group mb-1"
                        role="group"
                        aria-label="Basic example"
                      >
                        <!-- NEW SCHEDULE -->

                        <button
                          type="button"
                          class="btn btn-primary add-btn btn-lg d-flex align-items-center"
                        >
                          <router-link
                            :to="{ path: '/new_task' }"
                            class="text-white"
                          >
                            <i class="bx bx-add-alt"> </i>
                            New Work Schedule
                          </router-link>
                        </button>
                        <button
                          type="button"
                          class="btn btn-primary add-btn btn-lg d-flex align-items-center"
                        >
                          <router-link
                            :to="{ path: '/daily_work_calendar' }"
                            class="text-white"
                            style="margin-left: 10px"
                          >
                            <i class="bx bx-add-alt"> </i> Work Schedule
                            (Calendar View)
                          </router-link>
                        </button>
                      </div>
                      <h3>Daily Works</h3>
                      <br />
                      <div class="table-responsive">
                        <table
                          class="table table-bordered table-condensed table-hover table-striped"
                        >
                          <thead class="thead-dark">
                            <tr>
                              <th style="width: 6%">Date</th>
                              <th>Name/Dept.</th>
                              <template>
                                <template
                                  v-for="(schedule, index) in scheduleTypes"
                                >
                                  <th style="width: 25%" :key="index">
                                    {{ schedule.name }}
                                  </th>
                                </template>
                              </template>

                              <th style="width: 5%">Comments</th>
                              <th
                                v-if="filterForm.report_viwe == false"
                                style="width: 3%"
                              >
                                Action
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <template>
                                <tr
                                  :key="index"
                                  v-bind:class="[
                                    item.status == 1 ? 'done' : 'not_done',
                                  ]"
                                >
                                  <td>
                                    {{ format_Date(item.date) }}
                                  </td>
                                  <td>
                                    {{
                                      item.userjoin ? item.userjoin.name : ''
                                    }}
                                    <span v-if="item.deptjoin">
                                      (
                                      {{
                                        item.deptjoin ? item.deptjoin.name : ''
                                      }})</span
                                    >
                                  </td>
                                  <template
                                    v-for="(schedule, i) in scheduleTypes"
                                  >
                                    <td :key="i" style="vertical-align: top">
                                      <div v-if="item.tasks.length > 0">
                                        <table
                                          class="table table-bordered table-condensed table-hover table-striped"
                                        >
                                          <tbody>
                                            <template
                                              v-for="(
                                                row, index2
                                              ) in item.tasks"
                                            >
                                              <tr
                                                :key="index2"
                                                v-if="
                                                  searchValueTaypeVisible(
                                                    row.schedule_type_id
                                                  ) &&
                                                  row.schedule_type_id ==
                                                    schedule.id
                                                "
                                              >
                                                <td>
                                                  <p
                                                    style="
                                                      color: red;
                                                      font-size: 16px;
                                                    "
                                                    v-if="row.top_priority"
                                                  >
                                                    <strong>
                                                      <u> Top priority </u>
                                                    </strong>
                                                  </p>
                                                  <p
                                                    v-html="
                                                      row.task.replace(
                                                        /(?:\r\n|\r|\n)/g,
                                                        '<br />'
                                                      )
                                                    "
                                                  ></p>
                                                  <p
                                                    v-if="deptItems.length > 1"
                                                  >
                                                    {{ row.department_list }}
                                                  </p>
                                                  <p
                                                    class="start-to-end-time"
                                                    v-if="
                                                      row.start_time &&
                                                      row.start_time.length < 11
                                                    "
                                                  >
                                                    {{ row.start_time }} -
                                                    {{ row.end_time }}
                                                  </p>
                                                </td>
                                                <td
                                                  style="width: 5%"
                                                  v-if="
                                                    filterForm.report_viwe ==
                                                    false
                                                  "
                                                >
                                                  <a
                                                    @click="
                                                      taskStatusChange(
                                                        index,
                                                        index2,
                                                        1,
                                                        row
                                                      )
                                                    "
                                                    v-if="row.status == 0"
                                                  >
                                                    <img
                                                      :src="
                                                        base_url +
                                                        'assets/app-assets/images/logo/pen.png'
                                                      "
                                                      class="logo_done"
                                                      width="30px"
                                                    />
                                                  </a>
                                                  <a
                                                    @click="
                                                      taskStatusChange(
                                                        index,
                                                        index2,
                                                        0,
                                                        row
                                                      )
                                                    "
                                                    v-if="row.status == 1"
                                                  >
                                                    <img
                                                      :src="
                                                        base_url +
                                                        'assets/app-assets/images/logo/done.png'
                                                      "
                                                      class="logo_done"
                                                      width="30px"
                                                    />
                                                  </a>
                                                </td>
                                              </tr>
                                            </template>
                                          </tbody>
                                        </table>
                                      </div>
                                    </td>
                                  </template>

                                  <td v-if="filterForm.report_viwe == false">
                                    <div
                                      :key="index3"
                                      v-for="(com, index3) in item.comments"
                                    >
                                      <i> @{{ com.user_name }} </i>
                                      <br />
                                      <p v-html="com.comment"></p>
                                      <br />
                                    </div>
                                  </td>
                                  <td v-if="filterForm.report_viwe == false">
                                    <div class="dropup">
                                      <span
                                        aria-expanded="false"
                                        aria-haspopup="true"
                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                        data-toggle="dropdown"
                                        role="menu"
                                      >
                                      </span>
                                      <div
                                        class="dropdown-menu dropdown-menu-right"
                                      >
                                        <router-link
                                          v-if="
                                            (format_Date(item.date) ==
                                              currentDate &&
                                              deptInfo.template_setting == 2 &&
                                              (role_id == 3 || role_id == 5)) ||
                                            user_data.dept_id == 8
                                          "
                                          :to="{
                                            path: '/edit_task/' + item.id,
                                          }"
                                          class="dropdown-item"
                                        >
                                          <i class="bx bx-edit-alt mr-1"> </i>
                                          edit
                                        </router-link>

                                        <router-link
                                          v-if="
                                            (format_Date(item.date) ==
                                              currentDate &&
                                              deptInfo.template_setting == 1) ||
                                            role_id == 6 ||
                                            role_id == 7 ||
                                            role_id == 10 ||
                                            user_data.id == 24
                                          "
                                          :to="{
                                            path: '/edit_daily_work/' + item.id,
                                          }"
                                          class="dropdown-item"
                                        >
                                          <i class="bx bx-edit-alt mr-1"> </i>
                                          edit
                                        </router-link>

                                        <a
                                          @click="comment_show(item)"
                                          class="dropdown-item"
                                        >
                                          <i class="bx bx-comment mr-1"> </i>
                                          Comment
                                        </a>

                                        <a
                                          @click="delete_row(item.id)"
                                          v-if="item.status == 0"
                                          class="dropdown-item"
                                        >
                                          <i class="bx bx-trash mr-1"> </i>
                                          Delete
                                        </a>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </template>
                            </template>
                          </tbody>
                        </table>
                      </div>
                      <br />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <modal height="80%" name="comment" style="padding: 50px" width="65%">
      <i @click="comment_hidden()" class="bx bx-x-circle x-circle"> </i>
      <div class="app-content">
        <div class="card">
          <section id="dashboard-analytics">
            <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a
                  @click="tabs('comments')"
                  aria-controls="nav-home"
                  aria-selected="true"
                  class="nav-item nav-link"
                  data-toggle="tab"
                  href="#nav-home"
                  id="nav-home-tab"
                  role="tab"
                  v-bind:class="{ active: comment_active == 'comments' }"
                >
                  Comments
                </a>
                <a
                  @click="tabs('add')"
                  aria-controls="nav-home"
                  aria-selected="true"
                  class="nav-item nav-link"
                  data-toggle="tab"
                  href="#nav-home"
                  id="nav-home-tab"
                  role="tab"
                  v-bind:class="{ active: comment_active == 'add' }"
                >
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
                    <vue-editor
                      name="task"
                      placeholder="Comment...."
                      v-model="comment_mailForm.comment"
                    >
                    </vue-editor>
                  </th>
                </tr>
                <tr>
                  <th class="text-center">
                    <div class="form-group">
                      <label for="Profession"> Mail CC1 </label>
                      <div class="controls">
                        <input
                          class="form-control"
                          placeholder="example1@gmail.com"
                          type="text"
                          v-model="comment_mailForm.mailcc1"
                        />
                      </div>
                    </div>
                  </th>
                  <th class="text-center">
                    <div class="form-group">
                      <label for="Profession"> Mail CC3 </label>
                      <div class="controls">
                        <input
                          class="form-control"
                          placeholder="example2@gmail.com"
                          type="text"
                          v-model="comment_mailForm.mailcc2"
                        />
                      </div>
                    </div>
                  </th>
                  <th class="text-center">
                    <div class="form-group">
                      <label for="Profession"> Mail CC3 </label>
                      <div class="controls">
                        <input
                          class="form-control"
                          placeholder="example3@gmail.com"
                          type="text"
                          v-model="comment_mailForm.mailcc3"
                        />
                      </div>
                    </div>
                  </th>
                  <th class="text-center">
                    <button class="btn btn-success">Save</button>
                  </th>
                </tr>
              </tbody>
              <tbody v-if="comment_active == 'comments'">
                <tr class="text-center">
                  <th>Comment</th>
                  <th>User Name</th>
                  <th>Date</th>
                </tr>
                <tr
                  :key="index"
                  class="text-center"
                  v-for="(com, index) in item.comments"
                >
                  <th>
                    <p v-html="com.comment"></p>
                  </th>
                  <th>
                    {{ com.user_name }}
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
    Datepicker,
    VueEditor,
    Select2: Select2,
  },
  data() {
    return {
      columns: [
        {
          label: 'Task',
          field: 'task',
          dataFormat: this.taskData,
          rowspan: 4,
        },
        {
          label: 'Date',
          field: 'date',
          dataFormat: this.taskDate,
          rowspan: 4,
        },
        {
          label: 'Name',
          field: 'userjoin.name',
          rowspan: 4,
        },
      ],
      dataItemExel: [],
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      items: [],
      item: [],
      deptItems: [],
      status: '',
      scheduleTypes: [],
      searchValueTaype: [],
      deptInfo: [],
      comment_active: 'comments',
      filterForm: new Form({
        schedule: [],
        dept_id: '',
        things_to_do: true,
        report_viwe: false,
        dateRange: false,
        date: new Date(),
        toDate: new Date(),
        wing_id: '',
        user_id: '',
        old_format: false,
        task_type: 0,
        work_type: 0,
      }),
      comment_mailForm: new Form({
        mailcc1: '',
        mailcc2: '',
        mailcc3: '',
        comment: '',
        daily_schedule_id: '',
      }),
      statusForm: new Form({}),
      state: {
        disabledDates: {
          to: new Date(2020, 0, 0), // Disable all dates up to specific date
          from: new Date(2030, 0, 0), // Disable all dates after specific date
        },
      },
      currentDate: '',
      dailyCheck: [],
    }
  },
  created() {
    this.dailyTask()
    this.filterForm.user_id = this.user_data.id

    this.role_id = this.user_data.role_id
    this.dept_id = this.user_data.dept_id
    this.getItems()
    this.changeDept()
    this.getDept()
    this.getMydeptInfo(this.dept_id)
    this.dailyScheduleTypes()

    let today = new Date()
    let m
    m = today.getMonth() + 1
    if (m < 10) {
      m = '0' + m
    }
    let d
    d = today.getDate()
    if (d < 10) {
      d = '0' + d
    }
    this.currentDate = today.getFullYear() + '-' + m + '-' + d
  },
  methods: {
    async dailyTask() {
      let where = '?1=1'
      if (this.filterForm.date) {
        where += '&date=' + this.format_Date(this.filterForm.date)
      } else {
        where += '&date=' + this.format_Date(new Date())
      }
      let loader = this.$loading.show()
      try {
        await axios
          .get(this.api_url + 'today_task_list' + where, {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.dailyCheck = data.data
            }
            loader.hide()
          })
      } catch (error) {
        loader.hide()
      }
    },
    taskData(value) {
      return value.replace(/<\/?[^>]+>/gi, ' ')
    },
    taskDate(value) {
      return this.format_Date(value)
    },
    checkTypes(e, typeId) {
      if (e.target.checked) {
        this.searchValueTaype.push(typeId)
      } else {
        for (let index = 0; index < this.searchValueTaype.length; index++) {
          if (this.searchValueTaype[index] == typeId) {
            this.searchValueTaype.splice(index, 1)
          }
        }
      }
      this.getItems()
    },
    tabs(i) {
      this.comment_active = i
    },
    comment_hidden() {
      this.$modal.hide('comment')
    },
    task_comment() {
      try {
        let loader = this.$loading.show()
        this.comment_mailForm.daily_schedule_id = this.item.id
        this.comment_mailForm
          .post(this.api_url + 'daily_schedule_comments', {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(
            (res) => {
              if (res.data.success) {
                this.comment_hidden()
                this.$toasted.show(res.data.message, {
                  theme: 'bubble',
                  duration: 5000,
                  position: 'bottom-right',
                })
              }
              loader.hide()
            },
            (error) => {
              loader.hide()
            }
          )
      } catch (error) {}
    },
    checkDatacheck(role_id) {
      let return_typr = true
      this.items.filter((item) => {
        if (item.role_id == role_id) {
          return_typr = false
        }
      })
      return return_typr
    },
    taskStatusChange(index, intex2, type, item) {
      console.log('check data for live', item)
      let schedule_id =
        item.schedule_type_id === null ? 1 : item.schedule_type_id
      this.items[index].tasks[intex2].status = type ? type : 0
      let statusForm = new Form({
        daily_schedules_id: item.daily_schedules_id,
        status: type,
        schedule_type_id: schedule_id,
      })
      console.log('check data for live', statusForm)
      statusForm
        .put(this.api_url + 'daily_schedule_items/' + item.id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            this.$toasted.show('Your task status has been updated!', {
              theme: 'bubble',
              duration: 2000,
              position: 'bottom-right',
            })
            this.getItems()
            return (item.status = 1)
          },
          (error) => {}
        )
    },
    statusChange(type, item) {
      this.$swal({
        title: 'Are you sure?',
        text: type == 1 ? 'This task complete?' : 'This task status change ?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          item.status = type
          this.statusForm.task = item.task
          this.statusForm.status = item.status
          this.statusForm.kra_id = item.kra_id
          this.statusForm.kpi_id = item.kpi_id
          this.statusForm.mos_id = item.mos_id
          this.statusForm.date = item.date
          this.statusForm.start_time = item.start_time
          this.statusForm.end_time = item.end_time
          this.statusForm.user_id = item.user_id
          this.statusForm
            .put(this.api_url + 'daily_schedules/' + item.id, {
              headers: {
                'Content-Type': 'application/json',
                Authorization: this.token ? `Bearer ${this.token}` : '',
              },
            })
            .then(
              (res) => {
                console.log(res)
                this.$toasted.show('Your task status has been updated!', {
                  theme: 'bubble',
                  duration: 5000,
                  position: 'bottom-right',
                })
              },
              (error) => {}
            )
        } else {
          this.$toasted.show('Your task status is not change!', {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
        }
      })
    },
    datepickerClosedFunction() {
      this.getItems()
    },
    closedFunction() {},
    dateChange() {},
    dateSelected() {
      this.getItems()
    },
    popUp(item) {
      this.item = item
      this.$modal.show('popup-singel')
    },
    hide_pop() {
      this.$modal.hide('popup-singel')
    },
    async delete_row(id) {
      let loader = this.$loading.show()
      try {
        await axios
          .delete(this.api_url + 'daily_schedules/' + id, {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(({ res }) => {
            this.getItems()
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: 'bubble',
                duration: 5000,
                position: 'bottom-right',
              })
            }
            loader.hide()
          })
      } catch (error) {
        loader.hide()
      }
    },
    searchValueTaypeVisible(id) {
      if (this.searchValueTaype.length > 0) {
        for (let index = 0; index < this.searchValueTaype.length; index++) {
          if (this.searchValueTaype[index] == id) {
            return true
          }
        }
      } else {
        return true
      }
    },
    comment_show(item) {
      this.item = item
      this.$modal.show('comment')
    },
    async getItems() {
      let where = '?1=1'
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id
      }
      if (this.filterForm.task_type) {
        where += '&task_type=' + this.filterForm.task_type
      }

      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }

      if (this.filterForm.work_type) {
        where += '&work_type=' + this.filterForm.work_type
      }

      if (this.searchValueTaype.length > 0) {
        where += '&schedule_type=' + this.searchValueTaype // JSON.stringify(this.filterForm.schedule)  ;
      }

      if (this.filterForm.dateRange) {
        if (this.filterForm.date) {
          where += '&date=' + this.format_Date(this.filterForm.date)
        }
        if (this.filterForm.toDate) {
          where += '&toDate=' + this.format_Date(this.filterForm.toDate)
        }
      } else {
        if (this.filterForm.date) {
          where += '&date=' + this.format_Date(this.filterForm.date)
        }
        if (this.filterForm.toDate) {
          where += '&toDate=' + this.format_Date(this.filterForm.date)
        }
      }
      let loader = this.$loading.show()
      try {
        await axios
          .get(this.api_url + 'daily_task_list' + where, {
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
            loader.hide()
          })
      } catch (error) {
        loader.hide()
      }
    },
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
        })
    },
    functionName(id) {
      for (let index = 0; index < this.scheduleTypes.length; index++) {
        if (this.scheduleTypes[index].id == id) {
          return this.scheduleTypes[index].name
        }
      }
    },
    async getDept() {
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          this.deptItems = data.data
          if (this.deptItems.length > 1) {
            this.deptItems.push({ id: 'all', text: 'All Department' })
          }
        }
      })
    },
    async getMydeptInfo(id) {
      await axios
        .get(this.api_url + 'departments/' + id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.deptInfo = data.data
          if (
            (this.deptInfo.template_setting == 2 &&
              (this.role_id == 3 || this.role_id == 5)) ||
            this.role_id == 1
          ) {
            this.dailyScheduleTypes()
          }
        })
    },
    changeDept() {
      this.getWing()
      this.getItems()
    },
    changeWing() {
      this.getUser()
      this.getItems()
    },
    async getWing() {
      this.own = false
      let dept = 0
      if (this.filterForm.dept_id) {
        dept = this.filterForm.dept_id
      } else {
        dept = this.dept_id
      }
      await axios
        .get(this.api_url + 'wings?dept_id=' + dept, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.WingsItems = data.data
        })
    },
    async getUser() {
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
          this.userItems = data.data
          // console.log('this console test',this.employeeItem);
        })
    },
    async demoDownload() {
      // console.log('ddddddddddddd' , 'try');
      let formData = new FormData()
      if (this.filterForm.user_id) {
        formData.append('user_id', this.filterForm.user_id)
      } else {
        formData.append('user_id', this.user_data.id)
      }

      if (this.filterForm.date) {
        formData.append('date', this.format_Date(this.filterForm.date))
      }

      if (this.filterForm.toDate) {
        formData.append('todate', this.format_Date(this.filterForm.toDate))
      }
      // formData.append("user_id", this.filterForm.user_id);
      formData.append('year', this.year)
      await axios
        .post(this.api_url + 'download_daily_task', formData, {
          responseType: 'arraybuffer',
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]))
          var fileLink = document.createElement('a')
          fileLink.href = fileURL
          fileLink.setAttribute('download', 'daily_task.xlsx')
          document.body.appendChild(fileLink)
          fileLink.click()
        })
    },
  },
  computed: {},
}
</script>
<style>
.logo_done {
  width: 30px;
}

p {
  margin-top: 0;
  margin-bottom: 0rem;
}

thead th {
  background: #4b79a1;
  /* fallback for old browsers */
  background: -webkit-linear-gradient(to bottom, #283e51, #4b79a1);
  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to bottom, #283e51, #4b79a1);
  /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  padding: 20px 8px;
  border-top: none !important;
  border-bottom: none !important;
  color: #ffffff !important;
}

.note pre {
  white-space: pre-wrap;
  word-wrap: break-word;
  font-family: inherit;
}
</style>
