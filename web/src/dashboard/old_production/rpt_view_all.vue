

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
                               <li class="breadcrumb-item active">Production Report 
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
                                  <div class="row">
                                     <div class="col-12">
                                       <div  class="users-list-filter px-1"> 
                                          <div class="row border rounded py-2 mb-2">
                                              
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Factory</label>
                                                <fieldset class="form-group">
                                                   <select name="factory"  v-model="filterForm.factory"  @change="ProductGroup()"   id="products_search" class="form-control chzn-select">
                                                      <option value="">Select option</option>
                                                      <option  v-for="row in factory_old" :key="row.id" :value="row.id">{{ row.name}}</option>
                                                   </select>
                                                </fieldset>
                                             </div>
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Products</label>
                                                <fieldset class="form-group">
                                                   <select name="products"  v-model="filterForm.products" @change="getItems()"   id="products_search" class="form-control chzn-select">
                                                      <option value="">Select Products</option>
                                                      <option  v-for="row in productsGroup" :key="row.id" :value="row.id">{{ row.product_name}}</option>  
                                                   </select>
                                                </fieldset>
                                             </div>
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Year</label>
                                                <fieldset class="form-group">
                                                   <select name="year" id="year"   @change="getItems()"  v-model="filterForm.year"  class="form-control chzn-select">
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
                                                   <select name="month"  @change="getItems()"   v-model="filterForm.month"  class="form-control chzn-select" >
                                                      <option value="">Select</option> 
                                                      <option  v-for="row in months_old" :key="row.id" :value="row.id">{{ row.name}}</option>  
                                                   </select>
                                                </fieldset>
                                             </div>
                                               
                                                
                                             <div class="col-12 col-sm-6 col-lg-2">
                                                <label for="users-list-verified">Week</label>
                                                <fieldset class="form-group">
                                                   <select  v-model="filterForm.week"   @change="getItems()"  name="week"  class="form-control chzn-select" >
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
                                    <div v-html="report" ></div> 
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
            report : '',
            productsGroup:[],  
            status :  '' ,
            filterForm :  new  Form({
               factory : "",
               products : "",
               year : '2021', 
               month : '6',
               week : '' 

            }),
          factory: ''
            
        };
      },
      created() {  
        this.getItems();
      },
      methods: {
        
           async getItems(){

            if(this.filterForm.products &&  this.filterForm.factory ){
                
               let loader = this.$loading.show(); 
               let where = '?factory='+ this.filterForm.factory ;
               if(this.filterForm.products){
                  where += '&products='+this.filterForm.products ;
               }
               if(this.filterForm.year){
                  where += '&year='+this.filterForm.year ;
               }
               if(this.filterForm.month){
                  where += '&month='+this.filterForm.month ;
               }
               if(this.filterForm.week){
                  where += '&week='+this.filterForm.week ;
               } 
               await axios.get(this.api_url + "rpt_view_all"+ where , {
                        headers: {
                        "Content-Type": "application/json", 
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                .then(({ data }) => {  
                    this.report =  data
                    loader.hide();
                    console.log( this.report );   
                }); 
            }else {

              if (this.$route.query.factory && this.$route.query.product && this.$route.query.year) {
                let loader = this.$loading.show();
                let factory = this.$route.query.factory;

                //FIELD SELECTED
                this.filterForm.factory = factory;

                //GET PRODUCT GROUP
                this.ProductGroupByOldFactoryId(factory);

                let where = '?factory=' + factory;
                where += '&products=' + this.$route.query.product;
                where += '&year=' + this.$route.query.year;
                where += '&month=' + this.$route.query.month;
                where += '&week=' + this.$route.query.week;

                await axios.get(this.api_url + "rpt_view_all" + where, {
                  headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                  },
                })
                    .then(({data}) => {
                      this.report = data
                      loader.hide();
                      console.log(this.report);
                    });
              }
            }
               
          },
           async ProductGroup(){
               //departments_all
               let loader = this.$loading.show();
               await axios.get(this.api_url + "get_products_list?factory_id="+this.filterForm.factory, {
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

        //GET PRODUCT GROUP FOR NEW TO OLD REPORT
        async ProductGroupByOldFactoryId(factoryId){
          //departments_all
          let loader = this.$loading.show();
          await axios.get(this.api_url + "get_products_list?factory_id=" + factoryId, {
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

        }
      },
      computed: {},
    };
 </script>
 
 