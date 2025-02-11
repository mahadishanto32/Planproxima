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
                    <li @click="NewYear()" class="breadcrumb-item active">
                      Dashboard
                    </li>
                  </ol>
                </div>
              </div>
              <div class="col-sm-3">
                <router-link
                  :to="{ path: '/add_fac_work' }"
                  class="btn btn-primary add-btn"
                  v-if="
                    role_id == 5 &&
                    deptItems &&
                    deptInfo.is_factory == 1 &&
                    deptItems.length == 1
                  "
                >
                  <i class="bx bx-add-alt"> </i>
                  Fac Add
                </router-link>

                <router-link
                  v-if="
                    deptInfo.template_setting == 2 &&
                    (role_id == 3 || role_id == 5)
                  "
                  :to="{ path: '/new_task' }"
                  class="btn btn-primary add-btn"
                >
                  <i class="bx bx-add-alt"> </i>
                  New Work Schedule
                </router-link>

                <router-link
                  v-else-if="
                    (deptInfo.template_setting == 1 ||
                      role_id == 6 ||
                      role_id == 7 ||
                      role_id == 10 ||
                      role_id == 16 ||
                      user_data.id == 24) &&
                    user_data.dept_id != 8
                  "
                  :to="{ path: '/add_daily_work' }"
                  class="btn btn-primary add-btn"
                >
                  <i class="bx bx-add-alt"> </i>
                  New Work Schedule
                </router-link>

                <!-- <router-link v-else :to="{ path: '/new_task_dept' }" class="btn btn-primary add-btn">
                                    <i class="bx bx-add-alt"> </i>
                                    New Work Schedule
                                </router-link> -->

                <p v-else>
                  <router-link
                    v-if="Object.keys(dailyCheck).length > 0"
                    :to="{
                      path: '/edit_task/' + dailyCheck.id,
                    }"
                    class="btn btn-primary add-btn"
                  >
                    <i class="bx bx-add-alt"> </i>
                    New Work Schedule
                  </router-link>
                  <router-link
                    v-else
                    :to="{ path: '/new_task_dept' }"
                    class="btn btn-primary add-btn"
                  >
                    <i class="bx bx-add-alt"> </i>
                    New Work Schedule
                  </router-link>
                </p>

                <router-link
                  :to="{ path: '/new_tour' }"
                  class="btn btn-primary add-btn"
                  v-if="role_id == 10"
                >
                  <i class="bx bx-add-alt"> </i>
                  New Tour Plan
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <div v-click-outside="onClickOutside"></div>

        <section id="basic-datatable">
          <div class="content-body">
            <div class="users-list-filter px-1">
              <!-- <viewer>
                                <img class="img-fluid" :src=" base_url + 'assets/app-assets/images/new_year/2022.jpg'" alt="branding logo">
                              </viewer> -->
              <!-- QUICK LINK -->
              <div
                class="row border rounded py-2 mb-2"
                v-if="route_list.length > 0"
              >
                <ul
                  class="list-inline justify-content-center align-items-center"
                >
                  <li v-for="route in route_list" :key="route.id">
                    <router-link
                      v-if="route.route != 'add_daily_work'"
                      :to="{ path: route.route }"
                      class="btn btn-secondary btn-sm quicklink"
                      >{{ route.title }}
                    </router-link>
                    &nbsp;
                    <!-- <router-link
                      v-if="route.route == 'add_daily_work' && deptInfo.template_setting == 2 && (role_id == 3 || role_id == 5)"
                      :to="{ path: '/new_task' }" class="btn btn-secondary btn-sm quicklink">
                      New Work Schedule
                  </router-link>
                  <router-link
                      v-if="route.route == 'add_daily_work' && deptInfo.template_setting == 1 || (role_id == 6 || role_id == 7 || role_id == 10 || user_data.id == 24)"
                      :to="{ path: '/add_daily_work' }" class="btn btn-secondary btn-sm quicklink">
                      New Work Schedule
                  </router-link> -->
                  </li>
                </ul>
              </div>
              <div class="row border rounded mb-2">
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="deptItems.length > 1 && role_id == 1 || role_id == 2"
                >
                  <div class="mb-2">
                    <label for="users-list-verified"> Department </label>
                    <fieldset class="form-group">
                      <select
                        class="form-control"
                        id="users-list-verified"
                        v-model="filterForm.dept_id"
                        v-on:change="filter_data()"
                      >
                        <option value="">All</option>
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
                </div>
                <div class="col-4 col-sm-4 col-lg-2" v-if="role_id == 5">
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
                  class="col-4 col-sm-4 col-lg-2"
                  v-if="role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="changeEmployee()"
                      v-model="filterForm.user_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in employeeItem"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-xl-4">
                  <div class="card card_box">
                    <apexchart
                      :options="yearly_achievement.chartOptions"
                      :series="achievement_with_remaining"
                      height="500px"
                      type="pie"
                    >
                    </apexchart>
                  </div>
                </div>
                <!-- //Performance management -->
                <div
                  class="col-xl-4"
                  v-if="
                    role_id == 1 ||
                    role_id == 2 ||
                    role_id == 3 ||
                    role_id == 4 ||
                    role_id == 8 ||
                    role_id == 5 ||
                    role_id == 16
                  "
                >
                  <div class="card card_box">
                    <apexchart
                      :options="performance.chartOptions"
                      :series="performance_value"
                      ref="chart1"
                      type="donut"
                    >
                    </apexchart>
                  </div>
                </div>
                <div
                  class="col-xl-4"
                  v-if="
                    role_id == 1 ||
                    role_id == 2 ||
                    role_id == 3 ||
                    role_id == 4 ||
                    role_id == 8 ||
                    role_id == 5 ||
                    role_id == 16    
                  "
                >
                  <div class="card card_box">
                    <apexchart
                      :options="analytics_bar.chartOptions"
                      :series="analytics_bar.series"
                      ref="budget_analytics"
                      type="bar"
                    >
                    </apexchart>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>
