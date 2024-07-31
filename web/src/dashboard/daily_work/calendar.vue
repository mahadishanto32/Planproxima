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
              <div class="row "  v-if="
              role_id == 1 ||
              role_id == 2 ||
              role_id == 3 ||
              role_id == 4 ||
              role_id == 5 ||
              role_id == 6
            ">
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="
                    (user_data.dept_id==6 &&
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
                <!-- <div
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
                  <label for="users-list-verified"> Wing Test </label>
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
                </div> -->
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified"> Employee </label>
                  <fieldset class="form-group">
                    <!-- <select class="form-control" id="users-list-verified" v-model="filterForm.user_id"
                      v-on:change="getItems()">
                      <option value="">Select One</option>
                      <option :key="row.id" :value="row.id" v-for="row in userItems">
                        {{ row.name }}
                      </option>
                    </select> -->

                    <Select2 :key="render" placeholder="Select One" v-model="filterForm.user_id" :options="userItems"  />
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-1_5">
                  <label for="users-list-verified"> </label>
                  <fieldset class="form-group">
                    <button type="submit" @click="getItems()" class="btn btn-primary mb-2">Submit</button>
                  </fieldset>
                </div>


              </div>

            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">


                      <h3>Daily Works</h3>
                      <br />
                      <div class="table-responsive" :key="render">
                        <v-calendar class="custom-calendar max-w-full" 
                          :attributes="attributes"
                          disable-page-swipe
                          @change="getEvents()" is-expanded>
                          <template v-slot:day-content="{ day, attributes }" >
                            <div class="content_view">
                              <span class="calendar_day">{{ day.day }}</span>
                              <div class="">
                                <vue-custom-scrollbar v-for="attr in attributes" :key="attr.id"
                                  class="bg-orange-500 text-white" :settings="settings" @ps-scroll-y="scrollHanle">

                                  <!-- {{attr.customData.task}} -->
                                  <p v-if="attr.customData.task" v-html="attr.customData.task"></p>
                                  <p v-if="attr.customData.items" v-html="attr.customData.items.task"></p>

                                </vue-custom-scrollbar>
                              </div>
                            </div>
                          </template>
                        </v-calendar>
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
  </div>
</template>
<script>
  import axios from "../../axios_instance";
  import { Form } from "vform";
  import Select2 from 'v-select2-component';
  import vueCustomScrollbar from 'vue-custom-scrollbar'
  import "vue-custom-scrollbar/dist/vueScrollbar.css"
  export default {
    props: {},
    components: {
      'Select2': Select2,
      vueCustomScrollbar
    },
    data() {
      const month = new Date().getMonth();
      const year = new Date().getFullYear();
      return {
        render:1,
        month: month + 1,
        year: year,
        base_url: window.base_url,
        api_url: window.api_url,
        token: this.$localStorage.get("d_token"),
        user_data: JSON.parse(this.$localStorage.get("user")),
        role_id: "",
        items: [],
        deptItems: [],
        WingsItems: [],
        userItems: [],
        status: "",
        scheduleTypes: [],
        scheduleTypes_allow: false,
        attributes: [

        ],
        filterForm: new Form({
          schedule: [],
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



      };
    },
    created() {
      this.role_id = this.user_data.role_id;
      this.dept_id = this.user_data.dept_id;
      this.getItems();
      this.getUser();
      this.getDept();
    },
    methods: {

      async getItems() {
        let where = "?1=1";
        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }
        if (this.month) {
          where += '&month=' + this.month
        }
        if (this.year) {
          where += '&year=' + this.year
        }
        if (this.filterForm.wing_id) {
          where += "&wing_id=" + this.filterForm.wing_id;
        }
        if (this.filterForm.user_id) {
          where += "&user_id=" + this.filterForm.user_id;
        }


        //TO DATE

        let loader = this.$loading.show();
        try {
          await axios
            .get(this.api_url + "daily_schedules_list" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then(({ data }) => {
              if (data.success) {
                this.attributes = data.data;
                for (let index = 0; index < this.attributes.length; index++) {
                  this.attributes[index].dates = new Date(this.attributes[index].dates);

                }
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

      async getDept(type = null) {
        console.log(type);
        let loader = this.$loading.show();
        this.getDepartments(this.status).then(({ data }) => {
          if (data.success) {
            loader.hide();
            this.deptItems = data.data;
          } else {
            loader.hide();
          }
        });
      },

      async getWing() {
        this.getItems();
        this.getUser();
        this.render++;
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
        
        await axios.get(this.api_url + "users_list" + where, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        })
          .then(({ data }) => {
            this.userItems = data.data;
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
    background: #4b79a1;
    /* fallback for old browsers */
    background: -webkit-linear-gradient(to bottom,
        #283e51,
        #4b79a1);
    /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to bottom,
        #283e51,
        #4b79a1);
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    padding: 20px 8px;
    border-top: none !important;
    border-bottom: none !important;
    color: #ffffff !important;
  }
</style>