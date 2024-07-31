<template>
<div>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-1 mt-0">
                    <div class="row breadcrumbs-top">
                        <div class="col-sm-11">
                            <div class="breadcrumb-wrapper col-9">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">
                                        <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Cost Center
                                    </li>
                                </ol>
                            </div>
                        </div>
<!--                        <div class=" col-sm-1">
                            <div class="row ">
                                <div class=" col-12">
                                    <a  v-if="role_id == 1 || user_id == 1058"  class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>Data Upload </a>  
                                </div>  
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
                                <select required="" class="form-control"  v-on:change="getItems()"  v-model="filterForm.summary_group_id">
                                    <option value="">Select Product group</option>
                                    <option  v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id" >{{ row.description }}</option>
                                        
                                </select>
                                
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

                                        <!-- DATA UPLOAD -->
                                        <button v-if="role_id == 1 || user_id == 1058"   type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <a  class="text-white" @click="show_pop()" > <i class="bx bx-add-alt"></i>Capacity Upload </a>
                                        </button>
                                        <button v-if="role_id == 1 || user_id == 1058"   type="button"  
                                            class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                            <a class="text-white"  @click="demoDownload()" > <i
                                                    class="bx bx-add-alt"></i>Download sample file </a>
                                        </button>

                                      </div>

                                        <div class="table-responsive">
                                            <table class="tablesaw table-striped table-hover table-bordered table"> 
                                                    <thead>  
                                                      <tr>
                                                        <th>Sl</th> 
                                                        <th>Summary Group</th>
                                                        <th >Jan</th>
                                                        <th >Feb</th>
                                                        <th >Mar</th>
                                                        <th >Apr</th>
                                                        <th >May</th>
                                                        <th >Jun</th>
                                                        <th >Jul</th>
                                                        <th >Aug</th>
                                                        <th >Sep</th>
                                                        <th >Oct</th>
                                                        <th >Nov</th>
                                                        <th >Dec</th>  
                                                        <th>Type</th> 
                                                        <th>Year</th>  
                                                        <th v-if="role_id == 1 || user_id == 1058"  >Action</th>  
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td> {{ index + 1 }}</td>  
                                                        <td> {{ item.summary_groupjoin ? item.summary_groupjoin.code:''  }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.jan) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.feb) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.mar) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.apr) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.may) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.jun) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.jul) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.aug) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.sep) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.oct) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.nov) }}</td>
                                                        <td  class="text-right" > {{  formatPrice(item.dec) }}</td>  
                                                        <td> {{ item.type }}</td> 
                                                        <td> {{ item.year }}</td> 
                                                        <td v-if="role_id == 1 || user_id == 1058" > 
                                                            <div class="dropup">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right"> 
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
                <modal width="60%" height="30%" style="padding:50px" name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                       <div class="card">
                        <div class="col-sm-6"> 
                            <a class="btn-block glow users-list-clear mb-0 download_template"  >Capacity Data Upload</a>
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
import { Form } from "vform"; 
import axios from "../../axios_instance";
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
            user_data: JSON.parse(this.$localStorage.get("user")),
            user_id : '',
            items: [],   
            status: '',
            csv: null,
            csvName: null,
            csvform: {
                dept_id: "",
                head_id: "",
            },
            year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
            itemsFactorys : [],
            itemsSummaryGroup : [],
            filterForm: new Form({ 
                factory_id: "",    
                summary_group_id : "" 
            }),
        };
    },
    created() {
        this.role_id = this.user_data.role_id ; 
        this.user_id = this.user_data.id ; 
        this.getFactorys(); 
        this.getItems(); 
    },
    methods: { 
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
        demoDownload(){
            window.location.href = window.location.origin + "/demo/capacity_fomat.xlsx"
        },
        async deleteFile(id){
            await axios
                .delete(this.api_url + "factory_capacities/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    res
                }) => {
                    console.log(res);
                    //loader.hide(); 
                    this.$swal("Delete this row data", {
                        icon: "success",
                    });
                    this.hide_pop();
                    this.getItems();
                },(error)=>{
                console.log(error);
                // loader.hide(); 
                })
        },
        async delete_row(id) {

            this.$swal({
                title: "Are you sure?",
                text:  'this row delete',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                     
                    this.deleteFile(id);
 
                } else {
                   // loader.hide(); 
                    this.$swal("Not delete this row data");
                }
                });
 
        },

        async summaryList(){ 
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
        async getItems() {
            let where = '?1=1';  
            let loader = this.$loading.show();
            if(this.filterForm.summary_group_id){
                where += '&summary_group_id='+this.filterForm.summary_group_id ;
            }
            if(this.year){
                where +='&year='+ this.year ; 
            } 
            try {
                await axios
                    .get(this.api_url + "factory_capacities" + where, {
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
                let loader = this.$loading.show();
                axios
                    .post(this.api_url + "factory_capacities-file-upload", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    })
                    .then((res) => {
                            console.log(res.data.message);
                            loader.hide();
                            this.$modal.hide('file-upload');
                            this.$swal(res.data.message);
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
    
    },
    computed: {},
};
</script>
