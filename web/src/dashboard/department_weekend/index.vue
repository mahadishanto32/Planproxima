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
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active"> Department Weekend
                    </li>

                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <div class="users-list-filter px-1">
            <div class="row border rounded py-2 mb-2">
              <div class="col-sm-4 col-lg-2" v-if="role_id = 1">
                <label for="users-list-verified">Group</label>
                <fieldset class="form-group">
                  <select v-on:change="getItems()" v-model="weekendGroupForm.group_id" name="role_id"
                    class="form-control">
                    <option value="">Select one</option>
                    <option v-for="row in wgroup" :key="row.id" :value="row.id">{{ row.name }}</option>
                  </select>
                </fieldset>
              </div>
              <div class="col-sm-4 col-lg-2" v-if="role_id = 1">
                <label for="users-list-verified"></label>
                <fieldset class="form-group">
                  <!-- <a class="btn btn-primary add-btn" @click="weekend_group_pop()"> New Group </a> -->
                </fieldset>
              </div>
            </div>
          </div>
          <!-- Zero configuration table -->
          <section id="basic-datatable">
            <div class="row">
              <div class="col-12">
                <div class="card">

                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="btn-group mb-1" role="group" aria-label="Basic example">
                        <!-- ssss -->
                      </div>
                      <div class="table-responsive" v-if="attributes.length > 0" :key="reRender">
                        <v-calendar class="custom-calendar max-w-full" :attributes="attributes" disable-page-swipe
                          is-expanded>
                          <template v-slot:day-content="{ day, attributes }">
                            <div class="flex flex-col h-full z-10 overflow-hidden">
                              <span class="day-label text-sm text-gray-900">{{ day.day }}</span>
                              <input type="checkbox" @click="UpdateList(day)" checked v-if="dateCheck(day) > 0" />
                              <input type="checkbox" @click="UpdateList(day)" v-else />
                            </div>
                          </template>
                        </v-calendar>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-striped table-sm">
              <tbody>
                <tr>
                  <th>SL</th>
                  <th>Department </th>
                  <th>Group Name</th>
                  <th>Assign New Group</th>
                </tr>
                <tr v-for="rows in AssignDepItem" :key="rows.id" :value="rows.id">
                  <td>{{ rows.id }}</td>
                  <td>{{ rows.name }}</td>
                  <td>{{ rows.assign_name ? rows.assign_name : '' }}</td>
                  <td>
                    <select name="role_id" class="form-control" @change="AssignNewGroup(rows.id)">
                      <option value="">Select one</option>
                      <option v-for="gp in wgroup" :key="gp.id" :value="gp.id">{{ gp.name }}</option>
                    </select>  
                  </td>
                </tr>
              </tbody>
            </table>
          </section>

          <modal width="60%" height="70%" style="padding:50px" name="weekend-group">
            <i @click="weekend_group_hide()" class="bx bx-x-circle  x-circle"></i>
            <div class="app-content ">
              <div class="card">
                <table class="table table-bordered table-striped table-sm">
                  <tbody>
                    <tr>
                      <th>SL</th>
                      <th>Group Name</th>
                    </tr>
                    <tr v-for="row in wgroup" :key="row.id" :value="row.id">
                      <td>{{ row.id }}</td>
                      <td>{{ row.name }}</td>
                      <td>
                        <!-- <a class="dropdown-item"><i class="bx bx-trash mr-1"></i></a> -->
                      </td>
                    </tr>
                    <tr>
                      <td><input type='text' v-model="weekendGroupForm.group_name" /></td>
                      <td><button class="btn btn-primary add-btn" @click="addNewGroup()">Submit</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </modal>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Form } from "vform";
import axios from "../../axios_instance";

