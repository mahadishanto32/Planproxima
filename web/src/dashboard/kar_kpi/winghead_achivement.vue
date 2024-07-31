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
                    <li class="breadcrumb-item active">Achievement Approval</li>
                  </ol>
                </div>
              </div>
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
                  <div class="mb-2">
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
                    <select
                      class="form-control"
                      v-on:change="getItems()"
                      v-model="filterForm.user_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in employeeItem"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.employee_id ? row.employee_id + ' : ' : '' }}
                        {{ row.name }}
                      </option>
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
                              <th v-if="filterForm.show_kra == 1">KRA</th>
                              <th v-if="filterForm.show_kra == 1">Weightage</th>
                              <th v-if="filterForm.show_kpi == 1">KPI</th>
                              <th v-if="filterForm.show_mos == 1">MOS</th>
                              <th>M.Target</th>
                              <th>M.Achieve</th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.jan &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Jan
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.feb &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Feb
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.mar &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Mar
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.apr &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Apr
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.may &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                May
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.jun &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Jun
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.jul &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Jul
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.aug &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Aug
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.sep &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Sep
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.oct &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Oct
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.nov &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Nov
                              </th>
                              <th
                                style="width: 200px"
                                v-if="
                                  month_off.dec &&
                                  dateRangeValidation(
                                    month_off.start_date,
                                    month_off.end_date
                                  )
                                "
                              >
                                Dec
                              </th>
                              <th>Comment</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(item, index) in items">
                              <tr :key="item.id" :class="index">
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
                                  <strong
                                    >(T-{{
                                      kraTotalTarget(item.kra_id, 'target')
                                    }}/A-{{
                                      kraTotalTarget(
                                        item.kra_id,
                                        'achievement'
                                      )
                                    }})</strong
                                  >
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
                                  (T-{{
                                    kpiTotalTarget(item.kpi_id, 'target')
                                  }}/A-{{
                                    kpiTotalTarget(item.kpi_id, 'achievement')
                                  }})
                                </td>
                                <td v-if="filterForm.show_mos == 1">
                                  {{ item.mos_name }} (W-{{ item.weightage }})
                                  (T-{{ mosTotalTarget(item, 'target') }}/A-{{
                                    mosTotalTarget(item, 'achievement')
                                  }})
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
                                <td
                                  v-bind:class="
                                    achievementTotal(
                                      item,
                                      targetTotal(item),
                                      achievementjoinTotal(item)
                                    ) > 100
                                      ? 'gb_color_green'
                                      : achievementTotal(
                                          item,
                                          targetTotal(item),
                                          achievementjoinTotal(item)
                                        ) < 100 &&
                                        achievementTotal(
                                          item,
                                          targetTotal(item),
                                          achievementjoinTotal(item)
                                        ) > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  {{ Number(targetTotal(item)).toFixed(2)
                                  }}{{ item.isvalorper == 1 ? '%' : '' }}
                                </td>
                                <td
                                  v-bind:class="
                                    achievementTotal(
                                      item,
                                      targetTotal(item),
                                      achievementjoinTotal(item)
                                    ) > 100
                                      ? 'gb_color_green'
                                      : achievementTotal(
                                          item,
                                          targetTotal(item),
                                          achievementjoinTotal(item)
                                        ) < 100 &&
                                        achievementTotal(
                                          item,
                                          targetTotal(item),
                                          achievementjoinTotal(item)
                                        ) > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                  v-if="filterForm.show_yachi == 1"
                                >
                                  <span v-if="targetTotal(item) > 0">
                                    {{
                                      achievementTotal(
                                        item,
                                        targetTotal(item),
                                        achievementjoinTotal(item)
                                      )
                                    }}%
                                  </span>
                                </td>
                                <td
                                  v-if="
                                    month_off.jan &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'january') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'january') < 100 &&
                                        achievement(item, 'january') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.january"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.feb &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'february') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'february') < 100 &&
                                        achievement(item, 'february') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.february"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.mar &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'march') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'march') < 100 &&
                                        achievement(item, 'march') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.march"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.apr &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'april') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'april') < 100 &&
                                        achievement(item, 'april') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.april"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.may &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'may') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'may') < 100 &&
                                        achievement(item, 'may') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.may"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.jun &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'june') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'june') < 100 &&
                                        achievement(item, 'june') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.june"
                                  />
                                </td>

                                <td
                                  v-if="
                                    month_off.jul &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'july') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'july') < 100 &&
                                        achievement(item, 'july') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.july"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.aug &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'august') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'august') < 100 &&
                                        achievement(item, 'august') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.august"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.sep &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'september') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'september') < 100 &&
                                        achievement(item, 'september') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.september"
                                  />
                                </td>
                                <td
                                  v-if="
                                    month_off.oct &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'october') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'october') < 100 &&
                                        achievement(item, 'october') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.october"
                                  />
                                </td>

                                <td
                                  v-if="
                                    month_off.nov &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'november') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'november') < 100 &&
                                        achievement(item, 'november') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.november"
                                  />
                                </td>

                                <td
                                  v-if="
                                    month_off.dec &&
                                    dateRangeValidation(
                                      month_off.start_date,
                                      month_off.end_date
                                    )
                                  "
                                  v-bind:class="
                                    achievement(item, 'december') > 100
                                      ? 'gb_color_green'
                                      : achievement(item, 'december') < 100 &&
                                        achievement(item, 'december') > 0
                                      ? 'gb_color_yellow'
                                      : ''
                                  "
                                >
                                  <input
                                    v-if="targetTotal(item) > 0"
                                    width="100px"
                                    type="text"
                                    id="fname"
                                    name="fname"
                                    v-model="item.mosachievementjoin.december"
                                  />
                                </td>
                                <td>
                                  <textarea
                                    v-if="targetTotal(item) > 0"
                                    v-model="item.comment"
                                  ></textarea>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                        </table>
                        <button
                          v-if="items.length > 0"
                          type="button"
                          class="btn btn-primary add-btn btn-lg d-flex align-items-center"
                          @click="achivement_update()"
                        >
                          <a class="text-white">
                            <i class="bx bx-add-alt"> </i>Approve achievement
                          </a>
                        </button>
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
import { Form } from 'vform'
import axios from '../../axios_instance'
// import Dropdown from 'vue-simple-search-dropdown';
export default {
  props: {},
  components: {},
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      p_data: '',
      items: [],
      item: [],
      items_all: [],
      deptItems: [],
      WingsItems: [],
      month_off: [],
      employeeItem: [],
      templates: [],
      comments: [],
      quarter: this.$route.query.quarter,
      month: 'apr',
      dept_id: this.$route.query.dept_id,
      kra_id: this.$route.query.kra_id,
      comment_active: 'comments',
      feedback: '',
      filterForm: new Form({
        dept_id: '',
        wing_id: '',
        user_id: this.$route.params.id ? this.$route.params.id : '',
        show_kra: 1,
        show_kpi: 1,
        show_mos: 1,
        show_yachi: 1,
        month: '',
        show_zero: 1,
      }),
      status: '',
      kraItem: [],
      kpiItem: [],
      mosItem: [],
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : new Date().getFullYear(),
    }
  },
  created() {
    this.getWing()
    this.getItems(), this.department_templates()
    this.role_id = this.user_data.role_id
    this.filterForm.dept_id = this.user_data.dept_id
    if (this.filterForm.dept_id) {
      this.getWing()
      this.filterForm.wing_id = this.user_data.wing_id
        ? this.user_data.wing_id
        : ''
    }
    if (this.role_id == 6 || this.role_id == 7 || this.role_id == 5) {
      this.filterForm.dept_id = this.user_data.dept_id
      this.getEmployee()
      this.getItems()
    }

    this.deptChange()
    this.single_permission()
    if (
      this.role_id == 5 ||
      this.role_id == 6 ||
      this.role_id == 7 ||
      this.role_id == 10
    ) {
      this.getDepartment().then(({ data }) => {
        if (data.success) {
          this.month_off = data.data.setting
          // console.log('this.month_off=======================');
          console.log(this.month_off)
          // console.log('this.month_off');
        }
      })
    }
  },
  methods: {
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
          console.log('ds', this.p_data)
        })
    },
    countRow(index, item) {
      console.log(index)
      console.log(item)
      if (this.filterForm.month) {
        return ''
      }
    },
    monthChange() {
      this.items = this.items_all
      if (this.filterForm.show_zero) {
        return this.items
      } else {
        let a = this.items.filter((item) => {
          //for (let index = 0; index < this.items.length; index++) {
          let target = item.mostargetjoin
          //this.items.slice(0, index);
          let total = 0

          if (this.filterForm.month == 'jan') {
            total = target.january
          } else if (this.filterForm.month == 'feb') {
            total = target.february
          } else if (this.filterForm.month == 'mar') {
            total = target.march
          } else if (this.filterForm.month == 'apr') {
            total = target.april
          } else if (this.filterForm.month == 'may') {
            total = target.may
          } else if (this.filterForm.month == 'jun') {
            total = target.june
          } else if (this.filterForm.month == 'jul') {
            total = target.july
          } else if (this.filterForm.month == 'aug') {
            total = target.august
          } else if (this.filterForm.month == 'sep') {
            total = target.september
          } else if (this.filterForm.month == 'oct') {
            total = target.october
          } else if (this.filterForm.month == 'nov') {
            total = target.november
          } else if (this.filterForm.month == 'dec') {
            total = target.december
          } else {
            total = target.total
          }
          return total > 0
        })
        this.items = a
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
    achievementTotal(item, target, achievement) {
      if (target > 0 && achievement > 0) {
        if (item.mos_calculation == 0) {
          return ((achievement / target) * 100).toFixed()
        } else if (item.mos_calculation == 1) {
          return ((target / achievement) * 100).toFixed(2)
        } else if (item.mos_calculation == 2) {
          return ((achievement / target) * 100).toFixed(2)
        } else if (item.mos_calculation == 3) {
          return ((target / achievement) * 100).toFixed(2)
        } else {
          return ((achievement / target) * 100).toFixed(2)
        }
      } else {
        return 0
      }
    },
    achievement(item, month) {
      if (item.mostargetjoin) {
        let target = item.mostargetjoin[month]
        let achievement = item.mosachievementjoin[month]
        if (target > 0 && achievement > 0) {
          if (item.mos_calculation == 0) {
            return ((achievement / target) * 100).toFixed()
          } else if (item.mos_calculation == 1) {
            return ((target / achievement) * 100).toFixed(2)
          } else if (item.mos_calculation == 2) {
            return ((achievement / target) * 100).toFixed(2)
          } else if (item.mos_calculation == 3) {
            return ((target / achievement) * 100).toFixed(2)
          } else {
            return ((achievement / target) * 100).toFixed(2)
          }
        } else {
          return 0
        }
      } else {
        return 0
      }
    },
    colorCheck(month_id) {
      var currentTime = new Date()
      if (currentTime.getFullYear() >= this.year) {
        var month = currentTime.getMonth() + 1
        if (month_id < month) {
          return 'red'
        }
      } else {
        return false
      }
    },
    mosTotalTarget(item, type) {
      let g_total = 0
      //this.items =  this.items_all ;
      //this.items.filter(item => {
      let total = 0
      let target
      if (item.mostargetjoin) {
        if (type == 'target') {
          if (item.mostargetjoin) {
            target = item.mostargetjoin
          } else {
            return 0
          }
        } else if (type == 'achievement') {
          if (item.mosachievementjoin) {
            target = item.mosachievementjoin
          } else {
            return 0
          }
        }

        let q1
        let q2
        let q3
        let q4
        let q5
        let q6
        q1 = target.january + target.february + target.march
        q2 = target.april + target.may + target.june
        q3 = target.july + target.august + target.september
        q4 = target.october + target.november + target.december
        q5 = q1 + q2
        q6 = q3 + q4
        if (this.filterForm.month == '') {
          if (this.filterForm.quarter == 1) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1
            } else {
              total = q1 / 3
            }
          } else if (this.filterForm.quarter == 2) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q2
            } else {
              total = q2 / 3
            }
          } else if (this.filterForm.quarter == 3) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q3
            } else {
              total = q3 / 3
            }
          } else if (this.filterForm.quarter == 4) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q4
            } else {
              total = q4 / 3
            }
          } else if (this.filterForm.quarter == 5) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q5
            } else {
              total = q5 / 6
            }
          } else if (this.filterForm.quarter == 6) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q6
            } else {
              total = q6 / 6
            }
          } else {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1 + q2 + q3 + q4
            } else {
              total = (q1 + q2 + q3 + q4) / 12
            }

            // total =  q1 + q2 + q3+ q4 ;
          }
        } else {
          if (this.filterForm.month == 'jan') {
            total = target.january
          } else if (this.filterForm.month == 'feb') {
            total = target.february
          } else if (this.filterForm.month == 'mar') {
            total = target.march
          } else if (this.filterForm.month == 'apr') {
            total = target.april
          } else if (this.filterForm.month == 'may') {
            total = target.may
          } else if (this.filterForm.month == 'jun') {
            total = target.june
          } else if (this.filterForm.month == 'jul') {
            total = target.july
          } else if (this.filterForm.month == 'aug') {
            total = target.august
          } else if (this.filterForm.month == 'sep') {
            total = target.september
          } else if (this.filterForm.month == 'oct') {
            total = target.october
          } else if (this.filterForm.month == 'nov') {
            total = target.november
          } else if (this.filterForm.month == 'dec') {
            total = target.december
          }
          // console.log(total);
          // return total > 0 ;
        }
        g_total += total
      }
      //}
      //});
      return this.amountConvert(g_total, 2)
      //return g_total ;
    },
    kpiTotalTarget(kpi_id, type) {
      let g_total = 0
      //this.items =  this.items_all ;
      this.items.filter((item) => {
        if (item.mostargetjoin) {
          let total = 0
          let target
          if (item.kpi_id == kpi_id) {
            if (type == 'target') {
              target = item.mostargetjoin
            } else if (type == 'achievement') {
              target = item.mosachievementjoin
            }

            let q1
            let q2
            let q3
            let q4
            let q5
            let q6
            q1 = target.january + target.february + target.march
            q2 = target.april + target.may + target.june
            q3 = target.july + target.august + target.september
            q4 = target.october + target.november + target.december
            q5 = q1 + q2
            q6 = q3 + q4
            if (this.filterForm.month == '') {
              if (this.filterForm.quarter == 1) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1
                } else {
                  total = q1 / 3
                }
              } else if (this.filterForm.quarter == 2) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q2
                } else {
                  total = q2 / 3
                }
              } else if (this.filterForm.quarter == 3) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q3
                } else {
                  total = q3 / 3
                }
              } else if (this.filterForm.quarter == 4) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q4
                } else {
                  total = q4 / 3
                }
              } else if (this.filterForm.quarter == 5) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q5
                } else {
                  total = q5 / 6
                }
              } else if (this.filterForm.quarter == 6) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q6
                } else {
                  total = q6 / 6
                }
              } else {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1 + q2 + q3 + q4
                } else {
                  total = (q1 + q2 + q3 + q4) / 12
                }

                // total =  q1 + q2 + q3+ q4 ;
              }
            } else {
              if (this.filterForm.month == 'jan') {
                total = target.january
              } else if (this.filterForm.month == 'feb') {
                total = target.february
              } else if (this.filterForm.month == 'mar') {
                total = target.march
              } else if (this.filterForm.month == 'apr') {
                total = target.april
              } else if (this.filterForm.month == 'may') {
                total = target.may
              } else if (this.filterForm.month == 'jun') {
                total = target.june
              } else if (this.filterForm.month == 'jul') {
                total = target.july
              } else if (this.filterForm.month == 'aug') {
                total = target.august
              } else if (this.filterForm.month == 'sep') {
                total = target.september
              } else if (this.filterForm.month == 'oct') {
                total = target.october
              } else if (this.filterForm.month == 'nov') {
                total = target.november
              } else if (this.filterForm.month == 'dec') {
                total = target.december
              }
              // console.log(total);
              // return total > 0 ;
            }
            g_total += total
          }
        }
      })
      return this.amountConvert(g_total, 2)
      //return g_total ;
    },
    kraTotalTarget(kra_id, type) {
      let g_total = 0
      //this.items =  this.items_all ;

      this.items.filter((item) => {
        if (item.mostargetjoin) {
          let total = 0
          let target
          if (item.kra_id == kra_id) {
            if (type == 'target') {
              target = item.mostargetjoin
            } else if (type == 'achievement') {
              target = item.mosachievementjoin
            }

            let q1
            let q2
            let q3
            let q4
            let q5
            let q6
            q1 = target.january + target.february + target.march
            q2 = target.april + target.may + target.june
            q3 = target.july + target.august + target.september
            q4 = target.october + target.november + target.december
            q5 = q1 + q2
            q6 = q3 + q4
            if (this.filterForm.month == '') {
              if (this.filterForm.quarter == 1) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1
                } else {
                  total = q1 / 3
                }
              } else if (this.filterForm.quarter == 2) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q2
                } else {
                  total = q2 / 3
                }
              } else if (this.filterForm.quarter == 3) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q3
                } else {
                  total = q3 / 3
                }
              } else if (this.filterForm.quarter == 4) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q4
                } else {
                  total = q4 / 3
                }
              } else if (this.filterForm.quarter == 5) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q5
                } else {
                  total = q5 / 6
                }
              } else if (this.filterForm.quarter == 6) {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q6
                } else {
                  total = q6 / 6
                }
              } else {
                if (item.mos_calculation == 0 || item.mos_calculation == 1) {
                  total = q1 + q2 + q3 + q4
                } else {
                  total = (q1 + q2 + q3 + q4) / 12
                }

                // total =  q1 + q2 + q3+ q4 ;
              }
            } else {
              if (this.filterForm.month == 'jan') {
                total = target.january
              } else if (this.filterForm.month == 'feb') {
                total = target.february
              } else if (this.filterForm.month == 'mar') {
                total = target.march
              } else if (this.filterForm.month == 'apr') {
                total = target.april
              } else if (this.filterForm.month == 'may') {
                total = target.may
              } else if (this.filterForm.month == 'jun') {
                total = target.june
              } else if (this.filterForm.month == 'jul') {
                total = target.july
              } else if (this.filterForm.month == 'aug') {
                total = target.august
              } else if (this.filterForm.month == 'sep') {
                total = target.september
              } else if (this.filterForm.month == 'oct') {
                total = target.october
              } else if (this.filterForm.month == 'nov') {
                total = target.november
              } else if (this.filterForm.month == 'dec') {
                total = target.december
              }
              // console.log(total);
              // return total > 0 ;
            }
            g_total += total
          }
        }
      })
      return this.amountConvert(g_total, 2)
      // return g_total ;
    },
    targetTotal(item) {
      let total = 0
      if (item.mostargetjoin) {
        let target = item.mostargetjoin

        if (
          this.month_off.jan &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.january
        } else if (
          this.month_off.feb &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.february
        } else if (
          this.month_off.mar &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.march
        } else if (
          this.month_off.apr &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.april
        } else if (
          this.month_off.may &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.may
        } else if (
          this.month_off.jun &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.june
        } else if (
          this.month_off.jul &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.july
        } else if (
          this.month_off.aug &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.august
        } else if (
          this.month_off.sep &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.september
        } else if (
          this.month_off.oct &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.october
        } else if (
          this.month_off.nov &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.november
        } else if (
          this.month_off.dec &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = target.december
        } else {
          total = 0
        }
      }
      return total
    },
    moduleTotal(item) {
      if (item.mosmodulejoin) {
        let module = item.mosmodulejoin
        let total = 0
        let q1
        let q2
        let q3
        let q4
        let q5
        let q6
        q1 = module.january + module.february + module.march
        q2 = module.april + module.may + module.june
        q3 = module.july + module.august + module.september
        q4 = module.october + module.november + module.december
        q5 = q1 + q2
        q6 = q3 + q4
        if (this.filterForm.month == '') {
          if (this.filterForm.quarter == 1) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1
            } else {
              total = q1 / 3
            }
          } else if (this.filterForm.quarter == 2) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q2
            } else {
              total = q2 / 3
            }
          } else if (this.filterForm.quarter == 3) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q3
            } else {
              total = q3 / 3
            }
          } else if (this.filterForm.quarter == 4) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q4
            } else {
              total = q4 / 3
            }
          } else if (this.filterForm.quarter == 5) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q5
            } else {
              total = q5 / 6
            }
          } else if (this.filterForm.quarter == 6) {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q6
            } else {
              total = q6 / 6
            }
          } else {
            if (item.mos_calculation == 0 || item.mos_calculation == 1) {
              total = q1 + q2 + q3 + q4
            } else {
              total = (q1 + q2 + q3 + q4) / 12
            }

            // total =  q1 + q2 + q3+ q4 ;
          }
        } else {
          if (this.filterForm.month == 'jan') {
            total = module.january
          } else if (this.filterForm.month == 'feb') {
            total = module.february
          } else if (this.filterForm.month == 'mar') {
            total = module.march
          } else if (this.filterForm.month == 'apr') {
            total = module.april
          } else if (this.filterForm.month == 'may') {
            total = module.may
          } else if (this.filterForm.month == 'jun') {
            total = module.june
          } else if (this.filterForm.month == 'jul') {
            total = module.july
          } else if (this.filterForm.month == 'aug') {
            total = module.august
          } else if (this.filterForm.month == 'sep') {
            total = module.september
          } else if (this.filterForm.month == 'oct') {
            total = module.october
          } else if (this.filterForm.month == 'nov') {
            total = module.november
          } else if (this.filterForm.month == 'dec') {
            total = module.december
          } else {
            total = 0
          }
        }
        return total
      }
    },
    achievementjoinTotal(item) {
      let achievement = item.mosachievementjoin
      let total = 0
      if (item.mosachievementjoin) {
        if (
          this.month_off.jan &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.january
        } else if (
          this.month_off.feb &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.february
        } else if (
          this.month_off.mar &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.march
        } else if (
          this.month_off.apr &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.april
        } else if (
          this.month_off.may &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.may
        } else if (
          this.month_off.jun &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.june
        } else if (
          this.month_off.jul &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.july
        } else if (
          this.month_off.aug &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.august
        } else if (
          this.month_off.sep &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.september
        } else if (
          this.month_off.oct &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.october
        } else if (
          this.month_off.nov &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.november
        } else if (
          this.month_off.dec &&
          this.dateRangeValidation(
            this.month_off.start_date,
            this.month_off.end_date
          )
        ) {
          total = achievement.december
        } else {
          total = 0
        }
      }

      return total
    },
    select_months(mo) {
      //const d = new Date();
      if (this.filterForm.month != '') {
        if (this.filterForm.month == mo) {
          return true
        } else {
          return false
        }
      } else {
        if (this.filterForm.quarter != '') {
          if (
            this.filterForm.quarter == 1 &&
            (mo == 'jan' || mo == 'feb' || mo == 'mar')
          ) {
            return true
          } else if (
            this.filterForm.quarter == 2 &&
            (mo == 'apr' || mo == 'may' || mo == 'jun')
          ) {
            return true
          } else if (
            this.filterForm.quarter == 3 &&
            (mo == 'jul' || mo == 'aug' || mo == 'sep')
          ) {
            return true
          } else if (
            this.filterForm.quarter == 4 &&
            (mo == 'oct' || mo == 'nov' || mo == 'dec')
          ) {
            return true
          } else if (
            this.filterForm.quarter == 5 &&
            (mo == 'jan' ||
              mo == 'feb' ||
              mo == 'mar' ||
              mo == 'apr' ||
              mo == 'may' ||
              mo == 'jun')
          ) {
            return false
          } else if (
            this.filterForm.quarter == 6 &&
            (mo == 'jul' ||
              mo == 'aug' ||
              mo == 'sep' ||
              mo == 'oct' ||
              mo == 'nov' ||
              mo == 'dec')
          ) {
            return false
          } else {
            return false
          }
        } else {
          return true
        }
      }
    },
    async changeEmployee() {
      this.getEmployee()
      this.getItems(true)
    },
    async getEmployee() {
      //if(this.filterForm.wing_id){
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
          // console.log('this console test',this.employeeItem);
        })
      //}
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

    async getItems(load = false) {
      if (this.filterForm.dept_id || this.user_data.dept_id) {
        let where =
          '?year=' + (this.year ? this.year : new Date().getFullYear())
        if (this.filterForm.dept_id) {
          where +=
            '&dept_id=' +
            (this.filterForm.dept_id
              ? this.filterForm.dept_id
              : this.user_data.dept_id)
        }
        if (this.filterForm.kra_id) {
          where += '&kra_id=' + this.filterForm.kra_id
        }
        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id
        }
        if (this.filterForm.user_id) {
          where += '&user_id=' + this.filterForm.user_id
        }
        if (this.filterForm.kpi_id) {
          where += '&kpi_id=' + this.filterForm.kpi_id
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
                this.items_all = data.data
                this.items = data.data
                this.monthChange()
                this.items.filter(function (item, index) {
                  item['share_per'] = 0
                  let total = 0
                  if (item.working_member) {
                    item.working_member.filter(function (row, rIndex) {
                      total += row['rep_per']
                    })
                    item['share_per'] = total
                  }
                })
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
    // Note: Department templates
    department_templates() {
      axios
        .get(this.api_url + 'templates_department', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          if (data.success) {
            this.templates = data.data
          }
        })
    },
    //SHOW COMMENT MODAL

    tabs(i) {
      this.comment_active = i
    },

    //HIDE COMMENT MODAL
    comment_hidden() {
      this.$modal.hide('comment')
    },

    //MONTHLY REPORT MOS FEEDBACK
    task_comment() {
      try {
        let loader = this.$loading.show()
        this.comment_mailForm.mos_id = this.item.id
        this.comment_mailForm.dept_id = this.item.dept_id
        this.comment_mailForm.fmonth = this.filterForm.month

        this.comment_mailForm
          .post(this.api_url + 'mos_feadbacks', {
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
    wings_notification() {
      console.log('filterForm', this.filterForm)
      try {
        let loader = this.$loading.show()
        this.filterForm
          .post(this.api_url + 'achivement_notification', {
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
                console.log('res.data', res.data)
              }
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
    achivement_update() {
      try {
        let loader = this.$loading.show()
        this.filterForm.items = this.items
        // this.filterForm.comments = this.comments ;
        this.filterForm
          .post(this.api_url + 'achievement_approval', {
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
                console.log('res.data', res.data)
              }
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
<style>
.color_red {
  color: red;
}

.gb_color_green {
  background-color: seagreen;
  color: seashell;
}

.gb_color_yellow {
  background-color: yellow;
  color: black;
}
</style>
