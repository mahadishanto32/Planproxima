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
                                        <li class="breadcrumb-item active"> Entry Tour Program (Strategic Goal)
                                        </li>
                                    </ol>
                                </div>
                            </div>
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

                                            <div class="btn-group mb-1" role="group" aria-label="Basic example">
                                                <!-- NEW TOUR PLAN ENTRY -->
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <router-link class="text-white"
                                                        :to="{ path: '/new_tour_sbjectives' }"><i
                                                            class="bx bx-add-alt"></i>
                                                        New Strategic Goal
                                                    </router-link>
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Strategic Goal</th>
                                                            <th>Employee</th>
                                                            <th>Month</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row , index) in items" :key="row.id">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ row.objective ? row.objective : ""}}</td>
                                                            <td>{{ row.name }}</td>
                                                            <td>{{ row.month }}</td>
                                                            <td>
                                                                <div class="dropup">
                                                                    <span
                                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <router-link class="dropdown-item"
                                                                            :to="{ path: '/objectives_edit/'+row.id }">
                                                                            <i class="bx bx-edit-alt mr-1"></i> edit
                                                                        </router-link>
                                                                        <a class="dropdown-item"
                                                                            @click="delete_row(row.id)"><i
                                                                                class="bx bx-trash mr-1"></i>
                                                                            Delete</a>
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
    import { Form } from "vform";
    //import Select2 from 'v-select2-component';
    export default {
        props: {},
        components: { 
        },
        data() {
            return {
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
                // myValue: '',
                // myOptions: [{id: 1 ,text : 'op1'}, {id: 2 ,text : 'op2'}, {id: 3 ,text : 'op3'}] ,
                filterForm: new Form({
                    start_date: new Date(),
                    end_date: new Date(),
                    designation: '',
                    hq: '',
                    division_id: '',
                    emp_code: ''
                }),
                statusForm: new Form({

                }),
                designation: ''

            };
        },
        created() {
            this.role_id = this.user_data.role_id;
            this.user_id = this.user_data.id;
            this.getItems();
            this.getUsers();
            this.designations_fnctn();
            this.division_fnctn();
        },
        methods: {


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
                            }

                        });
                } catch (error) {
                    console.log(error);
                }
            },

            async delete_row(id) {
                console.log(id);
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "tour_entrie_objectives/" + id, {
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
                let where = '?1=1';

                if (this.filterForm.hq) {
                    where += '&hq=' + this.filterForm.hq;
                }
                if (this.filterForm.emp_code) {
                    where += '&emp_code=' + this.filterForm.emp_code;
                }
                let loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "tour_entrie_objectives" + where, {
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

        },
        computed: {},
    };
</script>
<style>
    .logo_done {
        width: 30px;
    }
</style>