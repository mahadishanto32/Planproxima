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
                                        <li class="breadcrumb-item active"> BPT Achivements(KRA KPI Update)</li>
                                    </ol>
                                </div>
                            </div>
                            <!--                        <div class=" col-sm-3">
                            <a class="btn btn-primary add-btn" @click="update()" > <i class="bx bx-add-alt"></i> Update</a>       
                        </div> -->
                        </div>
                    </div>
                </div>


                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable">
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">
                                <div v-if=" role_id == 1 " class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Department</label>
                                    <fieldset class="form-group">
                                        <Select2 v-model="filterForm.dept_id" placeholder="Select One"
                                            :options="deptItems" v-on:change="changeDept()" />
                                    </fieldset>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 1">
                                    <label for="users-list-verified">Employee</label>
                                    <fieldset class="form-group">
                             
                                        <Select2 placeholder="Select One" v-on:change="changeEmployee()"
                                            v-model="filterForm.user_id" :options="employeeItem" />
                                    </fieldset>

                                </div>



                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="btn-group mb-1" role="group" aria-label="Basic example">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th style="width:130px">MOS</th>
                                                            <th>
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-6 ">
                                                                        <label class="control-label"> Start Date
                                                                        </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker placeholder="Star Date"
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="startDateClosedFunction"
                                                                                v-model="start_date" name="start_date"
                                                                                class="form-control"></datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6  ">
                                                                        <label class="control-label">End Date </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker placeholder="End Date"
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="endDateClosedFunction"
                                                                                v-model="end_date" name="end_date"
                                                                                class="form-control"></datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>Jan <input type="checkbox" value="1"
                                                                    @change="check('jan',jan)" id="checkbox"
                                                                    v-model="jan"> </th>
                                                            <th>Feb <input type="checkbox" value="1"
                                                                    @change="check('feb',feb)" id="checkbox"
                                                                    v-model="feb"> </th>
                                                            <th>Mar <input type="checkbox" value="1"
                                                                    @change="check('mar',mar)" id="checkbox"
                                                                    v-model="mar"> </th>
                                                            <th>Apr <input type="checkbox" value="1"
                                                                    @change="check('apr',apr)" id="checkbox"
                                                                    v-model="apr"> </th>
                                                            <th>May <input type="checkbox" value="1"
                                                                    @change="check('may',may)" id="checkbox"
                                                                    v-model="may"> </th>
                                                            <th>Jun <input type="checkbox" value="1"
                                                                    @change="check('jun',jun)" id="checkbox"
                                                                    v-model="jun"> </th>
                                                            <th>Jul <input type="checkbox" value="1"
                                                                    @change="check('jul',jul)" id="checkbox"
                                                                    v-model="jul"> </th>
                                                            <th>Aug <input type="checkbox" value="1"
                                                                    @change="check('aug',aug)" id="checkbox"
                                                                    v-model="aug"> </th>
                                                            <th>Sep <input type="checkbox" value="1"
                                                                    @change="check('sep',sep)" id="checkbox"
                                                                    v-model="sep"> </th>
                                                            <th>Oct <input type="checkbox" value="1"
                                                                    @change="check('oct',oct)" id="checkbox"
                                                                    v-model="oct"> </th>
                                                            <th>Nov <input type="checkbox" value="1"
                                                                    @change="check('nov',nov)" id="checkbox"
                                                                    v-model="nov"> </th>
                                                            <th>Dec <input type="checkbox" value="1"
                                                                    @change="check('dec',dec)" id="checkbox"
                                                                    v-model="dec"> </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row ,index) in items" :key="row.id">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ row.mos_name }}
                                                                <p class="request_status"
                                                                    v-if="row.request_status == 1">Request
                                                                    Pendding</p>
                                                                <p class="request_status_appoved"
                                                                    v-if="row.request_status == 2">Open achievement
                                                                    panel</p>

                                                            </td>
                                                            <td>
                                                                <div class="row  ">
                                                                    <div class="col-12 col-sm-6 ">
                                                                        <label class="control-label"> Start Date
                                                                        </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="datepickerClosedFunction"
                                                                                v-model="row.start_date"
                                                                                name="start_date" class="form-control">
                                                                            </datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6  ">
                                                                        <label class="control-label">End Date </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="datepickerClosedFunction"
                                                                                v-model="row.end_date" name="end_date"
                                                                                class="form-control">
                                                                            </datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="checkbox" v-model="row.jan">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.feb">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.mar">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.apr">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.may">
                                                            </td>
                                                            <td>

                                                                <input type="checkbox" value="1" v-model="row.jun">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" v-model="row.jul">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.aug">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.sep">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.oct">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.nov">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" v-model="row.dec">
                                                            </td>
                                                        </tr>
                                                        <tr v-if="items.length < 1">
                                                            <td colspan="4">Data not found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button"
                                                    class=" btn btn-primary btn_bottom_fixed add-btn btn-lg d-flex align-items-center  "
                                                    @click="update()"> {{ user_data.role_id == 5 && filterForm.user_id
                                                    !="" ? 'Approved Request' : user_data.role_id == 1 ?
                                                    'Approved Request' : 'Permission Request'}}

                                                </button>
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
import { Form } from "vform";
import axios from "../../axios_instance";
import Datepicker from 'vuejs-datepicker';
import Select2 from 'v-select2-component';


