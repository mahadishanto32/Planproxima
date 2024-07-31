

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
                               <li class="breadcrumb-item active">Bill Entry
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
                                                             <select  name="factory"  @change="ProductGroup()"  v-model="addForm.factory"  class="form-control chzn-select" tabindex="2" required="">
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
                                                             <select name="products" v-model="addForm.products" class="form-control chzn-select" required="">
                                                                <option value="">Select option</option>
                                                                <option value="18">GLS Bulb</option>
                                                                <option value="21">Lead In Wire (LIW)</option>
                                                                <option value="20">Super Star Cap B-22</option>
                                                                <option value="19">Tube Light (FTL)</option>
                                                             </select>
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
                                                         <label for="text1" class="control-label col-lg-3">Cost</label>
                                                         <div class="col-lg-8">
                                                            <input type="text" id="cost"  v-model="addForm.cost"  name="cost" class="form-control number">
                                                         </div>
                                                      </div>
                                                   </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Week</label>
                                                          <div class="col-lg-8">
                                                             <select name="week"   v-model="addForm.week"  class="form-control chzn-select">
                                                                <option value="3">Week Three</option>
                                                             </select>
                                                          </div>
                                                       </div>
                                                    </div>
                                                   
                                                    <div class="col-md-12">
                                                       <div class="form-group d-flex">
                                                          <label for="text1" class="control-label col-lg-3">Unit/Ltr</label>
                                                          <div class="col-lg-8">
                                                             <input type="text" id="unit" name="unit"  v-model="addForm.unit" class="form-control number">
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
                                                <label for="users-list-verified">Factory </label>
                                                <fieldset class="form-group">
                                                   <select name="factory"  @change="ProductGroup()"    v-model="filterForm.factory"  id="products_search" class="form-control chzn-select">
                                                      <option value="">Select option</option>
                                                      <option  v-for="row in factory_old" :key="row.id" :value="row.id">{{ row.name}}</option> 
                                                   </select>
                                                </fieldset>
                                             </div>
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Products</label>
                                                <fieldset class="form-group">
                                                   <select name="products"  v-model="filterForm.products"  id="products_search" class="form-control chzn-select">
                                                      <option value="">Select Products</option>
                                                      <option value="18">GLS Bulb</option>
                                                      <option value="19">Tube Light (FTL)</option>
                                                      <option value="20">Super Star Cap B-22</option>
                                                      <option value="21">Lead In Wire (LIW)</option>
                                                   </select>
                                                </fieldset>
                                             </div>
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Year</label>
                                                <fieldset class="form-group">
                                                   <select name="year" id="year"  v-model="filterForm.year"  class="form-control chzn-select">
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
                                                   <select name="month"  v-model="filterForm.month"  class="form-control chzn-select" >
                                                      <option value="05">May</option>
                                                      <option value="06">June</option>
                                                      <option value="07">July</option>
                                                      <option value="08">August</option>
                                                      <option value="09">September</option>
                                                      <option value="10">October</option>
                                                      <option value="11">November</option>
                                                      <option value="12">December</option>
                                                   </select>
                                                </fieldset>
                                             </div>
                                               
                                                
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Week</label>
                                                <fieldset class="form-group">
                                                   <select  v-model="filterForm.week"   name="week"  class="form-control chzn-select" >
                                                      <option value="">All</option>
                                                      <option value="1">Week One</option>
                                                      <option value="2">Week Two</option>
                                                      <option value="3">Week Three</option>
                                                      <option value="4">Week Four</option>
                                                  </select>
                                                </fieldset>
                                             </div>
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified"></label>
                                                <fieldset class="form-group">
                                                   <button type="submit" class="btn btn-primary mb-2">Submit</button>
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
                                        <tr align="center">
                                           <td>1</td>
                                           <td>Factory (Brass Rod)</td>
                                           <td>Brass Rod</td>
                                           <td>Brass Rod</td>
                                           <td>OT HOURS</td>
                                           <td>2021</td>
                                           <td>
                                              June                                        
                                           </td>
                                           <td>week -2</td>
                                           <td>1350</td>
                                           <td>1331</td>
                                           <td>0</td>
                                           <td>0</td>
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
            status :  '' ,
            filterForm :  new  Form({
               factory : "",
               products : "",
                year : '2021', 
                month : '06',
                week : '3' 

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
               let loader = this.$loading.show();
               await axios.get(this.api_url + "departments_all", {
                        headers: {
                        "Content-Type": "application/json", 
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                .then(({ data }) => {  
                    this.items =  data.data
                    loader.hide();
                    console.log(this.WingsItems );   
                }); 
               
          }
      },
      computed: {},
    };
 </script>
 
 