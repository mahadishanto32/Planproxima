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
                    <li class="breadcrumb-item active">Daily Works</li>
                  </ol>
                </div>
              </div> 
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1 border rounded py-2 mb-2">
              <div class="row ">
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="
                    (role_id == 1 ||
                      role_id == 2 ||
                      role_id == 3 ||
                      role_id == 4 ||
                      role_id == 5) &&
                    deptItems.length > 1
                  "
                >
                  <label for="users-list-verified"> Department </label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      id="users-list-verified"
                      v-model="filterForm.dept_id"
                      v-on:change="getWing()"
                    >
                      <option value="">Select One</option>
                      <option
                        :key="row.id"
                        :value="row.id"
                        v-for="row in deptItems"
                      >
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="
                    role_id == 1 ||
                    role_id == 2 ||
                    role_id == 3 ||
                    role_id == 4 ||
                    role_id == 5 ||
                    role_id == 6
                  "
                >
                  <label for="users-list-verified"> Wing </label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      id="users-list-verified"
                      v-model="filterForm.wing_id"
                      v-on:change="getUser()"
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
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="
                    role_id == 1 ||
                    role_id == 2 ||
                    role_id == 3 ||
                    role_id == 4 ||
                    role_id == 5 ||
                    role_id == 6
                  "
                >
                  <label for="users-list-verified"> Employee </label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      id="users-list-verified"
                      v-model="filterForm.user_id"
                      v-on:change="getItems()"
                    >
                      <option value="">Select One</option>
                      <option
                        :key="row.id"
                        :value="row.id"
                        v-for="row in userItems"
                      >
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div> 
            
                <!-- FROM DATE  -->
                <div class="col-12 col-sm-6 col-lg-2" >
                  <label class="control-label"> <span v-if="filterForm.dateRange==true"> From </span> Date </label>
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

                <!-- TO DATE  -->
                <div class="col-12 col-sm-6 col-lg-2" v-if="filterForm.dateRange==true">
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

                <div class="col-12 col-sm-6 col-lg-2" >
                  <label class="control-label"> With Date Range </label>
                  <fieldset class="form-group">
                    <input type="checkbox" 
                      class="from-control" 
                      @click="datepickerClosedFunction"
                      name="dateRange" 
                      v-model="filterForm.dateRange" 
                    />

                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                  <div
                    class="row"
                    v-if="
                      role_id == 5 ||
                      role_id == 6 ||
                      role_id == 7 ||
                      role_id == 9 ||
                      role_id == 10
                    "
                  >
                    <div class="col-lg-2">
                      <label class="control-label"> KRA </label>
                      <fieldset class="form-group">
                        <input
                          checked=""
                          type="checkbox"
                          v-model="filterForm.show_kra"
                          value="1"
                        />
                      </fieldset>
                    </div>
                    <div class="col-lg-2">
                      <label class="control-label"> KPI </label>
                      <fieldset class="form-group">
                        <input
                          checked=""
                          type="checkbox"
                          v-model="filterForm.show_kpi"
                          value="1"
                        />
                      </fieldset>
                    </div>
                    <div class="col-lg-2">
                      <label class="control-label"> MOS </label>
                      <fieldset class="form-group">
                        <input
                          checked=""
                          type="checkbox"
                          v-model="filterForm.show_mos"
                          value="1"
                        />
                      </fieldset>
                    </div>
                    <!-- <div class="form-inline justify-content-center row" style="padding-top: 10px;">
                                    <label class="mb-2 mr-sm-2 col-1"> <strong></strong><br></label>
                                    <label class="mb-2 mr-sm-2 col-1"> <strong></strong> <input type="checkbox" checked="" value="1"  v-model="filterForm.show_kpi"></label>
                                    <label class="mb-2 mr-sm-2 col-1"> <strong></strong> <input type="checkbox" checked="" value="1"  v-model="filterForm."></label> 
                                </div> -->
                  </div>
                </div>
                <!-- @change="dateChange()" -->
              </div>
                <div class="row" v-if="scheduleTypes_allow"> 
                    <div class="col-12 col-sm-6 col-lg-2" v-for="(schedule ,index) in scheduleTypes" :key="index" >  
                        <input type="checkbox" 
                          class="from-control" 
                          @click="datepickerClosedFunction"
                          name="dateRange" 
                          :value="schedule.id"
                          v-model="filterForm.schedule[schedule.id]" 
                        />
                        <label class="control-label" style="padding-left:5px ;"> {{ schedule.name }} </label> 
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
                        <!-- NEW DEPARTMENT ENTRY -->
                        <button
                          type="button"
                          class="
                            btn btn-primary
                            add-btn
                            btn-lg
                            d-flex
                            align-items-center
                          "
                          v-if="
                            dept_id == 24 ||
                            dept_id == 25 ||
                            dept_id == 27 ||
                            dept_id == 30 ||
                            dept_id == 69 ||
                            dept_id == 50
                          "
                        >
                          <router-link
                            :to="{ path: '/add_fac_work' }"
                            class="text-white"
                          >
                            <i class="bx bx-add-alt"> </i>
                            Fac Add
                          </router-link>
                        </button>

                        <!-- NEW SCHEDULE -->
                        <button
                          type="button"
                          class="
                            btn btn-primary
                            add-btn
                            btn-lg
                            d-flex
                            align-items-center
                          "
                        > 
                          <router-link
                            :to="{ path: '/add_daily_work' }"
                            class="text-white" 
                          >
                            <i class="bx bx-add-alt"> </i>
                            New Work Schedule
                          </router-link> | 

                          <router-link
                          :to="{ path: '/daily_work_calendar' }"
                          class="text-white" style="margin-left: 10px ;"
                        >
                          <i class="bx bx-add-alt"> </i>
                          Work Schedule (Calendar View)
                        </router-link>

                          
                        </button>

                        <button
                          type="button"
                          class="
                            btn btn-primary
                            add-btn
                            btn-lg
                            d-flex
                            align-items-center
                          "
                          v-if="
                            role_id == 1 ||
                            role_id == 2 ||
                            role_id == 3 ||
                            role_id == 4 ||
                            role_id == 8
                          "
                        >
                          <!--  DAILY NOT UPDATE BUTTON -->
                          <router-link
                            :to="{ path: '/daliy_not_update' }"
                            class="text-white"
                          >
                            <i class="bx bx-add-alt"> </i>
                            Daily Not Update
                          </router-link>
                        </button>
                      </div>

                      <h3>Daily Works</h3>
                      <br />
                      <div class="table-responsive">
                        <table
                          class="
                            table
                            table-bordered
                            table-condensed
                            table-hover
                            table-striped
                          "
                        >
                          <thead class="thead-dark">
                            <tr>
                              <th style="width: 15%">Date</th>
                              <th style="width: 5%">Time</th>
                              <th
                                v-if="
                                  role_id == 1 ||
                                  role_id == 2 ||
                                  role_id == 3 ||
                                  role_id == 4 ||
                                  role_id == 8
                                "
                              >
                                Dept.
                              </th>
                              <th style="width: 70%">Things to Do</th>
                              <th
                                style="width: 5%"
                                v-if="
                                  filterForm.show_kra == 1 &&
                                  (role_id == 5 || role_id == 6 || role_id == 7)
                                "
                              >
                                KRA
                              </th>
                              <th
                                style="width: 5%"
                                v-if="
                                  filterForm.show_kpi == 1 &&
                                  (role_id == 5 || role_id == 6 || role_id == 7)
                                "
                              >
                                KPI
                              </th>
                              <th
                                style="width: 5%"
                                v-if="
                                  filterForm.show_mos == 1 &&
                                  (role_id == 5 || role_id == 6 || role_id == 7)
                                "
                              >
                                MOS
                              </th>
                              <th style="width: 5%">Comments</th>
                              <th
                                v-if="
                                  role_id == 5 || role_id == 6 || role_id == 7 || role_id==3
                                "
                              >
                                Name
                              </th>
                              <th style="width: 5%">Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                             
                            <template v-for="(item, index) in items">
                              <tr
                                :key="index"
                                v-if="
                                  item.role_id == 1 ||
                                  item.role_id == 2 ||
                                  item.role_id == 3 ||
                                  item.role_id == 4 ||
                                  item.role_id == 5
                                "
                                v-bind:class="[
                                  item.status == 1 ? 'done' : 'not_done',
                                ]"
                              >
                                <td>
                                  {{ format_Date(item.date) }}
                                </td>
                                <td>
                                  {{ formatAMPM(item.start_time) }} to
                                  {{ formatAMPM(item.end_time) }}
                                </td>
                                <td
                                  v-if="
                                    role_id == 1 ||
                                    role_id == 2 ||
                                    role_id == 3 ||
                                    role_id == 4 ||
                                    role_id == 8
                                  "
                                >
                                  {{ item.deptjoin ? item.deptjoin.name : "" }}
                                </td>
                                <td>
                                  <p class="blinking" v-if="item.role_id == 6">
                                    <strong>
                                      <u> Wing </u>
                                    </strong>
                                  </p>
                                  <p
                                    style="color: red; font-size: 16px"
                                    v-if="item.top_priority"
                                  >
                                    <strong>
                                      <u> Top priority </u>
                                    </strong>
                                  </p>
                                  <strong v-if="item.factory_formatjoin">
                                    {{
                                      item.factory_formatjoin
                                        ? item.factory_formatjoin.headname
                                        : ""
                                    }}
                                  </strong>
                                  
                                  <p v-html="item.task"></p>

                                  <div v-if="item.schedule_items.length > 0 ">
                                    <div v-for="(row, index) in item.schedule_items" :key="index" class="schedule_row_list" >
                                      <strong >{{row.name ? row.name: ""}}</strong>
                                      <p v-html="row.schedule_details"></p>
                                    </div>
                                  </div> 
                                 
                                   
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kra == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kpi == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_mos == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.mosjoin ? item.mosjoin.mos_name : ""
                                  }}
                                </td>
                                <td>
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
                                <td
                                  v-if="
                                    role_id == 5 || role_id == 6 || role_id == 7 || role_id==3
                                  "
                                >
                                  {{ item.userjoin ? item.userjoin.name : "" }}
                                </td>
                                <td>
                                  <a
                                    @click="statusChange(1, item)"
                                    v-if="item.status == 0"
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
                                  <a v-if="item.status == 1">
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
                                <td>
                                  <div class="dropup">
                                    <span
                                      @click="comment_show(item)"
                                      class="comment_count"
                                      v-if="item.comments.length > 0"
                                    >
                                      {{ item.comments.length }}
                                    </span>
                                    <span
                                      aria-expanded="false"
                                      aria-haspopup="true"
                                      class="
                                        bx bx-dots-vertical-rounded
                                        font-medium-3
                                        dropdown-toggle
                                        nav-hide-arrow
                                        cursor-pointer
                                      "
                                      data-toggle="dropdown"
                                      role="menu"
                                    >
                                    </span>
                                    <div
                                      class="dropdown-menu dropdown-menu-right"
                                    >
                                      <a
                                        @click="popUp(item)"
                                        class="dropdown-item"
                                      >
                                        <i class="bx bx-task mr-1"> </i>
                                        Details
                                      </a>
                                      <router-link
                                      v-if="format_Date(item.date) == currentDate "
                                        :to="{
                                          path: '/edit_daily_work/' + item.id,
                                        }"
                                         class="dropdown-item">
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
                                      <router-link
                                      class="dropdown-item"
                                        v-if="user_data.id == 26 "
                                        :to="{
                                          path: '/copy_follow_up/' + item.id,
                                        }"> 
                                        <i class="bx bx-comment mr-1"> </i>
                                        Send To Follow
                                      </router-link>
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
                            <tr v-if="role_id == 5">
                              <th
                                :colspan="
                                  role_id == 1 ||
                                  role_id == 2 ||
                                  role_id == 3 ||
                                  role_id == 4 ||
                                  role_id == 8
                                    ? 7
                                    : 9
                                "
                              >
                                <div style="padding-top: 20px">Wings</div>
                              </th>
                            </tr>
                            <tr
                              v-if="
                                checkDatacheck(6) == true &&
                                (role_id == 5 || role_id == 6)
                              "
                            >
                              <th
                                :colspan="
                                  role_id == 1 ||
                                  role_id == 2 ||
                                  role_id == 3 ||
                                  role_id == 4 ||
                                  role_id == 8
                                    ? 7
                                    : 9
                                "
                              >
                                No data found
                              </th>
                            </tr>
                            <template v-for="(item, index) in items">
                              <tr
                                :key="index"
                                v-if="item.role_id == 6"
                                v-bind:class="[
                                  item.status == 1 ? 'done' : 'not_done',
                                ]"
                              >
                                <td>
                                  {{ format_Date(item.date) }}
                                </td>
                                <td>
                                  {{ formatAMPM(item.start_time) }} to
                                  {{ formatAMPM(item.end_time) }}
                                </td>
                                <td
                                  v-if="
                                    role_id == 1 ||
                                    role_id == 2 ||
                                    role_id == 3 ||
                                    role_id == 4 ||
                                    role_id == 8
                                  "
                                >
                                  {{ item.deptjoin ? item.deptjoin.name : "" }}
                                </td>
                                <td>
                                  <strong v-if="item.factory_formatjoin">
                                    {{
                                      item.factory_formatjoin
                                        ? item.factory_formatjoin.headname
                                        : ""
                                    }}
                                  </strong>
                                  <p v-html="item.task"></p>
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kra == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kpi == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_mos == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.mosjoin ? item.mosjoin.mos_name : ""
                                  }}
                                </td>
                                <td>
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
                                <td
                                  v-if="
                                    role_id == 5 || role_id == 6 || role_id == 7
                                  "
                                >
                                  {{ item.userjoin ? item.userjoin.name : "" }}
                                </td>
                                <td>
                                  <a
                                    @click="statusChange(1, item)"
                                    v-if="item.status == 0"
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
                                    @click="statusChange(0, item)"
                                    v-if="item.status == 1"
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
                                <td>
                                  <div class="dropup">
                                    <span
                                      @click="comment_show(item)"
                                      class="comment_count"
                                      v-if="item.comments.length > 0"
                                    >
                                      {{ item.comments.length }}
                                    </span>
                                    <span
                                      aria-expanded="false"
                                      aria-haspopup="true"
                                      class="
                                        bx bx-dots-vertical-rounded
                                        font-medium-3
                                        dropdown-toggle
                                        nav-hide-arrow
                                        cursor-pointer
                                      "
                                      data-toggle="dropdown"
                                      role="menu"
                                    >
                                    </span>
                                    <div
                                      class="dropdown-menu dropdown-menu-right"
                                    >
                                      <a
                                        @click="popUp(item)"
                                        class="dropdown-item"
                                      >
                                        <i class="bx bx-task mr-1"> </i>
                                        Details
                                      </a>
                                      <router-link
                                      v-if="format_Date(item.date) == currentDate "
                                        :to="{
                                          path: '/edit_daily_work/' + item.id,
                                        }"
                                       
                                        class="dropdown-item"
                                      >
                                      <!-- v-if="format_Date(item.date) == currentDate" -->
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
                                      <router-link
                                      class="dropdown-item"
                                        v-if="user_data.id == 26 "
                                        :to="{
                                          path: '/copy_follow_up/' + item.id,
                                        }"> 
                                        <i class="bx bx-comment mr-1"> </i>
                                         Send To Follow
                                      </router-link>
                                      <a @click="delete_row(item.id)"  class="dropdown-item" >
                                        <i class="bx bx-trash mr-1"> </i>
                                        Delete
                                      </a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </template>

                            <tr v-if="role_id == 5 || role_id == 6">
                              <th
                                :colspan="
                                  role_id == 1 ||
                                  role_id == 2 ||
                                  role_id == 3 ||
                                  role_id == 4 ||
                                  role_id == 8
                                    ? 7
                                    : 9
                                "
                              >
                                <div class="blinking" style="padding-top: 20px">
                                  Employee
                                </div>
                              </th>
                            </tr>
                            <tr
                              v-if="
                                checkDatacheck(7) &&
                                (role_id == 5 || role_id == 6)
                              "
                            >
                              <th
                                :colspan="
                                  role_id == 1 ||
                                  role_id == 2 ||
                                  role_id == 3 ||
                                  role_id == 4 ||
                                  role_id == 8
                                    ? 7
                                    : 9
                                "
                              >
                                No data found
                              </th>
                            </tr>
                            <template v-for="(item, index) in items">
                              <tr
                                :key="index"
                                v-if="item.role_id == 7"
                                v-bind:class="[
                                  item.status == 1 ? 'done' : 'not_done',
                                ]"
                              >
                                <td>
                                  {{ format_Date(item.date) }}
                                </td>
                                <td>
                                  {{ formatAMPM(item.start_time) }} to
                                  {{ formatAMPM(item.end_time) }}
                                </td>
                                <td
                                  v-if="
                                    role_id == 1 ||
                                    role_id == 2 ||
                                    role_id == 3 ||
                                    role_id == 4 ||
                                    role_id == 8
                                  "
                                >
                                  {{ item.deptjoin ? item.deptjoin.name : "" }}
                                </td>
                                <td>
                                  <!--                                                                        <div style="width:100%; height:20px">
                                                                        <span @click="copy(item.task)" style="position:absolute; right: 10px; cursor: pointer;">
                                                                            <i class="bx bx-clipboard "></i>Copy
                                                                        </span>
                                                                        </div>-->
                                  <strong v-if="item.factory_formatjoin">
                                    {{
                                      item.factory_formatjoin
                                        ? item.factory_formatjoin.headname
                                        : ""
                                    }}
                                  </strong>
                                  <p v-html="item.task"></p>
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kra == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_kpi == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.kpijoin ? item.kpijoin.kpi_name : ""
                                  }}
                                </td>
                                <td
                                  v-if="
                                    filterForm.show_mos == 1 &&
                                    (role_id == 5 ||
                                      role_id == 6 ||
                                      role_id == 7)
                                  "
                                >
                                  {{
                                    item.mosjoin ? item.mosjoin.mos_name : ""
                                  }}
                                </td>
                                <td>
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
                                <td
                                  v-if="
                                    role_id == 5 || role_id == 6 || role_id == 7
                                  "
                                >
                                  {{ item.userjoin ? item.userjoin.name : "" }}
                                </td>
                                <td>
                                  <a
                                    @click="statusChange(1, item)"
                                    v-if="item.status == 0"
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
                                    @click="statusChange(0, item)"
                                    v-if="item.status == 1"
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
                                <td>
                                  <div class="dropup">
                                    <span
                                      @click="comment_show(item)"
                                      class="comment_count"
                                      v-if="item.comments.length > 0"
                                    >
                                      {{ item.comments.length }}
                                    </span>
                                    <span
                                      aria-expanded="false"
                                      aria-haspopup="true"
                                      class="
                                        bx bx-dots-vertical-rounded
                                        font-medium-3
                                        dropdown-toggle
                                        nav-hide-arrow
                                        cursor-pointer
                                      "
                                      data-toggle="dropdown"
                                      role="menu"
                                    >
                                    </span>
                                    <div
                                      class="dropdown-menu dropdown-menu-right"
                                    >
                                      <a
                                        @click="popUp(item)"
                                        class="dropdown-item"
                                      >
                                        <i class="bx bx-task mr-1"> </i>
                                        Details
                                      </a>
                                      <router-link
                                        :to="{
                                          path: '/edit_daily_work/' + item.id,
                                        }"
                                        v-if="format_Date(item.date) == currentDate "
                                        class="dropdown-item"
                                      >
                                      <!-- v-if="format_Date(item.date) == currentDate " -->
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
          <modal
            height="90%"
            name="popup-singel"
            style="padding: 50px"
            width="60%"
          >
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"> </i>
            <div class="app-content">
              <div class="card">
                <h4>
                  {{ item.userjoin ? item.userjoin.name : "" }}
                </h4>
                <table class="table table-bordered table-striped table-sm">
                  <tbody>
                    <tr class="text-center">
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Date</th>
                    </tr>
                    <tr>
                      <td>
                        {{ item.start_time }}
                      </td>
                      <td>
                        {{ item.end_time }}
                      </td>
                      <td>
                        {{ format_Date(item.date) }}
                      </td>
                    </tr>
                    <tr class="text-center">
                      <th>KRA</th>
                      <th>KPI</th>
                      <th>MOS</th>
                    </tr>
                    <tr>
                      <td>
                        {{ item.krajoin ? item.krajoin.kra_name : "" }}
                      </td>
                      <td>
                        {{ item.kpijoin ? item.kpijoin.kpi_name : "" }}
                      </td>
                      <td>
                        {{ item.mosjoin ? item.mosjoin.mos_name : "" }}
                      </td>
                    </tr>
                    <tr class="text-center">
                      <th colspan="3">Things to Do</th>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <p
                          style="color: red; font-size: 16px"
                          v-if="item.top_priority"
                        >
                          <strong>
                            <u> Top priority </u>
                          </strong>
                        </p>
                        <strong v-if="item.factory_formatjoin">
                          {{
                            item.factory_formatjoin
                              ? item.factory_formatjoin.headname
                              : ""
                          }}
                        </strong>
                        <p v-html="item.task"></p>
                      </td>
                    </tr>
                    <!-- <th>{{ item.end_time }}</td>
                                    <td     >
                                      <p v-if="item.top_priority" style="color: red; font-size: 16px"><strong><u>Top priority</u></strong></p>
                                      <p v-html="item.task" ></p>
                                    </td>
                                    <td>Comment</td>
                                    <td>{{ item.krajoin ? item.krajoin.kra_name : '' }} </td>
                                    <td>{{ item.kpijoin ? item.kpijoin.kpi_name : '' }} </td>
                                    <td>{{ item.mosjoin ? item.mosjoin.mos_name : '' }} </td>
                                    <td>{{ item.userjoin ? item.userjoin.name : '' }}  </td> -->
                  </tbody>
                </table>
              </div>
            </div>
          </modal>
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
      </div>
    </div>
  </div>
