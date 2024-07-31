<template>
  <header>
    <div class="header-navbar-shadow"></div>
    <nav
      class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top"
    >
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="navbar-collapse" id="navbar-mobile">
            <div
              class="mr-auto float-left bookmark-wrapper d-flex align-items-center"
            >
              <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu d-xl-none mr-auto">
                  <a
                    class="nav-link nav-menu-main menu-toggle hidden-xs"
                    href="#"
                    ><i class="ficon bx bx-menu"></i
                  ></a>
                </li>
              </ul>
            </div>
            <ul class="nav navbar-nav float-right">
              <li class="dropdown dropdown-notification nav-item">
                <a
                  class="nav-link nav-link-label"
                  href="#"
                  data-toggle="dropdown"
                  ><i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i
                  ><span
                    class="badge badge-pill badge-danger badge-up"
                    v-if="unread > '0'"
                    >{{ unread }}</span
                  ></a
                >
                <ul
                  class="dropdown-menu dropdown-menu-media dropdown-menu-right"
                >
                  <li class="dropdown-menu-header">
                    <div
                      class="dropdown-header px-1 py-75 d-flex justify-content-between"
                    >
                      <span class="notification-title" v-if="unread > '0'"
                        >{{ unread }} New Notification</span
                      >
                      <span class="notification-title" v-if="unread == '0'"
                        >No New Notification
                      </span>

                      <!-- <span class="text-bold-400 cursor-pointer">Mark all as read</span> -->
                    </div>
                  </li>

                  <li
                    class="scrollable-container media-list"
                    v-if="notification"
                  >
                    <template v-for="(item, index) in notification">
                      <div
                        :key="index"
                        class="d-flex justify-content-between cursor-pointer"
                        v-bind:class="
                          item.status == '0' ? 'read-notification' : ''
                        "
                        @click="notificationRead(item.id)"
                        v-if="
                          item.notification_type == 'Achivement Notification'
                        "
                      >
                        <a
                          v-on:click="
                            pathChange('/winghead_achivement/' + item.user_id)
                          "
                        >
                          <div class="media d-flex align-items-center">
                            <div class="media-left pr-0">
                              <div class="avatar m-0 mr-1 p-25">
                                <div class="avatar-content">
                                  <i
                                    class="bx bx-comment"
                                    style="padding: 0"
                                  ></i>
                                </div>
                              </div>
                            </div>

                            <div class="media-body">
                              <h6 class="media-heading">
                                <span class="text-bold-500">{{
                                  item.details
                                }}</span>
                              </h6>
                              <small class="notification-text"
                                >{{ format_Date(item.created_at) }}
                                {{ formatAMPM(item.created_at) }}</small
                              >
                            </div>
                          </div>
                        </a>
                      </div>
                      <div
                        :key="index"
                        class="d-flex justify-content-between cursor-pointer"
                        v-bind:class="
                          item.status == '0' ? 'read-notification' : ''
                        "
                        @click="notificationRead(item.id)"
                        v-else
                      >
                        <div class="media d-flex align-items-center">
                          <div class="media-left pr-0">
                            <div class="avatar m-0 mr-1 p-25">
                              <div class="avatar-content">
                                <i class="bx bx-comment" style="padding: 0"></i>
                              </div>
                            </div>
                          </div>
                          <div class="media-body">
                            <h6 class="media-heading">
                              <span class="text-bold-500">{{
                                item.details
                              }}</span>
                            </h6>
                            <small class="notification-text"
                              >{{ format_Date(item.created_at) }}
                              {{ formatAMPM(item.created_at) }}</small
                            >
                          </div>
                        </div>
                      </div>
                    </template>
                  </li>
                  <li
                    class="scrollable-container media-list"
                    v-if="!notification"
                  >
                    <div class="media d-flex align-items-center">Loading..</div>
                  </li>
                  <li
                    class="scrollable-container media-list"
                    v-if="!notification.length"
                  >
                    <div class="media d-flex align-items-center">
                      No Data Found
                    </div>
                  </li>

                  <!--  <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="javascript:void(0)">Read all notifications</a></li>  -->
                </ul>
              </li>
              <li @click="refresh()" class="dropdown dropdown-user nav-item">
                <fieldset class="form-group">
                  <i
                    class="bx bxs-analyse"
                    style="
                      font-size: 35px;
                      padding-top: 10px;
                      padding-right: 20px;
                    "
                  ></i>
                </fieldset>
              </li>
              <li class="dropdown dropdown-notification nav-item">
                <fieldset class="form-group">
                  <select
                    id="Profession"
                    v-model="year"
                    v-on:change="changeYear()"
                    name="year"
                    class="form-control"
                  >
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                  </select>
                </fieldset>
              </li>
              <li class="dropdown dropdown-user nav-item">
                <a
                  class="dropdown-toggle nav-link dropdown-user-link"
                  href="#"
                  data-toggle="dropdown"
                >
                  <div class="user-nav d-sm-flex d-none">
                    <span class="user-name"
                      >{{ user.name
                      }}<element v-if="user.employee_id"
                        >({{ user.employee_id }})
                      </element></span
                    >
                    <span class="user-status text-muted">
                      <element v-if="user.deptjoin"
                        >{{ user.deptjoin.name }}
                        <element v-if="user.wingjoin"
                          >- {{ user.wingjoin.wing_title }}</element
                        >
                      </element>
                    </span>
                  </div>
                  <span>
                    <img
                      class="round"
                      src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                      height="40"
                      width="40"
                      onerror=""
                    />
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pb-0">
                  <a class="dropdown-item">
                    <i class="bx bx-user mr-50"></i>
                    <router-link :to="{ path: '/profile' }">
                      My Profile
                    </router-link>
                  </a>
                  <a class="dropdown-item">
                    <i class="bx bx-lock mr-50"></i>
                    <router-link :to="{ path: '/change_password' }">
                      Change Password</router-link
                    >
                  </a>
                  <div class="dropdown-divider mb-0"></div>
                  <a class="dropdown-item" @click="Logout()">
                    <i class="bx bx-power-off mr-50"></i> Logout
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <div
      class="main-menu menu-fixed menu-light menu-accordion menu-shadow"
      style="touch-action: none; user-select: none"
      data-scroll-to-active="true"
    >
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="../">
              <!-- <div class="plan">Plan &nbsp;</div>
              <div class="planproxima">Proxima</div> -->
              <!-- <div class="brand-logo">
                <img class="logo" :src="base_url + 'assets/app-assets/images/logo/logo.png'" />
              </div> -->
              <div>
                <h4 class="brand-text">Plan Proxima</h4>
              </div>
            </a>
          </li>
          <!-- <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
              <i
                class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"
              ></i>
              <i
                class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                data-ticon="bx-disc"
              ></i>
            </a>
          </li> -->
        </ul>
      </div>
      <div class="shadow-bottom"></div>
      <div class="main-menu-content">
        <ul
          class="navigation navigation-main"
          id="main-menu-navigation"
          data-menu="menu-navigation"
        >
          <li class="nav-item">
            <router-link :to="{ path: '/' }"
              ><i class="bx bx-home-alt"></i
              ><span class="menu-item" data-i18n="Analytics">Dashboard</span>
            </router-link>
          </li>

          <li class="nav-item" v-if="permission('daily_work')">
            <router-link :to="{ path: '/task' }"
              ><i class="bx bx-building"></i
              ><span class="menu-item" data-i18n="Analytics">
                Work Schedule List
              </span></router-link
            >
          </li>

          <li v-if="permission('kra_kpi_mos_list')" class="nav-item">
            <a href="#" class="icon_right"
              ><i class="bx bxl-redux"></i
              ><span class="menu-title" data-i18n="Content">Master data</span>
              <i class="bx bx-chevron-down"></i
            ></a>
            <ul class="menu-content">
              <!-- -->
              <li v-if="permission('kra_kpi_mos_list')" class="nav-item">
                <router-link :to="{ path: '/kra_kpi_mos_list' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    KRA KPI List
                  </span></router-link
                >
              </li>
              <!-- <li class="nav-item"    v-if="permission('kra_kpi_setting')"> -->
              <li class="nav-item" >
                <router-link :to="{ path: '/kra_kpi_setting' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >KRA & KPI Settings
                  </span></router-link
                >
              </li>

              <li class="nav-item" if="role_id == 5 || role_id == 6 ">
                <router-link :to="{ path: '/mos_assign' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >MOS Assign
                  </span></router-link
                >
              </li>

              <li class="nav-item" v-if="permission('weightage_list')">
                <router-link :to="{ path: '/weightage_list' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Weightage List</span
                  ></router-link
                >
              </li>
              <li class="nav-item" v-if="permission('target_permission_list')">
                <router-link :to="{ path: '/target_permission_list' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Permission Request List</span
                  ></router-link
                >
              </li>
              <li class="nav-item" v-if="permission('wings')">
                <router-link :to="{ path: '/wings' }"
                  ><i class="bx bx-chevron-right"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Second Layer</span
                  >
                </router-link>
              </li>
            </ul>
          </li>
          <li v-if="permission('report_menu_title')" class="nav-item">
            <a href="#" class="icon_right"
              ><i class="bx bx-bar-chart"></i
              ><span class="menu-title" data-i18n="Content">Reports</span>
              <i class="bx bx-chevron-down"></i
            ></a>
            <ul class="menu-content">
              <!---KPI REPORT--->
              <li
                class="nav-item"
                v-if="
                  permission('bpt_report') || permission('manufacture_report')
                "
              >
                <router-link :to="{ path: '/bpt_report' }"
                  ><i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >KPI Report</span
                  >
                </router-link>
              </li>

              <li class="nav-item" v-if="permission('achievement_approval')">
                <router-link :to="{ path: '/winghead_achivement' }"
                  ><i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Achievement Approval</span
                  >
                </router-link>
              </li>

              <!-- { path : "/winghead_achivement/:id", name : "winghead_achivement" , component : winghead_achivement}, -->
              <li class="nav-item" v-if="permission('report_permission')">
                <router-link :to="{ path: '/performance_report' }"
                  ><i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Dept. Monthly performance report</span
                  >
                </router-link>
              </li>
              <li
                class="nav-item"
                v-if="
                  permission('bpt_report') || permission('manufacture_report')
                "
              >
                <router-link :to="{ path: '/kpi_score' }"
                  ><i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >KPI Score</span
                  >
                </router-link>
              </li>
              <li class="nav-item" v-if="permission('report_permission')">
                <router-link :to="{ path: '/kra_individual_update_list' }">
                  <i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Dept wise individual update</span
                  >
                </router-link>
              </li>

              <li class="nav-item" v-if="permission('report_permission')">
                <router-link :to="{ path: '/task_not_update' }">
                  <i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Work Schedule Status
                  </span>
                </router-link>
              </li>

              <li class="nav-item" v-if="permission('monthly_report')">
                <router-link :to="{ path: '/monthly_report' }"
                  ><i class="bx bx-bar-chart"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Monthly Summary</span
                  >
                </router-link>
              </li>
              <li class="nav-item" v-if="permission('report_permission')">
                <router-link :to="{ path: '/summay_report_update' }">
                  <i class="bx bx-bar-chart"></i>
                  <span class="menu-item" data-i18n="Analytics"
                    >Summary report of update
                  </span>
                </router-link>
              </li>
            </ul>
          </li>

          <li v-if="permission('settings')" class="nav-item">
            <a href="#" class="icon_right"
              ><i class="bx bx-sitemap"></i
              ><span class="menu-title" data-i18n="Content">Settings</span>
              <i class="bx bx-chevron-down"></i
            ></a>
            <ul class="menu-content">
              <li
                class="nav-item"
                v-if="permission('department') && role_id == 1"
              >
                <router-link :to="{ path: '/department' }"
                  ><i class="bx bx-server"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Department</span
                  >
                </router-link>
              </li>
              <li
                class="nav-item"
                v-if="permission('department_weekend') && role_id == 1"
              >
                <router-link :to="{ path: '/department_weekend' }"
                  ><i class="bx bx-server"></i
                  ><span class="menu-item" data-i18n="Analytics"
                    >Department Weekend</span
                  >
                </router-link>
              </li>

              <li class="nav-item" v-if="permission('department_setting')">
                <router-link :to="{ path: '/m_o_s_dept_setting' }"
                  ><i class="bx bx-spreadsheet"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    Achivements Permission
                  </span>
                </router-link>
              </li>
              <li
                class="nav-item"
                v-if="permission('m_o_s_achievement_permissions')"
              >
                <router-link :to="{ path: '/m_o_s_achievement_permissions' }"
                  ><i class="bx bx-spreadsheet"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    Achivements Permission
                  </span>
                </router-link>
              </li>
              <li class="nav-item" v-if="permission('weightage_list')">
                <router-link :to="{ path: '/weightage_list' }"
                  ><i class="bx bx-spreadsheet"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    Target Permission
                  </span>
                </router-link>
              </li>
              <li class="nav-item" v-if="permission('monthly_report_update')">
                <router-link :to="{ path: '/monthly_report_update' }"
                  ><i class="bx bx-spreadsheet"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    KRA KPI Modifie
                  </span></router-link
                >
              </li>
              <li class="nav-item" v-if="permission('monthly_summary_update')">
                <router-link :to="{ path: '/monthly_report_update_modifie' }"
                  ><i class="bx bx-spreadsheet"></i
                  ><span class="menu-item" data-i18n="Analytics">
                    Summary Report Permission
                  </span></router-link
                >
              </li>
            </ul>
          </li>

          <li itemprop="" class="nav-item" v-if="permission('users')">
            <router-link :to="{ path: '/users' }"
              ><i class="bx bxs-group"></i
              ><span class="menu-item" data-i18n="Analytics">Users </span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </header>
