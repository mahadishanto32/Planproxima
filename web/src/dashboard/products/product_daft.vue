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
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active">Products daft
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">

                    <section id="basic-datatable">
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Plant</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()" v-model="filterForm.plant"
                                            id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in itemsFactorys" :key="row.id" :value="row.fac_code">
                                                {{ row.dis_name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Data Type</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.product_type" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in itemsDataType" :key="row.id" :value="row.id">
                                                {{ row.name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Data Type</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.status" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in itemsStatus" :key="row.id" :value="row.id">
                                                {{ row.name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Material Code</label>
                                    <fieldset class="form-group">
                                        <input class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.material_code">
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-1_5">
                                    <label for="users-list-verified"></label>
                                    <fieldset class="form-group">
                                        <button type="submit" @click="getItems()"
                                            class="btn btn-primary mb-2">Submit</button>
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
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="show_pop()"> <i class=""></i>Product
                                                        Upload</a>
                                                </button>

                                                <!-- SYNC -->
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="sync()"> <i
                                                            class="bx bx-add-alt"></i>Sync</a>
                                                </button>

                                            </div>



                                            <div class="table-responsive">
                                                <table class="tablesaw table-striped table-hover table-bordered table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Sys ID</th>
                                                            <th>Plant</th>
                                                            <th>Product Group</th>
                                                            <th>Wastage Group</th>
                                                            <th>Material Code</th>
                                                            <th>Description</th>
                                                            <th>Material Group</th>
                                                            <th>Material Type</th>
                                                            <th>UOM</th>
                                                            <th>Product Type</th>
                                                            <th>Error Note</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(item , index ) in items" :key="item.id">
                                                            <td> {{ index + 1}} </td>
                                                            <td> {{ item.id }}</td>
                                                            <td> {{ item.plant }}</td>
                                                            <td> {{ item.product_group }}</td>
                                                            <td> {{ item.wastage_group}}</td>
                                                            <td> {{ item.material_code}}</td>
                                                            <td> {{ item.description}}</td>
                                                            <td> {{ item.material_group}}</td>
                                                            <td> {{ item.material_type}}</td>
                                                            <td> {{ item.base_unit_of_measure}}</td>
                                                            <td> {{ item.product_type }}</td>
                                                            <td> {{ item.error_note}}</td>
                                                            <td> {{ item.status }} </td>
                                                            <!-- <td>
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
                                                        </td>  -->
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
                                    <a class="btn-block glow users-list-clear mb-0 download_template">Material Data
                                        Upload</a>
                                    <br>
                                </div>
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody>

                                        <tr>
                                            <th class="text-center">
                                                <input type="file" accept=".xlsx" class="form-control" ref="file"
                                                    @change="handleFileObject()" />
                                            </th>
                                            <th class="text-center">
                                                <button @click="csvUpload()" class="btn btn-success">Save</button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-sm-12">
                                    <a @click="demoDownload()"> New
                                        Material Upload Template</a>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </modal>
                    <!-- <modal style="padding:50px" name="popup-singel">
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
                 </modal> -->
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
                itemsFactorys: [],
                itemsDataType: [{ id: 'consumption', name: 'Consumption' }, { id: 'wastage', name: 'Wastage' }],
                itemsStatus: [{ id: '1', name: 'Done' }, { id: '0', name: 'Pending' }, { id: '2', name: 'Not Sync' }],

                filterForm: new Form({
                    plant: '',
                    product_type: '',
                    status: 0,
                    limit: 500,
                }),
                csvform: {
                    dept_id: "",
                    head_id: "",
                },
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
                        this.$modal.hide("popup-singel");
                        this.$swal({
                            title: res.data.message,
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

            async sync() {
                let loader = this.$loading.show();
                let where = '?';
                await axios
                    .get(this.api_url + "product_sync" + where, {
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
                if (this.filterForm.plant) {
                    where += '&plant=' + this.filterForm.plant;
                }
                if (this.filterForm.product_type) {
                    where += '&product_type=' + this.filterForm.product_type;
                }
                if (this.filterForm.status) {
                    where += '&status=' + this.filterForm.status;
                }

                if (this.filterForm.material_code) {
                    where += '&material_code=' + this.filterForm.material_code;
                }

                // if(this.filterForm.date){
                //     where += '&date='+  this.format_Date(this.filterForm.date) ;
                // }

                let loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "product_drafts" + where, {
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

            //DOWNLOAD DEMO
            async demoDownload() {

                window.location.href = window.location.origin + "/demo/BPT_New_material_uploader.xlsx"
            }

        },
        computed: {},
    }; 
</script>