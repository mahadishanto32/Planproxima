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
                                        <li class="breadcrumb-item active"> Team
                                        </li>

                                    </ol>
                                </div>
                            </div>
                            <!--                        <div class=" col-sm-3">
                            <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>New Wing </a>  
                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable"> 
                            <div class="users-list-filter px-1">
                                <div class="row border rounded py-2 mb-2">
                                    <div v-if="deptItems.length > 1" class="col-12 col-sm-6 col-lg-2">
                                        <label for="users-list-verified">Department</label>
                                        <fieldset class="form-group">
                                            <select class="form-control" v-on:change="loadMethod()"
                                                v-model="filterForm.dept_id" id="users-list-verified">
                                                <option value="">Select One</option>
                                                <option v-for="row in deptItems" :key="row.id" :value="row.id">
                                                    {{ row.name }}
                                                </option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row border rounded py-2 mb-2" v-if="role_id==5 || role_id==6">
                                    <div  class="col-12 col-sm-6 col-lg-2">
                                        <label for="users-list-verified">Wings</label>
                                            <fieldset class="form-group">
                                            <select class="form-control" 
                                                v-on:change="getEmployee()"
                                                v-model="wingChangeForm.wing_id"
                                                id="users-list-verified">
                                                <option value="">Select Wing</option>
                                                <option v-for="row in WingsItems"
                                                    :key="row.id" :value="row.id">
                                                    {{ row.wing_title }}
                                                </option>
                                            </select>
                                        </fieldset>   
                                    </div>
                                </div>

                             
                            </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">


                                            <div class="btn-group mb-1" role="group" aria-label="Basic example" v-if="role_id==5 || role_id==6">

                                                <button  v-if="permission('team')" type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="show_pop()"> <i
                                                            class="bx bx-add-alt"></i>New Team </a>
                                                </button>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">

                                                        <table
                                                            class="tablesaw table-striped table-hover table-bordered table"
                                                            data-tablesaw-mode="columntoggle">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">SL</th>
                                                                    <th scope="col">Department</th>
                                                                    <th scope="col">Wings NAME</th>
                                                                    <th scope="col">WINGS HEAD NAME</th>
                                                                    <th scope="col">Team NAME</th>
                                                                    <th scope="col">Team Leader</th>
                                                                    <th scope="col">ACTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody align="center">
                                                                <tr v-for="(row, index) in teamItem" :key="row.id">
                                                                    <th scope="col"> {{ index + 1 }}</th>
                                                                    <th scope="col"> {{ row.deptjoin ? row.deptjoin.name
                                                                        : 'N/A' }} </th>
                                                                    <th scope="col">
                                                                        {{ row.wingJoin ? row.wingJoin.wing_title : ' ' }}
                                                                    </th>
                                                                    <th scope="col">
                                                                        {{ row.wingJoin ? row.wingJoin.userjoin.name : ' ' }}
                                                                    </th>
                                                                    <th scope="col"> {{ row.team_name }}</th>
                                                                    <th scope="col"> {{ row.userJoin.name }}</th>
                                                                    <th scope="col">
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <router-link class="dropdown-item"
                                                                                    :to="{ path: '/assign_team/'+row.id }">
                                                                                    <i class="bx bx-edit-alt mr-1"></i>
                                                                                    Assign New Employee </router-link>                                                                                
                                                                                <!-- <router-link class="dropdown-item"
                                                                                    :to="{ path: '/edit_wing/'+row.id }">
                                                                                    <i class="bx bx-edit-alt mr-1"></i>
                                                                                    Edit </router-link> -->
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_team(row.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>Delete
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <br>
                                                        <hr>

                                                        <div class="content-header row">
                                                            <div class="content-header-left col-12 mb-1 mt-0">
                                                                <div class="row breadcrumbs-top">
                                                                    <div class="col-sm-7">
                                                                        EMPLOYEE
                                                                    </div>

                                                                    <div v-if="role_id == 5 || role_id ==6" class="col-sm-3">
                                                                        <fieldset class="form-group">
                                                                            <select class="form-control"
                                                                                v-on:change="teamMember()"
                                                                                v-model="filterForm.team_id"
                                                                                id="users-list-verified">
                                                                                <option value="">Select Team</option>
                                                                                <option 
                                                                                v-for="row in teamItem" :key="row.id"
                                                                                :value="row.id">
                                                                                    {{ row.team_name }}
                                                                                </option>
                                                                            </select>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class=" col-sm-2" v-if="role_id==5 || role_id==6">
                                                                        <router-link class="btn btn-primary add-btn"
                                                                            :to="{ path: '/new_user' }"> <i
                                                                                class="bx bx-add-alt"></i> New user
                                                                        </router-link>
                                                                        <!-- <a class="btn btn-primary add-btn" @click="show_pop()" > <i class="bx bx-add-alt"></i>New Wing </a>   -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table
                                                            class="tablesaw table-striped table-hover table-bordered table"
                                                            data-tablesaw-mode="columntoggle">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">SL</th>
                                                                    <th scope="col">Wings Name</th>
                                                                    <th scope="col">Team Name</th>
                                                                    <th scope="col">Member </th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody align='center'>
                                                                <tr v-for="(row, index) in team_member" :key="row.id">
                                                                    <th scope="col">{{ index + 1 }}</th>
                                                                    <th scope="col">
                                                                        {{ row.wingJoin ? row.wingJoin.wing_title : ''
                                                                        }}
                                                                    </th>
                                                                    <th scope="col">
                                                                        {{ row.teamJoin ? row.teamJoin.team_name : ''
                                                                        }}
                                                                    </th>
                                                                    <th scope="col">{{row.userJoin ? row.userJoin.name : ''}}  {{row.userJoin ? (- row.userJoin.employee_id ): ''}}</th>
                                                                    <th scope="col">
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <router-link class="dropdown-item"
                                                                                    :to="{ path: '/edit_user/'+row.id }">
                                                                                    <i class="bx bx-edit-alt mr-1"></i>
                                                                                    Edit </router-link>
                                                                                <!-- <a class="dropdown-item"
                                                                                    @click="WingChange(row)"><i
                                                                                        class="bx bx-edit mr-1"></i>Wing Change
                                                                                </a> -->
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_member(row.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>Delete
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <modal width="60%" height="30%" style="padding:50px" name="popup-singel">
                        <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                        <div class="app-content ">
                            <div class="card">
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody>

                                        <tr>
                                            <th class="text-center">
                                                <input class="form-control text-center" v-model="addFrom.team_name"
                                                    placeholder="Team Name">
                                            </th>
                                            <th class="text-center">
                                                <fieldset class="form-group">
                                                    <select class="form-control" 
                                                        v-on:change="getEmployee()"
                                                        v-model="addFrom.wings_id"
                                                        id="users-list-verified">
                                                        <option value="">Select Wing</option>
                                                        <option v-for="row in WingsItems"
                                                            :key="row.id" :value="row.id">
                                                            {{ row.wing_title }}
                                                        </option>
                                                    </select>
                                                </fieldset>
                                            </th>        
                                            <th class="text-center">
                                                <fieldset class="form-group">
                                                    <select class="form-control" 
                                                        v-model="addFrom.team_leader"
                                                        id="users-list-verified">
                                                        <option value="">Select Employee</option>
                                                        <option v-for="row in employeeItem" 
                                                        :key="row.id"
                                                        :value="row.id"
                                                        >
                                                            {{ row.name }}
                                                        </option>
                                                    </select>
                                                </fieldset>
                                            </th>  
                                            <th class="text-center">
                                                <button @click="addTeam()" class="btn btn-success">Save</button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </modal>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { Form } from "vform";
    import axios from "../../axios_instance";
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
                token: this.$localStorage.get("d_token"),
                user: JSON.parse(this.$localStorage.get("user")),
                team_member: [],
                teamItem :[],
                items: [],
                item: [],
                status: '',
                dept_id: '',
                role_id: '',
                WingsItems: [],
                WingsUser: [],
                employeeItem: [],
                deptItems: [],
                wing_title: '',
                addFrom: new Form({
                    team_name: '',
                    team_leader: '',
                    wings_id: '',
                    dept_id: '',
                }),
                wing: new Form({
                    wing_title: '',
                }),
                filterForm: new Form({
                    wing_id: "",
                    dept_id : "",
                    team_id : "",
                }),
                userChang: new Form({
                    wing_id: '',
                    id: ''
                }),
                wingChangeForm: new Form({
                    wing_id: '',
                    id: ''
                }),

            };
        },
        created() {
            this.dept_id = this.user.dept_id;
            this.role_id = this.user.role_id; 
            this.filterForm.dept_id = this.user.dept_id;
            if(this.filterForm.dept_id){
                this.filterForm.wing_id = this.user.wing_id ? this.user.wing_id : ""; 
                this.addFrom.wings_id = this.user.wing_id ? this.user.wing_id : ""; 
            }             
            this.loadMethod();
        },
        methods: {
            async teamMember(){
                let where = '?1=1';
                if (this.filterForm.team_id) {
                    where += '&team_id=' + this.filterForm.team_id;
                }

                if (this.wingChangeForm.wing_id) {
                    where += '&wings_id=' + this.wingChangeForm.wing_id;
                }

                if(this.addFrom.wings_id){
                    where += '&wings_id=' + this.addFrom.wings_id;
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
            WingChange(item){
                this.item = item ;
                this.$modal.show("wing_change");
            },
            WingChangeHiden(){
                this.$modal.hide("wing_change");
            },
            wingChangeSubmit(){
                try {
                    let loader = this.$loading.show();
                    this.wingChangeForm.id = this.item.id;
                   // this.wingChangeForm.wing_id = wing_id;
                    this.wingChangeForm.post(this.api_url + "wing_change", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.getWing();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.WingChangeHiden();
                        this.getEmployee();
                        //this.$router.push('/wings');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                       
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            loadMethod(){
                this.getWing();
                this.getWingsUser();
                this.getEmployee();
                this.getDept();
                this.getItems();
                this.teamMember();                
            },
            hide_pop() {
                this.$modal.hide("popup-singel");
            },
            show_pop() {
                this.$modal.show("popup-singel");
            },
            async getDept() {
                let loader = this.$loading.show();
                this.getDepartments(this.status).then(({ data }) => {
                    if (data.success) {
                        loader.hide();
                        this.deptItems = data.data;
                        //this.getItems(); 
                    } else {
                        loader.hide();
                    }
                });
            },
            async getWing() {
                await axios.get(this.api_url + "wings?dept_id="+this.filterForm.dept_id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.WingsItems = data.data;
                        console.log(this.WingsItems);
                    });
            },
            async getWingsUser() {
                await axios.get(this.api_url + "user_wing?dept_id="+this.filterForm.dept_id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({ data }) => {
                        this.WingsUser = data.data;
                        console.log('this.WingsUser',this.WingsUser);
                    });
            },
            async getEmployee() {
                this.teamMember();
                this.getItems();
                let where = '?1=1';
                if (this.wingChangeForm.wing_id) {
                    where += '&wing_id=' + this.wingChangeForm.wing_id;
                }
                if(this.addFrom.wings_id){
                    where += '&wing_id=' + this.addFrom.wings_id;
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
            },
            async changeUser(wing_id) {
                try {
                    let loader = this.$loading.show();
                    this.userChang.wing_id = wing_id;
                    this.userChang.post(this.api_url + "users_change/", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.getWing();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        //this.$router.push('/wings');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
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
            async delete_team(id) {
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "teams/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                            this.loadMethod();

                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
            async delete_member(id) {
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "team_members/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then((res) => {
                            console.log(res);
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                            this.loadMethod();
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
            async getItems() {
                let where = '?1=1';
                if (this.wingChangeForm.wing_id) {
                    where += '&wings_id=' + this.wingChangeForm.wing_id;
                }
                if(this.addFrom.wing_id){
                    where += '&wings_id=' + this.addFrom.wing_id;
                }
                if (this.filterForm.dept_id) {
                    where += '&dept_id=' + this.filterForm.dept_id;
                }
                await axios.get(this.api_url + "teams" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({ data }) => {
                    this.teamItem = data.data;
                });
            },
            async addTeam() {
                try {
                    let loader = this.$loading.show();
                    this.addFrom.dept_id = this.dept_id;
                    this.addFrom.post(this.api_url + "teams", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log('res.data',res.data);
                        if (res.data.success) {
                            this.addFrom.team_name = '';
                            this.getWing();
                            this.getItems();
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        this.$modal.hide("popup-singel");
                        loader.hide();
                        this.$router.push('/team');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            }        
        },
        computed: {},
    };
</script>