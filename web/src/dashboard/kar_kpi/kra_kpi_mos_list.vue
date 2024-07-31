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
                    <li class="breadcrumb-item active">KRA , KPI and MOS</li>
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
                <div
                  class="col-sm-6 col-lg-2"
                  v-if="deptItems.length > 1 && user_data.email != 'cost'"
                >
                  <label for="users-list-verified">Department </label>
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
                  class="ccol-sm-6 col-lg-2"
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
                  class="col-sm-6 col-lg-2"
                  v-if="role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <!-- <select class="form-control" v-on:change="getItems()" v-model="filterForm.user_id"
                      id="users-list-verified">
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
                <!---FILTER-->
                <div class="col-sm-6">
                  <div
                    class="form-inline justify-content-center row"
                    style="padding-top: 30px"
                  >
                    <label class="mb-2 mr-sm-2 col-2"
                      >Show <strong>KRA</strong>
                      <input
                        type="checkbox"
                        checked=""
                        value="0"
                        v-model="filterForm.show_kra"
                    /></label>
                    <label class="mb-2 mr-sm-2 col-2"
                      >Show <strong>KPI</strong>
                      <input
                        type="checkbox"
                        checked=""
                        value="0"
                        v-model="filterForm.show_kpi"
                    /></label>
                    <label class="mb-2 mr-sm-2 col-2"
                      >Show <strong>MOS</strong>
                      <input
                        type="checkbox"
                        checked=""
                        value="0"
                        v-model="filterForm.show_mos"
                    /></label>

                    <label class="mb-2 mr-sm-2 col-2">
                      Download
                      <vue-excel-xlsx
                        :data="dataItemExel"
                        :columns="columns"
                        :filename="'KRA KPI List'"
                        :sheetname="'Target'"
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
                        <!-- <br> 
                        <br> -->
                        <h3>KRA , KPI and MOS</h3>
                        <br />
                        <br />
                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th v-if="filterForm.show_kra == 1">KRA</th>
                              <th v-if="filterForm.show_kra == 1">Weightage</th>
                              <th v-if="filterForm.show_kpi == 1">KPI</th>
                              <th v-if="filterForm.show_mos == 1">MOS</th>
                              <!-- this condition is for 2024 -->
                              <template v-if="year == 2024">
                                <th>Jul</th>
                                <th>Aug</th>
                                <th>Sep</th>
                                <th>Oct</th>
                                <th>Nov</th>
                                <th>Dec</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>May</th>
                                <th>Jun</th>
                              </template>
                              <!-- this condition is for till 2023 jun -->
                              <template v-else>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>May</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Aug</th>
                                <th>Sep</th>
                                <th>Oct</th>
                                <th>Nov</th>
                                <th>Dec</th>
                              </template>
                              <th
                                v-if="
                                  role_id == 5 ||
                                  role_id == 6 ||
                                  role_id == 7 ||
                                  role_id == 10
                                "
                              >
                                Action
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id">
                                <td
                                  :rowspan="rowVisible(index, item, 'kra')"
                                  v-if="
                                    filterForm.show_kra == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id ||
                                      index == 0)
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_name : ''
                                  }}
                                </td>
                                <td
                                  :rowspan="rowVisible(index, item, 'kra')"
                                  v-if="
                                    filterForm.show_kra == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kra_id !=
                                      item.kra_id ||
                                      index == 0)
                                  "
                                >
                                  {{
                                    item.krajoin ? item.krajoin.kra_weight : ''
                                  }}
                                </td>
                                <td
                                  :rowspan="rowVisible(index, item, 'kpi')"
                                  v-if="
                                    filterForm.show_kpi == 1 &&
                                    (items[index > 0 ? index - 1 : 0].kpi_id !=
                                      item.kpi_id ||
                                      index == 0)
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
                                <td v-if="filterForm.show_mos == 1">
                                  {{ item.mos_name }}
                                  <i
                                    class="permission_date"
                                    v-if="
                                      item.modification_type == 1 &&
                                      item.modification_status == 2 &&
                                      dateValidation(item.end_date)
                                    "
                                    >{{ item.start_date }} -
                                    {{ item.end_date }}</i
                                  >
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
                                <!-- this condition is for 2024 -->
                                <template v-if="year == 2024">
                                  <!-- Note: july -->
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.july
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.august
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.september
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.october
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.november
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.december
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <!--Note: january -->
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.january
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.february
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.march
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.april
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.may
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.june
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                </template>
                                <template v-else>
                                  <!--Note: january -->
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.january
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.february
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.march
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.april
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.may
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.june
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <!-- Note: july -->
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.july
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.august
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.september
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.october
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.november
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                  <td>
                                    <i
                                      v-if="
                                        item.mostargetjoin
                                          ? item.mostargetjoin.december
                                          : 0 > 0
                                      "
                                      class="bx bx-map"
                                    ></i>
                                  </td>
                                </template>
                                <!-- Note:December  -->
                                <td
                                  v-if="
                                    role_id == 5 ||
                                    role_id == 6 ||
                                    role_id == 7 ||
                                    role_id == 10
                                  "
                                >
                                  <div class="dropup">
                                    <span
                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                      data-toggle="dropdown"
                                      aria-haspopup="true"
                                      aria-expanded="false"
                                      role="menu"
                                    >
                                    </span>
                                    <div
                                      class="dropdown-menu dropdown-menu-right"
                                    >
                                      <!-- v-if="item.modification_status ==2 && dateValidation(item.end_date)" -->
                                      <router-link
                                        class="dropdown-item"
                                        target="_blank"
                                        :to="{
                                          path:
                                            '/measure_of_success/' +
                                            item.kpi_id,
                                        }"
                                      >
                                        <i class="bx bx-edit-alt mr-1"></i> MOS
                                        Target
                                      </router-link>
                                      <!--- KRA EDIT -->
                                      <a
                                        @click="editKra(item)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        KRA Edit
                                      </a>

                                      <!--- KRA DELETE -->
                                      <a
                                        @click="delete_kra(item.kra_id)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-trash mr-1"> </i>
                                        KRA Delete
                                      </a>

                                      <!--- KPI ADD -->
                                      <a
                                        @click="addKpi(item)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        KPI Add
                                      </a>

                                      <!--- KPI EDIT -->
                                      <a
                                        @click="editKpi(item)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        KPI Edit
                                      </a>

                                      <!--- KPI DELETE -->
                                      <a
                                        @click="delete_kpi(item.kpi_id)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-trash mr-1"> </i>
                                        KPI Delete
                                      </a>

                                      <!--- MOS ADD -->
                                      <a
                                        @click="addMos(item)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        MOS Add
                                      </a>

                                      <!--- MOS EDIT -->
                                      <a
                                        @click="editMos(item)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        MOS Edit
                                      </a>

                                      <!--- MOS DELETE -->
                                      <a
                                        @click="delete_mos(item.id)"
                                        target="_blank"
                                        class="dropdown-item"
                                        v-if="p_data"
                                      >
                                        <i class="bx bx-trash mr-1"> </i>
                                        MOS Delete
                                      </a>

                                      <!-- <a class="dropdown-item" @click="add_kpi(item )"><i class="bx bxs-comment-add mr-1"></i> Add || Edit KPI </a>   -->

                                      <!-- <a v-if="item.krajoin.role_id == 6 || item.krajoin.role_id == 7" @click="AchiTarget(item)" class="dropdown-item">
                                        <i class="bx bx-edit-alt mr-1"> </i>
                                        Yearly Target Achievement
                                      </a>  -->
                                    </div>
                                  </div>
                                  <!-- <button class="btn btn-success btn-sm" > Add || Edit KPI  </button>
                                                                    <router-link class="btn btn-primary add-btn" :to="{ path: '/measure_of_success/'+ item.kpi_id }"> <i class="bx bx-add-alt"></i>MOS  </router-link> 
                                                                     <a class="btn btn-primary btn-sm" href="https://bpt.ssgbd.com/value_fwr/125/378">FWR</a>   -->
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
                      <td>KRA Name</td>
                      <td>{{ item.krajoin ? item.krajoin.kra_name : '' }}</td>
                    </tr>
                    <tr>
                      <td>KPI Name</td>
                      <td>{{ item.kpijoin ? item.kpijoin.kpi_name : '' }}</td>
                    </tr>
                    <tr>
                      <td>MOS Name</td>
                      <td>{{ item.mos_name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </modal>

          <!-- EDIT KRA MODAL -->
          <modal width="60%" height="70%" style="padding: 50px" name="kraedit">
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="updateKra()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KRA Name
                            </label>
                            <div class="controls">
                              <input type="hidden" v-model="kra_id" />
                              <input
                                type="text"
                                v-model="kra_name"
                                class="form-control"
                                placeholder="KRA Name"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KRA Weightage
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="kra_weight"
                                class="form-control"
                                placeholder="KRA WEIGHTAGE"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Update
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </modal>

          <!-- EDIT KPI MODAL -->
          <modal width="60%" height="70%" style="padding: 50px" name="kpiedit">
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="updateKpi()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KPI Name
                            </label>
                            <div class="controls">
                              <input type="hidden" v-model="kra_id" />
                              <input type="hidden" v-model="kpi_id" />
                              <input
                                type="text"
                                v-model="kpi_name"
                                class="form-control"
                                placeholder="KPI Name"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KPI Weightage
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="kpi_weight"
                                class="form-control"
                                placeholder="KPI WEIGHTAGE"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Update
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </modal>

          <!-- ADD KPI MODAL -->
          <modal width="60%" height="70%" style="padding: 50px" name="kpiadd">
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="addNewKpi()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KPI Name
                            </label>
                            <div class="controls">
                              <input type="hidden" v-model="kra_id" />
                              <input
                                type="text"
                                v-model="kpi_name"
                                class="form-control"
                                placeholder="KPI Name"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >KPI Weightage
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="kpi_weight"
                                class="form-control"
                                placeholder="KPI WEIGHTAGE"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Add New
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </modal>

          <!-- ADD NEW MOS -->
          <modal width="60%" height="70%" style="padding: 50px" name="mosadd">
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="addNewMos()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >MOS Name
                            </label>
                            <div class="controls">
                              <input type="hidden" v-model="kra_id" />
                              <input type="hidden" v-model="kpi_id" />
                              <input
                                type="text"
                                v-model="mos_name"
                                class="form-control"
                                placeholder="MOS Name"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >MOS Weightage
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="mos_weight"
                                class="form-control"
                                placeholder="MOS WEIGHTAGE"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Add New
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </modal>

          <!-- EDIT MOS MODAL -->
          <modal width="60%" height="70%" style="padding: 50px" name="mosedit">
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="updateMos()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >MOS Name
                            </label>
                            <div class="controls">
                              <input type="hidden" v-model="mos_id" />
                              <input type="hidden" v-model="kpi_id" />
                              <input
                                type="text"
                                v-model="mos_name"
                                class="form-control"
                                placeholder="MOS Name"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >MOS Weightage
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="mos_weight"
                                class="form-control"
                                placeholder="KPI WEIGHTAGE"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Update
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </modal>

          <!-- Yearly Achiv and Target  -->
          <modal
            width="60%"
            height="70%"
            style="padding: 50px"
            name="achi_target"
          >
            <i @click="hide_pop()" class="bx bx-x-circle x-circle"></i>
            <div class="app-content">
              <div class="card">
                <form @submit.prevent="updateYearlyAchiv()">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-12">
                          <h4>
                            MOS : {{ item.mos_name }}(
                            <strong>W:{{ item.weightage }}</strong> )
                          </h4>
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >Target
                            </label>

                            <div class="controls">
                              <input
                                type="text"
                                v-model="mosatarget.total"
                                class="form-control"
                                placeholder="Target"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="text-gray-600 font-semibold text-lg"
                              >Achievement
                            </label>
                            <div class="controls">
                              <input
                                type="text"
                                v-model="mosachievement.total"
                                class="form-control"
                                placeholder="Achievement"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      Update
                    </button>
                  </div>
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
import Select2 from 'v-select2-component'
import { Form } from 'vform'
import axios from '../../axios_instance'

// import JsonExcel from "vue-json-excel"
export default {
  props: {},
  components: {
    Select2: Select2,
    // "downloadExcel": JsonExcel
    //VueExcelXlsx
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      //Jan	Feb	Mar	Apr	May	Jun	Jul	Aug	Sep	Oct	Nov	Dec
      columns: [
        {
          label: 'KRA',
          field: 'krajoin',
          dataFormat: this.kraName,
          rowspan: 4,
        },
        {
          label: 'KRA Weightage',
          field: 'krajoin',
          dataFormat: this.weightageValue,
        },
        {
          label: 'KPI',
          field: 'kpijoin',
          dataFormat: this.kpiName,
        },
        {
          label: 'KPI Weightage',
          field: 'kpijoin',
          dataFormat: this.kpiweightageValue,
        },
        {
          label: 'MOS',
          field: 'mos_name',
        },
        {
          label: 'MOS Weightage',
          field: 'weightage',
        },
        {
          label: 'Jan',
          field: 'mostargetjoin',
          dataFormat: this.targetJan,
        },
        {
          label: 'Fen',
          field: 'mostargetjoin',
          dataFormat: this.targetFeb,
        },
        {
          label: 'Mar',
          field: 'mostargetjoin',
          dataFormat: this.targetMar,
        },
        {
          label: 'Apr',
          field: 'mostargetjoin',
          dataFormat: this.targetApr,
        },
        {
          label: 'May',
          field: 'mostargetjoin',
          dataFormat: this.targetMay,
        },
        {
          label: 'Jun',
          field: 'mostargetjoin',
          dataFormat: this.targetJun,
        },
        {
          label: 'Jul',
          field: 'mostargetjoin',
          dataFormat: this.targetJul,
        },
        {
          label: 'Aug',
          field: 'mostargetjoin',
          dataFormat: this.targetAug,
        },
        {
          label: 'Sep',
          field: 'mostargetjoin',
          dataFormat: this.targetSep,
        },
        {
          label: 'Oct',
          field: 'mostargetjoin',
          dataFormat: this.targetOct,
        },
        {
          label: 'Nov',
          field: 'mostargetjoin',
          dataFormat: this.targetNov,
        },
        {
          label: 'Dec',
          field: 'mostargetjoin',
          dataFormat: this.targetDec,
        },
      ],
      dataItemExel: [],

      json_fields: {
        'Complete name': 'name',
        City: 'city',
        Telephone: 'phone.mobile',
        'Telephone 2': {
          field: 'phone.landline',
          callback: (value) => {
            return `Landline Phone - ${value}`
          },
        },
      },
      json_data: [
        {
          name: 'Tony Peña',
          city: 'New York',
          country: 'United States',
          birthdate: '1978-03-15',
          phone: {
            mobile: '1-541-754-3010',
            landline: '(541) 754-3010',
          },
        },
        {
          name: 'Thessaloniki',
          city: 'Athens',
          country: 'Greece',
          birthdate: '1987-11-23',
          phone: {
            mobile: '+1 855 275 5071',
            landline: '(2741) 2621-244',
          },
        },
      ],
      json_meta: [
        [
          {
            key: 'charset',
            value: 'utf-8',
          },
        ],
      ],
      p_data: '',
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      items: [],
      item: [],
      WingsItems: [],
      mosachievement: [],
      mosatarget: [],
      deptItems: [],
      ingsItems: [],
      employeeItem: [],
      filterForm: new Form({
        dept_id: this.$route.query.dept_id ? this.$route.query.dept_id : '',
        wing_id: '',
        user_id: '',
        kra_id: this.$route.query.kra_id ? this.$route.query.kra_id : '',
        kpi_id: '',
        quarter: this.$route.query.quarter ? this.$route.query.quarter : '',
        month: this.$route.query.month ? this.$route.query.month : '',
        show_kra: 1,
        show_kpi: 1,
        show_mos: 1,
        show_yachi: 1,
      }),
      kpiForm: new Form({
        dept_id: '',
      }),
      status: '',
      kra_id: '',
      kra_name: '',
      kra_weight: '',
      editKraForm: new Form({}),

      //KPOI INITIAL DATA
      kpi_id: '',
      kpi_name: '',
      kpi_weight: '',
      editKpiForm: new Form({}),
      addKpiForm: new Form({}),

      //MOS INITIAL DATA
      mos_id: '',
      mos_name: '',
      mos_weight: '',
      editMosForm: new Form({}),
      addMosForm: new Form({}),
    }
  },
  created() {
    this.role_id = this.user_data.role_id
    this.filterForm.dept_id = this.user_data.dept_id
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
      this.role_id == 5 ||
      this.role_id == 4
    ) {
      //this.getDept();
      this.getEmployee()
      this.deptChange()
    } else {
      //this.getItems();

      this.getItems()
    }
    this.single_permission()
    // this.getItems();
    //this.getDept();
  },
  methods: {
    target_permission() {
      axios
        .get(this.api_url + 'single_permission', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((res) => {
          this.p_data = res.data.data[0]
          console.log('ds', this.p_data)
        })
    },

    single_permission() {
      axios
        .get(this.api_url + 'single_permission', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((res) => {
          this.p_data = res.data.data[0]
        })
    },
    kpiName(value) {
      return value.kpi_name
    },
    weightageValue(value) {
      return value.kra_weight
    },
    kpiweightageValue(value) {
      return value.kpi_weight
    },
    kraName(value) {
      return value.kra_name
    },
    //Jan	Feb	Mar	Apr	May	Jun	Jul	Aug	Sep	Oct	Nov	Dec
    targetJan(e) {
      return e.january > 0 ? e.january : ''
    },
    targetFeb(e) {
      return e.february > 0 ? e.february : ''
    },
    targetMar(e) {
      return e.march > 0 ? e.march : ''
    },
    targetApr(e) {
      return e.april > 0 ? e.april : ''
    },
    targetMay(e) {
      return e.may > 0 ? e.may : ''
    },
    targetJun(e) {
      return e.june > 0 ? e.june : ''
    },
    targetJul(e) {
      return e.july > 0 ? e.july : ''
    },
    targetAug(e) {
      return e.august > 0 ? e.august : ''
    },
    targetSep(e) {
      return e.september > 0 ? e.september : ''
    },
    targetOct(e) {
      return e.october > 0 ? e.october : ''
    },
    targetNov(e) {
      return e.november > 0 ? e.november : ''
    },
    targetDec(e) {
      return e.december > 0 ? e.december : ''
    },

    hide_pop() {
      this.$modal.hide('popup-singel')
      this.$modal.hide('kraedit')
      this.$modal.hide('kpiedit')
      this.$modal.hide('mosedit')
      this.$modal.hide('kpiadd')
      this.$modal.hide('mosadd')
      this.$modal.hide('achi_target')
    },
    add_kpi(item) {
      this.item = item
      this.$modal.show('popup-singel')
    },
    AchiTarget(item) {
      this.item = item
      this.mosachievement = item.mosachievementjoin
      this.mosatarget = item.mostargetjoin
      this.$modal.show('achi_target')
    },
    AchiTargetHidden() {
      //this.item = item;
      this.$modal.hide('achi_target')
    },
    async updateYearlyAchiv() {
      let loader = this.$loading.show()
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
      })
      // mosatargetData.total  =  mosatarget.total ;
      mosatargetData
        .put(this.api_url + 'mos_datas/' + this.mosatarget.id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {})

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
      })
      // mosachievementData.total  =  mosachievement.total ;
      mosachievementData
        .put(this.api_url + 'mos_datas/' + this.mosachievement.id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
          this.AchiTargetHidden()
          loader.hide()
          //HIDE MODAL
          // this.$modal.hide("kraedit");
          //DATA RELOAD
          // this.getItems();
        })
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
      //if (this.filterForm.wing_id) {
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
      //}
    },
    async getItems(load = false) {
      if (this.filterForm.dept_id != '') {
        let where = ''
        //ADD YEAR PARAM
        where = '?year=' + this.year

        if (this.filterForm.dept_id) {
          where += '&dept_id=' + this.filterForm.dept_id
        }
        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id
        }
        if (this.filterForm.user_id) {
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
      // this.getWing();
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

    //EDIT KRA
    async editKra(item) {
      this.kra_id = item.kra_id
      this.kra_name = item.krajoin.kra_name
      this.kra_weight = item.krajoin.kra_weight
      this.$modal.show('kraedit')
    },

    //UPDATE KRA
    async updateKra() {
      this.editKraForm.id = this.kra_id
      this.editKraForm.kra_name = this.kra_name
      this.editKraForm.kra_weight = this.kra_weight
      this.editKraForm
        .put(this.api_url + 'k_r_a_s/' + this.kra_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })

          //HIDE MODAL
          this.$modal.hide('kraedit')

          //DATA RELOAD
          this.getItems()
        })
    },

    //EDIT KPI
    async editKpi(item) {
      this.kpi_id = item.kpi_id
      //this.kra_id = this.kra_id;
      this.kpi_name = item.kpijoin.kpi_name
      this.kpi_weight = item.kpijoin.kpi_weight
      this.$modal.show('kpiedit')
    },

    //UPDATE KPI
    async updateKpi() {
      this.editKpiForm.id = this.kpi_id
      this.editKpiForm.kpi_name = this.kpi_name
      this.editKpiForm.kpi_weight = this.kpi_weight
      this.editKpiForm
        .put(this.api_url + 'k_p_i_s/' + this.kpi_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })

          //HIDE MODAL
          this.$modal.hide('kpiedit')

          //DATA RELOAD
          this.getItems()
        })
    },

    //OPEN ADD NEW KPI MODAL
    async addKpi(item) {
      this.kra_id = item.kra_id
      this.$modal.show('kpiadd')
    },

    //ADD NEW KPI
    async addNewKpi() {
      this.addKpiForm.kra_id = this.kra_id
      this.addKpiForm.kpi_name = this.kpi_name
      this.addKpiForm.kpi_weight = this.kpi_weight
      this.addKpiForm.dept_id = this.user_data.dept_id
      this.addKpiForm.year = this.year
      this.addKpiForm
        .post(this.api_url + 'k_p_i_s', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })

          //HIDE MODAL
          this.$modal.hide('kpiadd')

          //DATA RELOAD
          this.getItems()
        })
    },

    //OPEN ADD NEW MOS MODAL
    async addMos(item) {
      this.kra_id = item.kra_id
      this.kpi_id = item.kpi_id
      this.$modal.show('mosadd')
    },

    //ADD NEW MOS
    async addNewMos() {
      this.addMosForm.kra_id = this.kra_id
      this.addMosForm.kpi_id = this.kpi_id
      this.addMosForm.dept_id = this.user_data.dept_id
      this.addMosForm.mos_name = this.mos_name
      this.addMosForm.weightage = this.mos_weight
      this.addMosForm.year = this.year
      this.addMosForm
        .post(this.api_url + 'm_o_s', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })

          //HIDE MODAL
          this.$modal.hide('mosadd')

          //DATA RELOAD
          this.getItems()
        })
    },

    //EDIT MOS
    async editMos(item) {
      this.mos_id = item.id
      this.mos_name = item.mos_name
      this.mos_weight = item.weightage
      this.kpi_id = item.kpi_id
      this.$modal.show('mosedit')
    },

    //UPDATE MOS
    async updateMos() {
      this.editMosForm.id = this.mos_id
      this.editMosForm.mos_name = this.mos_name
      this.editMosForm.weightage = this.mos_weight
      this.editMosForm.kpi_id = this.kpi_id
      this.editMosForm
        .put(this.api_url + 'm_o_s/' + this.mos_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })

          //HIDE MODAL
          this.$modal.hide('mosedit')

          //DATA RELOAD
          this.getItems()
        })
    },

    async deleteKra(id) {
      await axios
        .get(this.api_url + 'kra_delete/' + id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
          this.getItems()
        })
    },
    async delete_kra(id) {
      this.$swal({
        title: 'Are you sure you want to delete?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.deleteKra(id)
        }
      })
    },
    async delete_kpi(id) {
      this.$swal({
        title: 'Are you sure you want to delete?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.deleteKpi(id)
        }
      })
    },

    async deleteKpi(id) {
      await axios
        .get(this.api_url + 'kpi_delete/' + id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
          this.getItems()
        })
    },
    async delete_mos(id) {
      this.$swal({
        title: 'Are you sure you want to delete?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.deleteMos(id)
        }
      })
    },

    async deleteMos(id) {
      await axios
        .get(this.api_url + 'mos_delete/' + id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
          this.getItems()
        })
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
  computed: {},
}
</script>
