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
                                    <li class="breadcrumb-item active"> Products 
                                    </li>
                                </ol>
                            </div>
                        </div>
<!--                        <div class=" col-sm-2">
                            <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>Product Upload</a> 
                        </div>
                        <div class=" col-sm-2">
                            <router-link class="btn btn-primary add-btn" :to="{ path: '/new_product' }"> <i class="bx bx-add-alt"></i> Add Product</router-link>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="content-body">  
                <section id="basic-datatable">
                    <div  class="users-list-filter px-1"> 
                        <div class="row border rounded py-2 mb-2">
                            
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Factorys</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-on:change="summaryList()"   v-model="filterForm.factory_id"  id="users-list-verified" >
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
                                <select required="" class="form-control"   v-on:change="getWastageSummary()"  v-model="filterForm.summary_group_id">
                                    <option value="">Select Product group</option>
                                    <option  v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id" >{{ row.description }}</option>
                                        
                                </select> 
                                </fieldset>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Filter Type</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-on:change="summaryList()"   v-model="filterForm.filter_type"  id="users-list-verified" >
                                    <option value="">Select One</option>
                                    <option v-for="row in filterType" :key="row.id" :value="row.id" >
                                    {{ row.name }}
                                    </option>
                                </select>
                                
                                </fieldset>
                            </div>
                            <div  class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Wastage/Consumption Group</label>
                                <fieldset class="form-group">
                                <select required="" class="form-control"   v-on:change="getItems()"  v-model="filterForm.wastage_summary_group_id">
                                    <option value="">Select Summary Group</option>
                                    <option  v-for="row in itemsWastageSummaryGroup" :key="row.id" :value="row.id" >{{ row.group_name }}</option>
                                        
                                </select> 
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Material Code</label>
                                <fieldset class="form-group">
                                    <input type="text" name="material_code" v-model="filterForm.material_code" :class="{  'is-invalid': filterForm.errors.has('material_code'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Material Code">
                                </fieldset>
                            </div>
                            
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Limit</label>
                                <fieldset class="form-group">
                                    <select  v-on:change="getItems()"   class="form-control"  v-model="filterForm.limit">  
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="2000">2000</option> 
                                        <option value="5000">5000</option> 
                                        <option value="2000000">All</option> 
                                    </select> 
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-1_5"> 
                                <option value=""></option>
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
                                      </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped"> 
                                                    <thead> 
                                                       
                                                      <tr> 
                                                        <th>SL</th> 
                                                        <th>Plant</th>  
                                                        <th>Material Group</th> 
                                                        <th>Material Code</th>
                                                        <th>Description</th>
                                                        <th>UOM</th>  
                                                        <th>Product Group</th>  
                                                        <th>Wastage Summary Group</th>  
                                                        <th>Action</th>  
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index) in items" :key="item.id" > 
                                                        <td>{{ index + 1  }}</td>
                                                        <td>{{ item.plant }}</td>
                                                        <td>{{ item.product_group }}</td>  
                                                        <td>{{ item.material_code }} </td>
                                                        <td>{{ item.description }} </td>
                                                        <td>{{ item.base_unit_of_measure }} </td>  
                                                        <td>{{ item.summarygroupjoin ?  item.summarygroupjoin.code : '' }} </td>  
                                                        <td>{{ item.wastage_summary_group ? item.wastage_summary_group : ""  }} </td>  
                                                        <td>
                                                            <div class="dropup">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right"> 
                                                                    <router-link target="_blank" :to="{ path: '/product_edit/'+item.id }" class="dropdown-item">
                                                                        <i class="bx bx-edit-alt mr-1">
                                                                        </i>
                                                                        Details
                                                                    </router-link> 
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
                <modal name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="card">
                       <div class="row">
                        <div class="col-sm-6"> 
                            <a class="btn-block glow users-list-clear mb-0 download_template"  >Material Data Upload</a>
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
                          <div class="col-sm-12"> 
                            <router-link to="/demo/New_Material_Upload.xlsx"> New Material Upload Template</router-link>
                            
                            <br>
                        </div>
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
export default {
    props: {},
    components: {
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],
            csv: null,
            csvName: null,   
            filterType : [{id : 1 , name : 'Production'},{id : 2 , name : 'Wastage'},{id : 3 , name : 'Consumption'}],
            filterForm: new Form({  
                factory_id: "",    
                summary_group_id : "",
                wastage_summary_group_id : "",

                filter_type : 1,
                limit : 500 ,
            }),
            status: '',
            csvform: { 
            },
            itemsFactorys: [],   
            itemsSummaryGroup: [], 
            itemsWastageSummaryGroup: [], 
        };
    },
    created() { 
        this.getFactorys(); 
        this.getItems(); 
    },
    methods: { 
         hide_pop() {
            this.$modal.hide("popup-singel");
        },
        show_pop() { 
            this.$modal.show("popup-singel"); 
        },
        handleFileObject() {
            this.csv = this.$refs.file.files[0];
            console.log(this.csv);
            this.csvName = this.csv.name;
        },
        csvFile() {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                this.csvform.csv = event.target.result;
            };
        },
        async getWastageSummary(){ 
                await axios
                    .get(this.api_url + "wastage_summary_group/"+ this.filterForm.summary_group_id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.itemsWastageSummaryGroup = data.data
                        }
                         
                    }); 
            this.getItems();
        },
        csvUpload() {
            
                let formData = new FormData();
              
                formData.append("csvFile", this.csv); 
                let loader = this.$loading.show();
                axios
                    .post(this.api_url + "product-file-upload", formData, {
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
             
        },
        async getItems() {
            if(this.filterForm.factory_id || this.filterForm.material_code ){ 
                let where = '?1=1';  
                if(this.filterForm.summary_group_id){
                    where +='&summary_group_id='+this.filterForm.summary_group_id ;
                }
                if(this.filterForm.factory_id){
                    where +='&factory_id='+this.filterForm.factory_id ;
                }
                if(this.filterForm.filter_type){
                    where +='&filter_type='+this.filterForm.filter_type ;
                }
                if(this.filterForm.wastage_summary_group_id){
                    where +='&wastage_summary_group_id='+this.filterForm.wastage_summary_group_id ;
                }
                if(this.filterForm.limit){
                    where +='&limit='+this.filterForm.limit ;
                }
                let loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "products" + where, {
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
    
    },
    computed: {},
};
</script>
