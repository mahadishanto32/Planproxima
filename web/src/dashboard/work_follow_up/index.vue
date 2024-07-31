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
                                        <li class="breadcrumb-item active">Follow Up
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <router-link :to="{ path: '/new_follow_up' }" class="btn btn-primary add-btn btn-lg">
                                    <i class="bx bx-add-alt">
                                    </i>
                                    New Follow Up
                                </router-link>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable">
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">

                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label class="control-label">From</label>
                                    <fieldset class="form-group">
                                        <datepicker v-model="filterForm.start_date" name="start_date"
                                            class="form-control"></datepicker>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">To </label>
                                    <fieldset class="form-group">
                                        <datepicker v-model="filterForm.end_date" name="end_date" class="form-control">
                                        </datepicker>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Department </label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="getItems()" id="users-list-verified"
                                            v-model="filterForm.dept_id">
                                            <option value="">
                                                Select One
                                            </option>
                                            <option :key="row.id" :value="row.id" v-for="row in deptItems">
                                                {{ row.name }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-1_5">
                                    <label for="users-list-verified"> </label>
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
                                            <div class="table-responsive">
                                                <h2>Follow Up </h2>
                                                <br>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Date</th>
                                                            <th>Activity</th>
                                                            <th>Department</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row , i ) in items" :key="row.id">
                                                            <td>{{ i + 1 }}</td>
                                                            <td>{{ format_Date(row.date) }}</td>
                                                            <td>
                                                                <p v-html="row.details"> </p>
                                                            </td>
                                                            <td>
                                                                <p v-for="(dept , y ) in row.deptsjoin" :key="y">
                                                                    <i class="deptname"
                                                                        v-if="filterForm.dept_id && filterForm.dept_id == dept.id ">
                                                                        {{dept.name}} </i>
                                                                    <i class="deptname" v-if="!filterForm.dept_id">
                                                                        {{dept.name}}</i>

                                                                </p>
                                                            </td>
                                                            <td>
                                                                <a v-if="row.status ==  0 ">
                                                                    <img class="logo_done" @click="statusChange(1,row)"
                                                                        width="30px"
                                                                        :src="base_url+'assets/app-assets/images/logo/pen.png'" />
                                                                </a>
                                                                <a v-if="row.status ==  1 ">
                                                                    <img class="logo_done" @click="statusChange(0,row)"
                                                                        width="30px"
                                                                        :src="base_url+'assets/app-assets/images/logo/done.png'" />
                                                                </a>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="dropup"
                                                                    v-if="(row.status ==  0) && role_id == 3 ">
                                                                    <span
                                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <router-link class="dropdown-item"
                                                                            :to="{ path: '/edit_follow_up/'+row.id }"><i
                                                                                class="bx bx-edit-alt mr-1"></i> edit
                                                                        </router-link>
                                                                        <a class="dropdown-item"
                                                                            @click="delete_row(row.id)"><i
                                                                                class="bx bx-trash mr-1"></i> Delete</a>
                                                                    </div>
                                                                </div>
                                                                <a v-if="row.status ==  1 ">
                                                                    <span class="bg-success btn">Completed</span>
                                                                </a>
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
                    <modal width="60%" height="70%" style="padding:50px" name="popup-singel">
                        <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                        <div class="app-content ">
                            <div class="card">
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Department Name</td>
                                            <td>{{item.name}}</td>
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
    import {
        Form
    } from "vform";
    import axios from "../../axios_instance";
    import Datepicker from 'vuejs-datepicker';
    export default {
        props: {},
        components: {
            Datepicker
            // VueRecaptcha, facebookLogin
        },
        data() {
            return {
                base_url: window.base_url,
                api_url: window.api_url,
                token: this.$localStorage.get("d_token"),
                user_data: JSON.parse(this.$localStorage.get("user")),
                items: [],
                deptItems: [],
                item: [],
                dept_users: [],
                role_id: '',
                status: '',
                statusForm: new Form({}),

                filterForm: new Form({
                    start_date: new Date("1 1, 2020"),
                    end_date: new Date(),
                    dept_id: ''
                })

            };
        },
        created() {
            this.role_id = this.user_data.role_id;
            this.getItems();
            this.getDepts();
        },
        methods: {
            async getDepts() {
                await axios.get(this.api_url + "departments", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.deptItems = data.data;
                    });

            },
            async popUp(item) {
                this.item = item;
                let loader = this.$loading.show();
                let where = '?1=1';
                if (this.filterForm.dept_id) {
                    where += '&dept_id=' + this.filterForm.dept_id;
                }

                await axios.get(this.api_url + "department_assigns" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        loader.hide();
                        this.dept_users = data.data;
                    });
                this.$modal.show("popup-singel");
            },
            hide_pop() {
                this.$modal.hide("popup-singel");
            },
            statusChange(type, item) {
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
                            this.statusForm.put(this.api_url + "follow_ups_status/" + item.id, {
                                headers: {
                                    "Content-Type": "application/json",
                                    Authorization: this.token ? `Bearer ${this.token}` : ""
                                },
                            }).then((res) => {
                                console.log(res);
                                this.getItems();
                                this.$toasted.show('Your task status has been updated!', {
                                    theme: "bubble",
                                    duration: 5000,
                                    position: "bottom-right",
                                });

                            }, (error) => {
                                console.log(error);
                                // loader.hide(); 
                            })

                        }
                    });
            },

            async getItems() {
                //departments_all
                let where = '?status=0';
                if (this.filterForm.dept_id) {
                    where += '&dept_id=' + this.filterForm.dept_id;
                }
                if (this.filterForm.start_date && this.filterForm.end_date) {
                    where += '&start_date=' + this.format_Date(this.filterForm.start_date);
                    where += '&end_date=' + this.format_Date(this.filterForm.end_date);
                }
                let loader = this.$loading.show();
                await axios.get(this.api_url + "follow_ups" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.items = data.data
                        loader.hide();

                    });

            },
            async delete_row(id) {
                console.log(id);
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "follow_ups/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then(({
                            res
                        }) => {
                            this.getItems();
                            this.getMyItems();
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
        },
        computed: {},
    };
</script>
<style>
    i.deptname {
        width: auto;
        float: left;
        border: 1px solid #efefef;
        padding: 1px 5px 1px 3px;
        border-radius: 5px;
        margin-left: 10px;
        margin-top: 4px;
        background: #efefef4d;
    }
</style>