</template>
<script>
import axios from "../../axios_instance";
import { Form } from "vform";
import Datepicker from "vuejs-datepicker";
import { VueEditor } from "vue2-editor";
export default {
  props: {},
  components: {
    Datepicker,
    VueEditor,
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: "",
      myItems: [],
      items: [],
      item: [],
      deptItems: [],
      WingsItems: [],
      userItems: [],
      status: "",
      comment_active: "comments",
      scheduleTypes : [],
      scheduleTypes_allow : false ,
      filterForm: new Form({
        schedule : [],
        show_kra: 1,
        show_kpi: 1,
        show_mos: 1,
        dept_id: "",
        wing_id: "",
        user_id: "",
        dateRange: false,
        date: new Date(),
        toDate: new Date(),
      }),
      comment_mailForm: new Form({
        mailcc1: "",
        mailcc2: "",
        mailcc3: "",
        comment: "",
        daily_schedule_id: "",
      }),
      statusForm: new Form({}),
      state: {
        disabledDates: {
          to: new Date(2020, 0, 0), // Disable all dates up to specific date
          from: new Date(2023, 0, 0), // Disable all dates after specific date
        },
      },
      currentDate: "",
    };
  },
  created() {
    this.role_id = this.user_data.role_id;
    this.dept_id = this.user_data.dept_id;
    if (this.role_id == 5) {
      this.filterForm.dept_id = this.user_data.dept_id;
      this.getDept("first");
      this.getWing();
      // this.getMyItems();
      // this.getItems();
    } else if (this.role_id == 6) {
      this.filterForm.dept_id = this.user_data.dept_id;
      this.filterForm.wing_id = this.user_data.wing_id;
      this.getUser();
      this.getWing();
    } else if (
      this.role_id == 1 ||
      this.role_id == 2 ||
      this.role_id == 3 ||
      this.role_id == 4 ||
      this.role_id == 8
    ) {
      // this.getMyItems();
      this.getDept("first");
    } else if (this.role_id == 7) {
      this.filterForm.dept_id = this.user_data.dept_id;
      this.filterForm.wing_id = this.user_data.wing_id;
      this.filterForm.user_id = this.user_data.id;
      // this.getItems();
    } else {
      // this.getMyItems()
    }

    this.getItems();

    //CURRENT DATE
    let today = new Date();
    let m ;
    m = today.getMonth() +  1 ; 
    if(m < 10){
      m = '0'+m ;
    }
    let d ; 
    d = today.getDate(); 
    if(d < 10){
      d = '0'+d ;
    }
    
    this.currentDate = today.getFullYear() +"-" + m +  "-" + d ;
    if(this.role_id == 1 || this.role_id == 2 ||  this.role_id == 3 || this.role_id == 4 | this.role_id == 5){ 
        this.scheduleTypes_allow =  true ;
        this.dailyScheduleTypes();
    }
  },
  methods: {
    /*        copy(task){
             this.$clipboard(task);
        },*/
    checkDatacheck(role_id) {
      let return_typr = true;
      this.items.filter((item) => {
        if (item.role_id == role_id) {
          return_typr = false;
        }
      });
      return return_typr;
    },
    tabs(i) {
      this.comment_active = i;
    },
    statusChange(type, item) {
      // let loader = this.$loading.show();
      this.$swal({
        title: "Are you sure?",
        text: type == 1 ? "This task complete?" : "This task status change ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          item.status = type;
          this.statusForm.task = item.task;
          this.statusForm.status = item.status;
          this.statusForm.kra_id = item.kra_id;
          this.statusForm.kpi_id = item.kpi_id;
          this.statusForm.mos_id = item.mos_id;
          this.statusForm.date = item.date;
          this.statusForm.start_time = item.start_time;
          this.statusForm.end_time = item.end_time;
          this.statusForm.user_id = item.user_id;
          this.statusForm
            .put(this.api_url + "daily_schedules/" + item.id, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then(
              (res) => {
                console.log(res);
                //loader.hide();
                // this.$swal("Your task status has been updated!", {
                //     icon: "success",
                // });

                this.$toasted.show("Your task status has been updated!", {
                  theme: "bubble",
                  duration: 5000,
                  position: "bottom-right",
                });
              },
              (error) => {
                console.log(error);
                // loader.hide();
              }
            );
        } else {
          // loader.hide();
          this.$toasted.show("Your task status is not change!", {
            theme: "bubble",
            duration: 5000,
            position: "bottom-right",
          });
          //this.$swal("Your task status is not change!");
        }
      });
    },
    datepickerClosedFunction() {
      console.log(this.filterForm.date);
      this.getItems();
      // if(this.filterForm.dept_id){
      //     this.getItems();
      // }else{
      //     this.getMyItems();
      // }
    },
    dateChange() {
      console.log(this.filterForm.date);
    },
    dateSelected() {
      console.log(this.filterForm.date);
      this.getItems();
    },
    popUp(item) {
      this.item = item;
      this.$modal.show("popup-singel");
    },
    hide_pop() {
      this.$modal.hide("popup-singel");
    },
    comment_show(item) {
      this.item = item;
      console.log(this.item);
      this.$modal.show("comment");
    },
    comment_hidden() {
      this.$modal.hide("comment");
    },
    task_comment() {
      try {
        let loader = this.$loading.show();
        // let loader = this.$loading.show();

        this.comment_mailForm.daily_schedule_id = this.item.id;
        this.comment_mailForm
          .post(this.api_url + "daily_schedule_comments", {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(
            (res) => {
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
    async delete_row(id) {
      console.log(id);
      let loader = this.$loading.show();
      try {
        await axios
          .delete(this.api_url + "daily_schedules/" + id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ res }) => {
            this.getItems();
            //this.getMyItems();
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
    async getItems() {
      let where = "?1=1";
      if (this.filterForm.dept_id) {
        where += "&dept_id=" + this.filterForm.dept_id;
      }
      if (this.filterForm.wing_id) {
        where += "&wing_id=" + this.filterForm.wing_id;
      }
      if (this.filterForm.user_id) {
        where += "&user_id=" + this.filterForm.user_id;
      }
      if(this.dailyScheduleTypes ){
        where += "&schedule_type=" + JSON.stringify(this.filterForm.schedule)  ;
        // console.log('this.filterForm.schedule');
        // console.log(this.filterForm.schedule);
        // console.log('this.filterForm.schedule');
        
      }
      if (this.filterForm.dateRange) {
        if (this.filterForm.date) {
          where += "&date=" + this.format_Date(this.filterForm.date);
        }
        if (this.filterForm.toDate) {
          where += "&toDate=" + this.format_Date(this.filterForm.toDate);
        }
      }else{
        if (this.filterForm.date) {
          where += "&date=" + this.format_Date(this.filterForm.date);
        }
        if (this.filterForm.toDate) {
          where += "&toDate=" + this.format_Date(this.filterForm.date);
        }        
      }    
      //TO DATE

      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "daily_schedules" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.items = data.data;
            }
            loader.hide();
          });
        // if(this.role_id != 5 && this.role_id != 6){
        //     this.getMyItems() ;
        // }
      } catch (error) {
        loader.hide();
      }
    },
    async getWingItems() {
      let where = "?1=1";
      if (this.filterForm.dept_id) {
        where += "&dept_id=" + this.filterForm.dept_id;
      }
      // if (this.filterForm.wing_id) {
      //     where += '&wing_id=' + this.filterForm.wing_id;
      // }
      // if (this.filterForm.user_id) {
      //     where += '&user_id=' + this.filterForm.user_id;
      // }
      if (this.filterForm.date) {
        where += "&date=" + this.format_Date(this.filterForm.date);
      }

      where += "&role_id=7";

      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "daily_schedules" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.wingsitems = data.data;
            }
            loader.hide();
          });
        // if(this.role_id != 5 && this.role_id != 6){
        //     this.getMyItems() ;
        // }
      } catch (error) {
        loader.hide();
      }
    },

    async getMyItems() {
      // console.log('text');
      // if(this.filterForm.dept_id =='' && (this.role_id == 1 || this.role_id == 2 || this.role_id == 3 || this.role_id == 4 ||   this.role_id == 8 )){
      //         let where = '?1=1';
      //         if (this.filterForm.date) {
      //             where += '&date=' + this.format_Date(this.filterForm.date);
      //         }
      //         if (this.filterForm.toDate) {
      //           where += '&toDate=' + this.format_Date(this.filterForm.toDate);
      //         }
      //         try {
      //             await axios
      //                 .get(this.api_url + "my_daily_schedules"+where, {
      //                     headers: {
      //                         "Content-Type": "application/json",
      //                         Authorization: this.token ? `Bearer ${this.token}` : ""
      //                     },
      //                 })
      //                 .then(({
      //                     data
      //                 }) => {
      //                     if (data.success) {
      //                         this.myItems = data.data
      //                     }
      //                 });
      //         } catch (error) {
      //         console.log(error);
      //         }
      // }else{
      //     this.myItems = [];
      // }
    },
    async dailyScheduleTypes(){
                await axios.get(this.api_url + "daily_schedule_types", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.scheduleTypes = data.data;
                        console.log(this.scheduleTypes);
                    });
            },
    async getDept(type = null) {
      let loader = this.$loading.show();
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          loader.hide();
          this.deptItems = data.data;
          if (type != "first" && this.filterForm.dept_id != "") {
            this.getItems();
          }
        } else {
          loader.hide();
        }
      });
    },
    async getWing() {
      this.own = false;
      await axios
        .get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(({ data }) => {
          this.WingsItems = data.data;
          this.getItems();
          //this.getMyItems();
          console.log(this.WingsItems);
        });
    },
    async getUser() {
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
            this.userItems = data.data;
            this.getItems();
            // console.log('this console test',this.employeeItem);
        });

    },
  },
  computed: {},
};
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
  background: #4b79a1; /* fallback for old browsers */
  background: -webkit-linear-gradient(
    to bottom,
    #283e51,
    #4b79a1
  ); /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(
    to bottom,
    #283e51,
    #4b79a1
  ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  padding: 20px 8px;
  border-top: none !important;
  border-bottom: none !important;
  color: #ffffff !important;
}
</style>