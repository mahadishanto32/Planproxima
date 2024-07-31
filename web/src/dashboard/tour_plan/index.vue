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
                                    <li class="breadcrumb-item active"> Entry Tour Program
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <!-- <div class=" col-sm-3">
                            <router-link class="btn btn-primary add-btn" :to="{ path: '/new_tour' }"> <i class="bx bx-add-alt"></i> New  </router-link>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1">
                        <div class="row border rounded py-2 mb-2">
                            <div v-if="this.role==5" class="col-12 col-sm-6 col-lg-2" >
                                <label for="users-list-verified">Designation</label>
                                <fieldset class="form-group">
                                <select class="form-control" @change="getUsers()" v-model="filterForm.designation"  id="users-list-verified" >
                                    <option value="">All</option>
                                    <option v-for="row in designation_list" :key="row.designation" :value="row.designation" >
                                    {{ row.designation }}
                                    </option>
                                </select>

                                </fieldset>
                            </div>
                            <div v-if="this.role==5" class="col-12 col-sm-6 col-lg-2" >
                                <label for="users-list-verified">Division</label>
                                <fieldset class="form-group">
                                <select class="form-control" @change="getUsers()" v-model="filterForm.division_id"  id="users-list-verified" >
                                    <option value="">All</option>
                                    <option v-for="row in division_list" :key="row.id" :value="row.id" >
                                    {{ row.name }}
                                    </option>
                                </select>

                                </fieldset>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-2" >
                                <label for="users-list-verified">User</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-model="filterForm.hq"  id="users-list-verified" >
                                    <option value="">All</option>
                                    <option v-for="row in users" :key="row.id" :value="row.id" >
                                    {{ row.name }}
                                    </option>
                                </select>

                                </fieldset>
                            </div>

                            
                            <div   class="col-12 col-sm-6 col-lg-2">
                            <label class="control-label">From</label>
                                <fieldset class="form-group">
                                <datepicker v-model="filterForm.start_date" name="start_date" class="form-control"  ></datepicker>
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">To </label>
                                <fieldset class="form-group">
                                <datepicker v-model="filterForm.end_date" name="end_date" class="form-control"  ></datepicker>
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Employee Code </label>
                                <fieldset class="form-group">
                                    <input type="text" v-model="filterForm.emp_code" name="emp_code" class="form-control"  />
                                </fieldset>
                            </div>

                            <div   class="col-12 col-sm-6 col-lg-1_5">
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

                                      <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                        <!-- NEW TOUR PLAN ENTRY -->
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <router-link class="text-white" :to="{ path: '/new_tour' }"><i class="bx bx-add-alt"></i>
                                            New Tour Plan
                                          </router-link>
                                        </button>
                                      </div>
                                        <!-- <div :v-if="role_id==5" class="col-12 col-sm-6 col-lg-4 table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr align="center">
                                                        <th>Entry User Number</th>
                                                        <th>Not Entry User Number</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>sdf</td>
                                                        <td>sadf</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> -->
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Name</th>
                                                        <th>Employee Code</th>
                                                        <th>Designation</th>
                                                        <th>Date</th>
                                                        <th>Point</th>
                                                        <th>Route</th>
                                                        <th>Common Objective</th>
                                                        <th>Special Objective</th>
                                                        <th>Work With</th>
                                                        <th>Work Station</th>
                                                        <th>Remarks</th>
                                                        <th>Status</th>
                                                        <th>Approval</th>
                                                        <th>Action</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row , index) in items" :key="row.id">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ row.userjoin ?  row.userjoin.name  : ''}}</td>
                                                        <td>{{ row.userjoin ?  row.userjoin.employee_id  : ''}}</td>
                                                        <td>{{ row.userjoin ?  row.userjoin.designation  : ''}}</td>
                                                        <td>{{ row.date }}</td>
                                                        <td>{{ row.point }}</td>
                                                        <td>{{ row.route_name }}</td>
                                                        <td>{{ row.objectives }}</td>
                                                        <td>{{ row.specia_objective }}</td>
                                                        <td>{{ row.work_with  }}</td>
                                                        <td>{{ row.work_station  }}</td>
                                                        <td>  <p v-html="row.remarks" ></p></td>
                                                        <td>
                                                            <a v-if="row.status ==  0 " >
                                                                <img class="logo_done"  @click="statusChange(1,row)" width="30px" :src="base_url+'assets/app-assets/images/logo/pen.png'" />
                                                            </a>
                                                            <a v-if="row.status ==  1 " >
                                                              <span style="font-size: 30px;  " class="bx
                                                                bx-check-double"></span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a v-if="row.approval ==  0  && row.user_id ==  user_id " >
                                                               <span style="font-size: 30px; color: darkgoldenrod;" class="bx bx-dislike"></span>
                                                            </a>

                                                            <a v-if="row.approval ==  1  && row.user_id ==  user_id " >
                                                                <span style="font-size: 30px;  " class="bx 
                                                                bx-check-double"></span>
                                                            </a>

                                                            <a v-if="row.approval ==  0  && row.user_id !=  user_id "   @click="approveChange(1,row)" >
                                                               <span style="font-size: 30px; color: darkgoldenrod;" class="bx bx-dislike"></span>
                                                            </a>
                                                            <a v-if="row.approval ==  1  && row.user_id !=  user_id "  @click="approveChange(0,row)"  >
                                                                <span style="font-size: 30px;  " class="bx 
                                                                bx-check-double"></span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="dropup" v-if="(row.approval == 0 && row.user_id == user_id ) || row.user_id != user_id ">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <router-link class="dropdown-item" :to="{ path: '/tour_plan_edit/'+row.id }"><i class="bx bx-edit-alt mr-1"></i> Edit </router-link>
                                                                    <a v-if="row.user_id == user_id" class="dropdown-item" @click="delete_row(row.id)"><i class="bx bx-trash mr-1"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                            <div class="text-primary" v-else>Approved</div>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="items.length < 1">
                                                        <td colspan="4">Data not found</td>
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
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "../../axios_instance";
import { Form } from "vform";
import Datepicker from 'vuejs-datepicker';
export default {
    props: {},
    components: {
        Datepicker
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            users: [],
            items: [],
            status: '',
            rsm: [],
            adsm: [],
            dsm: [],
            asm: [],
            sm: [],
            divisional_head: [],
            hos: [],
            role: '',
            auth_user : [],
            designation_list: [],
            division_list : [],
            filterForm: new Form({
                start_date : new Date() ,
                end_date : new Date() ,
                designation : '',
                hq : '', 
                division_id : '',
                emp_code : ''
            }),
            statusForm : new Form({

            }),
            designation:''
            
        };
    },
    created() {
        this.role_id = this.user_data.role_id ;
        this.user_id = this.user_data.id ;
        this.getItems();
        this.getUsers();
        this.designations_fnctn();
        this.division_fnctn();
    },
    methods: {
        async designations_fnctn(){
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_designation" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                    })
                    .then(({
                            data
                        }) => {
                    if (data.success) {
                        this.users_data = data.data;
                        let auth_user = JSON.parse(this.$localStorage.get("user"));
                        this.role = auth_user.role_id;
                        
                        this.designation_list = this.users_data.users;
                        // console.log('disation list',this.designation_list);
                    }
                    loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
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
        statusChange(type, item) {

          //WITHOUT APPROVAL TOUR USER WILL NOT DONE HIS/HER TOUR PLAN
          if (item.approval != 1){
            this.$swal({
              title: "Sorry!",
              text: "Tour plan not approved!",
              icon: "warning",
              dangerMode: true,
            }); return false;
          }

          //CHECK BY CURRENT HOUR. IF LESS THEN THEN NO ACTION WILL FIRE
          var dateObject = new Date() ;
          var currentYear = dateObject.getFullYear() ;
          var currentMonth = ("0" + (dateObject.getMonth() + 1)).slice(-2);
          var currentDay = dateObject.getDate();

          //CURRENT DATE
          var currentDate = currentYear  +'-'+ currentMonth + '-' + currentDay;

          //CURRENT HOUR
          var currentHour = dateObject.getHours();

          console.log('currentDate.toString() == (item.date).toString()', currentDate.toString() == (item.date).toString())
          //TOUR DATE CHECK
          if (currentDate < (item.date)){
            this.$swal({
              title: "Sorry!",
              text: "You will change your tour plan status after 2 pm on your tour date",
              icon: "warning",
              dangerMode: true,
            }); return false;
          }

          if (currentHour < 14){
            this.$swal({
              title: "Sorry!",
              text: "You will change your tour plan status after 2 pm on your tour date",
              icon: "warning",
              dangerMode: true,
            }); return false;
          }

           // let loader = this.$loading.show();
            this.$swal({
                title: "Are you sure?",
                text: type == 1 ? "This task complete?" :'This task status change ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    item.status =  type ;
                    this.statusForm.status =  item.status ;
                    this.statusForm.point =  item.point ;
                    this.statusForm.route_name =  item.route_name ;
                    this.statusForm.objectives =  item.objectives ;
                    this.statusForm.specia_objective =  item.specia_objective ;
                    this.statusForm.contactperson =  item.contactperson ;
                    this.statusForm.put(this.api_url + "tour_entries/"+item.id , {
                        headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        //loader.hide();
                        this.$swal("Your task status has been updated!", {
                            icon: "success",
                        });

                    },(error)=>{
                    console.log(error);
                   // loader.hide();
                    })

                } else {
                   // loader.hide();
                    this.$swal("Your task status is not change!");
                }
                });
        },

        approveChange(type, item) {
           // let loader = this.$loading.show();
            this.$swal({
                title: "Are you sure?",
                text: type == 1 ? "This task complete?" :'This task status change ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    item.approval =  type ;
                    this.statusForm.status =  item.status ;
                    this.statusForm.approval =  item.approval ;
                    this.statusForm.point =  item.point ;
                    this.statusForm.route_name =  item.route_name ;
                    this.statusForm.objectives =  item.objectives ;
                    this.statusForm.specia_objective =  item.specia_objective ;
                    this.statusForm.contactperson =  item.contactperson ;
                    this.statusForm.put(this.api_url + "tour_entries/"+item.id , {
                        headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        //loader.hide();
                        this.$swal("Your task status has been updated!", {
                            icon: "success",
                        });

                    },(error)=>{
                    console.log(error);
                   // loader.hide();
                    })

                } else {
                   // loader.hide();
                    this.$swal("Your task status is not change!");
                }
                });
        },
        async getUsers() {
            console.log('sgsdgs',this.filterForm.designation);
            let where = '?';
            if (this.filterForm.designation) {
                where += '&designation=' + this.filterForm.designation;
            }
            if (this.filterForm.division_id) {
                where += '&division_id=' + this.filterForm.division_id;
            }

            try {
                await axios
                    .get(this.api_url + "supervisor" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.users = data.data
                            console.log('user data list',this.users);
                        }

                    });
            } catch (error) {
                console.log(error);
            }
        },

        async delete_row(id) {
            console.log(id);
            let loader = this.$loading.show();
            try {
                await axios
                    .delete(this.api_url + "tour_entries/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        res
                    }) => {
                        this.getItems();
                        if (res.data.success) {
                            this.getItems();
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
            let where = '?1=1';
            if(this.filterForm.designation){
                where += '&designation=' + this.filterForm.designation;
            }
            if ( this.filterForm.hq) {
                where += '&hq=' + this.filterForm.hq;
            }
            if ( this.filterForm.division_id) {
                where += '&division_id=' + this.filterForm.division_id;
            }
            if ( this.filterForm.start_date) {
                where += '&start_date=' +  this.format_Date(this.filterForm.start_date)  ;
            }
            if ( this.filterForm.end_date) {
                where += '&end_date=' +  this.format_Date(this.filterForm.end_date)  ;
            }
            if(this.filterForm.emp_code){
                where += '&emp_code=' + this.filterForm.emp_code ;
            }
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_entries" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.items = data.data
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },

    },
    computed: {},
};
</script>
<style>
      .logo_done {
        width: 30px;
    }
</style>

