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
                                    <li class="breadcrumb-item active"> Tour Users
                                    </li>
                                </ol>
                            </div>
                        </div>
<!--                        <div class=" col-sm-3">
                            <router-link class="btn btn-primary add-btn" :to="{ path: '/add_tour_user' }"> <i class="bx bx-add-alt"></i> New tour user </router-link>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="d-inline-flex col-lg-12">
                    <div v-if="this.role=5" class="col-sm-4 col-lg-4" >
                        <label for="users-list-verified">Designation</label>
                        <fieldset class="form-group">
                        <select class="form-control" @change="getItems(designation,actv_status)" v-model="designation"  id="users-list-verified" >
                            <option value="">All</option>
                            <option v-for="row in designation_list" :key="row.designation" :value="row.designation" >
                            {{ row.designation }}
                            </option>
                        </select>

                        </fieldset>
                    </div>
                    <div v-if="this.role=5" class="col-sm-4 col-lg-4" >
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" @change="getItems(designation,actv_status)" v-model="actv_status"  id="users-list-verified" >
                                <option value=0>Active</option>
                                <option value=1>Inactive</option>
                            </select>
                        </fieldset>
                    </div>
                    <div   class=" col-sm-4 col-lg-4">
                        <label for="users-list-verified"> </label>
                        <fieldset class="form-group">
                            <button type="submit"  class="btn btn-primary mb-2">Submit</button>
                        </fieldset>
                    </div>                    
                </div>

                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                      <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                        <!-- NEW TOUR USER ENTRY -->
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                          <router-link class="text-white" :to="{ path: '/add_tour_user' }"> <i class="bx bx-add-alt"></i> New tour user </router-link>
                                        </button>
                                      </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>SN </th>
                                                        <th>Name </th>
                                                        <th>Email/login ID <input type="text" class="text-center" placeholder="Search" v-model="search"> </th>
                                                        <th>Employee ID</th>
                                                        <th>Designation </th> 
                                                        <th>Phone</th>
                                                        <th>Organization Email</th> 
                                                        <th>Location</th> 
                                                        <th>Status</th> 
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row, index) in filteredItems" :key="row.id">
                                                        <td>{{ index+1 }}</td>
                                                        <td>{{ row.name }}</td>
                                                        <td>{{ row.email}}</td>
                                                        <td>{{ row.employee_id}}</td>
                                                        <td>{{ row.designation }}</td> 
                                                        <td>{{ row.phone }}</td> 
                                                        <td>{{ row.ad_mail }}</td> 
                                                        <td>{{ row.base_station_address }}</td> 
                                                        <td >
                                                            
                                                             <a v-if="row.status ==  0 ">
                                                                <div class="badge badge-pill badge-light-danger mr-1">Inactive</div>
                                                            </a>
                                                            <a v-if="row.status ==  1 ">
                                                                <div class="badge badge-pill badge-light-info mr-1">Active</div>
                                                            </a>
                                                        </td> 
                                                        <td>
                                                            <div class="dropup">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <router-link class="dropdown-item" :to="{ path: '/tour_user_edit/'+row.id }"><i class="bx bx-edit-alt mr-1"></i> Edit </router-link>
                                                                    <a class="dropdown-item" @click="delete_row(row.id)"><i class="bx bx-trash mr-1"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="items.length < 1">
                                                        <td colspan="4">Data not found</td>
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
export default {
    props: {},
    components: {
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            search : '',
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],   
            status: '',
            designation_list: [],
            designation:'',
            actv_status: 0,
            users_data : '',
            role : '',
        };
    },
    created() { 
        this.getItems(); 
        this.user();
        this.designations_fnctn();
    },
    methods: {
        async user() {
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_user" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                    })
                    .then(({
                            data
                        }) => {
                    if (data.success) {
                        this.users = data.data;
                        this.rsm = this.users.rsm;
                        this.adsm = this.users.adsm;
                        this.dsm = this.users.dsm;
                        this.asm = this.users.asm;
                        this.sm = this.users.sm;
                        this.divisional_head = this.users.divisional_head;
                        this.hos = this.users.hos;
                        console.log('this.rsm');
                        console.log(this.rsm);
                    }
                    loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async delete_row(id) {
            console.log(id);
            let loader = this.$loading.show();
            try {
                await axios
                    .delete(this.api_url + "users/" + id, {
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
        async getItems(designation_val,actv_status) {
            let where = '?'; 
      
            if (designation_val) {
                where += '&designation=' + designation_val;
            }

            if (actv_status) {
                where += '&actv_status=' + actv_status;
            }            
            let loader = this.$loading.show();

            try {
                await axios
                    .get(this.api_url + "tour_plan_users" + where, {
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
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async designations_fnctn(){
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_designation" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                    })
                    .then(({
                            data
                        }) => {
                    if (data.success) {
                        this.users_data = data.data;
                        let auth_user = JSON.parse(this.$localStorage.get("user"));
                        this.role = auth_user.role_id;
                        this.designation_list = this.users_data.users;
                        console.log('disation list',this.designation_list);
                    }
                    loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },    
    },
    computed: {
        filteredItems() {
            return this.items.filter(item => {
                return item.email.toLowerCase().indexOf(this.search.toLowerCase()) > -1
            })
        }
    },
};
</script>
