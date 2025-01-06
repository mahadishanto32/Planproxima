<template>
  <header>
    <div class="header-navbar-shadow"></div>
    <nav class="
        header-navbar
        main-header-navbar
        navbar-expand-lg navbar navbar-with-menu
        fixed-top
      ">
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="navbar-collapse" id="navbar-mobile">
            <div class="
                mr-auto
                float-left
                bookmark-wrapper
                d-flex
                align-items-center
              ">
              <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu d-xl-none mr-auto">
                  <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a>
                </li>
              </ul>
            </div>
            <ul class="nav navbar-nav float-right">
              <li class="dropdown dropdown-notification nav-item">
                <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i
                    class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i><span
                    class="badge badge-pill badge-danger badge-up" v-if="unread > '0'">{{ unread }}</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                  <li class="dropdown-menu-header">
                    <div class="
                        dropdown-header
                        px-1
                        py-75
                        d-flex
                        justify-content-between
                      ">
                      <span class="notification-title" v-if="unread > '0'">{{ unread }} New Notification</span>
                      <span class="notification-title" v-if="unread == '0'">No New Notification </span>

                      <!-- <span class="text-bold-400 cursor-pointer">Mark all as read</span> -->
                    </div>
                  </li>

                  <li class="scrollable-container media-list" v-if="notification">
                    <template v-for="(item, index) in notification">
                      <div :key="index" class="d-flex justify-content-between cursor-pointer" v-bind:class="
                        item.status == '0' ? 'read-notification' : ''
                      " @click="notificationRead(item.id)" v-if="item.notification_type == 'Achivement Notification'">
                        <a v-on:click="pathChange('/winghead_achivement/' + item.user_id)">
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
                              <small class="notification-text">{{ format_Date(item.created_at) }}
                                {{ formatAMPM(item.created_at) }}</small>
                            </div>
                          </div>
                        </a>
                      </div>
                      <div :key="index" class="d-flex justify-content-between cursor-pointer" v-bind:class="
                        item.status == '0' ? 'read-notification' : ''
                      " @click="notificationRead(item.id)" v-else>
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
                            <small class="notification-text">{{ format_Date(item.created_at) }}
                              {{ formatAMPM(item.created_at) }}</small>
                          </div>
                        </div>
                      </div>

                    </template>
                  </li>
                  <li class="scrollable-container media-list" v-if="!notification">
                    <div class="media d-flex align-items-center">Loading..</div>
                  </li>
                  <li class="scrollable-container media-list" v-if="!notification.length">
                    <div class="media d-flex align-items-center">
                      No Data Found
                    </div>
                  </li>

                  
                </ul>
              </li>
              <li @click="refresh()" class="dropdown dropdown-user nav-item">
                <fieldset class="form-group">
                  <i class="bx bxs-analyse" style="
                      font-size: 35px;
                      padding-top: 10px;
                      padding-right: 20px;
                    "></i>
                </fieldset>
              </li>
             
             
              
              
              <li class="dropdown dropdown-notification nav-item">
                <fieldset class="form-group">
                  <select id="Profession" v-model="year" v-on:change="changeYear()" name="year" class="form-control"> 
 
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2023-2024</option>
                    <option value="2025">2024-2025</option>
                  </select>
                </fieldset>
              </li>
              <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                  <div class="user-nav d-sm-flex d-none">
                    <span class="user-name">{{ user.name }}<element v-if="user.employee_id">({{ user.employee_id }})
                      </element></span>
                    <span class="user-status text-muted">
                      <element v-if="user.deptjoin">{{ user.deptjoin.name }} <element v-if="user.wingjoin">-
                          {{ user.wingjoin.wing_title }}</element>
                      </element>
                    </span>
                  </div>
                  <span>
                    <!-- <img class="round" :src="thumbnailphoto" height="40" width="40"
                      onerror="this.src=window.base_url+'assets/app-assets/images/logo/logo.png'"  alt="Logo" /> -->

                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pb-0">
                  <a class="dropdown-item">
                    <i class="bx bx-user mr-50"></i>
                    <router-link :to="{ path: '/profile' }"> My Profile </router-link>
                  </a>
                  <a class="dropdown-item">
                    <i class="bx bx-lock mr-50"></i>
                    <router-link :to="{ path: '/change_password' }"> Change Password</router-link>
                  </a>

                  <!-- <a  v-if="user.role_id !=1" class="dropdown-item">
                    <i class="bx bx-card mr-50"></i>
                    <router-link  :to="{ path: '/visitingcard/'+user.employee_id }"> Visiting Card </router-link>
                  </a>
  -->
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
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow"
      style="touch-action: none; user-select: none" data-scroll-to-active="true">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="../">
              <div class="brand-logo">
                <!-- <img class="logo" :src="base_url + 'assets/app-assets/images/logo/logo.png'"  alt="Logo" /> -->
              </div>
            </a>
          </li>
          <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
              <i class="
                  bx bx-x
                  d-block d-xl-none
                  font-medium-4
                  primary
                  toggle-icon
                "></i>
              <i class="
                  toggle-icon
                  bx bx-disc
                  font-medium-4
                  d-none d-xl-block
                  collapse-toggle-icon
                  primary
                " data-ticon="bx-disc"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="shadow-bottom"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"> 
          <li class="nav-item" v-for="(mainMenu, index) in menu_permission" > 
            <template v-if="mainMenu.sub_menu.length == 0"> 
              <li itemprop="" class="nav-item">
                <router-link :to="{ path: mainMenu.menu_url }">
                  <i class="bx bx-slider-alt"></i><span class="menu-title" data-i18n="Content">  &nbsp;  &nbsp; {{mainMenu.menu_name}}  
                  </span>
                </router-link>
              </li> 
            </template> 
            <template v-else>  
            <a href="#" class="icon_right"><i class="bx bx-sitemap"></i><span class="menu-title"
                data-i18n="Content">{{mainMenu.menu_name}} </span>
              <i class="bx bx-chevron-down"></i></a>
            <ul class="menu-content">
              
              <li class="nav-item"  v-for="(subMenu, index) in mainMenu.sub_menu" >
                <router-link :to="{ path: subMenu.menu_url }"><i class="bx bx-server"></i><span class="menu-item"
                    data-i18n="Analytics">{{subMenu.menu_name}} </span>
                </router-link>
              </li>  
            </ul>
            </template>
          </li>
          
          <!-- <li class="nav-item" >
            <a href="#" class="icon_right"><i class="bx bx-sitemap"></i><span class="menu-title"
                data-i18n="Content">Menu Manage</span>
              <i class="bx bx-chevron-down"></i></a>
            <ul class="menu-content">
              
              <li class="nav-item" v-if="permission('tour_plan')">
                <router-link :to="{ path: '/user_group' }"><i class="bx bx-server"></i><span class="menu-item"
                    data-i18n="Analytics">User Group</span>
                </router-link>
              </li>
              <li class="nav-item" v-if="permission('tour_plan')">
                <router-link :to="{ path: '/menu_setup' }"><i class="bx bx-server"></i><span class="menu-item"
                    data-i18n="Analytics"> Menu Setup</span>
                </router-link>
              </li>
              <li class="nav-item" >
                <router-link :to="{ path: '/menu_permission_setup' }"><i class="bx bx-server"></i><span class="menu-item"
                    data-i18n="Analytics"> Menu Permission Setup</span>
                </router-link>
              </li>
               

            </ul>
          </li> -->
        </ul>
      </div>
    </div>
  </header>
