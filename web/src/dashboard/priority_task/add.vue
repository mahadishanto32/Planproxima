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
                                        <h4 class="card-title">New Priority Task</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">


                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-content">
                                                                <div class="card-body card-dashboard">
                                                                    <div class="row">
                                                                        <div class="col-md-3">

                                                                            <div class="form-group">
                                                                                <label for="Profession">Quarter </label>
                                                                                <div class="controls">
                                                                                    <select class="form-control"
                                                                                        v-model="addForm.quarter"
                                                                                        id="users-list-verified">
                                                                                        <option value="">Select One</option>
                                                                                        <option
                                                                                            v-for="row in quarter_months"
                                                                                            :key="row.id" :value="row.id">
                                                                                            {{ row.name }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <table
                                                                        class="table table-bordered table-sm  task-table">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th style="width: 100px;">Priority</th>
                                                                                <th style="width: 30%;">Major Task </th>

                                                                                <template v-if="addForm.quarter == 3">
                                                                                    <th style="">January ({{ sumWeightage('jan') }}%)</th>
                                                                                    <th style="">February ({{ sumWeightage('feb') }}%)</th>
                                                                                    <th style="">March ({{ sumWeightage('mar') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 4">
                                                                                    <th style="">April ({{ sumWeightage('apr') }}%)</th>
                                                                                    <th style="">May ({{ sumWeightage('may') }}%)</th>
                                                                                    <th style="">June ({{ sumWeightage('jun') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 1">
                                                                                    <th style="">July ({{ sumWeightage('jul') }}%)</th>
                                                                                    <th style="">August ({{ sumWeightage('aug') }}%)</th>
                                                                                    <th style="">September ({{ sumWeightage('sep') }}%)</th>
                                                                                </template>
                                                                                <template v-if="addForm.quarter == 2">
                                                                                    <th style="">October
                                                                                        ({{ sumWeightage('oct') }}%)</th>
                                                                                    <th style="">November
                                                                                        ({{ sumWeightage('nov') }}%)</th>
                                                                                    <th style="">December
                                                                                        ({{ sumWeightage('dec') }}%)</th>
                                                                                </template>
                                                                                <th style="">Qtr-{{ addForm.quarter }}
                                                                                    ({{ sumWeightage('quarter_weightage')
                                                                                    }}%)
                                                                                </th>
                                                                                <th style="">Half Year
                                                                                    Weightage({{
                                                                                        sumWeightage('half_year_weightage') }}%)
                                                                                </th>
                                                                                <th style="">Year
                                                                                    Weightage({{
                                                                                        sumWeightage('year_weightage') }}%)
                                                                                </th>

                                                                                <th style="">Achievement (%)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <template
                                                                                v-for="(schedule, index) in addForm.tasks">
                                                                                <tr :key="index">
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
                                                                                        <td>
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

                                                                                        <td>
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
                                                                                        <td>
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
                                                                                        <td>
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
                                                                                        <td>
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
                                                                                        <td>
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
                                                                                        <td>
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

                                                                                        <td>
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
                                                                                        <td>
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

                                                                                        <td>
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
                                                                                        <td>
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
                                                                                        <td>
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
                                                                                                    @keyup="handleKeyUp($event, index, 'quarter_weightage')"
                                                                                                    class="form-control"
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
                                                                                                    name="year_weightagee"
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
                                                                                                    @keyup="handleKeyUpAchive($event, index)"
                                                                                                    :class="{ 'is-invalid': addForm.errors.has('name'), }"
                                                                                                    class="form-control"
                                                                                                    data-validation-required-message="This field is required"
                                                                                                    placeholder="Weightage (%)">
                                                                                            </div>
                                                                                        </div>


                                                                                    </td>
                                                                                </tr>
                                                                            </template>

                                                                        </tbody>
                                                                    </table>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>

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
                tasks: [
                    {
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
                        priority_value: 1,
                    },
                    {
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
                        priority_value: 2,
                    },
                    {
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
                        priority_value: 3,
                    },
                    {
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
                        priority_value: 4,
                    },
                    {
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

                ],
                quarter: 0,
                year: this.$localStorage.get("year")

            }),
            roles: [],
        };
    },
    created() {
        this.addForm.quarter = this.getCurrentQuarterId();
        this.getRole();
        this.dept();
    },
    methods: {
        sumWeightage(month) {
            const sum = this.addForm.tasks.reduce((accumulator, task) => {
                return accumulator + Number(task[month]);
            }, 0);
            return sum;
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
        create() {

            try {
                let loader = this.$loading.show();
                // this.addForm.task = this.$refs.editor.getContent();
                this.addForm.post(this.api_url + "priority_tasks", {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                }).then((res) => {
                    console.log(res);
                    console.log(console.log(res.headers));
                    if (res.data.success) {
                        this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
                    }
                    loader.hide();
                    this.$router.push('/priority_tasks');
                }, (error) => {
                    console.log(error);
                    loader.hide();
                })
            } catch (error) {
                // loader.hide(); 
                console.log(error);
            }


        },


    },
    computed: {},
};
</script>
<style>
.task-table .thead-dark th {
    background: #e65e0c !important;
    border-color: 1px solid #DFE3E7 !important;
}</style>
