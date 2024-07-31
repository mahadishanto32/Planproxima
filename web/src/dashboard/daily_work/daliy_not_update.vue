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
                    <li class="breadcrumb-item active"> Daliy Not Update
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <a class="btn btn-primary add-btn" @click="show_pop()"> <i class="bx bx-add-alt"></i>Mail({{
                    dept_selects.length
                }}) </a>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">

          <section id="basic-datatable">

            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4"
                  class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Filter</label>
                  <fieldset class="form-group">
                    <input type="text" class="text-center form-control" placeholder="Search" v-model="search">
                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label">Date </label>
                  <fieldset class="form-group">
                    <datepicker :disabled-dates="state.disabledDates" v-model="filterForm.date" name="date"
                      class="form-control"></datepicker>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label class="control-label"> </label>
                  <fieldset class="form-group">
                    <button v-on:click="getItems()" type="submit" class="btn btn-primary">Submit</button>
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
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>SL</th>
                              <th>Department Name</th>
                              <th>
                                <input v-on:change="AllDept()" v-model="all_dept" type="checkbox">
                                <a @click="show_pop()" class="mail_send"> <i class="bx bx-mail-send"></i> </a>
                                <!-- <div class="dropup">
                                  <span
                                    class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    role="menu"
                                    >
                                  </span>
                                  <div class="dropdown-menu dropdown-menu-right" >

                                      <a  class="dropdown-item" @click="multi_statusChangeDelete()" ><i class="bx bx-edit-alt mr-1"></i> Delete</a >

                                      <a  class="dropdown-item" @click="multi_send_to_budget()" ><i class="bx bx-paper-plane mr-1"></i> Send to budget</a >
                                  </div>
                                </div>  -->
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in filteredItems" :key="item.id">
                              <td class="title"> {{ index + 1 }}</td>
                              <td>{{ item.deptjoin ? item.deptjoin.name : '' }}</td>
                              <td>
                                <input v-on:change="selectChange(index)" v-model="item.checked" value="row.id"
                                  type="checkbox">
                                <a @click="show_pop_singel(index)" class="mail_send"> <i class="bx bx-mail-send"></i>
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <modal width="75%" height="80%" style="padding:50px" name="popup-singel">
            <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
            <div class="app-content ">
              <div class="card">
                <table class="table table-bordered table-striped table-sm">
                  <tbody>
                    <tr>
                      <th class="text-center">
                        <fieldset class="form-group">
                          <input @change="AllDept()" type="checkbox" v-model="all_dept" class="form-control"
                            value="1">Department
                          All
                        </fieldset>
                      </th>
                      <th colspan="3" class="text-center">

                        <div class=" col-sm-12">

                          <fieldset class="form-group">
                            <multiselect v-model="dept_selects" :options="items" :multiple="true"
                              placeholder="Select(Dept)" :label="'name'" track-by="id" :searchable="true"
                              :close-on-select="false" :show-labels="false">
                              <template slot="selection" slot-scope="{ values , isOpen }"><span
                                  class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length
                                  }} options selected</span>
                              </template>
                            </multiselect>
                          </fieldset>
                        </div>
                      </th>
                    </tr>
                    <tr>

                      <th colspan="4" class="text-center">
                        <vue-editor v-model="daliy_mailForm.all_dept_comm" name="task" placeholder="Comment....">
                        </vue-editor>
                      </th>

                    </tr>
                    <tr>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC1</label>
                          <div class="controls">
                            <input v-model="daliy_mailForm.mailcc1" placeholder="example1@gmail.com"
                              class="form-control" type="text" />
                          </div>
                        </div>

                      </th>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC3</label>
                          <div class="controls">
                            <input v-model="daliy_mailForm.mailcc2" placeholder="example2@gmail.com"
                              class="form-control" type="text" />
                          </div>
                        </div>

                      </th>
                      <th class="text-center">
                        <div class="form-group">
                          <label for="Profession">Mail CC3</label>
                          <div class="controls">
                            <input v-model="daliy_mailForm.mailcc3" placeholder="example3@gmail.com"
                              class="form-control" type="text" />
                          </div>
                        </div>

                      </th>
                      <th class="text-center">
                        <button @click="create()" class="btn btn-success">Save</button>
                      </th>
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
import axios from "../../axios_instance";
import { Form } from "vform";
import { VueEditor } from "vue2-editor";
import Datepicker from 'vuejs-datepicker';
import Multiselect from 'vue-multiselect';

export default {
  props: {},
  components: {
    VueEditor,
    Multiselect,
    Datepicker
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      dept_selects: [],
      all_dept: '',
      search: '',
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: '',
      items: [],
      deptItems: [],
      item: [],
      status: '',
      all_row_select: false,
      filterForm: new Form({
        dept_id: "",
        date: new Date(),

      }),
      daliy_mailForm: new Form({
        mailcc1: "",
        mailcc2: "",
        mailcc3: "",
        dept_selects: "",
        all_dept_comm: ""
      }),
      state: {
        disabledDates: {
          to: new Date(2020, 0, 0), // Disable all dates up to specific date
          from: new Date(), // Disable all dates after specific date

        }
      }
    };
  },
  created() {

    this.role_id = this.user_data.role_id;
    this.getDept('first');
    this.getItems();
  },
  methods: {
    async getDept(type = null) {
      if (type != 'first') {
        this.own = false;
      }

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
    selectChange(index) {
      this.items[index].checked = this.items[index].checked ? true : false;
      if (this.items[index].checked) {
        this.dept_selects.push(this.items[index]);
      }

      //console.log(this.items);
    },
    show_pop_singel(index) {
      this.dept_selects.push(this.items[index]);
      this.$modal.show("popup-singel");
    },

    AllDept() {
      if (this.all_dept == 1) {
        this.dept_selects = this.items;

      } else {
        this.dept_selects = [];
      }
      for (let index = 0; index < this.items.length; index++) {
        this.items[index].checked = this.all_dept == 1 ? true : false;
      }
    },

    hide_pop() {
      this.$modal.hide("popup-singel");
    },
    show_pop() {
      this.$modal.show("popup-singel");
      // for (let index = 0; index < this.items.length; index++) {
      //         if(this.items[index].checked ){
      //             this.dept_selects.push(this.items[index]);
      //         }
      // }
    },
    create() {
      try {
        let loader = this.$loading.show();
        this.daliy_mailForm.dept_selects = this.dept_selects;
        this.daliy_mailForm.post(this.api_url + "daliy_mail", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
          loader.hide();
          this.hide_pop();
          // this.$router.push('/daily_work');
        }, (error) => {
          console.log(error);
          loader.hide();
        })
      } catch (error) {
        // loader.hide();
        console.log(error);
      }
    },
    async dept() {
      this.getDepartments().then(({ data }) => {
        if (data.success) {
          this.DepartmentsItems = data.data;
        }
      });
    },
    async getItems() {
      let where = '?1=1';
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }

      if (this.filterForm.date) {
        where += '&date=' + this.format_Date(this.filterForm.date);
      }

      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "daliy_not_update/" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            if (data.success) {
              this.items = data.data;

            }
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },

    //REPORT GENERATED BY DATE SELECT
    dateSelected() {
      this.getItems()
    },
  },
  computed: {
    filteredItems() {
      return this.items.filter(item => {
        return item.deptjoin.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1
      })
    }
  },
};
</script>
    