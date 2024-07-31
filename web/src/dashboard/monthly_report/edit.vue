<template>
    <div>
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-12 mb-1 mt-0">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <div class="breadcrumb-wrapper col-12">
                                    <ol class="breadcrumb p-0 mb-0">
                                        <li class="breadcrumb-item">
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item  ">
                                            <router-link :to="{ path: '/daily_work' }"> Daily Work </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Add Task
                                        </li>

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <section class="input-validation">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Add daily work</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="editForm.date" name="date"
                                                                    class="form-control"></datepicker>
                                                                <!-- <input type="text" name="date" v-model="editForm.date" :class="{  'is-invalid': editForm.errors.has('date'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Date"> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">Start Time</label>
                                                            <div class="controls">
                                                                <input type="text" name="start_time"
                                                                    v-model="editForm.start_time"
                                                                    :class="{  'is-invalid': editForm.errors.has('start_time'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Start Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">End Time</label>
                                                            <div class="controls">
                                                                <input type="text" name="name"
                                                                    v-model="editForm.end_time"
                                                                    :class="{  'is-invalid': editForm.errors.has('end_time'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="End Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">KRA</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kra_id" @change="getKpi()"
                                                                    v-model="editForm.kra_id"
                                                                    :class="{  'is-invalid': editForm.errors.has('kra_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kraItem" :key="row.id"
                                                                        :value="row.id">{{ row.kra_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">KPI</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kpi_id" @change="getMos()"
                                                                    v-model="editForm.kpi_id"
                                                                    :class="{  'is-invalid': editForm.errors.has('kpi_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kpiItem" :key="row.id"
                                                                        :value="row.id">{{ row.kpi_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">MOS</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="mos_id"
                                                                    v-model="editForm.mos_id"
                                                                    :class="{  'is-invalid': editForm.errors.has('mos_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in mosItem" :key="row.id"
                                                                        :value="row.id">{{ row.mos_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <div class="form-group">
                                                            <label>Work Details</label>
                                                            <div class="controls">
                                                                <vue-editor name="task" v-model="editForm.task"
                                                                    :class="{  'is-invalid': editForm.errors.has('task'),  }">
                                                                </vue-editor>
                                                                <!-- //<textarea name="task" v-model="editForm.task" :class="{  'is-invalid': editForm.errors.has('task'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Work Task"></textarea> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Input Validation end -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "../../axios_instance";
    import {
        Form
    } from "vform";
    import Datepicker from 'vuejs-datepicker';
    import {
        VueEditor
    } from "vue2-editor";
    export default {
        props: {},
        components: {
            Datepicker,
            VueEditor
            // VueRecaptcha, facebookLogin 
        },
        data() {
            return {
                year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
                base_url: window.base_url,
                api_url: window.api_url,
                DepartmentsItems: [],
                token: this.$localStorage.get("d_token"),
                id: this.$route.params.id,
                editForm: new Form({
                    task: "",
                    kra_id: "",
                    kpi_id: "",
                    mos_id: "",
                    date: "",
                    start_time: "",
                    end_time: "",
                    user_id: 1,

                }),
                kraItem: [],
                kpiItem: [],
                mosItem: [],
            };
        },
        created() {
            this.getItem("daily_schedules/" + this.id).then(({
                data
            }) => {
                if (data.success) {
                    this.item = data.data;
                    this.editForm.task = this.item.task;
                    this.editForm.status = this.item.status;
                    this.editForm.kra_id = this.item.kra_id;
                    this.editForm.kpi_id = this.item.kpi_id;
                    this.editForm.mos_id = this.item.mos_id;
                    this.editForm.date = this.item.date;
                    this.editForm.start_time = this.item.start_time;
                    this.editForm.end_time = this.item.end_time;
                    this.editForm.user_id = this.item.user_id;
                    this.getKpi(this.item.kra_id);
                    this.getMos(this.item.kpi_id);
                }
            });

            this.getKRA()
            this.dept();

        },
        methods: {
            create() {
                try {
                    let loader = this.$loading.show();
                    this.editForm.put(this.api_url + "daily_schedules/" + this.id, {
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
                        this.$router.push('/daily_work');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async dept() {
                this.getDepartments().then(({
                    data
                }) => {
                    if (data.success) {
                        this.DepartmentsItems = data.data;
                    }
                });
            },

            async getKRA() {
                await axios.get(this.api_url + "k_r_a_s", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.kraItem = data.data;
                        console.log(this.kraItem);
                    });
            },
            async getKpi(kra_id) {
                await axios.get(this.api_url + "k_p_i_s?kra_id=" + kra_id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.kpiItem = data.data;
                        console.log(this.roles);
                    });
            },
            async getMos(kpi_id) {
                await axios.get(this.api_url + "m_o_s?kpi_id=" + kpi_id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.mosItem = data.data;
                        console.log(this.roles);
                    });
            },
            async getRole() {
                await axios.get(this.api_url + "role", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.roles = data.data;
                        console.log(this.roles);
                    });
            },
        },
        computed: {},
    };
</script>