</template>

<script>
import axios from "./axios_instance";

export default {
  props: {},
  components: {},
  data() {
    return {
      token: this.$localStorage.get("d_token"),
      base_url: window.base_url,
      api_url: window.api_url,
      user_data: JSON.parse(this.$localStorage.get("user")),
      menu_permission:   JSON.parse(this.$localStorage.get("menu_permission")),
      role_id: "",
      user: JSON.parse(this.$localStorage.get("user")),
      is_login: false,
      user_type: null,
      notification: "",
      thumbnailphoto: "",
      unread: "",
      p_data: "",
      // defaultImg: 'this.src="' + require(window.base_url+'assets/app-assets/images/logo/logo.png') + '"',
    };
  },
  created() {
    //this.profile_thamnail();
    this.role_id = this.user_data.role_id;
    if (this.$localStorage.get("d_token")) {
      this.is_login = true;
      this.user_type = this.user.type;
    } else {
      this.is_login = false;
    }
    //this.$localStorage.remove("menu_permission");
    this.getUserMenu();
    // this.single_permission();
    // this.getNotification();
  },

  methods: {
    changeYear() {
      this.$localStorage.set("year", this.year);
      this.$router.go(this.$router.currentRoute);
    },
    refresh() {
      this.$router.go(this.$router.currentRoute);
    },
    Logout() {
      axios
        .get(this.api_url + "auth/logout", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        });
      this.$localStorage.remove("user");
      this.$localStorage.remove("d_token");
      this.$localStorage.remove("menu_permission");
      this.$router.push("/login");
    },
    //get_user_menu
    async getUserMenu() {
      try {
        //  
        await axios.get(this.api_url + "get_user_menu", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          if (res.data.status == 1) { 
            this.menu_permission = res.data.menu_permission ;
            this.$localStorage.set("menu_permission", JSON.stringify(res.data.menu_permission)); 
            
          }else if(res.data.status == 3) { 
            this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
            this.$localStorage.remove("user");
            this.$localStorage.remove("d_token");
            this.$localStorage.remove("menu_permission");
            this.$router.push("/login");
          }
        }, (error) => {
          console.log(error); 
        })
      } catch (error) {  
        console.log(error);
      }
    },

    // async getNotification() {
    //   try {
    //     await axios
    //       .get(this.api_url + "get-notification", {
    //         headers: {
    //           "Content-Type": "application/json",
    //           Authorization: this.token ? `Bearer ${this.token}` : "",
    //         },
    //       })
    //       .then(({ data }) => {
    //         if (data) {
    //           this.notification = data.notification;
    //           this.unread = data.unread;
    //         }

    //       });
    //   } catch (error) {
    //     console.log(error);
    //   }
    // },
    pathChange(path) {
      this.$router.push(path);
    },
    notificationRead(notifid) {
      axios
        .get(this.api_url + "read-notification?nid=" + notifid, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((data) => {
          this.getNotification();
        });
    },
    profile_thamnail() {
      axios
        .get(this.api_url + "profile_thamnail", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          this.thumbnailphoto = window.backend_url + res.data.data;
        });
    },
    single_permission() {
      axios
        .get(this.api_url + "single_permission", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((res) => {
          this.p_data = res.data.data[0];
        });
    },
  },

  computed: {},
};
</script>