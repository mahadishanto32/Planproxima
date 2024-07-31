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
                    <li class="breadcrumb-item active"> Users
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link class="btn btn-primary add-btn" :to="{ path: '/new_user' }"><i class="bx bx-add-alt"></i>
                  New user
                </router-link>
              </div>

            </div>
          </div>
        </div>
        <div class="content-body">
          <div class="users-list-filter px-1">
            <div class="row border rounded py-2 mb-2">
              <div class="col-sm-4 col-lg-2" v-if="role_id = 1">
                <label for="users-list-verified">Role</label>
                <fieldset class="form-group">
                  <select v-on:change="getItems()" name="role_id" v-model="filterForm.role_id" class="form-control">
                    <option value="">Select one</option>
                    <option v-for="row in roles" :key="row.id" :value="row.id">{{ row.title}}</option>
                  </select>
                </fieldset>
              </div>

              <div class="col-sm-4 col-lg-2" v-if="(deptItems.length > 1)">
                <label for="users-list-verified">Department</label>
                <fieldset class="form-group">
                  <select class="form-control" v-on:change="getWing()" v-model="filterForm.dept_id"
                    id="users-list-verified">
                    <option value="">Select One</option>
                    <option v-for="row in deptItems" :key="row.id" :value="row.id">
                      {{ row.name }}
                    </option>
                  </select>
                </fieldset>
              </div>
              <div class="ccol-sm-4 col-lg-2" v-if="role_id < 6">
                <label for="users-list-verified">Wings</label>
                <fieldset class="form-group">
                  <select class="form-control" v-on:change="getItems()" v-model="filterForm.wing_id"
                    id="users-list-verified">
                    <option value="">Select One</option>
                    <option v-for="row in WingsItems" :key="row.id" :value="row.id">
                      {{ row.wing_title }}
                    </option>
                  </select>
                </fieldset>
              </div>
              <div class="ccol-sm-4 col-lg-2" >
                <label for="users-list-verified">Search</label>
                <fieldset class="form-group">
                  <input type="text" name="search"  v-on:keyup="getItems()"  v-model="filterForm.search"  class="form-control" data-validation-required-message="This field is required" placeholder="Name , Employee ID , Phone , Email"> 
                </fieldset>
              </div>
              <div class="ccol-sm-4 col-lg-2" >
                <label for="users-list-verified">Limit</label>
                <fieldset class="form-group">
                  <select class="form-control" v-on:change="getItems()" v-model="filterForm.limit"
                    id="users-list-verified">
                    <option value="">Select One</option>
                    <option value="20">20 </option>
                    <option value="100">100 </option>
                    <option value="500">500 </option>
                    <option value="1000">1000 </option>
                    <option value="3000">3000 </option>
                    <option value="5000">5000 </option>
                  </select>
                </fieldset>
              </div>
              <div class="ccol-sm-4 col-lg-2"  v-if="role_id = 1" >
                <label for="users-list-verified">Status</label>
                <fieldset class="form-group">
                  <select class="form-control" v-on:change="getItems()" v-model="filterForm.status"
                    id="users-list-verified">
                    <option value="">Select One</option>
                    <option value="1">Active </option>
                    <option value="0">Inactive </option> 
                  </select>
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

                      <div class="table-responsive">
                        <h4>Users List</h4><br>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Sl.No</th>
                              <th>Name</th>
                              <th>User Name</th>
                              <th>Phone</th>
                              <th>Department</th>
                              <th>Organization Email</th>
                              <th>Employee ID</th> 
                              <th>Designation</th>
                              <th>User Role</th>
                              <th>Status</th>
                              <th>Action</th>

                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(row , index) in items" :key="row.id">
                              <td>{{ index + 1}}</td>
                              <td>{{ row.name }}</td>
                              <td>{{ row.email }}</td>
                              <td>{{ row.phone }}</td>
                              <td>{{ row.deptjoin ? row.deptjoin.name : '' }}</td>
                              <td>{{ row.ad_mail }}</td>
                              <td>{{ row.employee_id }}</td> 
                              <td>{{ row.designation }}</td>
                              <td>{{ row.rolejoin ? row.rolejoin.title : '' }}</td>
                              <td :class="row.status === 1 ? 'active' : 'inactive'">
                                {{ row.status === 1 ? 'Active' : 'Inactive' }}
                              </td>
                              <td>
                                <div class="dropup">
                                  <span
                                    class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                  </span>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <router-link class="dropdown-item" :to="{ path: '/edit_user/'+row.id }"><i
                                        class="bx bx-edit-alt mr-1"></i> Edit
                                    </router-link>
                                    <router-link
                                      v-if="row.role_id == 5 || row.role_id == 1 || row.role_id == 2 || row.role_id == 3 || row.role_id == 4  || row.role_id == 8"
                                      class="dropdown-item" :to="{ path: '/dept_permission/'+row.id }"><i
                                        class="bx bx-edit-alt mr-1"></i> Dept. Permission
                                    </router-link>
                                    <router-link class="dropdown-item" :to="{ path: '/dept_transfer/'+row.id }"><i
                                        class="bx bx-edit-alt mr-1"></i> Department Transfer
                                    </router-link> 
                                    <router-link 
                                    v-if="user_data.role_id == 1 "
                                    class="dropdown-item" :to="{ path: '/kra_kpi_permission/'+row.id }"><i
                                        class="bx bx-edit-alt mr-1"></i> KRA KPI, Share 
                                    </router-link>

                                    <a class="dropdown-item" @click="delete_row(row.id)"><i
                                        class="bx bx-trash mr-1"></i>
                                      Delete</a>
                                  </div>
                                </div>
                              </td>
                            </tr>
                            <tr v-if="items.length < 1">
                              <td colspan="4">Data not found</td>
                            </tr>

                          </tbody>
                        </table>
                        <br>
                        <br>
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
import axios from "../../axios_instance";
import { Form } from "vform";
export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      deptItems: [],
      search: '',
      base_url: window.base_url,
      api_url: window.api_url,
      role_id: '',
      user_data: JSON.parse(this.$localStorage.get("user")),
      employeeItem: [],
      token: this.$localStorage.get("d_token"),
      items: [],
      status: '',
      mployeeItem: [],
      WingsItems: [],
      wing_user: '',
      roles: [],
      filterForm: new Form({
        dept_id: this.$route.query.dept_id ? this.$route.query.dept_id : "",
        wing_id: "",
        user_id: "",
        role_id: "",
        status: 1,
        limit: "20"
      }),

    };
  },
  created() {
    this.role_id = this.user_data.role_id;
    this.getItems();
    this.getDept();
    this.getRole();
    if (this.role_id == 5) {
      this.getWing();
    }
  },
  methods: {
    //PAGINATION
    setPage: function (pageNumber) {
      this.currentPage = pageNumber
    },
    async getEmployee() {
      //if (this.filterForm.wing_id) {
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
          this.employeeItem = data.data;
        });
      //}

    },
    async getWing() {
      await axios.get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.getItems();
          this.WingsItems = data.data;
        });
    },
    async getDept() {
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
    async delete_row(id) {
      console.log(id);
      let loader = this.$loading.show();
      try {
        await axios
          .delete(this.api_url + "users/" + id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            res
          }) => {
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
      let where = '?';
      where += '&status=' + this.filterForm.status;
      if (this.filterForm.role_id) {
        where += '&role_id=' + this.filterForm.role_id;
      }
      if (this.filterForm.limit) {
        where += '&limit=' + this.filterForm.limit;
      }
      if (this.dept_id) {
        where += '&dept_id=' + this.dept_id;
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id;
      }
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id;
      }
      if (this.filterForm.search) {
        where += '&search=' + this.filterForm.search;
      }
      //search
      if (this.filterForm.user_id) {
        where += '&wing_id=' + this.filterForm.user_id;
      }
      let loader = this.$loading.show();
      try {
        await axios
          .get(this.api_url + "users" + where, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            data
          }) => {
            this.items = data.data;
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    async getRole() {
      await axios.get(this.api_url + "role", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.roles = data.data;
        });
    },

  },
  computed: {
    filteredItems() {
      return this.items.filter(item => {
        return item.email.toLowerCase().indexOf(this.search.toLowerCase()) > -1
      })
    },
  },
}
  ;
</script>
<style>
.active {
  color: green;
}

.inactive {
  color: red;
}
</style>