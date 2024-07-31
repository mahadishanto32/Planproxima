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
                                        <li class="breadcrumb-item"><router-link :to="{ path: '/' }"><i
                                                    class="bx bx-home-alt"></i></router-link>
                                        </li>
                                        <li class="breadcrumb-item  "> <router-link :to="{ path: '/daily_work' }"> Daily
                                                Work </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Edit Task
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
                                        <h4 class="card-title">Edit daily work</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <br>
                                            <Prioroty_task v-if="role_id < 6"/> 
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-3">
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
                                                            <label for="Profession">KRA</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kra_id" @change="getKpi()"
                                                                    v-model="editForm.kra_id"
                                                                    :class="{ 'is-invalid': editForm.errors.has('kra_id'), }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kraItem" :key="row.id"
                                                                        :value="row.id">{{ row.kra_name }}</option>
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
                                                                    :class="{ 'is-invalid': editForm.errors.has('kpi_id'), }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kpiItem" :key="row.id"
                                                                        :value="row.id">{{ row.kpi_name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">MOS</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="mos_id"
                                                                    v-model="editForm.mos_id"
                                                                    :class="{ 'is-invalid': editForm.errors.has('mos_id'), }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in mosItem" :key="row.id"
                                                                        :value="row.id">{{ row.mos_name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     

                                                    <table class="table table-bordered table-sm">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th style="width: 4px;">No</th>
                                                                <th>Work Details </th>
                                                                <th style="width: 130px;" v-if="role_id == 1 || role_id == 2
                                                                    || role_id == 3 || role_id == 4
                                                                    || role_id == 5">Working Type</th>
                                                                <th style="width: 130px;" v-else> Project </th>
                                                                <th style="width: 20px;" z>Start Time</th>
                                                                <th style="width: 20px;">End Time</th>
                                                                <th style="width: 20px;">Working Time</th>
                                                                <th style="width: 100px;"
                                                                    v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4 || role_id == 5">
                                                                    Top Priority</th>
                                                                <th style="width: 100px;"
                                                                    v-if="role_id == 6 || role_id == 7">UNPLANNED</th>
                                                                <th style="width: 100px;"
                                                                    v-if="role_id == 6 || role_id == 7">NON-OPT</th>
                                                                <th style="width: 80px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <template v-for="(schedule, index) in editForm.tasks">
                                                                <tr :key="index">
                                                                    <td>{{ index + 1 }}</td>
                                                                    <td>
                                                                        <textarea rows="1"
                                                                            v-model="editForm.tasks[index].task"
                                                                            class="form-control"></textarea>
                                                                        <!-- <input placeholder="Work task" style="width: 100%;" type="text" value=""
                                                                            v-model="editForm.tasks[index].schedule_details" /> -->
                                                                    </td>
                                                                    <td
                                                                        v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4 || role_id == 5">
                                                                        <select id="Profession" name="mos_id"
                                                                            v-model="editForm.tasks[index].schedule_type_id"
                                                                            :class="{ 'is-invalid': editForm.errors.has('mos_id'), }"
                                                                            class="form-control">
                                                                            <option value="">Select one</option>
                                                                            <option v-for="row in scheduleTypes"
                                                                                :key="row.id" :value="row.id">{{ row.name }}
                                                                            </option>
                                                                        </select>
                                                                    </td>
                                                                    <td v-else>
                                                                        <!-- <select id="Profession" name="mos_id"
                                                                            v-model="editForm.tasks[index].project_id"
                                                                            :class="{  'is-invalid': editForm.errors.has('mos_id'),  }"
                                                                            class="form-control" required>
                                                                            <option value="">Select Projects</option>
                                                                            <option v-for="row in projectsItem" :key="row.id"
                                                                                :value="row.id">{{ row.name}}</option>
                                                                        </select> -->

                                                                        <Select2 placeholder="Select Project"
                                                                            v-model="editForm.tasks[index].project_id"
                                                                            :options="projectsItem" />
                                                                    </td>

                                                                    <td>

                                                                        <vue-timepicker format="hh:mm A" close-on-complete
                                                                            manual-input
                                                                            v-model="editForm.tasks[index].start_time"
                                                                            :minute-interval="15"></vue-timepicker>
                                                                    </td>
                                                                    <td>

                                                                        <vue-timepicker format="hh:mm A" close-on-complete
                                                                            manual-input
                                                                            v-model="editForm.tasks[index].end_time"
                                                                            :minute-interval="15"></vue-timepicker>
                                                                    </td>
                                                                    <td>{{ timeCalculation(index) }}</td>

                                                                    <td v-if="role_id == 6 || role_id == 7">
                                                                        <input class="form-control custom_checkbox"
                                                                            v-model="editForm.tasks[index].work_type"
                                                                            style="border: 1px solid #efefef ; border-radius:  10px;"
                                                                            type="checkbox" name="top_priority" value="1">
                                                                    </td>
                                                                    <td v-if="role_id == 6 || role_id == 7">
                                                                        <input class="form-control custom_checkbox"
                                                                            v-model="editForm.tasks[index].task_type"
                                                                            style="border: 1px solid #efefef ; border-radius:  10px;"
                                                                            type="checkbox" name="top_priority" value="1">
                                                                    </td>

                                                                    <td
                                                                        v-if="role_id == 1 || role_id == 2 || role_id == 3 || role_id == 4 || role_id == 5">
                                                                        <input class="form-control custom_checkbox"
                                                                            v-model="editForm.tasks[index].top_priority"
                                                                            style="border: 1px solid #efefef ; border-radius:  10px;"
                                                                            type="checkbox" name="top_priority"
                                                                            value="1"><label style="padding-left:5px ;"
                                                                            for="Profession"></label>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn-success" type="button"
                                                                            @click="item_add()"
                                                                            v-if="(editForm.tasks.length - 1 == index)"><i
                                                                                class="bx bx-plus"></i></button>
                                                                        <button class="btn-danger" type="button"
                                                                            @click="item_removes(index, schedule)"><i
                                                                                class="bx bx-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                            <template v-for="(type, index2) in scheduleTypes">
                                                                <tr :key="index2">
                                                                    <td colspan="2" v-if="index2 == 0"
                                                                        :rowspan="scheduleTypes.length"></td>
                                                                    <td colspan="3" class="text-right">{{ type.name }}</td>
                                                                    <td>{{ timeConvert(getTypeTime(type.id)) }}</td>
                                                                    <td>{{ getTypeTopPriority(type.id) }}</td>
                                                                </tr>
                                                            </template>
                                                            <tr>
                                                                <th colspan="4"></th>
                                                                <th class="text-right">Total</th>
                                                                <th>{{ timeConvert(getTypeTotalTime()) }}</th>
                                                                <th>{{ getTypeTopTotalPriority() }}</th>
                                                            </tr>
                                                            <!-- <tr> 
                                                                <td colspan="3">Function</td>
                                                                <td colspan="2">1h</td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
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
import Select2 from 'v-select2-component';
import { Form } from "vform";
import Datepicker from 'vuejs-datepicker';
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic' ;
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import Prioroty_task from '../priority_task/priority_task.vue';

export default {
    props: {
    },
    components: {
        Prioroty_task,
        'Select2': Select2,
        VueTimepicker,
        Datepicker, 
    },
    data() {
        return {
            
            filterForm: new Form({
                wing_id: "",
            }), 
            base_url: window.base_url,
            api_url: window.api_url,
            DepartmentsItems: [],
            id: this.$route.params.id,
            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            role_id: '',
            scheduleTypes: [],
            scheduleTypes_allow: false,
            editForm: new Form({
                task: "",
                kra_id: "",
                kpi_id: "",
                mos_id: "",
                date: "",
                top_priority: 0,
                start_time: "",
                end_time: "",
                user_id: "",
                tasks: [
                    {
                        schedule_type_id: 1,
                        task: "",
                        start_time: "08:30 am",
                        end_time: "09:30 am",
                        duration: 0,
                        top_priority: false,
                        tasks: '',
                        task_type: '',
                        work_type: '',
                    }
                ],

            }),
            kraItem: [],
            kpiItem: [],
            mosItem: [],
        };
    },
    created() {
        // console.log( 'ssss', this.user_data);
        this.getProjects();
        this.role_id = this.user_data.role_id;
        if(this.user_data.role_id < 6){
            this.getProjects();
        } 
        this.getItem("daily_schedules/" + this.id).then(({ data }) => {
            if (data.success) {
                this.item = data.data;
                // this.$refs.editor.setContent(this.item.task); 
                this.editForm.task = this.item.task;
                this.editForm.status = this.item.status;
                this.editForm.kra_id = this.item.kra_id;
                this.editForm.kpi_id = this.item.kpi_id;
                this.editForm.mos_id = this.item.mos_id;
                this.editForm.date = this.item.date;
                this.editForm.top_priority = this.item.top_priority;
                this.editForm.start_time = this.item.start_time;
                this.editForm.end_time = this.item.end_time;
                this.editForm.user_id = this.item.user_id;
                this.getKpi(this.item.kra_id);
                this.getMos(this.item.kpi_id);
                this.editForm.tasks = this.item.tasks;
            }
        });
        if (this.role_id == 1 || this.role_id == 2 || this.role_id == 3 || this.role_id == 4 | this.role_id == 5) {
            this.scheduleTypes_allow = true;
            this.dailyScheduleTypes();
        }
        this.getKRA()
        this.dept();

    },
    methods: {
        
        async getProjects() {
            let where = "?1=1";

            if (this.filterForm.wing_id) {
                where += '&wing_id=' + this.filterForm.wing_id;
            }
            await axios.get(this.api_url + "projects" + where, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({
                    data
                }) => {
                    this.projectsItem = data.data;
                    // console.log(this.scheduleTypes);
                });
        },
        timeCalculation(index) {
            let start_time = this.editForm.tasks[index].start_time;
            let end_time = this.editForm.tasks[index].end_time;
            if (start_time && end_time) {
                let total = this.timeToMin(end_time) - this.timeToMin(start_time);
                this.editForm.tasks[index].duration = total;
                return this.timeConvert(total);
            } else {
                return '0m';
            }
        },
        timeConvert(n) {
            var num = n;
            var hours = (num / 60);
            var rhours = Math.floor(hours);
            var minutes = (hours - rhours) * 60;
            var rminutes = Math.round(minutes);
            return (rhours ? rhours + "h " : "") + (rminutes ? rminutes + "m" : '');
        },
        timeToMin(start_time) {
            let start_time_end = start_time.split(" ");
            let am_pm = start_time_end[1];
            let timeArray = start_time_end[0].split(":");
            let h = Number(timeArray[0]);
            let m = Number(timeArray[1])
            if (am_pm == 'pm' && h != 12) { h = h + 12; }
            m = (h * 60) + m;
            return m;
        },
        getToDate() {
            var date = new Date();
            date.setDate(date.getDate() - 3);
            var finalDate = date.getFullYear() + ', ' + (date.getMonth() + 1) + ', ' + date.getDate();
            return finalDate;
        },
        getTypeTime(id) {
            let totalTime = 0;
            this.editForm.tasks.forEach(element => {
                if (element.schedule_type_id == id) {
                    totalTime = totalTime + Number(element.duration)
                }
            });
            return totalTime;
        },
        getTypeTotalTime() {
            let totalTime = 0;
            this.editForm.tasks.forEach(element => {
                totalTime = totalTime + Number(element.duration)
            });
            return totalTime;
        },
        getTypeTopPriority(id) {
            let top_priority = 0;
            this.editForm.tasks.forEach(element => {
                if (element.schedule_type_id == id && element.top_priority == true) {
                    top_priority = top_priority + 1;
                }
            });
            return top_priority ? top_priority : '';
        },
        getTypeTopTotalPriority() {
            let top_priority = 0;
            this.editForm.tasks.forEach(element => {
                if (element.top_priority == true) {
                    top_priority = top_priority + 1;
                }
            });
            return top_priority ? top_priority : '';
        },
        //end 
        async dailyScheduleTypes() {
            await axios.get(this.api_url + "daily_schedule_types", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({
                    data
                }) => {
                    this.scheduleTypes = data.data;
                    console.log(this.scheduleTypes);
                });
        },
        scheduleTypesName(id) {
            for (let index = 0; index < this.scheduleTypes.length; index++) {
                if (this.scheduleTypes[index].id == id) {
                    return this.scheduleTypes[index].name;
                }
            }
        },
        item_add() {
            let newItem = {
                schedule_type_id: "",
                schedule_details: "",
                task: "",
                start_time: "",
                end_time: "",
                duration: 0,
                top_priority: false
            };
            this.editForm.tasks.push(newItem);
        },
        item_removes(index, item) {
            console.log(index);
            console.log(item);

            this.$swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (item.id) {
                        this.delete_row(item.id)
                    }

                    if (willDelete) {
                        this.editForm.tasks.splice(index, 1);
                    }
                });
        },
        getFromDate() {
            var date = new Date();
            date.setDate(date.getDate() + 1);
            var finalDate = date.getFullYear() + ', ' + (date.getMonth() + 1) + ', ' + date.getDate();
            return finalDate;

        },
        async delete_row(id) {
            await axios
                .delete(this.api_url + "daily_schedule_items/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    res
                }) => {

                    if (res.data.success) {
                        // this.getItems();
                        // this.$toasted.show(res.data.message, {
                        //     theme: "bubble",
                        //     duration: 5000,
                        //     position: "bottom-right",
                        // });
                    }
                });

        },
        create() {
            try {
                let loader = this.$loading.show();
                //this.editForm.task = this.$refs.editor.getContent();
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
                    this.$router.push('/task');
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
            this.getDepartments().then(({ data }) => {
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
                .then(({ data }) => {
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
                .then(({ data }) => {
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
                .then(({ data }) => {
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
                .then(({ data }) => {
                    this.roles = data.data;
                    console.log(this.roles);
                });
        },
    },
    computed: {},
};
</script>
<style>
.task-table .thead-dark th {
  background: #e65e0c !important;
  border-color:  1px solid #DFE3E7 !important;
}
</style>