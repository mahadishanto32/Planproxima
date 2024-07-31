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
                                        <li class="breadcrumb-item  "> <router-link :to="{ path: '/priority_tasks' }"> Priority
                                                Task </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Priority Task
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
                                        <h4 class="card-title">Update Priority Task</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="update()">
                                                <!-- <div class="row">
                                                    <div class="col-md-3">

                                                        <div class="form-group">
                                                            <label for="Profession">Quarter </label>
                                                            <div class="controls">
                                                                <select class="form-control" v-model="addForm.quarter"
                                                                    id="users-list-verified">
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in quarter_months" :key="row.id"
                                                                        :value="row.id">
                                                                        {{ row.name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div> -->

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">

                                                            <div class="card-content">
                                                                <div class="card-body card-dashboard">
                                                                    <br><br>
                                                                    <table
                                                                        class="table table-bordered table-sm  task-table">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th rowspan="2">Priority</th>
                                                                                <th rowspan="2" style="width: 25%;">Major Task </th>
                                                                                <th colspan="6">Weightage</th>
                                                                                <th rowspan="2">Achievement (%)</th>
                                                                                <th rowspan="2">Action</th>
                                                                            </tr>
                                                                            <tr> 
                                                                                <template v-if="addForm.quarter == 3">
                                                                                    <th style="">January ({{
                                                                                        sumWeightage('jan') }}%)</th>
                                                                                    <th style="">February ({{
                                                                                        sumWeightage('feb') }}%)</th>
                                                                                    <th style="">March ({{
                                                                                        sumWeightage('mar') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 4">
                                                                                    <th style="">April ({{
                                                                                        sumWeightage('apr') }}%)</th>
                                                                                    <th style="">May ({{ sumWeightage('may')
                                                                                    }}%)</th>
                                                                                    <th style="">June ({{
                                                                                        sumWeightage('jun') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 1">
                                                                                    <th style="">July ({{
                                                                                        sumWeightage('jul') }}%)</th>
                                                                                    <th style="">August ({{
                                                                                        sumWeightage('aug') }}%)</th>
                                                                                    <th style="">September ({{
                                                                                        sumWeightage('sep') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 2">
                                                                                    <th style="">October ({{
                                                                                        sumWeightage('oct') }}%)</th>
                                                                                    <th style="">November ({{
                                                                                        sumWeightage('nov') }}%)</th>
                                                                                    <th style="">December ({{
                                                                                        sumWeightage('dec') }}%)</th>
                                                                                </template>
                                                                                <th>Qtr-{{ addForm.quarter }} ({{
                                                                                    sumWeightage('quarter_weightage') }}%)
                                                                                </th>
                                                                                <th>Half Year ({{
                                                                                    sumWeightage('half_year_weightage') }}%)
                                                                                </th>
                                                                                <th>Year ({{
                                                                                    sumWeightage('year_weightage') }}%)
                                                                                </th>
                                                                             

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <template
                                                                                v-for="(schedule, index) in addForm.tasks">
                                                                                <tr :key="index" :class="addForm.tasks[index].quarter_achiv == 100 ? 'bg-green' : ''">
                                                                                    <td>Priority {{ index + 1 }}</td>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <div class="controls">

                                                                                                <textarea rows="1"
                                                                                                    v-model="addForm.tasks[index].task"
                                                                                                    class="form-control "
                                                                                                    placeholder="Task "></textarea>

                                                                                            </div>
                                                                                        </div>
                                                                                    </td> 

                                                                                    <template v-if="addForm.quarter == 3">
                                                                                        <td :class="getCurrentMonth() == 'jan' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="jan"
                                                                                                        v-model="addForm.tasks[index].jan"
                                                                                                        @keyup="handleKeyUp($event, index, 'jan')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('jan') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>

                                                                                        <td :class="getCurrentMonth() == 'feb' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="feb"
                                                                                                        v-model="addForm.tasks[index].feb"
                                                                                                        @keyup="handleKeyUp($event, index, 'feb')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('feb') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'mar' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="mar"
                                                                                                        v-model="addForm.tasks[index].mar"
                                                                                                        @keyup="handleKeyUp($event, index, 'mar')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('mar') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>

                                                                                    </template>
                                                                                    <template v-if="addForm.quarter == 4">
                                                                                        <td :class="getCurrentMonth() == 'apr' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="apr"
                                                                                                        v-model="addForm.tasks[index].apr"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('apr') }"
                                                                                                        @keyup="handleKeyUp($event, index, 'apr')"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'may' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="may"
                                                                                                        v-model="addForm.tasks[index].may"
                                                                                                        @keyup="handleKeyUp($event, index, 'may')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('may') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'jun' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="jun"
                                                                                                        v-model="addForm.tasks[index].jun"
                                                                                                        @keyup="handleKeyUp($event, index, 'jun')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('jun') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>


                                                                                    </template>

                                                                                    <template v-if="addForm.quarter == 1">
                                                                                        <td :class="getCurrentMonth() == 'jul' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="jul"
                                                                                                        v-model="addForm.tasks[index].jul"
                                                                                                        @keyup="handleKeyUp($event, index, 'jul')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('jul') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>

                                                                                        <td :class="getCurrentMonth() == 'aug' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="aug"
                                                                                                        v-model="addForm.tasks[index].aug"
                                                                                                        @keyup="handleKeyUp($event, index, 'aug')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('aug') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'sep' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="sep"
                                                                                                        v-model="addForm.tasks[index].sep"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('sep') }"
                                                                                                        @keyup="handleKeyUp($event, index, 'sep')"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>

                                                                                    </template>
                                                                                    <template v-if="addForm.quarter == 2">

                                                                                        <td :class="getCurrentMonth() == 'oct' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="oct"
                                                                                                        v-model="addForm.tasks[index].oct"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('oct') }"
                                                                                                        @keyup="handleKeyUp($event, index, 'oct')"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'nov' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="nov"
                                                                                                        v-model="addForm.tasks[index].nov"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('nov') }"
                                                                                                        @keyup="handleKeyUp($event, index, 'nov')"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td :class="getCurrentMonth() == 'dec' ? 'current_month' : ''">
                                                                                            <div class="form-group">
                                                                                                <div class="controls">
                                                                                                    <input type="text"
                                                                                                        name="dec"
                                                                                                        v-model="addForm.tasks[index].dec"
                                                                                                        @keyup="handleKeyUp($event, index, 'dec')"
                                                                                                        :class="{ 'is-invalid': addForm.errors.has('dec') }"
                                                                                                        class="form-control"
                                                                                                        data-validation-required-message="This field is required"
                                                                                                        placeholder="Weightage (%)">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>


                                                                                    </template>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <div class="controls">
                                                                                                <input type="text"
                                                                                                    name="name"
                                                                                                    v-model="addForm.tasks[index].quarter_weightage"
                                                                                                    :class="{ 'is-invalid': addForm.errors.has('name'), }"
                                                                                                    class="form-control"
                                                                                                    @keyup="handleKeyUp($event, index, 'quarter_weightage')"
                                                                                                    data-validation-required-message="This field is required"
                                                                                                    placeholder="Weightage (%)">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <div class="controls">
                                                                                                <input type="text"
                                                                                                    name="half_year_weightage"
                                                                                                    max="100"
                                                                                                    v-model="addForm.tasks[index].half_year_weightage"
                                                                                                    :class="{ 'is-invalid': addForm.errors.has('half_year_weightage'), }"
                                                                                                    class="form-control"
                                                                                                    @keyup="handleKeyUp($event, index, 'half_year_weightage')"
                                                                                                    data-validation-required-message="This field is required"
                                                                                                    placeholder="Half Year Weightage (%)">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>

                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <div class="controls">
                                                                                                <input type="text"
                                                                                                    name="namyear_weightagee"
                                                                                                    v-model="addForm.tasks[index].year_weightage"
                                                                                                    @keyup="handleKeyUp($event, index, 'year_weightage')"
                                                                                                    :class="{ 'is-invalid': addForm.errors.has('name'), }"
                                                                                                    class="form-control"
                                                                                                    data-validation-required-message="This field is required"
                                                                                                    placeholder="Yeary Weightage (%)">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>

                                                                                        <div class="form-group">
                                                                                            <div class="controls">
                                                                                                <input type="text"
                                                                                                    name="name"
                                                                                                    v-model="addForm.tasks[index].quarter_achiv"
                                                                                                    :class="{ 'is-invalid': addForm.errors.has('name'), }"
                                                                                                    class="form-control"
                                                                                                    @keyup="handleKeyUpAchive($event, index)"
                                                                                                    data-validation-required-message="This field is required"
                                                                                                    placeholder="Weightage (%)">
                                                                                            </div>
                                                                                        </div>


                                                                                    </td>
                                                                                    <td>

                                                                                        <button class="btn-danger"
                                                                                            type="button"
                                                                                            @click="item_removes(index)"><i
                                                                                                class="bx bx-trash"></i></button>
                                                                                        <button class="btn-success"
                                                                                            type="button" @click="AddMore()"
                                                                                            v-if="(addForm.tasks.length - 1 == index)"><i
                                                                                                class="bx bx-plus"></i></button>
                                                                                    </td>
                                                                                </tr>
                                                                            </template>

                                                                        </tbody>
                                                                    </table>
                                                                    <div style="position: relative;">
                                                                        <div style="position: absolute; right: 0px;">

                                                                            <button type="submit"
                                                                                class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
import { Form } from "vform";
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
            DepartmentsItems: [],
            token: this.$localStorage.get("d_token"),
            addForm: new Form({
                tasks: [],
                quarter: 0,
                id: 0

            }),
            roles: [],
        };
    },
    created() {
        this.getItems();
    },
    methods: {

        sumWeightage(month) {
            const sum = this.addForm.tasks.reduce((accumulator, task) => {
                return accumulator + Number(task[month]);
            }, 0);
            return sum;
        },
        AddMore() {

            this.addForm.tasks.push(
                {
                    id: 0,
                    task: "",
                    quarter_weightage: "",
                    jan: "",
                    feb: "",
                    mar: "",
                    apr: "",
                    may: "",
                    jun: "",
                    jul: "",
                    aug: "",
                    sep: "",
                    oct: "",
                    nov: "",
                    dec: "",
                    quarter_achiv: "",
                    half_year_weightage: "",
                    year_weightage: "",
                    priority_value: 5,
                }
            )
        },
        item_removes(id) {
            console.log(id);
            this.$swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        if (this.addForm.tasks[id].id) {
                            this.delete_row(this.addForm.tasks[id].id)
                        }
                        this.addForm.tasks.splice(id, 1);

                    }
                });
        },
        handleKeyUpAchive(event, index) {
            const value = this.addForm.tasks[index]['quarter_achiv'];
            const numericValue = parseFloat(value.replace(/[^\d.]/g, 0));
            this.addForm.tasks[index]['quarter_achiv'] = numericValue;
            if (numericValue > 100) {
                this.addForm.tasks[index]['quarter_achiv'] = 100;
            }
        },
        handleKeyUp(event, index, month) {
            const value = this.addForm.tasks[index][month];
            const numericValue = parseFloat(value.replace(/[^\d.]/g, 0));
            this.addForm.tasks[index][month] = numericValue;

            const sum = this.addForm.tasks.reduce((accumulator, task) => {
                return accumulator + Number(task[month]);
            }, 0);
            if (100 <= sum) {
                const diff = sum - 100;

                if (diff > 0) {
                    // If the difference is positive, reduce the current value by the difference
                    this.addForm.tasks[index][month] -= diff;
                }
                this.$toasted.show('The maximum weightage is 100.', {
                    theme: "bubble",
                    duration: 2000,
                    position: "bottom-right",
                });
            }
        },
        async getItems() {
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "priority_tasks/" + this.$route.params.id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.addForm.tasks = data.data.tasks;
                        this.addForm.quarter = data.data.quarter_id;
                        this.addForm.id = data.data.id;

                        loader.hide();
                    });
            } catch (error) {
                loader.hide();
            }
        },
        async update() {
            try {
                let loader = this.$loading.show();
                this.addForm.post(this.api_url + "priority_task_items_update?id=" + this.$route.params.id, {
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
                    if (this.$route.query.redirect_to) {
                        const redirectToPath = decodeURIComponent(this.$route.query.redirect_to);
                        this.$router.push(redirectToPath);
                    }
                    // this.$router.push({path: '/'+this.$route.params.redirect_to, query:{key: value}})
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

        async delete_row(id) {
            let loader = this.$loading.show();
            try {
                await axios
                    .delete(this.api_url + "priority_task_items/" + id, {
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
            this.getItems();
        },


    },
    computed: {},
};
</script>
<style>.task-table .thead-dark th {
    background: #e65e0c !important;
    border-color: 1px solid #DFE3E7 !important;
}
.current_month {
  background: #f4d2c55e;
}

.bg-green {
  background: #00800036;
}


</style>