export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      reRender: 0,
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      items: [],
      item: [],
      AssignDepItem: [],
      wgroup: [],
      dept_users: [],
      status: '',
      attributes: [
        // {
        //   key: 1,
        //   customData: {
        //     title: 'Lunch with mom.',
        //     class: 'bg-red-600 text-white',
        //   },
        //   dates: new Date(year, month, 1),
        // },
        // {
        //   key: 2,
        //   customData: {
        //     title: 'Take Noah to basketball practice',
        //     class: 'bg-blue-500 text-white',
        //   },
        //   dates: new Date(year, month, 2),
        // },
        // {
        //   key: 3,
        //   customData: {
        //     title: "Noah's basketball game.",
        //     class: 'bg-blue-500 text-white',
        //   },
        //   dates: new Date(year, month, 5),
        // },
        // {
        //   key: 4,
        //   customData: {
        //     title: 'Take car to the shop',
        //     class: 'bg-indigo-500 text-white',
        //   },
        //   dates: new Date(year, month, 5),
        // },
        // {
        //   key: 4,
        //   customData: {
        //     title: 'Meeting with new client.',
        //     class: 'bg-teal-500 text-white',
        //   },
        //   dates: new Date(year, month, 7),
        // },
        // {
        //   key: 5,
        //   customData: {
        //     title: "Mia's gymnastics practice.",
        //     class: 'bg-pink-500 text-white',
        //   },
        //   dates: new Date(year, month, 11),
        // },
        // {
        //   key: 6,
        //   customData: {
        //     title: 'Cookout with friends.',
        //     class: 'bg-orange-500 text-white',
        //   },
        //   dates: { months: 5, ordinalWeekdays: { 2: 1 } },
        // },
        // {
        //   key: 7,
        //   customData: {
        //     title: "Mia's gymnastics recital.",
        //     class: 'bg-pink-500 text-white',
        //   },
        //   dates: new Date(year, month, 22),
        // },
        // {
        //   key: 8,
        //   customData: {
        //     title: 'Visit great grandma.',
        //     class: 'bg-red-600 text-white',
        //   },
        //   dates: new Date(year, month, 25),
        // },
      ],
      weekendGroupForm: new Form({
        group_name: '',
        group_id: 1,
        check: '',
        date: ''
      }),
    };
  },
  created() {
    this.getItems();
    // this.getData();
    this.getAssignList();
    this.wGroupItems();
  },
  methods: {
    AssignNewGroup(department){
      let group = event.target.value;
      let where = '?';
      if (group) {
        where += '&group_id=' + group;
      }
      if (department) {
        where += '&department_id=' + department;
      }  
      axios.get(window.api_url + "dep_weekend_assign" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
      .then(({ data }) => {
        if (data.success) {
            this.$toasted.show('Assign Successfully', {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
        }
        this.getItems();
        // this.getData();
        this.getAssignList();
        this.wGroupItems();
      });      
    },
    async popUp(item) {
      this.item = item;
      let loader = this.$loading.show();

      await axios.get(this.api_url + "department_assigns?dept_id=" + item.id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          loader.hide();
          this.dept_users = data.data;
        });
      this.$modal.show("popup-singel");
    },
    hide_pop() {
      this.$modal.hide("popup-singel");
    },
    weekend_group_pop() {
      this.$modal.show("weekend-group");
    },
    weekend_group_hide() {
      this.$modal.hide("weekend-group");
    },
    async getItems() {
      let where = '?';
      if (this.weekendGroupForm.group_id) {
        where += '&group_id=' + this.weekendGroupForm.group_id;
      } else {
        where += '&group_id=' + 1;
      }
      let loader = this.$loading.show();
      await axios.get(window.api_url + "dep_weekend" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
      .then(({ data }) => {
        this.reRender++;
        this.items = [];
        this.items = data.data;
        this.attributes = [];
        this.items.filter((row, index) => {

          let atr = {
            key: index,
            customData: {
              title: '',
              class: '',
            },
            dates: row.date,//new Date(year, month, 1),
            type: 1
          };
          this.attributes[index] = atr;
        });
        // console.log('this.attributes',this.attributes.length());
        // attributes
        loader.hide();
      });
    },
    async wGroupItems() {
      let loader = this.$loading.show();
      await axios.get(window.api_url + "weekend_group", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.wgroup = data.data
          loader.hide();
        });
    },
    async getData() {
      let loader = this.$loading.show();
      this.getItem("departments_all").then(({ data }) => {
        if (data.success) {
          loader.hide();
          // this.deptItems = data.data;
        } else {
          loader.hide();
        }
      });
    },
    async getAssignList() {
      let loader = this.$loading.show();
      this.getItem("weekend_assign").then(({ data }) => {
        if (data.success) {
          loader.hide();
          this.AssignDepItem = data.data;
        } else {
          loader.hide();
        }
      });
    },
    addNewGroup() {
      this.weekendGroupForm
        .post(this.api_url + "weekendgroup_add", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(
          (res) => {
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }
            this.wGroupItems();
          },
          (error) => {
            loader.hide();
          }
        );
    },
    dateCheck(date) {
      let monthGet = date.month < 10 ? '0' + date.month : date.month;
      let dateGet = date.day < 10 ? '0' + date.day : date.day;
      let dates = date.year + '-' + monthGet + '-' + dateGet;
      let responce = 0;
      this.attributes.filter((row, index) => {
        if (row.dates == dates) {
          // console.log('responce' , 1);
          responce = 1;
          return responce;
        }
      })
      return responce;
    },
    UpdateList(date) {
      let monthGet = date.month < 10 ? '0' + date.month : date.month;
      let dateGet = date.day < 10 ? '0' + date.day : date.day;
      let dates = date.year + '-' + monthGet + '-' + dateGet;
      this.weekendGroupForm.date = dates;
      this.weekendGroupForm.check = event.target.checked;
      try {
        this.weekendGroupForm.post(this.api_url + "dep_weekend_updates", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          // console.log(res);
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
        }, (error) => {
          console.log(error);
        })
      } catch (error) {
        // loader.hide(); 
        console.log(error);
      }
    }
  },
  computed: {},
};
</script>