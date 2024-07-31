<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item  ">
                      <router-link :to="{ path: '/tour_plan_users' }"> Tour Users</router-link>
                    </li>
                    <li class="breadcrumb-item active"> New Tour User
                    </li>

                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section class="input-validation">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">New Tour User</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <form @submit.prevent="create()">
                        <div class="row">
                          <div class="col-md-6">

                            <div class="form-group">
                              <label>Name</label>
                              <div class="controls">
                                <input type="text" name="name" v-model="addForm.name"
                                       :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Name">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Employee Id</label>
                              <div class="controls">
                                <input type="text" name="employee_id" v-model="addForm.employee_id"
                                       :class="{  'is-invalid': addForm.errors.has('employee_id'),  }"
                                       class="form-control" data-validation-required-message="This field is required"
                                       placeholder="Employee ID">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Email/Username</label>
                              <div class="controls">
                                <input type="text" name="email" v-model="addForm.email"
                                       :class="{  'is-invalid': addForm.errors.has('email'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Email">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Password</label>
                              <div class="controls">
                                <input type="password" name="password" v-model="addForm.password"
                                       :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Password">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Phone</label>
                              <div class="controls">
                                <input type="text" name="phone" v-model="addForm.phone"
                                       :class="{  'is-invalid': addForm.errors.has('phone'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Phone">
                              </div>
                            </div>

                            <!--USER BASE STATION ADDRESS-->
                            <div class="form-group">
                              <label>base station address</label>
                              <div class="controls">
                                <input type="text" name="base_station_address" v-model="addForm.base_station_address"
                                       :class="{  'is-invalid': addForm.errors.has('base_station_address'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Enter base station address">
                              </div>
                            </div>

                            <div class="form-group">
                              <label>Designation</label>
                              <div class="controls">
                                <select name="designation" v-model="addForm.designation"
                                        class="form-control chzn-select">
                                  <option value="">Select One</option>
                                  <option value="Sales Manager">Sales Manager</option>
                                  <option value="Assistant General Manager">Assistant General Manager</option>
                                  <option value="Assistant Sales Manager">Assistant Sales Manager</option>
                                  <option value="Divisional Sales Manager">Divisional Sales Manager</option>
                                  <option value="Assistant Divisional Sales Manager">Assistant Divisional Sales Manager</option>
                                  <option value="Regional Sales Manager">Regional Sales Manager</option>
                                  <option value="Territory Sales Manager">Territory Sales Manager</option>
                                  <option value="DIVISONAL_HEAD">DIVISONAL HEAD</option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label>BUSINESS TYPE</label>
                              <div class="controls">
                                <select class="form-control chzn-select" v-model="addForm.la">
                                  <option value="">Select One</option>
                                  <option v-for="row in businessTypes" :key="row.id" :value="row.id">
                                    {{ row.title }}
                                  </option>
                                </select>
                              </div>
                            </div>

                           

                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>RSM SUPER VISOR</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.rsm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in rsm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label>ADSM SUPERVISOR</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.adsm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in adsm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>DSM SUPERVISOR </label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.dsm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in dsm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>ASM SUPERVISOR</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.asm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in asm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>AGM/SM SUPERVISOR</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.sm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in sm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label>DIVISIONAL HEAD</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.divisional_head" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in divisional_head" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>HEAD OF SALES</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.hos" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in hos" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Division</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="addForm.division_id" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in division_list" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>  
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- Input Validation end -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
//import axios from "../../axios_instance"; 
import {Form} from "vform";
import axios from "../../axios_instance";

export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin 
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      DepartmentsItems: [],
      users: [],
      rsm: [],
      adsm: [],
      dsm: [],
      asm: [],
      sm: [],
      divisional_head: [],
      division_list: [],
      hos: [],
      businessTypes: [],
      token: this.$localStorage.get("d_token"),
      addForm: new Form({
        name: "",
        email: "",
        dept_id: "",
        password: "",
        phone: "",
        base_station_address: "",
        role_id: 10,
        wing_id: "",
        employee_id: "",
        designation: "",
        tour_permission: 1,
        la: "1",
        rsm: "",
        adsm: "",
        dsm: "",
        asm: "",
        sm: "",
        division_head: "",
        division_id : ""

      }),
      roles: [],
      WingsItems: [],
    };
  },
  created() {
    this.user();
    this.getBusinessTypes();
    this.division_fnctn();
  },
  methods: {
    async division_fnctn(){
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "divisions" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                    })
                    .then(({
                            data
                        }) => {
                    if (data.success) {
                        this.division_list = data.data;
                        
                    }
                    loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
    //tour_users
    async user() {
      let where = '?';
      let loader = this.$loading.show();
      try {
        await axios
            .get(this.api_url + "tour_user" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
              },
            })
            .then(({
                     data
                   }) => {
              if (data.success) {
                this.users = data.data;
                this.rsm = this.users.rsm;
                this.adsm = this.users.adsm;
                this.dsm = this.users.dsm;
                this.asm = this.users.asm;
                this.sm = this.users.sm;
                this.divisional_head = this.users.divisional_head;
                this.hos = this.users.hos;
                console.log('this.rsm');
                console.log(this.rsm);
              }
              loader.hide();
            });
      } catch (error) {
        loader.hide();
      }
    },

    //GET TOUR BUSINESS TYPE
    async getBusinessTypes() {
      let where = '?';
      let loader = this.$loading.show();
      try {
        await axios
            .get(this.api_url + "tour_business_types" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
              },
            })
            .then(({
                     data
                   }) => {
              if (data.success) {
                this.businessTypes = data.data;
                console.log('businessTypes',this.businessTypes);
              }
              loader.hide();
            });
      } catch (error) {
        loader.hide();
      }
    },

  //SAVE TOUR USER
    create() {
      try {
        let loader = this.$loading.show();
        this.addForm.post(this.api_url + "add_tour_user", {
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
          this.$router.push('/tour_plan_users');
        }, (error) => {
          console.log(error);
          loader.hide();
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