export default {
    props: {
    },
    components: {
        'Select2': Select2,

        Datepicker,
        'Select2': Select2,
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            state: {
                disabledDates: {
                    to: new Date(new Date().getTime() - 86400000),
                    from: new Date(new Date().getTime() + (86400000 * 30))
                }
            },
            start_date: '',
            end_date: '',
            jan: false,
            feb: false,
            mar: false,
            apr: false,
            may: false,
            jun: false,
            jul: false,
            aug: false,
            sep: false,
            oct: false,
            nov: false,
            dec: false,
            checked: false,
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            role_id: '',
            items: [],
            deptItems: [],
            status: '',
            employeeItem: [],
           // year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
            filterForm: new Form({
                dept_id: "",
                wing_id: "",
                user_id: ""
            }),
            updateForm: new Form({
                items: '',
            }),
        };
    },
    created() {
        this.role_id = this.user_data.role_id;
        // this.getItems();
        this.getDept();
        if (this.$route.query.user_id) {
            this.filterForm.user_id = this.$route.query.user_id;
        }
        if (this.$route.params.id) {
            this.filterForm.dept_id = this.$route.params.id;
           
            this.getItems();
        } else if (this.user_data.role_id == 5) {
            this.filterForm.dept_id = this.user_data.dept_id;
            this.getItems();
        } else {
            
            this.getItems();
        } 
        
        this.getEmployee(this.filterForm.dept_id);

    },
    methods: {
        async getEmployee(dept_id) { 

            let where = '?1=1';
            where += '&dept_id=' + dept_id ; 
            console.log(where);
            await axios.get(this.api_url + "users" + where, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({ data }) => {
                    this.employeeItem = data.data;
                    let allVal = {
                        id: 0,
                        la : null,
                        name : "ALL",
                        text :  "ALL"
                    };
                    this.employeeItem.unshift(allVal);   
                    console.log('erdftygdweeds' , this.employeeItem);
                });
             
        },
        changeEmployee() {
            this.getItems();
        },
        async getDept() {
            let loader = this.$loading.show();
            this.getDepartments(this.status).then(({ data }) => {
                if (data.success) {
                    loader.hide();
                    this.deptItems = data.data;
                } else {
                    loader.hide();
                }
            });
        },
        check(month, value) {
            for (let index = 0; index < this.items.length; index++) {
                this.items[index][month] = value;
            }
        },
        datepickerClosedFunction() {

        },
        startDateClosedFunction() {
            console.log(this.start_date);
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].start_date = this.start_date;
            }

        },
        endDateClosedFunction() {
            console.log(this.start_date);
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].end_date = this.end_date;
            }
        },
        async statusChange(item, status) {
            let editForm = new Form({
                status: status,
                name: item.name
            })
            try {
                let loader = this.$loading.show();
                editForm.put(this.api_url + "departments/" + item.id, {
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
        update() {
            let loader = this.$loading.show();
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].start_date = this.format_Date(this.items[index].start_date);
                this.items[index].end_date = this.format_Date(this.items[index].end_date);
            }
            this.updateForm.items = this.items;
            this.updateForm.year = this.year;
            this.updateForm.post(this.api_url + "m_o_s_achievement_permissions_update", {
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
                this.getItems();
                //this.$router.push('/department');
            }, (error) => {
                console.log(error);
                loader.hide();
            })

        },
        changeDept() {
            
            this.getEmployee(this.filterForm.dept_id);
            this.getItems();

        },
        async getItems() {
            //departments_all
            let loader = this.$loading.show();
            let where = "?year=" + this.year;
            if (this.filterForm.dept_id) {
                where += "&dept_id=" + this.filterForm.dept_id;
            }
            if (this.filterForm.user_id) {
                where += "&user_id=" + this.filterForm.user_id;
            }

            //year

            await axios.get(this.api_url + "m_o_s_achievement_permissions" + where, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({ data }) => {
                    this.items = data.data
                    loader.hide();
                });

        }
    },
    computed: {},
};
</script>
<style>
.request_status {
    font-size: 11px;
    background: #ec9941cc;
    color: #fff;
    width: 101px;
    display: block;
    text-align: center;
    margin: 0px;
    padding: 0 0 2px 0;
    position: absolute;
    right: 1px;
    bottom: 1px;
    height: 23px;
    border-radius: 7px;
}

.request_status_appoved {
    font-size: 11px;
    background: #d85219ef;
    color: #fff;
    width: 140px;
    ;
    display: block;
    text-align: center;
    margin: 0px;
    padding: 0 0 2px 0;
    position: absolute;
    right: 1px;
    bottom: 1px;
    height: 23px;
    border-radius: 7px;

}
</style>