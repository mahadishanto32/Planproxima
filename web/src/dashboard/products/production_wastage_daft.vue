<template>
<div>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-1 mt-0">
                    <div class="row breadcrumbs-top">
                        <div class="col-sm-8">
                            <div class="breadcrumb-wrapper col-9">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">
                                        <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active">SAP Data daft
                                    </li>
                                </ol>
                            </div>
                        </div> 
                        <!--<div class="col-sm-2">
                            <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>Data Upload </a>  
                        </div>
                        <div class="col-sm-2"> 
                                <div class=" col-5">
                                    <a class="btn btn-primary add-btn" @click="sync()" > <i class="bx bx-add-alt"></i>Sync</a>  
                                </div> 
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="content-body">
           
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1"> 
                        <div class="row border rounded py-2 mb-2">
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Company</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-model="filterForm.comp_code"  id="users-list-verified" >
                                    <option value="">Select One</option>
                                    <option v-for="row in companis" :key="row.id" :value="row.id" >
                                    {{ row.name }}
                                    </option>
                                </select>
                                
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Type</label>
                                <fieldset class="form-group">
                                    <select required="" class="form-control"  v-on:change="getItems()"   v-model="filterForm.type"> 
                                        <option value="">Select type  </option> 
                                        <option value="PRODUCTION">PRODUCTION</option>
                                        <option value="WASTAGE">WASTAGE</option>
                                        <option value="RETURN">RETURN</option>
                                        <option value="DELIVERY">DELIVERY</option>
                                        <option value="CONSUMTION">CONSUMPTION</option> 
                                            
                                    </select> 
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Sync Type</label>
                                <fieldset class="form-group">
                                    <select required="" class="form-control"  v-on:change="getItems()"  v-model="filterForm.sync_type"> 
                                        <option value="">All</option> 
                                        <option value="approved">Approved </option>
                                        <option value="pending">Pending</option>  
                                    </select> 
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Year</label>
                                <fieldset class="form-group">
                                    <select required="" class="form-control"  v-on:change="getItems()"  v-model="filterForm.year">  
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option> 
                                    </select> 
                                </fieldset>
                            </div>
                            
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Month</label> 
                                        <fieldset class="form-group">
                                            <select class="form-control"  v-on:change="getItems()"  v-model="filterForm.month"  id="users-list-verified" > 
                                                <option value="">Select month</option>
                                                <option value="01">Jan</option>
                                                <option value="02">Feb</option>
                                                <option value="03">Mar</option>
                                                <option value="04">Apr</option>
                                                <option value="05">May</option>
                                                <option value="06">Jun</option>
                                                <option value="07">Jul</option>
                                                <option value="08">Aug</option>
                                                <option value="09">Sep</option>
                                                <option value="10">Oct</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Dec</option> 
                                            </select> 
                                         </fieldset>  
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Limit</label>
                                <fieldset class="form-group">
                                    <select  v-on:change="getItems()"   class="form-control"  v-model="filterForm.limit">  
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="2000">2000</option> 
                                        <option value="5000">5000</option> 
                                        <option value="2000000">All</option> 
                                    </select> 
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-1_5"> 
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

                                      <div class="btn-group" role="group" aria-label="Basic example">

                                        <!-- DATA UPLOAD -->
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <a class="text-white" @click="show_pop()" > <i class="bx bx-add-alt"></i>Data Upload </a>
                                        </button>

                                        <!-- SYNC -->
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <a class="text-white" @click="sync()" > <i class="bx bx-add-alt"></i>Sync</a>
                                        </button>

                                      </div>



                                        <div class="table-responsive">
                                            <table class="tablesaw table-striped table-hover table-bordered table"> 
                                                <thead>  
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Comp Code</th>
                                                        <th>Unit Code</th>
                                                        <th>Product Code</th>
                                                        <th>Date</th> 
                                                        <th>Quantity(GNH)</th>
                                                        <th>Quantity(OTH)</th>
                                                        <th>Delivery Qty</th>
                                                        <th>Consumtion Qty</th>
                                                        <th>Consumtion Value</th>
                                                        <th>Wastage</th>
                                                        <th>Wastage Value</th>
                                                        <th>Return</th>
                                                        <th>Type</th>
                                                        <th>Remarks</th>
                                                        <th>Error Note</th>
                                                        <th>Status</th>
                                                        <th>Update Matrial</th>
                                                     </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr v-for="(item , index ) in items" :key="item.id" >
                                                        <td> {{ index + 1}} </td>
                                                        <td> {{ item.comp_code  }}</td>
                                                        <td> {{ item.unit_code }}</td>
                                                        <td> {{ item.product_code }}</td>
                                                        <td> {{ item.date}}</td> 
                                                        <td class="text-right"> {{ item.production_quantity_gnh}}</td>
                                                        <td class="text-right"> {{ item.production_quantity_oth}}</td>
                                                        <td class="text-right"> {{ item.delivery_qty}}</td> 
                                                        <td class="text-right"> {{ item.consumtion}}</td> 
                                                        <td class="text-right"> {{ item.consumtion_value}}</td> 
                                                        <td class="text-right"> {{ item.wastage}}</td> 
                                                        <td class="text-right"> {{ item.wastage_value}}</td> 
                                                        <td> {{ item.return }}</td> 
                                                        <td> {{ item.type}}</td> 
                                                        <td> {{ item.remarks}}</td>  
                                                        <td> {{ item.error_note}}</td>  
                                                        <td> {{ item.status }}  </td> 
                                                        <td>
                                                            <div class="dropup">
                                                                <span aria-expanded="false" aria-haspopup="true" class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <router-link :to="{ path: '/product_edit/'+item.id }" class="dropdown-item">
                                                                        <i class="bx bx-edit-alt mr-1">
                                                                        </i>
                                                                        edit
                                                                    </router-link>
                                                                    <a @click="delete_row(item.id)" class="dropdown-item">
                                                                        <i class="bx bx-trash mr-1">
                                                                        </i>
                                                                        Delete
                                                                    </a>
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
                <modal style="padding:50px" name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                       <div class="card">
                        <div class="col-sm-6"> 
                            <a class="btn-block glow users-list-clear mb-0 download_template"  >SAP Data Upload</a>
                            <br>
                        </div>
                          <table class="table table-bordered table-striped table-sm">
                             <tbody> 
                                
                                <tr>
                                   <th class="text-center">
                                    <input type="file" accept=".xlsx" class="form-control" ref="file" @change="handleFileObject()" /> 
                                   </th>
                                   <th class="text-center">
                                      <button @click="csvUpload()" class="btn btn-success">Save</button>
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
//import Datepicker from 'vuejs-datepicker';
export default {
    props: {},
    components: {
        //Datepicker
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],   
            status: '',
            csv: null,
            csvName: null,
            filterForm: new Form({ 
                comp_code: "",   
                type : "",
                start_date : new Date() ,
                year : '2022' ,
                month : '01' ,
                end_date : new Date() ,
                sync_type : "" ,
                limit : 500 ,
                end_date_previous : null  

            }),
            csvform: {
                dept_id: "",
                head_id: "",
            },
        };
    },
    created() { 
        this.getItems(); 
    },
    methods: { 
        hide_pop() {
            this.$modal.hide("popup-singel");
        },
        show_pop() { 
            this.$modal.show("popup-singel"); 
        },
        csvFile() {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                this.csvform.csv = event.target.result;
            };
        },
        csvUpload() {
            if (this.headId != "") {
                let formData = new FormData();
                formData.append("csvFile", this.csv); 
                console.log('formData',formData);
                let loader = this.$loading.show();
                axios
                    .post(this.api_url + "production-wastage-daft-file-upload", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    })
                    .then((res) => {
                            console.log(res.data.message);
                            loader.hide();
                            this.$modal.hide('file-upload');
                            this.$swal({
                                title: res.data.message , 
                                icon: "warning",
                            });
                            this.getItems();
                        },
                        (err) => {
                            loader.hide();
                            console.log(err);
                        }
                    );
            } else {
                this.$swal("Head field is required");
            }
        },
        handleFileObject() {
            this.csv = this.$refs.file.files[0];
            console.log(this.csv);
            this.csvName = this.csv.name;
        },
        async sync(){
            let loader = this.$loading.show();
            let where = '?'; 
            await axios
                .get(this.api_url + "sync" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    loader.hide();
                    if (data.success) {
                       this.$swal(data.message);
                       this.getItems();
                    }
                    loader.hide();
            });
        }, 
        async getItems() {
            let where = '?1=1';  
            if(this.filterForm.comp_code){
                where += '&comp_code='+ this.filterForm.comp_code ;
            }
            if(this.filterForm.type){
                where += '&type='+ this.filterForm.type ;
            }
            if(this.filterForm.type){
                where += '&type='+ this.filterForm.type ;
            }
            if(this.filterForm.sync_type){
                where += '&sync_type='+ this.filterForm.sync_type ;
            }
            if(this.filterForm.year){
                where += '&year='+ this.filterForm.year ;
            }
            if(this.filterForm.month){
                where += '&month='+ this.filterForm.month ;
            }
            if(this.filterForm.limit){
                where += '&limit='+ this.filterForm.limit ;
            }
            // if(this.filterForm.date){
            //     where += '&date='+  this.format_Date(this.filterForm.date) ;
            // }
           
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "production-wastage-daft" + where, {
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
