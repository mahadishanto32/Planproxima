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
                                        <li class="breadcrumb-item active"> Priority Tasks
                                        </li>
                                    </ol>
                                </div>
                            </div>
                             

                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="users-list-filter px-1">
                    </div>

                    <section id="basic-datatable">
                        <div class="content-body">
                            <div class="row">
                                <div class="col-md-2">
                                <div class="form-group">
                                    <label for="Profession">Quarter </label>
                                    <div class="controls">
                                    <select  class="form-control" v-on:change="getItems()"
                                        v-model="filterForm.quarter" id="users-list-verified">
                                    
                                        <option v-for="row in quarter_months" :key="row.id" :value="row.id">
                                        {{ row.name }}
                                        </option> 
                                    </select>
                                    </div>
                                </div>
                                </div>
                               
                                <div class="col-12 col-sm-6 col-lg-2">
                                <label class="control-label">Status </label>

                                <fieldset class="form-group">
                                    <select class="form-control" v-model="filterForm.status" v-on:change="getItems()" id="users-list-verified">
                                        <option value="Yes">Uploaded</option>
                                        <option value="No">Not Uploaded</option>
                                    </select>
                                </fieldset>
                                </div>
                                <!-- <fieldset class="form-group">
                                    <datepicker @closed="datepickerClosedFunction" :disabled-dates="state.disabledDates"
                                    v-model="filterForm.date" name="date" class="form-control"></datepicker>
                                </fieldset> -->
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">

                                            <div class="table-responsive">
                                                <h4>Priority tasks not updated</h4>
                                            </div>
                                            <table class="table table-bordered table-sm task-table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Dept. Name</th>
                                                        <th>HOD Name</th>
                                                        <th>Status</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(item, i) in items">
                                                        <tr>
                                                            <td>{{ i + 1 }}</td>
                                                            <td>{{ item.name }}</td>
                                                            <td>{{ item.hod_name }}</td>
                                                            <td>{{ filterForm.status }} </td>
                                                            
                                                        </tr>
                                                    </template>

                                                </tbody> 

                                            </table>

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
export default {
    props: {},
    components: {
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {

            items: [],

            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            base_url: window.base_url,
            api_url: window.api_url,

            filterForm: new Form({
                user_id: "",
                limit: "50",
                quarter: 0,
                dept_id: "",
                year: null,
                status : "No"
            }),
            commontForm: new Form({
                comment: "",
                priority_item_task_id: 0,
                is_read: 0,
            }),

        };
    },
    created() {
        this.filterForm.year = this.year;
        this.role_id = this.user_data.role_id;
        this.filterForm.quarter = this.getCurrentQuarterId();
        this.getItems();

    },
    methods: {
        totalEntry(type = 'yes') {
            var total = 0;
            for (let index = 0; index < this.items.length; index++) {
                if (Number(this.items[index].task_status) > 0) {
                total = total + 1;
                }
            }
            return total;
        },
        async getItems() {
            let where = '?1=1';
            if (this.filterForm.quarter) {
                where += '&quarter=' + this.filterForm.quarter;
            }
            if(this.filterForm.status){
                where += '&status=' + this.filterForm.status;
            }
            if (this.filterForm.dept_id) {
                where += '&dept_id=' + this.filterForm.dept_id;
            } 
            where += "&year=" + this.year; 

            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "priority_task_not_update" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.items = data.data;
                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },

    },
    computed: {

    }
}
    ;
</script>
<style></style>
  