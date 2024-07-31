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
                                        <li class="breadcrumb-item active"> Entry Tour Program
                                            <label class="mb-2 mr-sm-2 col-1" @click="demoDownload()">
                                                <vue-excel-xlsx :data="dataItemExel" :columns="columns"
                                                    :filename="'Daily Task'" :sheetname="'Task'">
                                                    <p class="bx bxs-cloud-download"></p>
                                                </vue-excel-xlsx>
                                            </label>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- <div class=" col-sm-3">
                                <router-link class="btn btn-primary add-btn" :to="{ path: '/new_tour' }"> <i class="bx bx-add-alt"></i> New  </router-link>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable">
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">
                                <div v-if="this.role == 5 || this.role == 9" class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Designation</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" @change="getUsers()" v-model="filterForm.designation"
                                            id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option value="all">All</option>
                                            <option v-for="row in designation_list" :key="row.designation"
                                                :value="row.designation">
                                                {{ row.designation }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>
                                <div v-if="this.role == 5 || this.role == 9" 
                                class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Division</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" 
                                        @change="getUsers()" 
                                        v-model="filterForm.division_id"
                                            id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option value="all">All</option>
                                            <option v-for="row in division_list" :key="row.id" :value="row.id">
                                                {{ row.name }}
                                            </option>
                                        </select>

                                    </fieldset>
                                </div>

                                <div v-if="this.role = 5 || this.role == 9" class="col-sm-3 col-lg-3">
                                    <label for="users-list-verified">Business Type</label>
                                    <fieldset class="form-group">
                                        <select class="form-control chzn-select" v-model="filterForm.business_type">
                                            <option value="">Select One</option>
                                            <option value="all">All</option>
                                            <option v-for="row in businessTypes" :key="row.id" :value="row.id">
                                                {{ row.title }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <label for="users-list-verified">User</label>
                                    <fieldset class="form-group">
                                        <Select2 placeholder="Select One" v-model="filterForm.hq" :options="users" />

                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Employee Code </label>
                                    <fieldset class="form-group">
                                        <input type="text" placeholder="Employee Code " v-model="filterForm.emp_code"
                                            name="emp_code" class="form-control" />
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label class="control-label">From</label>
                                    <fieldset class="form-group">
                                        <datepicker v-model="filterForm.start_date" name="start_date" class="form-control">
                                        </datepicker>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">To </label>
                                    <fieldset class="form-group">
                                        <datepicker v-model="filterForm.end_date" name="end_date" class="form-control">
                                        </datepicker>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-1_5">
                                    <label for="users-list-verified"> </label>
                                    <fieldset class="form-group">
                                        <button type="submit" @click="getItems()"
                                            class="btn btn-primary mb-2">Search</button>
                                    </fieldset>
                                </div>
                            </div>

                            <div v-if="objectList.length > 0" class="row objective_box" style="background: #ffffff;">
                                <div style="background: #ffffff;">
                                    <h3>{{ monthNames[filterForm.start_date.getMonth()] }} Strategic Goal </h3>
                                    <ul v-if="objectList.length > 0" class="object_list">
                                        <li class="object_item" :style="{ background: colorArray[index] }"
                                            v-for="(item, index) in objectList" :key="index"> {{ index + 1 }}.
                                            {{ item.objective }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">

                                            <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                                <!-- NEW TOUR PLAN ENTRY -->
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <router-link class="text-white" :to="{ path: '/add_new_tour' }" 
                                                    v-if="user_data.designation == 'Assistant Divisional Sales Manager'
                                                    || user_data.designation == 'Assistant Manager'
                                                    || user_data.designation == 'DIVISONAL_HEAD'
                                                    || user_data.designation == 'Assistant Sales Manager'
                                                    || user_data.designation == 'Divisional Sales Manager'
                                                    || user_data.designation == 'Junior Territory Sales Manager'
                                                    || user_data.designation == 'National Sales Manager'
                                                    || user_data.designation == 'Regional Sales Manager'
                                                    || user_data.designation == 'RMS'
                                                    || user_data.designation == 'Sales Manager'
                                                    || user_data.designation == 'Territory Sales Manager'
                                                    ">
                                                        <i class="bx bx-add-alt"></i>
                                                        New Tour Plan
                                                    </router-link>

                                                    <router-link v-else class="text-white" :to="{ path: '/new_tour' }">
                                                        <i class="bx bx-add-alt"></i>
                                                        New Tour Plan
                                                    </router-link>

                                                  

                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Name</th>
                                                            <th>Emp. ID</th>
                                                            <th>Designation</th>
                                                            <th>Division</th>
                                                            <th>Business Type</th>
                                                            <th>Date</th>
                                                            <th>Point</th>
                                                            <th>SAP Code</th>
                                                            <th>Route</th>
                                                            <th>Common Objective</th>
                                                            <th>Special Objective</th>
                                                            <th>Statigic Goal</th>
                                                            <th>Work With</th>
                                                            <th>Work With ID</th>
                                                            <th>Work Station</th>
                                                            <th>Feedback</th>
                                                            <th>Status</th>
                                                            <th>Approval</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in items" :key="row.id">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ row.name ? row.name : "" }} </td>
                                                             <td> {{ row.employee_id ?
                                                                row.employee_id : ""
                                                            }}</td>
                                                            <td>{{ row.designation }}</td>
                                                            <td>{{ row.touruser ? (row.touruser.division ?
                                                                row.touruser.division.name : '') : ''
                                                            }}</td>
                                                            <td>{{ row.touruser ? (row.touruser.businesstype ?
                                                                row.touruser.businesstype.title : '') : ''
                                                            }}</td>


                                                            <td>{{ row.date ? row.date : "" }}</td>
                                                            <td>
                                                                <span v-if="!row.pointjoin.length">{{ row.point ? row.point : ""}}</span>
                                                                <span v-else v-for="(row , index) in row.pointjoin" :key="row.id"> {{row.point_name}}, </span>                                                                 
                                                            </td>
                                                            <td>
                                                                <span v-if="!row.pointjoin.length">{{ row.sap_code ? row.sap_code : ""}}</span>
                                                                <span v-else v-for="(row , index) in row.pointjoin" :key="row.id"> {{row.sap_code}},  </span>                                                              
                                                            </td>
                                                            <td>
                                                                <span v-if="!row.routejoin.length">{{row.route_name}}</span>
                                                                <span v-else v-for="(row , index) in row.routejoin" :key="row.id"> {{row.route_name}},  </span>
                                                            </td>
                                                            <td>{{ row.objectives }}</td>
                                                            <td>{{ row.specia_objective }}</td>
                                                            <td>

                                                                <ul v-if="objectList.length > 0 && row.objective_id != ''"
                                                                    class="object_list">
                                                                    <template v-for="(item, index) in objectList">
                                                                        <li v-if="row.objective_id == item.id"
                                                                            class="object_item"
                                                                            :style="{ background: colorArray[index] }"
                                                                            :key="index">{{ item.objective }}</li>
                                                                    </template>

                                                                </ul>

                                                            </td>

                                                            <td>
                                                                <span v-if="!row.fojoin.length">{{row.work_with}}</span>
                                                                <span v-else v-for="(row , index) in row.fojoin" :key="row.id"> {{row.display_name}},  </span>
                                                                
                                                            </td>
                                                            <td>
                                                                <span v-if="!row.fojoin.length">{{row.work_with_id}}</span>
                                                                <span v-else v-for="(row , index) in row.fojoin" :key="row.id">  {{row.email}}, </span>                                                                
                                                            </td>
                                                            <td>{{ row.work_station }}</td>
                                                            <td>
                                                                <p v-html="row.remarks"></p>
                                                            </td>
                                                            <td>
                                                                <a v-if="row.status == 0">
                                                                    <img class="logo_done" @click="statusChange(1, row)"
                                                                        width="30px"
                                                                        :src="base_url + 'assets/app-assets/images/logo/pen.png'" />
                                                                </a>
                                                                <a v-if="row.status == 1">
                                                                    <span style="font-size: 30px;  " class="bx
                                                                        bx-check-double"></span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a v-if="row.approval == 0 && row.user_id == user_id">
                                                                    <span style="font-size: 30px; color: darkgoldenrod;"
                                                                        class="bx bx-dislike"></span>
                                                                </a>

                                                                <a v-if="row.approval == 1 && row.user_id == user_id">
                                                                    <span style="font-size: 30px;  " class="bx 
                                                                        bx-check-double"></span>
                                                                </a>

                                                                <a v-if="row.approval == 0 && row.user_id != user_id"
                                                                    @click="approveChange(1, row)">
                                                                    <span style="font-size: 30px; color: darkgoldenrod;"
                                                                        class="bx bx-dislike"></span>
                                                                </a>
                                                                <a v-if="row.approval == 1 && row.user_id != user_id"
                                                                    @click="approveChange(0, row)">
                                                                    <span style="font-size: 30px;  " class="bx 
                                                                        bx-check-double"></span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="dropup"
                                                                    v-if="(row.approval == 0 && row.user_id == user_id) || row.user_id != user_id">
                                                                    <span
                                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">

                                                                        <router-link v-if="user_data.designation == 'Assistant Divisional Sales Manager'
                                                                        || user_data.designation == 'Assistant Manager'
                                                                        || user_data.designation == 'DIVISONAL_HEAD'
                                                                        || user_data.designation == 'Assistant Sales Manager'
                                                                        || user_data.designation == 'Divisional Sales Manager'
                                                                        || user_data.designation == 'Junior Territory Sales Manager'
                                                                        || user_data.designation == 'National Sales Manager'
                                                                        || user_data.designation == 'Regional Sales Manager'
                                                                        || user_data.designation == 'RMS'
                                                                        || user_data.designation == 'Sales Manager'
                                                                        || user_data.designation == 'Territory Sales Manager'
                                                                        " class="dropdown-item"
                                                                            :to="{ path: '/tour_plan_update/' + row.id }">
                                                                            <i class="bx bx-edit-alt mr-1"></i> Edit
                                                                        </router-link>

                                                                        <router-link 
                                                                        v-else
                                                                            :to="{ path: '/tour_plan_edit/' + row.id }">
                                                                            <i class="bx bx-edit-alt mr-1"></i> Edit
                                                                        </router-link> 

                                                                        <a v-if="row.user_id == user_id"
                                                                            class="dropdown-item"
                                                                            @click="delete_row(row.id)"><i
                                                                                class="bx bx-trash mr-1"></i> Delete</a>
                                                                    </div>
                                                                </div>
                                                                <div class="text-primary" v-else>Approved</div>
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
import { Form } from "vform";
import Datepicker from 'vuejs-datepicker';
import Select2 from 'v-select2-component';
export default {
    props: {},
    components: {
        Datepicker,
        'Select2': Select2
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            monthNames: ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ],
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            users: [],
            items: [],
            status: '',
            rsm: [],
            adsm: [],
            dsm: [],
            asm: [],
            sm: [],
            divisional_head: [],
            hos: [],
            role: '',
            auth_user: [],
            designation_list: [],
            division_list: [],
            businessTypes: [],
            objectList: [],
            // myValue: '',
            // myOptions: [{id: 1 ,text : 'op1'}, {id: 2 ,text : 'op2'}, {id: 3 ,text : 'op3'}] ,
            filterForm: new Form({
                start_date: new Date(),
                end_date: new Date(),
                designation: '',
                hq: '',
                division_id: '',
                business_type: '',
                emp_code: ''
            }),
            statusForm: new Form({

            }),
            designation: '',
            colorArray: ['#FF6633', '#00B3E6',
                '#E6B333', '#3366E6', '#B34D4D',
                '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A',
                '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
                '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF']

        };
    },
    created() {
        this.role_id = this.user_data.role_id;
        this.user_id = this.user_data.id;
        this.getItems();
        this.getUsers();
        this.designations_fnctn();
        this.division_fnctn();
        this.getBusinessTypes();
    },
    methods: {
        async designations_fnctn() {
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

                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async division_fnctn() {
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "divisions" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.division_list = data.data;

                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async getBusinessTypes() {
            let where = '?';
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_business_types" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.businessTypes = data.data;
                            //console.log('businessTypes',this.businessTypes);
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        statusChange(type, item) {

            //WITHOUT APPROVAL TOUR USER WILL NOT DONE HIS/HER TOUR PLAN
            if (item.approval != 1) {
                this.$swal({
                    title: "Sorry!",
                    text: "Tour plan not approved!",
                    icon: "warning",
                    dangerMode: true,
                }); return false;
            }

            //CHECK BY CURRENT HOUR. IF LESS THEN THEN NO ACTION WILL FIRE
            var dateObject = new Date();
            var currentYear = dateObject.getFullYear();
            var currentMonth = ("0" + (dateObject.getMonth() + 1)).slice(-2);
            var currentDay = dateObject.getDate();

            //CURRENT DATE
            var currentDate = currentYear + '-' + currentMonth + '-' + currentDay;

            //CURRENT HOUR
            var currentHour = dateObject.getHours();
            //TOUR DATE CHECK
            if (currentDate < (item.date)) {
                this.$swal({
                    title: "Sorry!",
                    text: "You will change your tour plan status after 2 pm on your tour date",
                    icon: "warning",
                    dangerMode: true,
                }); return false;
            }

            if (currentHour < 14) {
                this.$swal({
                    title: "Sorry!",
                    text: "You will change your tour plan status after 2 pm on your tour date",
                    icon: "warning",
                    dangerMode: true,
                }); return false;
            }

            // let loader = this.$loading.show();
            this.$swal({
                title: "Are you sure?",
                text: type == 1 ? "This task complete?" : 'This task status change ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        item.status = type;
                        this.statusForm.status = item.status;
                        this.statusForm.point = item.point;
                        this.statusForm.route_name = item.route_name;
                        this.statusForm.objectives = item.objectives;
                        this.statusForm.specia_objective = item.specia_objective;
                        this.statusForm.contactperson = item.contactperson;
                        this.statusForm.put(this.api_url + "tour_entries/" + item.id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        }).then((res) => {
                            this.$swal("Your task status has been updated!", {
                                icon: "success",
                            });

                        }, (error) => {
                            // loader.hide();
                        })

                    } else {
                        // loader.hide();
                        this.$swal("Your task status is not change!");
                    }
                });
        },

        approveChange(type, item) {
            // let loader = this.$loading.show();
            this.$swal({
                title: "Are you sure?",
                text: type == 1 ? "This task complete?" : 'This task status change ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        item.approval = type;
                        this.statusForm.status = item.status;
                        this.statusForm.approval = item.approval;
                        this.statusForm.point = item.point;
                        this.statusForm.route_name = item.route_name;
                        this.statusForm.objectives = item.objectives;
                        this.statusForm.specia_objective = item.specia_objective;
                        this.statusForm.contactperson = item.contactperson;
                        this.statusForm.put(this.api_url + "tour_entries/" + item.id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        }).then((res) => {
                            //loader.hide();
                            this.$swal("Your task status has been updated!", {
                                icon: "success",
                            });

                        }, (error) => {
                            // loader.hide();
                        })

                    } else {
                        // loader.hide();
                        this.$swal("Your task status is not change!");
                    }
                });
        },
        async getUsers() {
            let where = '?';
            if (this.filterForm.designation) {
                where += '&designation=' + this.filterForm.designation;
            }

            if (this.filterForm.division_id) {
                where += '&division_id=' + this.filterForm.division_id;
            }

            try {
                await axios
                    .get(this.api_url + "tour_supervisor" + where, {
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
                            console.log('this.users', this.users);
                        }

                    });
            } catch (error) {
            }
        },
        async delete_row(id) {
            let loader = this.$loading.show();
            try {
                await axios
                    .delete(this.api_url + "tour_entries/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        res
                    }) => {
                        this.getItems();
                        if (res.data.success) {
                            this.getItems();
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
        async getItems() {
            this.getMonthObjective();
            let where = '?1=1';
            if (this.filterForm.designation) {
                where += '&designation=' + this.filterForm.designation;
            }
            if (this.filterForm.hq) {
                where += '&hq=' + this.filterForm.hq;
            }
            if (this.filterForm.division_id) {
                where += '&division_id=' + this.filterForm.division_id;
            }
            if (this.filterForm.start_date) {
                where += '&start_date=' + this.format_Date(this.filterForm.start_date);
            }
            if (this.filterForm.end_date) {
                where += '&end_date=' + this.format_Date(this.filterForm.end_date);
            }
            if (this.filterForm.emp_code) {
                where += '&emp_code=' + this.filterForm.emp_code;
            }
            if (this.filterForm.business_type) {
                where += '&business_type=' + this.filterForm.business_type;
            }
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "tour_entrie_list" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            // console.log('-------*******88=========', data.data);
                            this.items = data.data
                        }
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async getMonthObjective() {
            let where = '?1=1';
            if (this.filterForm.hq) {
                where += '&user_id=' + this.filterForm.hq;

                if (this.filterForm.start_date) {
                    where += '&start_date=' + this.format_Date(this.filterForm.start_date);
                }

                await axios.get(this.api_url + "tour_entrie_month_objectives" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.objectList = data.data
                        }
                        // loader.hide();
                    });
            } else {
                this.objectList = [];
            }

        },
        //tour_entrie_month_objectives
        async demoDownload() {
            // console.log('ddddddddddddd' , 'try');
            let formData = new FormData();
            if (this.filterForm.emp_code) {
                formData.append("emp_code", this.filterForm.emp_code);
            } 

            if (this.filterForm.start_date) {
                formData.append("start_date", this.format_Date(this.filterForm.start_date));
            }

            if (this.filterForm.end_date) {
                formData.append("end_date", this.format_Date(this.filterForm.end_date));
            }

            if (this.filterForm.designation) {
                formData.append("designation", this.format_Date(this.filterForm.designation));
            }   
            if (this.filterForm.hq) {
                formData.append("hq", this.format_Date(this.filterForm.hq));
            }
            if (this.filterForm.division_id) {
                formData.append("division_id", this.format_Date(this.filterForm.division_id));
            }            
            if (this.filterForm.business_type) {
                formData.append("business_type", this.format_Date(this.filterForm.business_type));
            }            
            // formData.append("user_id", this.filterForm.user_id);
            await axios.post(this.api_url + 'download_tour_list', formData,
                {
                    responseType: 'arraybuffer',
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(response => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'daily_task.xlsx');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                })
        },
    },
    computed: {},
};
</script>
<style>
.logo_done {
    width: 30px;
}
</style>