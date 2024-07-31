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
                    <li class="breadcrumb-item active"> Edit Tour User
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
                    <h4 class="card-title">Edit Tour User</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <form @submit.prevent="update()">
                        <div class="row">
                          <div class="col-md-6">

                            <div class="form-group">
                              <label>Name</label>
                              <div class="controls">
                                <input type="text" name="name" v-model="editForm.name"
                                       :class="{  'is-invalid': editForm.errors.has('name'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Name">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Employee Id</label>
                              <div class="controls">
                                <input type="text" name="employee_id" v-model="editForm.employee_id"
                                       :class="{  'is-invalid': editForm.errors.has('employee_id'),  }"
                                       class="form-control" data-validation-required-message="This field is required"
                                       placeholder="Employee ID">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Username</label>
                              <div class="controls">
                                <input type="text" name="email" v-model="editForm.email"
                                       :class="{  'is-invalid': editForm.errors.has('email'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Email">
                              </div>
                            </div>
                            <div class="form-group">
                              <label>Organization Mail</label>
                              <div class="controls">
                                <input type="text" name="ad_mail" v-model="editForm.ad_mail"
                                       :class="{  'is-invalid': editForm.errors.has('ad_mail'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Mail">
                              </div>
                            </div>
                            <!----PASSWORD----->
                            <div class="form-group">
                              <input type="checkbox" name="is_password_change" v-model="editForm.is_password_change"
                                     id="is_password_change" value="1">&nbsp;
                              <label for="is_password_change">Check this if password change</label>
                            </div>

                            <div class="form-group" v-if="editForm.is_password_change">
                              <label>Password</label>
                              <div class="controls">
                                <input type="password" name="password" v-model="editForm.password" autocomplete="off"
                                       class="form-control" placeholder="Password">
                              </div>
                            </div>

                            <div class="form-group">
                              <label>Phone</label>
                              <div class="controls">
                                <input type="text" name="phone" v-model="editForm.phone"
                                       :class="{  'is-invalid': editForm.errors.has('phone'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Phone">
                              </div>
                            </div>

                            <!--BASE STATION ADDRESS-->
                            <div class="form-group">
                              <label>Base station address</label>
                              <div class="controls">
                                <input type="text" name="phone" v-model="editForm.base_station_address"
                                       :class="{  'is-invalid': editForm.errors.has('base_station_address'),  }" class="form-control"
                                       data-validation-required-message="This field is required" placeholder="Enter base station address">
                              </div>
                            </div>

                            <div class="form-group">
                              <label>Designation</label>
                              <div class="controls">
                                <select name="designation" v-model="editForm.designation"
                                        class="form-control chzn-select">
                                   
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
                              <label>BUSINESS TYPE (Chanel)</label>
                              <div class="controls">
                                <select class="form-control chzn-select" v-model="editForm.business_type">
                                  <option value="">Select One</option>
                                  <option v-for="row in businessTypes" :key="row.id" :value="row.id">
                                    {{ row.title }}
                                  </option>
                                </select>
                              </div>
                            </div>


                            <div class="form-group">
                              <label>Status</label>
                              <div class="controls">
                                <select class="form-control" v-model="editForm.status">
                                  <option value="1">Active</option>
                                  <option value="0">Inactive</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label>RSM SUPER VISOR </label>
                              <div class="form-group row">
                                <select class="form-control" v-model="editForm.rsm" id="users-list-verified">
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
                                <select class="form-control" v-model="editForm.adsm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in adsm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label>DSM SUPERVISOR  </label>
                              <div class="form-group row">
                                <select class="form-control" v-model="editForm.dsm" id="users-list-verified">
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
                                <select class="form-control" v-model="editForm.asm" id="users-list-verified">
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
                                <select class="form-control" v-model="editForm.sm" id="users-list-verified">
                                  <option value="">Select One</option>
                                  <option v-for="row in sm" :key="row.id" :value="row.id">
                                    {{ row.name }}
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                              <label>DIVISONAL HEAD</label>
                              <div class="form-group row">
                                <select class="form-control" v-model="editForm.division_head" id="users-list-verified">
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
                                <select class="form-control" v-model="editForm.head_of_sales" id="users-list-verified">
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
                                <select class="form-control" v-model="editForm.division_id" id="users-list-verified">
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
import {Form} from "vform";
import axios from "../../axios_instance";

