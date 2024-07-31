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
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">
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
                                <div class="text-center section">
                                    <v-calendar class="custom-calendar max-w-full" :attributes="attributes"
                                        disable-page-swipe @change="getEvents()" is-expanded>
                                        <template v-slot:day-content="{ day, attributes }">
                                            <div class="content_view">
                                                <span class="calendar_day">{{ day.day }}</span>
                                                <div class="">
                                                    <vue-custom-scrollbar v-for="attr in attributes" :key="attr.id"
                                                        class="bg-orange-500 text-white" :settings="settings"
                                                        @ps-scroll-y="scrollHanle">

                                                        {{attr.customData.route_name}}
                                                        <!-- {{ attr.customData.title }} -->
                                                    </vue-custom-scrollbar>
                                                </div>
                                            </div>
                                        </template>
                                    </v-calendar>
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
    import Select2 from 'v-select2-component';
    import vueCustomScrollbar from 'vue-custom-scrollbar'
    import "vue-custom-scrollbar/dist/vueScrollbar.css"

    export default {
        props: {},
        components: {
            // Datepicker,
            'Select2': Select2,
            vueCustomScrollbar
            // VueRecaptcha, facebookLogin
        },
        data() {
            const month = new Date().getMonth();
            const year = new Date().getFullYear();
            return {
                month: month + 1,
                year: year,
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
                designation: '',

                masks: {
                    weekdays: 'WWW',
                },
                attributes: [

                ],

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
            updateRange() {
                console.log('Change');

            },
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
                                // console.log('disation list',this.designation_list);
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

                console.log('currentDate.toString() == (item.date).toString()', currentDate.toString() == (item.date).toString())
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
                                console.log(res);
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
                                console.log(res);
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
                let where = '?1=1';

                if (this.filterForm.hq || this.filterForm.emp_code) {
                    where += '&hq=' + this.filterForm.hq;

                    if (this.filterForm.designation) {
                        where += '&designation=' + this.filterForm.designation;
                    }


                    if (this.month) {
                        where += '&month=' + this.month
                    }
                    if (this.year) {
                        where += '&year=' + this.year
                    }
                    if (this.filterForm.emp_code) {
                        where += '&emp_code=' + this.filterForm.emp_code;
                    }
                    let loader = this.$loading.show();
                    try {
                        await axios
                            .get(this.api_url + "tour_entrie_month_list" + where, {
                                headers: {
                                    "Content-Type": "application/json",
                                    Authorization: this.token ? `Bearer ${this.token}` : ""
                                },
                            })
                            .then(({
                                data
                            }) => {
                                if (data.success) {
                                    this.attributes = data.data;
                                    for (let index = 0; index < this.attributes.length; index++) {
                                        this.attributes[index].dates = new Date(this.attributes[index].dates);

                                    }
                                    console.log(' this.attributes');
                                    console.log(this.attributes);



                                    // attributes: [
                                    //     {
                                    //     id: 1, 
                                    //     customData: {
                                    //         title: 'Lunch with mom.', 
                                    //     },
                                    //     dates: new Date(year, month, 20),
                                    //     }

                                    // ],

                                }
                                loader.hide();
                            });
                    } catch (error) {
                        loader.hide();
                    }
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