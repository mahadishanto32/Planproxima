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
                                            <router-link :to="{ path: '/users' }"> Buyer Contact </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Details 
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
                                        <h4 class="card-title">Buyer Contact</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="update()">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="Profession">COMPANY</label>
                                                            <div class="controls">
                                                                <input readonly type="text" id="Profession" name="role_id"
                                                                    v-model="editForm.company"
                                                                    :class="{ 'is-invalid': editForm.errors.has('company'), }"
                                                                    class="form-control" placeholder="COMPANY">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Profession">PRODUCT TYPE</label>
                                                            <div class="controls">
                                                                <input readonly  type="text" id="Profession" name="product_type"
                                                                    v-model="editForm.product_type"
                                                                    :class="{ 'is-invalid': editForm.errors.has('product_type'), }"
                                                                    class="form-control" placeholder="PRODUCT TYPE">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Profession">COUNTRY ORIGIN</label>
                                                            <div class="controls">
                                                                <input readonly type="text" id="country_origin" name="country_origin"
                                                                    v-model="editForm.country_origin"
                                                                    :class="{ 'is-invalid': editForm.errors.has('country_origin'), }"
                                                                    class="form-control" placeholder="COUNTRY ORIGIN"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>DESIGNATION</label>
                                                            <input readonly type="text" id="designation" name="designation"
                                                                v-model="editForm.designation"
                                                                :class="{ 'is-invalid': editForm.errors.has('designation'), }"
                                                                class="form-control" placeholder="DESIGNATION">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>CONTACT PERSON</label>
                                                            <div class="controls">
                                                                <input readonly type="text" name="contact_person"
                                                                    v-model="editForm.contact_person"
                                                                    :class="{ 'is-invalid': editForm.errors.has('contact_person'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="CONTACT PERSON">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>MOBILE NUMBER</label>
                                                            <div class="controls">
                                                                <input readonly type="text" name="mobile_number"
                                                                    v-model="editForm.mobile_number"
                                                                    :class="{ 'is-invalid': editForm.errors.has('mobile_number'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="MOBILE NUMBER">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>EMAIL</label>
                                                            <div class="controls">
                                                                <input readonly type="text" name="email" v-model="editForm.email"
                                                                    disabled
                                                                    :class="{ 'is-invalid': editForm.errors.has('email'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="EMAIL">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>PROJECT</label>
                                                            <div class="controls">
                                                                <input readonly type="text" name="project" v-model="editForm.project"
                                                                    :class="{ 'is-invalid': editForm.errors.has('project'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="PROJECT">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>SEASON</label>
                                                            <div class="controls">
                                                                <input readonly type="text" name="season" v-model="editForm.season"
                                                                    :class="{ 'is-invalid': editForm.errors.has('season'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="season">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 d-flex justify-content-center">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th style="width: 4px;">No</th>
                                                                    <th>Column Name</th>
                                                                    <th>Value </th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(schedule, index) in editForm.tasks">
                                                                    <td>{{ index + 1 }}</td>
                                                                    <td class="">
                                                                        <input readonly type="text"
                                                                            v-model="editForm.tasks[index].column_name"
                                                                            placeholder="Column Name" class="form-control"
                                                                            required />
                                                                    </td>
                                                                    <td>
                                                                        <textarea rows="1" readonly
                                                                            v-model="editForm.tasks[index].column_value"
                                                                            class="form-control" placeholder="Column Value"
                                                                            required></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <!-- <button class="btn-success" type="button"
                                                                            @click="item_add()"
                                                                            v-if="(editForm.tasks.length - 1 == index)">
                                                                            <i class="bx bx-plus"></i>
                                                                        </button>
                                                                        <button class="btn-danger" type="button"
                                                                            @click="item_removes(index)">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button> -->
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Input readonly Validation end -->
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
                company: "",
                product_type: "",
                country_origin: "",
                designation: "",
                contact_person: "",
                mobile_number: "",
                email: "",
                project: "",
                season: "",
                user_id: '',
                tasks: [
                    {
                        sl: 1,
                        column_name: "",
                        column_value: "",
                    }
                ]                
            }),

        };
    },
    created() {
        this.role_id = this.user_data.role_id;
        this.getItem("buyer_enquiry_lists/" + this.id).then(({ data }) => {
            console.log(data);
            if (data.success) {
                this.item = data.data
                this.editForm.company = this.item.company;
                this.editForm.product_type = this.item.product_type;
                this.editForm.country_origin = this.item.country_origin;
                this.editForm.designation = this.item.designation;
                this.editForm.designation = this.item.designation;
                this.editForm.contact_person = this.item.contact_person;
                this.editForm.mobile_number = this.item.mobile_number;
                this.editForm.email = this.item.email;
                this.editForm.email = this.item.email;
                this.editForm.project = this.item.project;
                this.editForm.season = this.item.season;
                this.editForm.user_id = this.item.user_id;
                this.editForm.tasks = this.item.buyer_columnjoin;
                this.getWing();
            }
        });
        // this.dept();
        // this.getRole();

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
                this.editForm.put(this.api_url + "buyer_enquiry_lists/" + this.id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                }).then((res) => {
                    // console.log(res);
                    if (res.data.success) {
                        this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
                    }
                    loader.hide();
                    this.$router.push('/buyer_contact');
                }, (error) => {
                    console.log(error);
                    loader.hide();
                })
            } catch (error) {
                // loader.hide(); 
                console.log(error);
            }
        },
        item_add() {
            let newItem = {
                sl: 1,
                column_name: "",
                column_value: "",
            };
            this.editForm.tasks.push(newItem);
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
                        this.editForm.tasks.splice(id, 1);
                        // this.$swal("Your item has been deleted!", {
                        // icon: "success",
                        // });
                    }
                });
        },        
    },
    computed: {},
};
</script>
