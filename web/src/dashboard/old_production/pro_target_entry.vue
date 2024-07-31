

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
                               <li class="breadcrumb-item active"> Target Vs Actual Entry
                               </li>
                            </ol>
                         </div>
                      </div>
                      <!-- <div class=" col-sm-3"> 
                         <router-link class="btn btn-primary add-btn" :to="{ path: '/new_department' }">   <i class="bx bx-add-alt"></i> New Department  </router-link>
                                       
                         </div>  -->
                   </div>
                </div>
             </div>
             <div class="content-body">
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                   <div class="row">
                      <div class="col-12">
                         <div class="card">
                            <div class="card-content">
                               <div class="card-body card-dashboard">
                                  <form @submit.prevent="create()">
                                     <div class="form-body">
                                        <div class="card-body">
                                           <div class="row p-t-20 justify-content-center">
                                              <div class="col-md-8">
                                                 <div class="row">
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Factory</label>
                                                          <div class="col-lg-8">
                                                             <select  name="factory" @change="ProductGroup()"  v-model="addForm.factory"  class="form-control chzn-select" tabindex="2" required="">
                                                                <option value="">Select option</option>
                                                                <option  v-for="row in factory_old" :key="row.id" :value="row.id">{{ row.name}}</option> 
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Product Group</label>
                                                          <div class="col-lg-8">
                                                             <select name="products" @change="ProductGroupSub()" v-model="addForm.products" class="form-control chzn-select" required="">
                                                                <option value="">Select option</option>
                                                                <option  v-for="row in productsGroup" :key="row.iid" :value="row.iid">{{ row.vproduct_name}}</option>  
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Product Sub Group</label>
                                                          <div class="col-lg-8">
                                                             <select name="sub_products" id="sub_products"  v-model="addForm.sub_products"  class="form-control chzn-select">
                                                                <option value="">Select option</option>
                                                                <option  v-for="row in productsGroupSub" :key="row.id" :value="row.id">{{ row.name}}</option>  
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Section Name</label>
                                                          <div class="col-lg-8">
                                                             <select name="section"  v-model="addForm.section"  class="form-control chzn-select">
                                                                <option value="">Select option</option>
                                                                <option value="2">GENERAL HOURS</option>
                                                                <option value="1">OT HOURS</option>
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Delivery FC</label>
                                                          <div class="col-lg-8">
                                                             <input type="text" id="delivery_fc" v-model="addForm.delivery_fc"  name="delivery_fc" class="form-control number">
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Delivery Actual</label>
                                                          <div class="col-lg-8">
                                                             <input type="text" id="delivery_actual" v-model="addForm.delivery_actual" name="delivery_actual" class="form-control">
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Year</label>
                                                          <div class="col-lg-8">
                                                             <select name="year" id="year"  v-model="addForm.year"  class="form-control chzn-select">
                                                                <option value="2021">2021</option>
                                                                <option value="2020">2020</option>
                                                                <option value="2019">2019</option>
                                                                <option value="2018">2018</option>
                                                                <option value="2017">2017</option>
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Month</label>
                                                          <div class="col-lg-8">
                                                             <select name="month"  v-model="addForm.month" class="form-control chzn-select">
                                                                <option value="05">May</option>
                                                                <option value="06">June</option>
                                                                <option value="07">July</option>
                                                                <option value="08">August</option>
                                                                <option value="09">September</option>
                                                                <option value="10">October</option>
                                                                <option value="11">November</option>
                                                                <option value="12">December</option>
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Week</label>
                                                          <div class="col-lg-8">
                                                             <select name="week"   v-model="addForm.week"  class="form-control chzn-select">
                                                                <option value="1">Week One</option>
                                                                <option value="2">Week Two</option>
                                                                <option value="3">Week Three</option>
                                                                <option value="4">Week Four</option>
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Production Target</label>
                                                          <div class="col-lg-8">
                                                             <input type="text" id="target"  v-model="addForm.target"  name="target" class="form-control number">
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Actual Production</label>
                                                          <div class="col-lg-8">
                                                             <input type="text" id="actual" name="actual"  v-model="addForm.actual" class="form-control number">
                                                          </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Remarks</label>
                                                          <div class="col-lg-8">
                                                             <vue-editor name="task" v-model="addForm.remarks"   ></vue-editor>
                                                          </div>
                                                       </div>
                                                    </div>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="form-actions">
                                           <div class="card-body">
                                              <div align="center">
                                                 <button type="submit" class="btn btn-primary">Add Target</button> 
                                              </div>
                                           </div>
                                        </div>
                                     </div>
                                  </form>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-12">
                         <div class="card">
                            <div class="card-content">
                               <div class="card-body card-dashboard">
                                <div class="row">
                                    <div class="col-12">
                                      <div  class="users-list-filter px-1"> 
                                         <div class="row border rounded py-2 mb-2">
                                             
                                          
                                          
                                            <div class="col-12 col-sm-6 col-lg-2">
                                               <label for="users-list-verified">Year</label>
                                               <fieldset class="form-group">
                                                  <select  @change="getItems()"  name="year" id="year"  v-model="filterForm.year"  class="form-control chzn-select">
                                                     <option value="">Select Year</option>
                                                     <option value="2021">2021</option>
                                                     <option value="2020">2020</option>
                                                     <option value="2019">2019</option>
                                                     <option value="2018">2018</option>
                                                     <option value="2017">2017</option>
                                                  </select>
                                               </fieldset>
                                            </div>
                                              
                                            <div class="col-12 col-sm-6 col-lg-2">
                                               <label for="users-list-verified">Month</label>
                                               <fieldset class="form-group">
                                                  <select  @change="getItems()" name="month"  v-model="filterForm.month"  class="form-control chzn-select" >
                                                     <option value="">Select Month</option>
                                                     <option  v-for="row in months_old" :key="row.id" :value="row.id">{{ row.name}}</option>  
                                                  </select>
                                               </fieldset>
                                            </div>
                                              
                                               
                                            <div class="col-12 col-sm-6 col-lg-2">
                                               <label for="users-list-verified">Week</label>
                                               <fieldset class="form-group">
                                                  <select  @change="getItems()"  v-model="filterForm.week"   name="week"  class="form-control chzn-select" >
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
                                 </div>
                                  <div class="table-responsive">
                                    <table class="table table-striped"> 
                                    <thead>
                                        <tr>
                                           <th>sl</th>
                                           <th>Factory</th>
                                           <th>Products</th>
                                           <th>Sub Products</th>
                                           <th>Section</th>
                                           <th>Year</th>
                                           <th>Month</th>
                                           <th>Week</th>
                                           <th>P.target</th>
                                           <th>A.target</th>
                                           <th>Delivery&nbsp;fc</th>
                                           <th>A.delivery</th>
                                           <th width="250">Remarks</th>
                                           <th width="15%">Action</th>
                                        </tr>
                                     </thead>
                                     <tbody>
                                        <tr v-for="(item ,index) in items" :key="item.id" align="center">
                                           <td>{{index + 1}}</td>
                                           <td>{{ item.name  }}</td>
                                           <td>{{ item.vproduct_name }}</td>
                                           <td>{{ item.product_sub_name }}</td>
                                           <td>{{ item.vsection_name  }}</td>
                                           <td>{{ item.year  }}</td>
                                           <td>{{ item.imonth }}</td>
                                           <td>{{ item.iweek }}</td>
                                           <td>{{ item.iweek }}</td>
                                           <td>{{ item.iproducttarget }}</td>
                                           <td>{{ item.iproductactual }}</td>
                                           <td>{{ item.ideliveryfc }}</td>
                                           <td></td>
                                           <td> 
                                              <i class="fa fa-edit" style="font-size:24px"></i> 
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
             </div>
          </div>
       </div>
    </div>
 </template>
 <script>
    import { Form } from "vform"; 
    import axios from "../../axios_instance";
    import { VueEditor } from "vue2-editor";
    export default {
      props: { 
      },
      components: {
        VueEditor
        // VueRecaptcha, facebookLogin
      },
      data() {
        return {
            base_url: window.base_url,
            api_url: window.api_url, 
            token: this.$localStorage.get("d_token"),
            items:[],  
            productsGroup:[],
            productsGroupSub:[],
            status :  '' ,
            filterForm :  new  Form({
                year : '',
                month : '',
                week : '' 

            }),
            addForm: new Form({  
                factory :"", 
                products :"",
                sub_products :"",
                section :"",
                delivery_fc :"",
                delivery_actual :"",
                year :"2021",
                month :"06",
                week :"3",
                target :"",
                remarks :"",
                actual : "",
    
            }),
          
        };
      },
      created() {  
        this.getItems();
      },
      methods: {
        create(){ 
            try {
                let loader = this.$loading.show();
                this.addForm.post(this.api_url + "pro_target_insert", {
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
                    this.$router.push('/pro_target_entry');
                },(error)=>{
                console.log(error);
                loader.hide(); 
                })
            } catch (error) {
                // loader.hide(); 
                console.log(error);
            }
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
        async ProductGroup(){
            //departments_all
            let loader = this.$loading.show();
            await axios.get(this.api_url + "get_products_list?factory_id="+this.addForm.factory, {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ data }) => {  
                this.productsGroup =  data.data ;
                console.log( this.productsGroup);
                loader.hide();
                
            }); 
            
        },
        async ProductGroupSub(){
            //departments_all
            let loader = this.$loading.show();
            await axios.get(this.api_url + "get_sub_products_list?products_group_id="+this.addForm.products, {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ data }) => {  
                this.productsGroupSub =  data.data ;
                console.log( this.productsGroup);
                loader.hide();
                
            }); 
            
        },
        async delete_row(id){ 
            let loader = this.$loading.show();
            try {
                await axios
                .delete(this.api_url + "departments/"+id, {
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
               let where = '?year='+this.filterForm.year ;
               if(this.filterForm.month){
                where += '&month='+this.filterForm.month ;
               }
               if(this.filterForm.week){
                where += '&week='+this.filterForm.week ;
               }
               let loader = this.$loading.show();
               await axios.get(this.api_url + "pro_target_entry"+where, {
                        headers: {
                        "Content-Type": "application/json", 
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                .then(({ data }) => {  
                    this.items =  data.data.target_data.data ;
                    loader.hide();
                    console.log(this.items );   
                }); 
               
          }
      },
      computed: {},
    };
 </script>
 
 