<script>
import vClickOutside from 'v-click-outside'
import { Form } from 'vform'
import VueApexCharts from 'vue-apexcharts'
import axios from '../axios_instance'
export default {
  props: {},
  components: {
    apexchart: VueApexCharts,
  },
  directives: {
    clickOutside: vClickOutside.directive,
  },
  data() {
    return {
      monthly_activity: [],
      target_achievement: [],
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : new Date().getFullYear(),
      newYearAlert: false,
      achievement: JSON.parse(this.$localStorage.get('achievement')),
      target: JSON.parse(this.$localStorage.get('target')),
      achievement_with_remaining: JSON.parse(
        this.$localStorage.get('achievement_with_remaining')
      ),
      piecolor: JSON.parse(this.$localStorage.get('piecolor')),
      monthname: JSON.parse(this.$localStorage.get('monthname')),
      //performance_value : JSON.parse(this.$localStorage.get("performance_value")),
      user_data: JSON.parse(this.$localStorage.get('user')),
      base_url: window.base_url,
      api_url: window.api_url,
      search: '',
      item: [],
      deptInfo: [],
      route_list: [],
      current_month: '',
      current_monthstr: '',
      month_index: 0,
      deptItems: [],
      WingsItems: [],
      employeeItem: [],
      monthly_activity_show: false,
      filterForm: new Form({
        dept_id: '',
        filter_by: 'year',
        wing_id: '',
        user_id: '',
      }),
      token: this.$localStorage.get('d_token'),
      yearly_achievement: {
        chartOptions: {
          colors: JSON.parse(this.$localStorage.get('piecolor')),
          chart: {
            height: '100%',
            type: 'pie',
          },
          title: {
            text: 'Yearly Achievement',
            align: 'center',
          },
          labels: JSON.parse(this.$localStorage.get('monthname')),
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      achievement_1: [12, 34, 12],
      quarter_achievement_1: {
        chartOptions: {
          chart: {
            height: '100%',
            type: 'pie',
          },
          title: {
            text: 'Quarter-1 Achievement',
            align: 'center',
          },
          labels: ['Jan', 'Feb', 'Mar'],
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      achievement_2: [20, 34, 12],
      quarter_achievement_2: {
        chartOptions: {
          chart: {
            height: '100%',
            type: 'pie',
          },
          title: {
            text: 'Quarter-2 Achievement',
            align: 'center',
          },
          labels: ['Apr', 'May', 'Jun'],
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      achievement_3: [12, 10, 12],
      quarter_achievement_3: {
        chartOptions: {
          chart: {
            height: '100%',
            type: 'pie',
          },
          title: {
            text: 'Quarter-3 Achievement',
            align: 'center',
          },
          labels: ['Jul', 'Aug', 'Sep'],
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      achievement_4: [50, 34, 12],
      quarter_achievement_4: {
        chartOptions: {
          chart: {
            height: '100%',
            type: 'pie',
          },
          title: {
            text: 'Quarter-4 Achievement',
            align: 'center',
          },
          labels: ['Oct', 'Nov', 'Dec'],
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      performance_value: JSON.parse(
        this.$localStorage.get('performance_value')
      ),
      performance: {
        chartOptions: {
          chart: {
            height: '100%',
            type: 'donut',
          },
          title: {
            text: 'Performance Management',
            align: 'center',
          },
          labels: ['Performance', 'Remaining '],
          theme: {
            monochrome: {
              enabled: true,
            },
          },
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200,
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
        },
      },
      analytics_bar: {
        series: [
          {
            name: 'Target',
            data: JSON.parse(this.$localStorage.get('target')),
          },
          {
            name: 'Achievement',
            data: JSON.parse(this.$localStorage.get('achievement')),
          },
        ],
        chartOptions: {
          chart: {
            type: 'bar',
            height: 200,
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '65%',
              endingShape: 'rounded',
            },
          },
          dataLabels: {
            enabled: false,
          },
          title: {
            text: 'Target vs Achievement',
            align: 'center',
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent'],
          },
          xaxis: {
            categories: [
              'Jan',
              'Feb',
              'Mar',
              'Apr',
              'May',
              'Jun',
              'Jul',
              'Aug',
              'Sep',
              'Oct',
              'Nov',
              'Dec',
            ],
          },
          yAxes: [
            {
              stacked: false,
              position: 'left',
              id: 'y-axis-0',
              ticks: {
                beginAtZero: true,
              },
              scaleLabel: {
                display: true,
                labelString: 'Left',
              },
            },
            {
              stacked: false,
              position: 'right',
              id: 'y-axis-1',
              ticks: {
                min: -1000,
                max: 8000,
                stepSize: 1000,
                beginAtZero: true,
              },
              scaleLabel: {
                display: true,
                labelString: 'Right',
              },
            },
          ],
          fill: {
            opacity: 1,
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return val
              },
            },
          },
        },
      },
      dept_analytics_series: [],
      singel_analytics_bar: {
        series: [
          {
            name: 'Target',
            data: [30, 40, 45, 50, 49, 60, 70, 91, 0, 5, 34, 100],
          },
          {
            name: 'Achievement',
            data: [14, 30, 30, 40, 30, 0, 50, 21, 0, 5, 24, 55],
          },
        ],
        chartOptions: {
          chart: {
            type: 'bar',
            height: 100,
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '65%',
              endingShape: 'rounded',
            },
          },
          dataLabels: {
            enabled: false,
          },
          title: {
            text: 'Achievement and Target Analytics ',
            align: 'center',
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent'],
          },
          xaxis: {
            categories: [
              'January',
              'February',
              'March',
              'April',
              'May',
              'June',
              'July',
              'August',
              'September',
              'October',
              'November',
              'December',
            ],
          },
          yaxis: {
            title: {
              text: 'Value',
            },
          },
          fill: {
            opacity: 1,
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return 'TK ' + val + ' M'
              },
            },
          },
        },
      },
      userManuals: '',
      currentIndex: 0,
      dailyCheck: [],
    }
  },
  mounted: function () {},
  created() {

  
   // this.dailyTask()
    this.dept_id = this.user_data.dept_id
    this.role_id = this.user_data.role_id
    if (this.role_id == 5 || this.role_id == 6 || this.role_id == 7) {
      this.filterForm.dept_id = this.user_data.dept_id
      // this.getWing();
      this.getDept()
    } else {
      this.getDept()
    }

    
    if (this.$route.params.type == 'd') {
      this.$router.push('/')
      this.$router.go('/home')
    }
   
    let d = new Date()
    this.month_index = d.getMonth()
    // this.current_month = this.monthNames[this.month_index]
    // this.current_monthstr = this.current_month.toLowerCase()
    console.log('--------0000-yy--ttt-------------------------------------------');
    this.getTargetAchievement()
    // this.getMydeptInfo(this.dept_id)
    this.getWing()
    axios
      .get(this.api_url + 'quick-link-list/' + this.user_data.id, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: this.token ? `Bearer ${this.token}` : '',
        },
      })
      .then((response) => {
        this.route_list = response.data
      })
    if (this.$route.query.permission) {
      this.$swal({
        //title: "Are you sure?",
        text: this.$route.query.permission,
        icon: 'success',
        showConfirmButton: false,
        timer: 2500,
      }).then((willDelete) => {
        console.log(willDelete)
      })
    }
    //this.fetchUserManual();

     
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
    onClickOutside(event) {
      if (event.target.className != 'img-fluid newImage') {
        this.newYearAlert = false
      }
    },

    changeMonth() {
      let loader = this.$loading.show()
      this.current_month = this.monthNames[this.month_index]
      this.current_monthstr = this.current_month.toLowerCase()
      loader.hide()
    },
    hide_pop() {
      this.$modal.hide('popup-singel')
    },
    filter_data() {
      this.getTargetAchievement()
      this.getMonthly_activity()
    },
    show_pop(row) {
      if (this.item.target && this.item.achievement) {
        this.item = row
        this.$modal.show('popup-singel')
        this.$refs.singel_analytics_bar.updateSeries(this.dept_analytics_series)
      }
    },
    async getTargetAchievement() {
      //let loader = this.$loading.show();
      let where = '?'
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id
      }
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      if (this.filterForm.user_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      where += '&year=' + this.year
      try {
        await axios
          .get(this.api_url + 'dashboard' + where, {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.target_achievement = data.data
              this.achievement = this.target_achievement.achievement
              this.achievement_with_remaining =
                this.target_achievement.achievement_with_remaining
              this.piecolor = this.target_achievement.color
              this.monthname = this.target_achievement.monthname
              this.performance_value = this.target_achievement.performance_value
              this.target = this.target_achievement.target
              this.$localStorage.set(
                'achievement',
                JSON.stringify(this.achievement)
              )
              this.$localStorage.set('target', JSON.stringify(this.target))
              this.$localStorage.set(
                'achievement_with_remaining',
                JSON.stringify(this.achievement_with_remaining)
              )
              this.$localStorage.set('piecolor', JSON.stringify(this.piecolor))
              this.$localStorage.set(
                'monthname',
                JSON.stringify(this.monthname)
              )
              this.$localStorage.set(
                'performance_value',
                JSON.stringify(this.performance_value)
              )
            }
            //   loader.hide();
          })
      } catch (error) {
        //  loader.hide();
      }
    },
    async getMonthly_activity() {
      this.monthly_activity_show = true
      let where = '?'
      // let loader = this.$loading.show();
      where = '?year=' + (this.year ? this.year : new Date().getFullYear())
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      if (this.filterForm.user_id) {
        where += '&user_id=' + this.filterForm.wing_id
      }
      if (this.filterForm.dept_id) {
        where += '&id=' + this.filterForm.dept_id
      }
      try {
        await axios
          .get(this.api_url + 'department_wise_monthly_activity' + where, {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(({ data }) => {
            if (data.success) {
              this.monthly_activity = data.data
            }
            // loader.hide();
          })
      } catch (error) {
        // loader.hide();
      }
    },
    async getDept() {
      // let loader = this.$loading.show();
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          // loader.hide();
          this.deptItems = data.data
        } else {
          // loader.hide();
        }
      })
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

    async getEmployee() {
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
    },
    async changeEmployee() {
      this.getEmployee()
      this.filter_data()
    },
    // async getMydeptInfo(id) {
    //   await axios
    //     .get(this.api_url + 'departments/' + id, {
    //       headers: {
    //         'Content-Type': 'application/json',
    //         Authorization: this.token ? `Bearer ${this.token}` : '',
    //       },
    //     })
    //     .then(({ data }) => {
    //       this.deptInfo = data.data
    //     })
    // },
 

    show_pop_permission() {
      this.$modal.show('popup-permission')
    },
    hide_pop_permission() {
      if (this.userManuals.length >= this.currentIndex) {
        this.currentIndex = this.currentIndex + 1
      }
      this.$modal.hide('popup-permission')
    },
  },
  computed: {
    filteredItems() {
      return this.monthly_activity.filter((item) => {
        return item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1
      })
    },
  },
}
</script>
<style scoped>
.modal {
  /* display: none;   */
  position: fixed;
  /* Stay in place */
  z-index: 1;
  /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  /* Full width */
  /* height: 100%;   */
  overflow: auto;
  /* Enable scroll if needed */
  z-index: 999999;
  background-color: rgba(247, 247, 247, 0.79);
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 6% auto;
  /* 15% from the top and centered */
  /* padding: 20px; */
  border: 1px solid #888;
  height: 70%;
  width: 65%;
  /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.new_year_close {
  color: red !important;
  position: absolute !important;
  top: 10px !important;
  z-index: 9999999 !important;
  right: 20px !important;
  background: #fff !important;
  border-radius: 20px !important;
}

.newImage {
  outline: 0;
  max-width: 100%;
  max-height: 100%;
  display: block;
}

.modal .active {
  display: block;
}

.app-content {
  overflow-y: auto;
  width: auto;
  height: 100%;
}

.vm--modal {
  left: 231px !important;
}
</style>
