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
                                            <router-link :to="{ path: '/users' }"> Users </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Edit User
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
                                        <h4 class="card-title">Edit User</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="update()">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="Profession">Role</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="role_id"
                                                                    v-model="editForm.role_id"
                                                                    :class="{ 'is-invalid': editForm.errors.has('type'), }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in roles" :key="row.id"
                                                                        :value="row.id">{{ row.title }}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div v-if="editForm.role_id == 5 || editForm.role_id == 6"
                                                            class="form-group">
                                                            <label for="Profession">Department</label>
                                                            <div class="controls">
                                                                <select class="form-control" v-model="editForm.dept_id"
                                                                    id="users-list-verified">
                                                                    <option value="">All</option>
                                                                    <option v-for="row in DepartmentsItems"
                                                                        :key="row.id" :value="row.id">
                                                                        {{ row.name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div v-if="editForm.role_id == 7 || editForm.role_id == 6"
                                                            class="form-group">
                                                            <label for="Profession">Wing</label>
                                                            <div class="controls">
                                                                <select class="form-control" v-model="editForm.wing_id"
                                                                    id="users-list-verified">
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in WingsItems" :key="row.id"
                                                                        :value="row.id">
                                                                        {{ row.wing_title }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <div class="controls">
                                                                <input type="text" name="name" v-model="editForm.name"
                                                                    :class="{ 'is-invalid': editForm.errors.has('name'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Username</label>
                                                            <div class="controls">
                                                                <input type="text" name="username"
                                                                    v-model="editForm.email"
                                                                    :class="{ 'is-invalid': editForm.errors.has('email'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Username">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>AD Mail</label>
                                                            <div class="controls">
                                                                <input type="text" name="ad_mail"
                                                                    v-model="editForm.ad_mail"
                                                                    :class="{ 'is-invalid': editForm.errors.has('ad_mail'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="AD Mail">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Employee ID (HRIS User ID)</label>
                                                            <div class="controls">
                                                                <input type="text" name="employee_id"
                                                                    v-model="editForm.employee_id"
                                                                    :class="{ 'is-invalid': editForm.errors.has('ad_mail'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="employee id">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Designation</label>
                                                            <div class="controls">
                                                                <input type="text" name="designation"
                                                                    v-model="editForm.designation"
                                                                    :class="{ 'is-invalid': editForm.errors.has('designation'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Designation">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="Profession">Employee Status</label>
                                                            <div class="controls">
                                                                <select class="form-control"
                                                                    v-model="editForm.employee_status"
                                                                    id="users-list-verified">
                                                                    <option value="0">Probation </option>
                                                                    <option value="1">Confirmation</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <div class="controls">
                                                                <input type="text" name="phone" v-model="editForm.phone"
                                                                    :class="{ 'is-invalid': editForm.errors.has('phone'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Phone">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Profession">Status</label>
                                                            <div class="controls">
                                                                <select class="form-control" v-model="editForm.status"
                                                                    id="users-list-verified">
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div v-if="role_id == 1 || role_id == 5 || role_id == 6"
                                                            class="form-group">
                                                            <label for="Profession">Tour Permission</label>
                                                            <div class="controls">
                                                                <select class="form-control"
                                                                    v-model="editForm.tour_permission"
                                                                    id="users-list-verified">
                                                                    <option value="1">Yes</option>
                                                                    <option value="0">No</option>

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <div class="controls">
                                                                <input type="password" name="password"
                                                                    v-model="editForm.password"
                                                                    :class="{ 'is-invalid': editForm.errors.has('password'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="password">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Confirm Password</label>
                                                            <div class="controls">
                                                                <input type="password" name="con_password"
                                                                    v-model="editForm.con_password"
                                                                    :class="{ 'is-invalid': editForm.errors.has('con_password'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Confirm Password">
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
            role_id: '',
            user_data: JSON.parse(this.$localStorage.get("user")),
            token: this.$localStorage.get("d_token"),
            id: this.$route.params.id,
            roles: [],
            WingsItems: [],
            DepartmentsItems: [],
            editForm: new Form({
                name: "",
                dept_id: "",
                role_id: "",
                ad_mail: "",
                employee_id: "",
                email: "",
                phone: "",
                designation: "",
                wing_id: "",
                status: "",
                employee_status: "",
                tour_permission: ""

            }),

        };
    },
    created() {
        this.role_id = this.user_data.role_id;
        this.getItem("users/" + this.id).then(({ data }) => {
            console.log(data);
            if (data.success) {
                this.item = data.data
                this.editForm.name = this.item.name;
                this.editForm.dept_id = this.item.dept_id;
                this.editForm.role_id = this.item.role_id;
                this.editForm.phone = this.item.phone;
                this.editForm.designation = this.item.designation;
                this.editForm.ad_mail = this.item.ad_mail;
                this.editForm.employee_id = this.item.employee_id;
                this.editForm.employee_status = this.item.employee_status;
                this.editForm.email = this.item.email;
                this.editForm.wing_id = this.item.wing_id;
                this.editForm.status = this.item.status;
                this.editForm.tour_permission = this.item.tour_permission;
                this.getWing();
            }
        });
        this.dept();
        this.getRole();

    },
    methods: {
        dept() {
            this.getDepartments(this.editForm.dept_id).then(({ data }) => {
                if (data.success) {
                    this.DepartmentsItems = data.data;
                } else {
                    this.DepartmentsItems = [];
                }
            });

        },
        async getWing() {
            await axios.get(this.api_url + "wings?dept_id=" + this.editForm.dept_id, {
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
        async getItemdDpartments() {
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "departments/", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({ data }) => {
                        if (data.success) {
                            this.itemDepartments = data.data
                        }
                        loader.hide();
                        console.log(this.item);
                    });
            } catch (error) {
                loader.hide();
            }
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
        update() {

            try {
                let loader = this.$loading.show();
                this.editForm.put(this.api_url + "users/" + this.id, {
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
                    //  this.$router.push('/users');
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