export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      DepartmentsItems: [],
      users: [],
      rsm: [],
      adsm: [],
      dsm: [],
      asm: [],
      sm: [],
      hos: [],
      division_list: [],
      businessTypes: [],
      divisional_head: [],
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      id: this.$route.params.id,
      
      roles: [],
      WingsItems: [],
      editForm: new Form({
        name : '' ,
        designation : '' ,
        employee_id : '' ,
        phone : '' ,
        email : '' ,
        wing_id : '' ,
        status : '' ,
        rsm : '' ,
        adsm : '' ,
        dsm : '' ,
        asm: '' ,
        sm: '' ,
        division_id: '' ,
        head_of_sales: '' ,
        division_head: '' ,
        base_station_address: '' ,
        business_type: '' , 
        ad_mail: '' 
      }),
      item : {}

    };
  },
  created() {
    this.division_fnctn();
    
    this.getItem("tour_user_edit/" + this.id).then(({data}) => {
     
      if (data.success) {
        //console.log('data.data', data.data);
        //item = data.data
        this.editForm.name = data.data.name;
        this.editForm.designation = data.data.designation; 
        this.editForm.employee_id = data.data.employee_id; 
        this.editForm.phone = data.data.phone;
        this.editForm.email = data.data.email;
        this.editForm.wing_id = data.data.wing_id;
        this.editForm.status = data.data.status;
        this.editForm.rsm = data.data.rsm;
        this.editForm.adsm = data.data.adsm;
        this.editForm.dsm = data.data.dsm;
        this.editForm.asm = data.data.asm;
        this.editForm.sm = data.data.sm;
        this.editForm.division_id = data.data.division_id;
        this.editForm.head_of_sales = data.data.head_of_sales;
        this.editForm.division_head = data.data.division_head;
        this.editForm.base_station_address = data.data.base_station_address;
        this.editForm.business_type = data.data.business_type;
        this.editForm.status = data.data.status;
        this.editForm.division_head = data.data.division_head;
        this.editForm.ad_mail = data.data.ad_mail;
        

        //console.log('this.editForm', this.editForm);
        
      }
    });
    this.user();
    this.getBusinessTypes();

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
                this.hos = this.users.hos;
                this.division_id = this.users.division_id;
                this.divisional_head = this.users.divisional_head;
                this.hos = this.users.hos;
                //console.log('this.rsm');
                //console.log(this.rsm);
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
                //console.log('businessTypes', this.businessTypes);
              }
              loader.hide();
            });
      } catch (error) {
        loader.hide();
      }
    },

    dept() {
      this.getDepartments(this.editForm.dept_id).then(({data}) => {
        if (data.success) {
          this.DepartmentsItems = data.data;
        } else {
          this.DepartmentsItems = [];
        }
      });

    },

    async getWing() {
      await axios.get(this.api_url + "wings?dept_id=" + this.editForm.dept_id, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
          .then(({data}) => {
            this.WingsItems = data.data;
            //console.log(this.WingsItems);
          });
    },
    async getItemdDpartments() {
      let loader = this.$loading.show();
      try {
        await axios
            .get(this.api_url + "departments/", {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
              },
            })
            .then(({data}) => {
              if (data.success) {
                this.itemDepartments = data.data
              }
              loader.hide();
              //console.log(this.item);
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
          .then(({data}) => {
            this.roles = data.data;
            //console.log(this.roles);
          });
    },
    update() {
      try {
        let loader = this.$loading.show();
        this.editForm.put(this.api_url + "users/" + this.id, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : ""
          },
        }).then((res) => {
          //console.log(res);
          if (res.data.success) {
            this.$toasted.show(res.data.message, {
              theme: "bubble",
              duration: 5000,
              position: "bottom-right",
            });
          }
          loader.hide();
          this.$router.push('/tour_users');
        }, (error) => {
          console.log(error);
          loader.hide();
        })
      } catch (error) {
        // loader.hide();
        //console.log(error);
      }
    }
  },
  computed: {},
};
</script>
