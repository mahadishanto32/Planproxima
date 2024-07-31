<template>
<div>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-1 mt-0">
                    <div class="row breadcrumbs-top">
                        <div class="col-sm-10">
                            <div class="breadcrumb-wrapper col-9">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item">
                                        <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Costs
                                    </li>
                                </ol>
                            </div>
                        </div>
<!--                        <div class=" col-sm-2">
                            <div class="row ">
                                <div class=" col-7">
                                    <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>Data Upload </a>  
                                </div> 
                                <div class=" col-5">
                                    <a class="btn btn-primary add-btn" @click="sync()" > <i class="bx bx-add-alt"></i>Sync</a>  
                                </div>
                            </div> 
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="content-body">
           
                <section id="basic-datatable">
                    <div class="row"> 
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                      <div class="btn-group mb-1" role="group" aria-label="Basic example">

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
                                            <table class="table table-striped"> 
                                                    <thead>  
                                                      <tr>
                                                        <th>Sl</th>
                                                        <th>Cost center</th> 
                                                        <th>GL Code</th> 
                                                        <th>Date</th> 
                                                        <th>Cost</th> 
                                                        <th>Remarks</th> 
                                                        <th>error_note</th>  
                                                        
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td> {{ index + 1 }}</td> 
                                                        <td> {{ item.cost_center }}</td> 
                                                        <td> {{ item.gl_code }}</td> 
                                                        <td> {{ item.date}}</td> 
                                                        <td> {{ item.cost}}</td> 
                                                        <td> {{ item.remarks}}</td>  
                                                        <td> {{ item.error_note}}</td> 
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
                            <a class="btn-block glow users-list-clear mb-0 download_template"  >COST Data Upload</a>
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
            status: '',
            csv: null,
            csvName: null,
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
        async getItems() {
            let where = '?';  
            let year = this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear();
            if(year){
                where = '?year='+year;
            }            
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "costs_drafts" + where, {
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
                    .post(this.api_url + "costs_draft-file-upload", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    })
                    .then((res) => {
                            console.log(res.data.message);
                            loader.hide();   
                            this.hide_pop() ;
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
        async sync(){
            let loader = this.$loading.show();
            let where = '?'; 
            await axios
                .get(this.api_url + "costs_draft-sync" + where, {
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
    
    },
    computed: {},
};
</script>
