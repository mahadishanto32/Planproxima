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
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Wings
                                        </li>

                                    </ol>
                                </div>
                            </div>
                            <!--                        <div class=" col-sm-3">
                            <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>New Wing </a>  
                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable"> 
                            <div class="users-list-filter px-1">
                                <div class="row border rounded py-2 mb-2">
                                    <div v-if="deptItems.length > 1" class="col-12 col-sm-6 col-lg-2">
                                        <label for="users-list-verified">Department</label>
                                        <fieldset class="form-group">
                                            <select class="form-control" v-on:change="loadMethod()"
                                                v-model="filterForm.dept_id" id="users-list-verified">
                                                <option value="">Select One</option>
                                                <option v-for="row in deptItems" :key="row.id" :value="row.id">
                                                    {{ row.name }}
                                                </option>
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

                                                <button  v-if="permission('new_wing')" type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="show_pop()"> <i
                                                            class="bx bx-add-alt"></i>New Wing </a>
                                                </button>

                                                <button type="button" v-if="permission('wing_kra_kpi_upload')" 
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="kpiUpload()"> <i
                                                            class="bx bx-add-alt"></i>Upload (KRA and KPI ) </a>
                                                </button>
                                                <button type="button" v-if="permission('wing_kra_kpi_upload')" 
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white"  @click="demoDownload()" > <i
                                                            class="bx bx-add-alt"></i>Download sample file </a>
                                                </button>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">

                                                        <table
                                                            class="tablesaw table-striped table-hover table-bordered table"
                                                            data-tablesaw-mode="columntoggle">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">SL</th>
                                                                    <th scope="col">Department</th>
                                                                    <th scope="col">WINGS HEAD NAME</th>
                                                                    <th scope="col">SCREEN NAME</th>
                                                                    <th scope="col">ACTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(row, index) in WingsItems" :key="row.id">
                                                                    <th scope="col"> {{ index + 1 }}</th>
                                                                    <th scope="col"> {{ row.deptjoin ? row.deptjoin.name
                                                                        : 'N/A' }} </th>
                                                                    <th scope="col">
                                                                        <div v-if="!row.userjoin">
                                                                            <div class="form-group">
                                                                                <label for="Profession">Assign wing
                                                                                    head</label>
                                                                                <div class="controls">
                                                                                    <select class="form-control"
                                                                                        v-model="userChang.id"
                                                                                        v-on:change="changeUser(row.id)"
                                                                                        id="users-list-verified">
                                                                                        <option value="">Select One
                                                                                        </option>
                                                                                        <option v-for="row in WingsUser"
                                                                                            :key="row.id"
                                                                                            :value="row.id">
                                                                                            {{ row.name }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{ row.userjoin ? row.userjoin.name : ' ' }}
                                                                    </th>
                                                                    <th scope="col"> {{ row.wing_title }}</th>
                                                                    <th scope="col">
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <router-link class="dropdown-item"
                                                                                    :to="{ path: '/edit_wing/'+row.id }">
                                                                                    <i class="bx bx-edit-alt mr-1"></i>
                                                                                    Edit </router-link>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_wing(row.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <br>
                                                        <hr>

                                                        <div class="content-header row">
                                                            <div class="content-header-left col-12 mb-1 mt-0">
                                                                <div class="row breadcrumbs-top">
                                                                    <div class="col-sm-7">
                                                                        EMPLOYEE
                                                                    </div>

                                                                    <div v-if="role_id == 5 || 6" class="col-sm-3">
                                                                        <fieldset class="form-group">
                                                                            <select class="form-control"
                                                                                v-on:change="getEmployee()"
                                                                                v-model="filterForm.wing_id"
                                                                                id="users-list-verified">
                                                                                <option value="">Select Wing</option>
                                                                                <option v-for="row in WingsItems"
                                                                                    :key="row.id" :value="row.id">
                                                                                    {{ row.wing_title }}
                                                                                </option>
                                                                            </select>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class=" col-sm-2">
                                                                        <router-link class="btn btn-primary add-btn"
                                                                            :to="{ path: '/new_user' }"> <i
                                                                                class="bx bx-add-alt"></i> New user
                                                                        </router-link>
                                                                        <!-- <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>New Wing </a>   -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table
                                                            class="tablesaw table-striped table-hover table-bordered table"
                                                            data-tablesaw-mode="columntoggle">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">SL</th>
                                                                    <th scope="col">WINGS HEAD NAME</th>
                                                                    <th scope="col">EMPLOYEE ID</th>
                                                                    <th scope="col">SCREEN NAME</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(row, index) in employeeItem" :key="row.id">
                                                                    <th scope="col">{{ index + 1 }}</th>
                                                                    <th scope="col">
                                                                        {{ row.wingjoin ? row.wingjoin.wing_title : ''
                                                                        }}
                                                                    </th>
                                                                    <th scope="col">{{ row.email }}</th>
                                                                    <th scope="col">{{ row.name }}</th>
                                                                    <th scope="col">
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <router-link class="dropdown-item"
                                                                                    :to="{ path: '/edit_user/'+row.id }">
                                                                                    <i class="bx bx-edit-alt mr-1"></i>
                                                                                    Edit </router-link>
                                                                                <a class="dropdown-item"
                                                                                    @click="WingChange(row)"><i
                                                                                        class="bx bx-edit mr-1"></i>Wing Change</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_user(row.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody>

                                        <tr>
                                            <th class="text-center">
                                                <input class="form-control text-center" v-model="wing_title"
                                                    placeholder="Wing Title">
                                            </th>
                                            <th class="text-center">
                                                <button @click="addWing()" class="btn btn-success">Save</button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </modal>
                    <modal width="60%" height="250px" style="padding:50px" name="kpiPopup">
                        <i @click="hiddenkpiUpload()" class="bx bx-x-circle  x-circle"></i>
                        <div class="app-content ">
                            <div class="card">
                                <div class="col-sm-6">
                                    <a  @click="demoDownload()" class="btn-block glow users-list-clear mb-0 download_template">KRA, KPI and MOS Data Upload Format</a>
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
                    <modal width="60%" height="40%" style="padding:50px" name="wing_change">
                        <i @click="WingChangeHiden()" class="bx bx-x-circle  x-circle"></i>
                        <div class="app-content ">
                            <div class="card">
                                <div class="col-sm-6">
                                    <a  class="btn-block glow users-list-clear mb-0 download_template">Wing Change </a>
                                    <br>
                                </div>
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody> 
                                        <tr>
                                            <th class="text-center">
                                                <fieldset class="form-group">
                                                    <select class="form-control" 
                                                        v-model="wingChangeForm.wing_id"
                                                        id="users-list-verified">
                                                        <option value="">Select Wing</option>
                                                        <option v-for="row in WingsItems"
                                                            :key="row.id" :value="row.id">
                                                            {{ row.wing_title }}
                                                        </option>
                                                    </select>
                                                </fieldset>
                                            </th> 
                                            <th class="text-center">
                                                <button @click="wingChangeSubmit()" class="btn btn-success">Save</button>
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
                user: JSON.parse(this.$localStorage.get("user")),
                items: [],
                item: [],
                status: '',
                dept_id: '',
                role_id: '',
                WingsItems: [],
                WingsUser: [],
                employeeItem: [],
                deptItems: [],
                wing_title: '',
                wing: new Form({
                    wing_title: '',
                }),
                filterForm: new Form({
                    wing_id: "",
                    dept_id : ""
                }),
                userChang: new Form({
                    wing_id: '',
                    id: ''
                }),
                wingChangeForm: new Form({
                    wing_id: '',
                    id: ''
                }),

            };
        },
        created() {
            this.dept_id = this.user.dept_id;
            this.role_id = this.user.role_id; 
            this.filterForm.dept_id = this.user.dept_id;
            if(this.filterForm.dept_id){
                this.getWing();
                this.filterForm.wing_id = this.user.wing_id ? this.user.wing_id : ""; 
            }             
            this.loadMethod();

        },
        methods: {
            WingChange(item){
                this.item = item ;
                this.$modal.show("wing_change");
            },
            WingChangeHiden(){
                this.$modal.hide("wing_change");
            },
            wingChangeSubmit(){
                try {
                    let loader = this.$loading.show();
                    this.wingChangeForm.id = this.item.id;
                   // this.wingChangeForm.wing_id = wing_id;
                    this.wingChangeForm.post(this.api_url + "wing_change", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.getWing();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.WingChangeHiden();
                        this.getEmployee();
                        //this.$router.push('/wings');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                       
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            loadMethod(){
                this.getWing();
                this.getWingsUser();
                this.getEmployee();
                this.getDept();
            },
            demoDownload(){
                window.location.href = window.location.origin + "/demo/Employee_wise_KRA_KPI_yearly.xlsx"
            },
            hide_pop() {
                this.$modal.hide("popup-singel");
            },
            show_pop() {
                this.$modal.show("popup-singel");
            },
            kpiUpload() {
                this.$modal.show("kpiPopup");
            },
            hiddenkpiUpload() {
                this.$modal.hide("kpiPopup");
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
                        .post(this.api_url + "kpi-mos-upload", formData, {
                            headers: {
                                "Content-Type": "multipart/form-data",
                                Authorization: this.token ? `Bearer ${this.token}` : "",
                            },
                        })
                        .then((res) => {
                            console.log(res.data.message);
                            loader.hide();
                            this.hiddenkpiUpload();
                            this.$modal.hide('file-upload');
                            this.$swal({
                                title: res.data.message,
                                icon: "success",
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
            async getDept() {
                let loader = this.$loading.show();
                this.getDepartments(this.status).then(({ data }) => {
                    if (data.success) {
                        loader.hide();
                        this.deptItems = data.data;
                        //this.getItems(); 
                    } else {
                        loader.hide();
                    }
                });
            },
            async getWing() {
                await axios.get(this.api_url + "wings?dept_id="+this.filterForm.dept_id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.WingsItems = data.data;
                        console.log(this.WingsItems);
                    });
            },
            async getWingsUser() {
                await axios.get(this.api_url + "user_wing?dept_id="+this.filterForm.dept_id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.WingsUser = data.data;
                        console.log('this.WingsUser',this.WingsUser);
                    });
            },
            async getEmployee() {
                let where = '?1=1';
                if (this.filterForm.wing_id) {
                    where += '&wing_id=' + this.filterForm.wing_id;
                }
                if (this.filterForm.dept_id) {
                    where += '&dept_id=' + this.filterForm.dept_id;
                }
                await axios.get(this.api_url + "users" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.employeeItem = data.data;
                        console.log(this.WingsUser);
                    });
            },
            changeUser(wing_id) {
                try {
                    let loader = this.$loading.show();
                    this.userChang.wing_id = wing_id;
                    this.userChang.post(this.api_url + "users_change/", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.getWing();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        //this.$router.push('/wings');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async statusChange(item, status) {
                let editForm = new Form({
                    status: status,
                    name: item.name
                })
                try {
                    let loader = this.$loading.show();
                    editForm.put(this.api_url + "departments/" + item.id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.getItems();
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async delete_wing(id) {
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "wings/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                            this.getWing();

                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
            async delete_user(id) {
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "users/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                            this.getWing();
                            this.getEmployee();
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
            async getItems() {
                let loader = this.$loading.show();
                this.getDepartments(this.status).then(({ data }) => {
                    if (data.success) {
                        loader.hide();
                        this.items = data.data
                    } else {
                        loader.hide();
                    }
                });
            },
            async addWing() {
                try {
                    let loader = this.$loading.show();
                    this.wing.wing_title = this.wing_title;
                    this.wing.dept_id = this.dept_id;
                    this.wing.post(this.api_url + "wings", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.wing_title = '';
                            this.wing.wing_title = '';
                            this.getWing();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.$router.push('/wings');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            }
        },
        computed: {},
    };
</script>