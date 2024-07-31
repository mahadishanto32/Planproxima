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
                                    <li class="breadcrumb-item active">Manufacturer
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class=" col-sm-3">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
           
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1"> 
                        <div class="row border rounded py-2 mb-2">
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Factory</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-on:change="summaryList()"  v-model="filterForm.factory_id"  id="users-list-verified" >
                                    <option value="">Select One</option>
                                    <option v-for="row in itemsFactorys" :key="row.id" :value="row.id" >
                                    {{ row.dis_name }}
                                    </option>
                                </select>
                                
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Product group</label>
                                <fieldset class="form-group">
                                <select required="" class="form-control"  v-model="filterForm.summary_group_id">
                                    <option value="">Select Product group</option>
                                    <option  v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id" >{{ row.description }}</option>
                                        
                                </select>
                                
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                            <label class="control-label">From</label>
                                <fieldset class="form-group"> 
                                <datepicker v-model="filterForm.start_date" name="start_date" class="form-control"  ></datepicker>
                                <!-- <input type="text" value="04-05-2021" id="start_date" class="form-control singledate"  v-model="filterForm.start_date"  >  -->
                                </fieldset>
                            </div> 
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">To </label>
                                <fieldset class="form-group">
                                <datepicker v-model="filterForm.end_date" name="end_date" class="form-control"  ></datepicker>
                                <!-- <input type="text" value="29-05-2021" id="end_date" class="form-control singledate"  v-model="filterForm.end_date"  > -->
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
                                        <option value="">All</option> 
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

                                      <section id="dashboard-analytics">
                                        <nav>
                                          <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a @click="tabs('production')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link" data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab" v-bind:class="{ active: report_tabs  == 'production' }">
                                              Production
                                            </a>
                                            <a @click="tabs('delivery')" aria-controls="nav-home" aria-selected="true" class="nav-item nav-link" data-toggle="tab" href="#nav-home" id="nav-home-tab" role="tab" v-bind:class="{ active: report_tabs  == 'delivery' }">
                                              Delivery
                                            </a>
                                          </div>
                                        </nav>
                                      </section>
                                      

                                        <div class="table-responsive" v-if="report_tabs =='production'">
                                            <table class="tablesaw table-striped table-hover table-bordered table"> 
                                                    <thead>  
                                                      <tr>
                                                        <th>Sl</th>
                                                        <th>Product Group</th>
                                                        <th>Material Code</th> 
                                                        <th>Product</th>
                                                        <th>Date</th> 
                                                        <th>Quantity (GNH)</th>
                                                        <th>Quantity (OTH)</th> 
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td>{{ index + 1 }}</td>
                                                        <td> {{ item.summarygroupjoin ? item.summarygroupjoin.code : '--' }}</td>
                                                        <td> {{ item.productjoin ? item.productjoin.material_code : '--' }}</td> 
                                                        <td> {{ item.productjoin ? item.productjoin.description : '--' }}</td>
                                                        <td> {{ item.date }}</td> 
                                                        <td class="text-right"> {{ formatPrice(item.production_quantity_gnh) }}</td>
                                                        <td class="text-right"> {{ formatPrice(item.production_quantity_oth) }}</td>
                                                       
                                                      </tr> 
                                                    </tbody>
                                                  </table>
                                        </div>
                                        <div class="table-responsive" v-if="report_tabs =='delivery'">
                                            <table class="tablesaw table-striped table-hover table-bordered table"> 
                                                    <thead>  
                                                      <tr>
                                                        <th>Sl</th> 
                                                        <th>Material Code</th>
                                                        <th>Product</th>
                                                        <th>Date</th>  
                                                        <th>Delivery</th> 
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in itemsDelivery" :key="item.id" > 
                                                        <td>{{ index + 1 }}</td>
                                                        <td> {{ item.productjoin ? item.productjoin.material_code : '--' }}</td>
                                                        <td> {{ item.productjoin ? item.productjoin.description : '--' }}</td>
                                                        <td> {{ item.date }}</td> 
                                                        <td class="text-right"> {{ formatPrice(item.delivery_qty) }}</td> 
                                                        <!-- <td> {{ item.summarygroupjoin ? item.summarygroupjoin.code : '--' }}</td>
                                                        <td> {{ item.productjoin ? item.productjoin.material_code : '--' }}</td> 
                                                        
                                                       
                                                        <td class="text-right"> {{ formatPrice(item.production_quantity_gnh) }}</td>
                                                        <td class="text-right"> {{ formatPrice(item.production_quantity_oth) }}</td>
                                                        <td>{{ item.delivery }}</td>  
                                                        <td>{{ item.remarks}}</td>   -->
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
            report_tabs : 'production',
            base_url: window.base_url, 
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],   
            status: '',
            itemsFactorys: [],  
            itemsDelivery : [], 
            itemsSummaryGroup: [], 
            filterForm: new Form({ 
                limit :  500 ,
                factory_id:  this.$route.params.factory_id ? this.$route.params.factory_id : '',   
                summary_group_id :  this.$route.params.summary_group_id ? this.$route.params.summary_group_id : '', 
                start_date : this.$route.params.start_date ? new Date(this.$route.params.start_date) :  new Date() ,
                end_date : this.$route.params.end_date ?  new Date(this.$route.params.end_date) :  new Date() , 
            }),
        };
    },
    created() { 
        this.getFactorys(); 
        this.getItems(); 
        this.delivery();
    },
    methods: { 
        tabs(i){
            this.report_tabs = i ;
        },
        async getItems() {
            let where = '?1=1'; 
            if( this.filterForm.summary_group_id){
                where += '&summary_group_id='+ this.filterForm.summary_group_id ;
            } 
            if( this.filterForm.limit){
                where += '&limit='+ this.filterForm.limit ;
            } 
            if( this.filterForm.start_date &&  this.filterForm.end_date){
                where += '&start_date='+  this.format_Date(this.filterForm.start_date) ;
                where += '&end_date='+  this.format_Date(this.filterForm.end_date) ;
            } 
            
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "manufacturer" + where, {
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
                            this.summaryList();
                            this.delivery();
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async delivery() {
            let where = '?1=1'; 
            if( this.filterForm.summary_group_id){
                where += '&summary_group_id='+ this.filterForm.summary_group_id ;
            } 
            if( this.filterForm.limit){
                where += '&limit='+ this.filterForm.limit ;
            } 
            if( this.filterForm.start_date &&  this.filterForm.end_date){
                where += '&start_date='+  this.format_Date(this.filterForm.start_date) ;
                where += '&end_date='+  this.format_Date(this.filterForm.end_date) ;
            } 
            
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "delivery" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.itemsDelivery = data.data; 
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async summaryList(){
            //summary_list
            this.filterForm.post(this.api_url + "summary_list", {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            }).then((res) => {
                console.log(res);
                this.itemsSummaryGroup =  res.data.data ;  
                
            },(error)=>{
            console.log(error); 
            })
        },
        async getFactorys() {
            let where = '?';   
                await axios
                    .get(this.api_url + "factorys" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.itemsFactorys = data.data
                        }
                         
                    });
              
        },
        async delete_row(id) {
            console.log(id);
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "sap_files_delete/" + id, {
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
    
    },
    computed: {},
};
</script>
