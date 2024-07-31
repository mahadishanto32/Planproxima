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
                                        <li class="breadcrumb-item active"> KRA, KPI and MOS Weightage List
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- <div class=" col-sm-3">
                                <router-link class="btn btn-primary add-btn" :to="{ path: '/add_daily_work' }"> <i class="bx bx-add-alt"></i> Add daily work </router-link>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="content-body">

                    <section id="basic-datatable">
                        <div class="users-list-filter px-1">
                            <div class="row border rounded py-2 mb-2">
                                <div v-if="deptItems.length > 1" class="col-12 col-sm-6 col-lg-2">
                                    <label for="users-list-verified">Department</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="deptChange()"
                                            v-model="filterForm.dept_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in deptItems" :key="row.id" :value="row.id">
                                                {{ row.name }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6">
                                    <label for="users-list-verified">Wings</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="changeEmployee()"
                                            v-model="filterForm.wing_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in WingsItems" :key="row.id" :value="row.id">
                                                {{ row.wing_title }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-2" v-if="teamItem.length>0 && (role_id == 6 || role_id == 7)">
                                    <label for="users-list-verified">Team</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" v-on:change="teamMember()"
                                            v-model="filterForm.team_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in teamItem" :key="row.id" :value="row.id">
                                                {{ row.team_name }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>        

                                <div class="col-12 col-sm-6 col-lg-2" v-if="teamItem.length>0 && team_member.length>0">
                                    <label for="users-list-verified">Team Member</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" 
                                            v-model="filterForm.member_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in team_member" :key="row.id" :value="row.id">
                                                {{row.userJoin ? row.userJoin.name : ''}}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div> 

                                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5 || role_id == 6 ">
                                    <label for="users-list-verified">Employee</label>
                                    <fieldset class="form-group">
                                        <select class="form-control"
                                            v-model="filterForm.user_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                                                {{ row.employee_id ?   row.employee_id + ' : ' : ''  }} {{ row.name }}
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2" v-if="(role_id == 5 || role_id == 6 ) || (teamItem.length>0)">
                                    <label for="users-list-verified"> </label>
                                    <fieldset class="form-group">
                                        <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                            <a class="text-white" @click="create()" > <i class="bx bx-add-alt"></i> Assign</a>
                                        </button>
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
                                                <table class="table table-bordered table-sm">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>KRA</th>
                                                            <th>KRA Weightage</th>
                                                            <th>KPI</th>
                                                            <th>KPI Weightage</th>
                                                            <th>Assign KRA Weightage</th>
                                                            <th>MOS</th>
                                                            <th>MOS Weightage</th>
                                                            <th>Assign KPI Weightage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template v-for="(item , index ) in items">
                                                            <tr :key="item.id">
                                                                <td :rowspan="item.kra_count"
                                                                    v-if="items[index > 0 ? index - 1 : 0 ].kra_id != item.kra_id || index ==0">
                                                                    {{ item.krajoin ? item.krajoin.kra_name : '' }}
                                                                    <input type="checkbox" :value="item.krajoin.id" @change="checked_fun(item,'kra',$event)"  id="value_checked" v-model="item.kra_checked" > 
                                                                </td>
                                                                <td :rowspan="item.kra_count"
                                                                    v-if="items[index > 0 ? index - 1 : 0 ].kra_id != item.kra_id || index ==0">
                                                                    {{ item.krajoin ? item.krajoin.kra_weight : '' }}
                                                                </td>
                                                                <td :rowspan="item.kpi_count"
                                                                    v-if="items[index > 0 ? index - 1 : 0 ].kpi_id != item.kpi_id || index ==0">
                                                                    {{ item.kpijoin ? item.kpijoin.kpi_name : '' }} 
                                                                    <input type="checkbox" value="0"  @change="checked_fun(item,'kpi',$event)"  id="value_checked" v-model="item.kpi_checked"> 
                                                                </td>
                                                                <td :rowspan="item.kpi_count"
                                                                    v-if="items[index > 0 ? index - 1 : 0 ].kpi_id != item.kpi_id || index ==0">
                                                                    {{ item.kpijoin ? item.kpijoin.kpi_weight : '' }}
                                                                </td>
                                                                <td :rowspan="item.kpi_count"
                                                                    v-if="items[index > 0 ? index - 1 : 0 ].kpi_id != item.kpi_id || index ==0">
                                                                    <input type="number" value="" class="kra_weight_assign"  v-model="item.kra_weight_assign" />
                                                                </td>                                                                
                                                                <td>
                                                                    <input type="checkbox" value="0" @change="checked_kpi()"  id="value_checked" v-model="item.mos_checked">
                                                                    {{ item.mos_name }}
                                                                </td>
                                                                <td>{{ item.weightage }}</td> 
                                                                <td><input type="number" value="" class="kpi_weight_assign"  v-model="item.kpi_weight_assign" /></td>                                                            
                                                            </tr>
                                                        </template>
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
    export default {
        props: {},
        components: {
            // VueRecaptcha, facebookLogin
        },
        data() {
            return {
                base_url: window.base_url,
                api_url: window.api_url,
                token: this.$localStorage.get("d_token"),
                user_data: JSON.parse(this.$localStorage.get("user")),
                role_id: '',
                teamItem: [],
                team_member: [],
                items: [],
                status: '',
                deptItems: [],
                WingsItems: [],
                employeeItem: [],
                year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
                kra_checked: '',
                kpi_checked: '',
                filterForm: new Form({
                    dept_id: "",
                    wing_id: "",
                    team_id: "",
                    member_id: "",
                    user_id : "",
                    item : '',
                    year : '',
                }),
            };
        },
        created() {
            this.filterForm.dept_id = this.user_data.dept_id;
            this.role_id = this.user_data.role_id;
            if(this.filterForm.dept_id){
                this.getWing();
                this.filterForm.wing_id = this.user_data.wing_id ? this.user_data.wing_id : ""; 
            } 
            if (this.role_id == 1 || 
            this.role_id == 2 || 
            this.role_id == 3 || 
            this.role_id == 4 || 
            this.role_id == 5) {
                this.getEmployee();
                this.deptChange();
            } else {
                //this.getItems();  
                this.getItems();
            }
            
        },
        methods: {
            // weightage_cal(){
            //    return 100;
            // },
            checked_fun (data,type){
                if(event.target.checked){
                    for (let index = 0; index < this.items.length; index++) { 
                        if(type=='kra'){
                            if(data.kra_id == this.items[index].kra_id){
                                this.items[index].kra_checked = true;
                                this.items[index].kpi_checked = true;
                                this.items[index].mos_checked = true;
                            }
                        }else{
                            if(data.kpi_id == this.items[index].kpi_id){
                                this.items[index].kpi_checked = true;
                                this.items[index].kra_checked = true;
                                this.items[index].mos_checked = true;
                            }
                        }
                    }                     
                }else{
                    for (let index = 0; index < this.items.length; index++) { 
                        if(type=='kra'){
                            if(data.kra_id == this.items[index].kra_id){
                                this.items[index].kpi_checked = false;
                                this.items[index].kpi_checked = false;
                                this.items[index].mos_checked = false;
                            }
                        }else{
                            if(data.kpi_id == this.items[index].kpi_id){
                                this.items[index].kpi_checked = false;
                                this.items[index].kra_checked = false;
                                this.items[index].mos_checked = false;
                            }                            
                        }
                    }                    
                }
            },
            achievement(item, month) {
                if (item.mostargetjoin[month] > 0 && item.mosachievementjoin[month] > 0) {
                    return ((item.mostargetjoin[month] / item.mosachievementjoin[month]) * 100).toFixed();
                } else {
                    return 0;
                }
                // (item.mostargetjoin.january / item.mosachievementjoin.january)/100

            },
            checkConditionKra(length, kpi_index, mos_index) {
                if (kpi_index == 0 && mos_index == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            checkConditionKpi(length, mos_index) {
                if (mos_index == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            async getWing() {
                await axios.get(this.api_url + "wings?dept_id="+this.filterForm.dept_id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.getEmployee();
                       // this.getTeam();
                        this.WingsItems = data.data; 
                        console.log(this.WingsItems);
                    });
            },
            async getTeam() {
                let where = '?1=1';
                if(this.role_id == 7){
                    where += '&team_leader=' + this.user_data.id;
                }else{
                    if (this.filterForm.wing_id) {
                        where += '&wings_id=' + this.filterForm.wing_id;
                    }
                    if (this.filterForm.dept_id) {
                        where += '&dept_id=' + this.filterForm.dept_id;
                    }
                }
                await axios.get(this.api_url + "teams" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({ data }) => {
                    this.teamItem = data.data;
                    console.log('this.teamItem',this.teamItem);
                });
            },
            async teamMember(){
                let where = '?1=1';
                if (this.filterForm.team_id) {
                    where += '&team_id=' + this.filterForm.team_id;
                }
                await axios.get(this.api_url + "team_members" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({ data }) => {
                    this.team_member = data.data;
                });
            },
            async changeEmployee(){
                this.getEmployee();
            },
            async getEmployee() {
                //    this.getTeam();
               // if(this.filterForm.wing_id){
                    let where = '?1=1';
                    if (this.filterForm.wing_id) {
                        where += '&wing_id=' + this.filterForm.wing_id;
                    }
                    if (this.filterForm.dept_id) {
                        where += '&dept_id=' + this.filterForm.dept_id;
                    }
                    await axios.get(this.api_url + "users" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({ data }) => {
                        this.employeeItem = data.data;
                        
                        console.log(this.WingsUser);
                    });
               // }
               
            },
            async getItems(load = false) {
                //this.getWing();
                if (this.filterForm.dept_id != '') {
                    let where = '?year=' + (this.year ? this.year : new Date().getFullYear());
                    if (this.filterForm.dept_id) {
                        where += '&dept_id=' + this.filterForm.dept_id;
                    }
                    if (this.filterForm.wing_id) {
                        where += '&wing_id=' + this.filterForm.wing_id;
                    }
                    // if (this.filterForm.user_id) {
                    //     where += '&user_id=' + this.filterForm.user_id;
                    // }
                    let loader ; 
                    if(load){loader = this.$loading.show();}
                    
                    try {
                        await axios
                            .get(this.api_url + "kra_kpi_mos_list_unassign" + where, {
                                headers: {
                                    "Content-Type": "application/json",
                                    Authorization: this.token ? `Bearer ${this.token}` : ""
                                },
                            })
                            .then(({
                                data
                            }) => {
                                if (data.success) {
                                    this.items = data.data;
                                }
                                if(load){ loader.hide();}
                               
                            });
                    } catch (error) {
                        if(load){ loader.hide();}
                    }
                }

            },
            async deptChange(){
                this.getDept();
                this.getWing();
                this.getItems(true); 
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
            create(){
                let loader = this.$loading.show();
                this.filterForm.item = this.items;
                if(this.filterForm.item.kra_weight_assign ){
                    console.log('this.filterForm.item.kra_weight_assign',this.filterForm.item.kra_weight_assign);
                }
                this.filterForm.year = this.year;            
                this.filterForm.post(this.api_url + "assign_kra", {
                    headers: {
                        "Content-Type": "application/json", 
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                }).then((res) => {
                    if(res.data.success){
                        this.$toasted.show(res.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                        });
                    } 
                    loader.hide(); 
                    this.getItems();
                },(error)=>{
                    console.log(error);
                    loader.hide(); 
                })
            },
        },
        computed: {
            
        },
    };
</script>