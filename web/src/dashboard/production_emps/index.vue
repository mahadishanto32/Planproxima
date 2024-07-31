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
                                   <li class="breadcrumb-item"><router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Employee Turnover 
                                    </li>
                                     
                                </ol> 
                            </div>
                        </div>
<!--                        <div class=" col-sm-3">
                            <router-link class="btn btn-primary add-btn" :to="{ path: '/production_emps_new' }">   <i class="bx bx-add-alt"></i>  Employee Turnover Entry   </router-link>
                                          
                        </div> -->
                    </div>
                </div>
            </div> 
            <div class="content-body">
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1"> 
                        <div  class="users-list-filter px-1"> 
                            <div class="row border rounded py-2 mb-2">
                               <div  class="col-12 col-sm-6 col-lg-2">
                                  <label for="users-list-verified">Year</label>
                                  <fieldset class="form-group">
                                     <select class="form-control"   v-on:change="getItems()"  v-model="filterForm.year"  id="users-list-verified" >
                                      
                                         <option value="">Select year</option>
                                         <option value="2017">2017</option>
                                         <option value="2018">2018</option>
                                         <option value="2019">2019</option>
                                         <option value="2020">2020</option>
                                         <option value="2021">2021</option>
                                     </select>
                                    
                                  </fieldset>
                               </div>
                               <div  class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Month</label>
                                <fieldset class="form-group">
                                   <select class="form-control"  v-on:change="getItems()"  v-model="filterForm.month"  id="users-list-verified" > 
                                       <option value="">Select month</option>
                                       <option value="1">Jan</option>
                                       <option value="2">Feb</option>
                                       <option value="3">Mar</option>
                                       <option value="4">Apr</option>
                                       <option value="5">May</option>
                                       <option value="6">Jun</option>
                                       <option value="7">Jul</option>
                                       <option value="8">Aug</option>
                                       <option value="9">Sep</option>
                                       <option value="10">Oct</option>
                                       <option value="11">Nov</option>
                                       <option value="12">Dec</option>
                                       
                                   </select>
                                  
                                </fieldset>
                             </div>
                             <div  class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Week</label>
                                <fieldset class="form-group">
                                   <select class="form-control"  v-on:change="getItems()"  v-model="filterForm.week"  id="users-list-verified" > 
                                    <option value="">All</option>
                                    <option value="1">Week One</option>
                                    <option value="2">Week Two</option>
                                    <option value="3">Week Three</option>
                                    <option value="4">Week Four</option>
                                   </select> 
                                </fieldset>
                             </div> 
                            </div>  
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                  
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                      <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                        <!-- EMPLOYEE TURNOVER ENTRY -->
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <router-link class="text-white" :to="{ path: '/production_emps_new' }">   <i class="bx bx-add-alt"></i>  Employee Turnover Entry   </router-link>
                                        </button>

                                      </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Factory</th>
                                                        <th>Product Name</th>
                                                        <th>Year</th>
                                                        <th>Month</th>
                                                        <th>Week</th>
                                                        <th>Begining Emp</th>
                                                        <th>Resign Emp</th>
                                                        <th>New Join</th>
                                                        <th>Ending Emp</th>
                                                        <th width="250">Remarks</th>
                                        
                                                        <th width="15%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row , index) in items" :key="row.id">
                                                        <td >{{ index + 1 }}</td>  
                                                        <td >{{ row.deptjoin ? row.deptjoin.name : '' }}</td>   
                                                        <td >{{  row.projoin ? row.projoin.product_name  :'' }}</td>  
                                                        <td >{{ row.year }}</td>  
                                                        <td >{{ row.month }}</td>  
                                                        <td > week -{{row.week}}  </td>  
                                                        <td >{{ row.begining_emp }}</td>  
                                                        <td >{{ row.number_of_resig }}</td>  
                                                        <td >{{ row.number_of_join }}</td>  
                                                        <td >{{ row.ending_emp }}</td>  
                                                        <td >
                                                            <p v-html="row.remarks" ></p> </td>  
                                                        <td > </td>  
                                                         
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
                <modal width="60%" height="70%" style="padding:50px" name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                       <div class="card">
                          <table class="table table-bordered table-striped table-sm">
                             <tbody> 
                                 <tr>
                                    <td>Department Name</td>
                                    <td>{{item.name}}</td>
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
  props: { 
  },
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        base_url: window.base_url,
        api_url: window.api_url, 
        token: this.$localStorage.get("d_token"),
        items:[], 
        item : [], 
        dept_users : [],
        status :  '' ,
        filterForm: new Form({ 
            year: "",   
            month : "",
            week : "", 
        }),
      
    };
  },
  created() {  
    this.getItems();
  },
  methods: {
    async popUp(item){
        this.item =  item ;
        let loader = this.$loading.show();
        
        await axios.get(this.api_url + "production_emps?dept_id="+item.id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            loader.hide(); 
            this.dept_users =  data.data ;  
        }); 
        this.$modal.show("popup-singel"); 
    },
    hide_pop() {
        this.$modal.hide("popup-singel");
    }, 
    async statusChange(item , status){
        let editForm =  new Form({    
            status: status,
            name : item.name
        })
        try {
         let loader = this.$loading.show();
            editForm.put(this.api_url + "departments/"+ item.id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            }).then((res) => {
                console.log(res);
                if(res.data.success){ 
                    this.$toasted.show(res.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                        });
                } 
                loader.hide(); 
                this.getItems();
            },(error)=>{
            console.log(error);
            loader.hide(); 
            })
        } catch (error) {
            // loader.hide(); 
            console.log(error);
        }
    }, 
    async delete_row(id){ 
        let loader = this.$loading.show();
        try {
            await axios
            .delete(this.api_url + "production_emps/"+id, {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ res }) => {  
                    this.$toasted.show(res.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                        });
                    this.getItems();
               
                loader.hide();
            });
        } catch (error) {
            loader.hide();
        }
      },
       async getItems(){
           //departments_all
           let loader = this.$loading.show();
           let where = '?1=1';
           if(this.filterForm.year){
               where +='&year='+ this.filterForm.year ;
           }
           if(this.filterForm.month){
               where +='&month='+ this.filterForm.month ;
           }
           if(this.filterForm.week){
               where +='&week='+ this.filterForm.week ;
           }
           await axios.get(this.api_url + "production_emps"+where, {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ data }) => {  
                this.items =  data.data
                loader.hide(); 
            }); 
           
      }
  },
  computed: {},
};
</script>