</template>
<style>
.brand_logo {
  height: 60px;
}
</style>

<script>
import Image from '@/assets/cus_logo.png'
import axios from './axios_instance'

export default {
  props: {},
  components: {},
  data() {
    return {
      token: this.$localStorage.get('d_token'),
      base_url: window.base_url,
      api_url: window.api_url,
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      user: JSON.parse(this.$localStorage.get('user')),
      is_login: false,
      user_type: null,
      notification: '',
      thumbnailphoto: '',
      unread: '',
      p_data: '',
      ImageUrl: Image,
      // defaultImg: 'this.src="' + require(window.base_url+'assets/app-assets/images/logo/logo.png') + '"',
    }
  },

  methods: {
    changeYear() {
      this.$localStorage.set('year', this.year)
      this.$router.go(this.$router.currentRoute)
    },
    refresh() {
      this.$router.go(this.$router.currentRoute)
    },
    Logout() {
      axios.get(this.api_url + 'auth/logout', {
        headers: {
          'Content-Type': 'application/json',
          Authorization: this.token ? `Bearer ${this.token}` : '',
        },
      })
      this.$localStorage.remove('user')
      this.$localStorage.remove('d_token')
      this.$router.push('/login')
    },
    async getNotification() {
      try {
        await axios
          .get(this.api_url + 'get-notification', {
            headers: {
              'Content-Type': 'application/json',
              Authorization: this.token ? `Bearer ${this.token}` : '',
            },
          })
          .then(({ data }) => {
            if (data) {
              this.notification = data.notification
              this.unread = data.unread
            }
          })
      } catch (error) {
        console.log(error)
      }
    },
    pathChange(path) {
      this.$router.push(path)
    },
    notificationRead(notifid) {
      axios
        .get(this.api_url + 'read-notification?nid=' + notifid, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.getNotification()
        })
    },
    profile_thamnail() {
      axios
        .get(this.api_url + 'profile_thamnail', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((res) => {
          this.thumbnailphoto = window.backend_url + res.data.data
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
  },
  created() {
    //this.profile_thamnail();
    this.role_id = this.user_data.role_id
    if (this.$localStorage.get('d_token')) {
      this.is_login = true
      this.user_type = this.user.type
    } else {
      this.is_login = false
    }
    this.single_permission()
    this.getNotification()
  },
  computed: {},
}
</script>
