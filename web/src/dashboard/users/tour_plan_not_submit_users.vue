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
                                    <li class="breadcrumb-item active"> Tour not submit users
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
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                        <div class="row">

                                            <div v-if="this.role==5" class="col-12 col-sm-3 col-lg-2" >
                                                <label for="users-list-verified">Designation</label>
                                                <fieldset class="form-group">
                                                <select class="form-control" @change="getUsers()" v-model="filterForm.designation"  id="users-list-verified" >
                                                    <option value="">All</option>
                                                    <option v-for="row in designation_list" :key="row.designation" :value="row.designation" >
                                                    {{ row.designation }}
                                                    </option>
                                                </select>

                                                </fieldset>
                                            </div>

                                            <div v-if="this.role==5" class="col-12 col-sm-3 col-lg-2" >
                                                <label for="users-list-verified">User</label>
                                                <fieldset class="form-group">
                                                <select class="form-control"  v-model="filterForm.hq"  id="users-list-verified" >
                                                    <option value="">All</option>
                                                    <option v-for="row in users" :key="row.id" :value="row.id" >
                                                    {{ row.name }}
                                                    </option>
                                                </select>

                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-3 col-lg-2">
                                                <label for="users-list-verified">Date</label>
                                                <datepicker v-model="filterForm.start_date" name="start_date" class="form-control"></datepicker>
                                            </div>
                                            <div class="col-12 col-sm-3 col-lg-2">
                                                <label for="users-list-verified"></label>
                                                <fieldset class="form-group">
                                                    <button type="submit" @click="getDateWiseNotTourPlanUser()"  class="btn btn-primary mb-2">Search</button>
                                                </fieldset>
                                            </div>
                                        </div>
                                        


                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>SN </th>
                                                        <th>Name </th>
                                                        <th>Email/login ID <input type="text" class="text-center" placeholder="Search" v-model="search"> </th>
                                                        <th>Designation </th> 
                                                        <th>Phone</th> 
                                                        <th>Location</th> 

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row, index) in filteredItems" :key="row.id">
                                                        <td>{{ index+1 }}</td>
                                                        <td>{{ row.name }}</td>
                                                        <td>{{ row.email}}</td>
                                                        <td>{{ row.designation }}</td> 
                                                        <td>{{ row.phone }}</td> 
                                                        <td>{{ row.base_station_address }}</td> 
                                                        
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
import {Form} from "vform";
import Datepicker from 'vuejs-datepicker';
import moment from "moment";

export default {
    props: {},
    components: {
        Datepicker
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            users: [],
            designation_list: [],
            search : '',
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],   
            status: '',
            filterForm: new Form({
                start_date: new Date(),
                designation : '',
                hq : ''
            }),
            role : JSON.parse(this.$localStorage.get("user")).role_id,        
        };
    },
    created() { 
        this.getItems(); 
        this.designations_fnctn();
    },
    methods: {
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
                        // let auth_user = JSON.parse(this.$localStorage.get("user"));
                        // this.role = auth_user.role_id;
                        
                        this.designation_list = this.users_data.users;
                        // console.log('disation list',this.designation_list);
                    }
                    loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },      
        
        async getUsers() {
            console.log('sgsdgs',this.filterForm.designation);
            let where = '?';
            if (this.filterForm.designation) {
                where += '&designation=' + this.filterForm.designation;
            }

            try {
                await axios
                    .get(this.api_url + "supervisor" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.users = data.data
                            console.log('user data list',this.users);
                        }

                    });
            } catch (error) {
                console.log(error);
            }
        },
        async getItems() {
            let where = '?'; 
      
            if (this.filterForm.start_date) {
                where += '&date=' + moment(String(this.filterForm.start_date)).format('YYYY-MM-DD');
            }

            if (this.filterForm.designation) {
                where += '&designation=' + this.filterForm.designation;
            }
            if (this.filterForm.hq) {
                where += '&hq=' + this.filterForm.hq;
            }
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_plan_not_submit_users" + where, {
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

        async getDateWiseNotTourPlanUser() {
            this.getItems();
        }
    
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
