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
                                        <li class="breadcrumb-item active"> Dept Achivements Permission
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

                                            <!-- <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                                
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="update()"> <i
                                                            class="bx bx-add-alt"></i> Update</a>
                                                </button>
                                            </div> -->

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Dept Name</th>
                                                            
                                                            <th>
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-6 ">
                                                                        <label class="control-label"> Start Date
                                                                        </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="startDateClosedFunction"
                                                                                v-model="start_date" name="start_date"
                                                                                class="form-control"></datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6  ">
                                                                        <label class="control-label">End Date </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
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
                                                        <template v-for="(row ,index) in items">


                                                            <tr :key="row.id" v-if="row.setting">
                                                                <td>{{ index + 1 }}</td>
                                                                <td>
                                                                    <router-link
                                                                        :to="{ path: '/m_o_s_achievement_permissions/'+row.id }">
                                                                        {{ row.name }} <span
                                                                            :title="row.name+' Department total MOS (' + row.setting.totla_mo + ')'"
                                                                            class="count_mos">{{
                                                                            row.setting.totla_mos }}</span>
                                                                    </router-link>

                                                                </td>
                                                                <template v-if="row.setting.totla_mos > 0 ">
                                                                    <td>
                                                                        <div class="row  ">
                                                                            <div class="col-12 col-sm-6 ">
                                                                                <label class="control-label"> Start Date
                                                                                </label>
                                                                                <fieldset class="form-group">
                                                                                    <datepicker v-if="row.setting"
                                                                                        :disabled-dates="state.disabledDates"
                                                                                        @closed="datepickerClosedFunction"
                                                                                        v-model="row.setting.start_date"
                                                                                        name="start_date"
                                                                                        class="form-control">
                                                                                    </datepicker>
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="col-12 col-sm-6  ">
                                                                                <label class="control-label">End Date
                                                                                </label>
                                                                                <fieldset class="form-group">
                                                                                    <datepicker v-if="row.setting"
                                                                                        :disabled-dates="state.disabledDates"
                                                                                        @closed="datepickerClosedFunction"
                                                                                        v-model="row.setting.end_date"
                                                                                        name="end_date"
                                                                                        class="form-control">
                                                                                    </datepicker>
                                                                                </fieldset>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.jan_on > 0">{{
                                                                            row.setting.jan_on }}</span>
                                                                        <input type="checkbox" id="checkbox"
                                                                            @change="updatePermission('jan',row.setting.jan , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            v-if="row.setting"
                                                                            v-model="row.setting.jan">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.feb_on > 0">{{
                                                                            row.setting.feb_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('feb',row.setting.feb , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.feb">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.mar_on > 0">{{
                                                                            row.setting.mar_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('mar',row.setting.mar , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.mar">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.apr_on > 0">{{
                                                                            row.setting.apr_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('apr',row.setting.apr , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.apr">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.may_on > 0">{{
                                                                            row.setting.may_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('may',row.setting.may , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.may">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.jun_on > 0">{{
                                                                            row.setting.jun_on }}</span>

                                                                        <input type="checkbox"
                                                                            @change="updatePermission('jun',row.setting.jun , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.jun">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.jul_on > 0">{{
                                                                            row.setting.jul_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('jul',row.setting.jul , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            v-if="row.setting"
                                                                            v-model="row.setting.jul">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.aug_on > 0">{{
                                                                            row.setting.aug_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('aug',row.setting.aug , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.aug">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.sep_on > 0">{{
                                                                            row.setting.sep_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('sep',row.setting.sep , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.sep">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.oct_on > 0">{{
                                                                            row.setting.oct_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('oct',row.setting.oct , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.oct">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.nov_on > 0">{{
                                                                            row.setting.nov_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('nov',row.setting.nov , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.nov">
                                                                    </td>
                                                                    <td>
                                                                        <span class="count_mos_on"
                                                                            v-if="row.setting.dec_on > 0">{{
                                                                            row.setting.dec_on }}</span>
                                                                        <input type="checkbox"
                                                                            @change="updatePermission('dec',row.setting.dec , row.setting.start_date , row.setting.end_date , row.setting.only_dept ,  row.setting.all_user , row.id  )"
                                                                            value="1" v-if="row.setting"
                                                                            v-model="row.setting.dec">
                                                                    </td>
                                                                </template>
                                                                <template v-if="row.setting.totla_mos == 0">
                                                                    <td colspan="14"></td>

                                                                </template>
                                                            </tr>
                                                        </template>
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
import { Form } from "vform";
import axios from "../../axios_instance";
import Datepicker from 'vuejs-datepicker';
export default {
    props: {
    },
    components: {
        Datepicker
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
            start_date: new Date().toISOString().slice(0, 10), // Current date
      end_date: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10), // Current date + 3 days
            only_dept : false ,
            all_user : false ,
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
            items: [],
            status: '',
            updateForm: new Form({
                items: '',
            }),
        };
    },
    created() {
        this.getItems();
    },
    methods: {
        check(month, value) {
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].setting[month] = value;
            }
             
            this.updatePermission(month, value, this.start_date, this.end_date, this.only_dept , this.all_user , null);

        },
        updatePermission(month, value, start_date, end_date, only_dept , all_user ,  dept_id) {
            if (dept_id) {
                this.updateForm.dept_id = dept_id;
            }
            console.log('end_date');
            console.log(end_date);
            console.log('end_date');
            this.updateForm.month = month;
            this.updateForm.month_value = value ? 1 : 0;

            this.items.start_date = this.format_Date(start_date);
            this.items.end_date = this.format_Date(end_date); 
            this.updateForm.start_date = this.format_Date(start_date);
            this.updateForm.end_date = this.format_Date(end_date); 
            this.updateForm.only_dept = only_dept ;
            this.updateForm.all_user =  all_user ;
            //this.updateForm.items = this.items; 

            this.updateForm.post(this.api_url + "all_dept_mos_permission_update", {
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
                //this.$router.push('/department');
            }, (error) => {
                console.log(error);
                loader.hide();
            })
        },
        datepickerClosedFunction() {

        },
        startDateClosedFunction() {
            console.log(this.start_date);
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].setting.start_date = this.start_date;
            }

            this.updateForm.items = this.items;
            this.updateForm.post(this.api_url + "department_mos_permission_settings_update", {
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
                //this.$router.push('/department');
            }, (error) => {
                console.log(error);
                loader.hide();
            })

        },
        endDateClosedFunction() {
            console.log(this.start_date);
            for (let index = 0; index < this.items.length; index++) {
                this.items[index].setting.end_date = this.end_date;
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
                this.items[index].setting.start_date = this.format_Date(this.items[index].setting.start_date);
                this.items[index].setting.end_date = this.format_Date(this.items[index].setting.end_date);
            }
            this.updateForm.items = this.items;
            this.updateForm.post(this.api_url + "department_mos_permission_settings_update", {
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

        async getItems() {
            //departments_all
            let loader = this.$loading.show();
            await axios.get(this.api_url + "department_mos_setting", {
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
.count_mos {
    background: #f0b8b8;
    color: #151313;
    font-size: 12px;
    border-radius: 6px;
}

.count_mos_on {
    background: #eab107f0;
    font-size: 10px;
    border-radius: 9px;
    margin-right: 2px;
    border: 1px solid #151313;
    padding: 2px;
    color: #fff;
    font-weight: bold;
}
</style>