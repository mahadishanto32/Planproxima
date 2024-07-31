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
                                    <li class="breadcrumb-item active"> Daily work 
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class=" col-sm-3">
                            <router-link class="btn btn-primary add-btn" :to="{ path: '/add_daily_work' }"> <i class="bx bx-add-alt"></i> Add daily work </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
           
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1"> 
                        <div class="row border rounded py-2 mb-2">
                           <div v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4 " class="col-12 col-sm-6 col-lg-2">
                              <label for="users-list-verified">Department</label>
                              <fieldset class="form-group">
                                 <select class="form-control" v-on:change="getWing()" v-model="filterForm.dept_id"  id="users-list-verified" >
                                     <option value="">Select One</option>
                                     <option v-for="row in deptItems" :key="row.id" :value="row.id" >
                                     {{ row.name }}
                                     </option>
                                 </select>
                                
                              </fieldset>
                           </div>
                           <div v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4  || role_id == 5"  class="col-12 col-sm-6 col-lg-2">
                              <label for="users-list-verified">Wing</label>
                              <fieldset class="form-group">
                                <select class="form-control" v-on:change="getUser()"  v-model="filterForm.wing_id"  id="users-list-verified" >
                                    <option value="">Select One</option>
                                    <option v-for="row in WingsItems" :key="row.id" :value="row.id" >
                                    {{ row.wing_title }}
                                    </option>
                                </select> 
                              </fieldset>
                           </div>
                           <div v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4  || role_id == 5 || role_id == 6"   class="col-12 col-sm-6 col-lg-2">
                            <label for="users-list-verified">Employee</label>
                            <fieldset class="form-group">
                              <select class="form-control"  v-on:change="getItems()" v-model="filterForm.user_id"  id="users-list-verified" >
                                  <option value="">Select One</option>
                                  <option v-for="row in userItems" :key="row.id" :value="row.id" >
                                  {{ row.name }}
                                  </option>
                              </select> 
                            </fieldset>
                         </div>
                           <div   class="col-12 col-sm-6 col-lg-2">
                             <label class="control-label">Date </label>
                              <fieldset class="form-group"> 
                                <datepicker  @selected="dateSelected()" :disabled-dates="state.disabledDates"  v-model="filterForm.date" name="date" class="form-control"  ></datepicker> 
                              </fieldset>
                           </div>
