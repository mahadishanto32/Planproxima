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
                                        <li class="breadcrumb-item active"> Standard Cost
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
                                    <label for="users-list-verified">Factory</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="summaryList($event)"
                                            v-model="filterForm.factory_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in itemsFactorys" :key="row.id" :value="row.id">
                                                {{ row.dis_name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Product group</label>
                                    <fieldset class="form-group">
                                        <select required="" class="form-control" v-on:change="getCostCenter()"
                                            v-model="filterForm.summary_group_id">
                                            <option value="">Select Product group</option>
                                            <option v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id">{{
                                                row.description
                                            }}
                                            </option>

                                        </select>

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Cost Center</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.cost_center_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in cost_centers" :key="row.id" :value="row.id">
                                                {{ row.name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div class="ccol-sm-4 col-lg-2">
                                    <label for="users-list-verified">Search (GL CODE , Cost Center)</label>
                                    <fieldset class="form-group">
                                        <input type="text" name="search" v-on:keyup="getItems()" v-model="filterForm.search"
                                            class="form-control" data-validation-required-message="This field is required"
                                            placeholder="GL CODE , Cost Center">
                                    </fieldset>
                                </div>
                                <div class="ccol-sm-4 col-lg-2">
                                    <label for="users-list-verified">Limit</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()" v-model="filterForm.limit"
                                            id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option value="100">100 </option>
                                            <option value="500">500 </option>
                                            <option value="1000">1000 </option>
                                            <option value="3000">3000 </option>
                                            <option value="10000">10000 </option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="ccol-sm-4 col-lg-2">
                                    <label for="users-list-verified">Year</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()" v-model="filterForm.year"
                                            id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option value="2021">2021 </option>
                                            <option value="2022">2022 </option>
                                            <option value="2023">2023 </option>
                                            <option value="2024">2024 </option>
                                            <option value="2025">2025 </option>
                                             
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
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="show_pop()"> <i
                                                            class="bx bx-add-alt"></i>Data Upload </a>
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Cost Center</th>
                                                            <th>GL Text</th>
                                                            <th>GL Code</th>
                                                            <th>Product Group</th>
                                                            <th>Jan</th>
                                                            <th>Feb</th>
                                                            <th>Mar</th>
                                                            <th>Apr</th>
                                                            <th>May</th>
                                                            <th>Jun</th>
                                                            <th>Jul</th>
                                                            <th>Aug</th>
                                                            <th>Sep</th>
                                                            <th>Oct</th>
                                                            <th>Nov</th>
                                                            <th>Dec</th>
                                                            <th>Cost (Yearly) </th>
                                                            <th>Year</th>
                                                            <th>Type </th>
                                                            <th>Report Type </th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(item, index ) in items" :key="item.id">
                                                            <td> {{ index + 1 }}</td>
                                                            <td> {{ item.cost_center }}</td>
                                                            <td> {{ item.gl_text }}</td>
                                                            <td> {{ item.gl_code }}</td>
                                                            <td> {{ item.product_group['description'] }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.jan) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.feb) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.mar) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.apr) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.may) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.jun) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.jul) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.aug) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.sep) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.oct) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.nov) }}</td>
                                                            <td class="text-right"> {{ formatPrice(item.dec) }}</td>
                                                            <td> {{ item.cost_amount }}</td>
                                                            <td> {{ item.year }}</td>
                                                            <td> {{ item.type }}</td>
                                                            <td> {{ item.report_type }}</td>
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
                                    <a class="btn-block glow users-list-clear mb-0 download_template">COST Data Upload</a>
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
            items: [],
            itemsFactorys: [],
            itemsSummaryGroup: [],
            status: '',
            csv: null,
            csvName: null,
            csvform: {
                dept_id: "",
                head_id: "",
            },
            cost_centers: [],

            filterForm: new Form({
                factory_id: "",
                summary_group_id: "",
                cost_center_id: "",
                search : "",
                year : this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
                limit: 100
            }),
        };
    },
    created() {
        this.getFactorys();
        this.getCostCenter();
        //this.getItems();
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

        async summaryList() {
            //summary_list
            this.filterForm.post(this.api_url + "summary_list", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            }).then((res) => {
                console.log(res);
                this.itemsSummaryGroup = res.data.data;

            }, (error) => {
                console.log(error);
            })
        },

        async getCostCenter() {
            let where = '?';
            if (this.filterForm.summary_group_id) {
                where += '&summary_group_id=' + this.filterForm.summary_group_id;
            }

            await axios
                .get(this.api_url + "cost_center" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    if (data.success) {
                        this.cost_centers = data.data
                    }
                });

        },
        // async summaryList(){
        //     //summary_list
        //     this.filterForm.post(this.api_url + "summary_list", {
        //         headers: {
        //         "Content-Type": "application/json", 
        //         Authorization: this.token ? `Bearer ${this.token}` : ""
        //         },
        //     }).then((res) => {
        //         console.log(res);
        //         this.itemsSummaryGroup =  res.data.data ;  

        //     },(error)=>{
        //     console.log(error); 
        //     })
        // },
        async getItems() {
            console.log(this.filterForm);
            let where = '?limit=' + this.filterForm.limit;
            let loader = this.$loading.show();

            if (this.filterForm.cost_center_id) {
                where += '&cost_center_id=' + this.filterForm.cost_center_id;
            }
            //search
            if (this.filterForm.search) {
                where += '&search=' + this.filterForm.search;
            }
          //  let year = this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear();
            if (this.filterForm.year) {
                where += '&year=' + this.filterForm.year;
            }
            try {
                await axios
                    .get(this.api_url + "factory_standards" + where, {
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
                    .post(this.api_url + "factory_standards-file-upload", formData, {
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