<!--                        
                        
                           <div   class="col-12 col-sm-6 col-lg-1_5">
                            <label for="users-list-verified"></label>
                             <fieldset class="form-group">
                                 <button type="submit" @click="getItems()" class="btn btn-primary mb-2">Submit</button>
                             </fieldset> 
                           </div> -->
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
                                                        <th>SL </th>
                                                        <th>Start Time </th>
                                                        <th>End Time</th>
                                                        <th class="col-xs-9">Things to Do</th>
                                                        <!-- <th>MD Comments </th> -->
                                                        <th v-if="role_id == 5 || role_id == 6 || role_id == 7" >KRA</th>
                                                        <th v-if="role_id == 5 || role_id == 6 || role_id == 7" >KPI</th>
                                                        <th v-if="role_id == 5 || role_id == 6 || role_id == 7" >MOS</th>
                                                        <th v-if="role_id == 5 || role_id == 6 || role_id == 7" >Employee Name </th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item, index) in items" :key="item.id" >
                                                        <td  @click="popUp(item)" class="title"> {{ index + 1  }}</td>
                                                        <td  @click="popUp(item)" >{{ item.start_time }}</td>
                                                        <td  @click="popUp(item)" >{{ item.end_time }}</td>
                                                        <td  @click="popUp(item)"  >
                                                          <p v-if="item.top_priority" style="color: red; font-size: 16px"><strong><u>Top priority</u></strong></p>
                                                          <p v-html="item.task" ></p>
                                                        </td>
                                                        <!-- <td>Comment</td> -->
                                                        <td v-if="role_id == 5 || role_id == 6 || role_id == 7" >{{ item.krajoin ? item.krajoin.kra_name : '' }} </td>
                                                        <td v-if="role_id == 5 || role_id == 6 || role_id == 7" >{{ item.kpijoin ? item.kpijoin.kpi_name : '' }} </td>
                                                        <td v-if="role_id == 5 || role_id == 6 || role_id == 7" >{{ item.mosjoin ? item.mosjoin.mos_name : '' }} </td>
                                                        <td v-if="role_id == 5 || role_id == 6 || role_id == 7" >{{ item.userjoin ? item.userjoin.name : '' }}  </td>
                                                        <td>
                                                            <a v-if="item.status ==  0 "><div class="badge badge-pill badge-light-danger mr-1">Inactive</div></a>
                                                            <a v-if="item.status ==  1 " ><div class="badge badge-pill badge-light-info mr-1">Active</div></a> 
                                                            <!-- <div class="dropup" style="float: inline-start;">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right"> 
                                                                     <a class="dropdown-item" @click="statusChange(item , item.status == 0 ? 1  : 0  )"><i class="bx bx-edit-alt mr-1"></i> {{ item.status == 0 ? ' Active '  :  'Inactive'}} </a>  
                                                                </div>
                                                            </div>  -->
                                                        </td>
                                                        <td>
                                                            <div class="dropup">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                     <router-link class="dropdown-item" :to="{ path: '/edit_daily_work/'+item.id }"><i class="bx bx-edit-alt mr-1"></i> edit </router-link>
                                                                     <a class="dropdown-item" @click="delete_row(item.id)"><i class="bx bx-trash mr-1"></i> Delete</a>  
                                                                </div>
                                                            </div> 
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
                <modal width="60%" height="70%" style="padding:50px" name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                       <div class="card">
                          <table class="table table-bordered table-striped table-sm">
                             <tbody>
                                <tr class="text-center">
                                    <th>Start Time</th> 
                                    <th>End Time</th> 
                                    <th>Date</th> 
                                </tr>
                                <tr>
                                   
                                    <td>{{ item.start_time }}</td> 
                                    <td>{{ item.end_time }}</td> 
                                    <td>{{ item.date }}</td>
                                </tr>
                                <tr  class="text-center" >
                                    <th>KRA</th> 
                                    <th>KPI</th> 
                                    <th>MOS</th> 
                                </tr>
                                <tr> 
                                    <td>{{ item.krajoin ? item.krajoin.kra_name : '' }}</td> 
                                    <td>{{ item.kpijoin ? item.kpijoin.kpi_name : '' }}</td> 
                                    <td>{{ item.mosjoin ? item.mosjoin.mos_name : '' }}</td>
                                </tr>
                                <tr class="text-center">
                                    <th colspan="3">Things to Do</th>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p v-if="item.top_priority" style="color: red; font-size: 16px"><strong><u>Top priority</u></strong></p>
                                        <p v-html="item.task" ></p>
                                    </td>
                                </tr>
                                    <!-- <th>{{ item.end_time }}</td>
                                    <td     >
                                      <p v-if="item.top_priority" style="color: red; font-size: 16px"><strong><u>Top priority</u></strong></p>
                                      <p v-html="item.task" ></p>
                                    </td>
                                    <td>Comment</td>
                                    <td>{{ item.krajoin ? item.krajoin.kra_name : '' }} </td>
                                    <td>{{ item.kpijoin ? item.kpijoin.kpi_name : '' }} </td>
                                    <td>{{ item.mosjoin ? item.mosjoin.mos_name : '' }} </td>
                                    <td>{{ item.userjoin ? item.userjoin.name : '' }}  </td> -->
                                 
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
            role_id : '',
            items: [],  
            item: [],  
            deptItems : [], 
            WingsItems : [], 
            userItems : [], 
            status: '',
            filterForm: new Form({ 
                dept_id: "",   
                wing_id : "",
                user_id : "",
                date    : new Date() ,

            }),
            state : {
                disabledDates: {
                    to: new Date(2020, 0, 0), // Disable all dates up to specific date
                    from: new Date(), // Disable all dates after specific date
                    
                }
            }
        };
    },
    created() { 
        this.role_id = this.user_data.role_id ; 
        if(this.role_id == 5){
           this.filterForm.dept_id =   this.user_data.dept_id ;
           this.getWing();
        }else if(this.role_id == 6){
            this.filterForm.dept_id =   this.user_data.dept_id ;
            this.filterForm.wing_id =   this.user_data.wing_id ;
            this.getUser();
        }else if(this.role_id == 6){
            this.filterForm.dept_id =   this.user_data.dept_id ;
            this.filterForm.wing_id =   this.user_data.wing_id ;
            this.filterForm.user_id =   this.user_data.id ;
        }else{
            this.getDept();
        }
       
       // this.getItems(); 
    },
    methods: {
        dateSelected () { 
            console.log(this.filterForm.date); 
            this.getItems();
        },
        popUp(item){
            this.item =  item ;
            this.$modal.show("popup-singel"); 
        },
        hide_pop() {
            this.$modal.hide("popup-singel");
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
            let where = '?1=1'; 
      
            if (this.filterForm.dept_id) {
                where += '&dept_id=' + this.filterForm.dept_id;
            }
            if (this.filterForm.wing_id) {
                where += '&wing_id=' + this.filterForm.wing_id;
            }
            if (this.filterForm.user_id) {
                where += '&user_id=' + this.filterForm.user_id;
            }
            if (this.filterForm.date) {
                where += '&date=' + this.format_Date(this.filterForm.date);
            }
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "daily_schedules" + where, {
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

        async getDept(){
            let loader = this.$loading.show();
            this.getDepartments( this.status ).then(({ data }) => { 
                if(data.success){
                    loader.hide();
                    this.deptItems =  data.data ;
                    this.getItems(); 
                }else{
                    loader.hide(); 
                }
            }); 
       },
       async getWing(){ 
        await axios.get(this.api_url + "wings?dept_id="+this.filterForm.dept_id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.WingsItems =  data.data ;
            this.getItems(); 
            console.log(this.WingsItems );   
        });
    },
       async getUser(){ 
        await axios.get(this.api_url + "users?wing_id="+this.filterForm.wing_id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.userItems =  data.data ;
            this.getItems(); 
            console.log(this.userItems );   
        });
    }
    
    },
    computed: {},
};
</script>
