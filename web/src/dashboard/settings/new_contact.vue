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
                                        <li class="breadcrumb-item  "> <router-link :to="{ path: '/users' }"> Users
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> New Contact
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
                                        <h4 class="card-title">New Contact</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="Profession">COMPANY</label>
                                                            <div class="controls">
                                                                <input type="text" id="Profession" name="role_id"
                                                                    v-model="addForm.company"
                                                                    :class="{ 'is-invalid': addForm.errors.has('company'), }"
                                                                    class="form-control" placeholder="COMPANY" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Profession">PRODUCT TYPE</label>
                                                            <div class="controls">
                                                                <input type="text" id="Profession" name="product_type"
                                                                    v-model="addForm.product_type"
                                                                    :class="{ 'is-invalid': addForm.errors.has('product_type'), }"
                                                                    class="form-control" placeholder="PRODUCT TYPE" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Profession">COUNTRY ORIGIN</label>
                                                            <div class="controls">
                                                                <!-- <input type="text" id="country_origin" name="country_origin"   
                                                            v-model="addForm.country_origin" 
                                                            :class="{  'is-invalid': addForm.errors.has('country_origin'),  }" 
                                                            class="form-control"
                                                            placeholder="COUNTRY ORIGIN"> -->

                                                                <Select2 placeholder="Select Country"
                                                                    v-model="addForm.country_origin"
                                                                    :options="CountryAry" required />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>DESIGNATION</label>
                                                            <input type="text" id="designation" name="designation"
                                                                v-model="addForm.designation"
                                                                :class="{ 'is-invalid': addForm.errors.has('designation'), }"
                                                                class="form-control" placeholder="DESIGNATION" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>CONTACT PERSON</label>
                                                            <div class="controls">
                                                                <input type="text" name="contact_person"
                                                                    v-model="addForm.contact_person"
                                                                    :class="{ 'is-invalid': addForm.errors.has('contact_person'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="CONTACT PERSON" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>MOBILE NUMBER</label>
                                                            <div class="controls">
                                                                <input type="text" name="mobile_number"
                                                                    v-model="addForm.mobile_number"
                                                                    :class="{ 'is-invalid': addForm.errors.has('mobile_number'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="MOBILE NUMBER" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>EMAIL</label>
                                                            <div class="controls">
                                                                <input type="email" name="email" v-model="addForm.email"
                                                                    :class="{ 'is-invalid': addForm.errors.has('email'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="EMAIL" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>PROJECT</label>
                                                            <div class="controls">
                                                                <input type="text" name="project" v-model="addForm.project"
                                                                    :class="{ 'is-invalid': addForm.errors.has('project'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="PROJECT" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>SEASON</label>
                                                            <div class="controls">
                                                                <input type="text" name="season" v-model="addForm.season"
                                                                    :class="{ 'is-invalid': addForm.errors.has('season'), }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="season" required>
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
                                                                <tr v-for="(schedule, index) in addForm.tasks">
                                                                    <td>{{ index + 1 }}</td>
                                                                    <td class="">
                                                                        <input type="text"
                                                                            v-model="addForm.tasks[index].column_name"
                                                                            placeholder="Column Name" class="form-control"
                                                                            required />
                                                                    </td>
                                                                    <td>
                                                                        <textarea rows="1"
                                                                            v-model="addForm.tasks[index].column_value"
                                                                            class="form-control" placeholder="Column Value"
                                                                            required></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn-success" type="button"
                                                                            @click="item_add()"
                                                                            v-if="(addForm.tasks.length - 1 == index)">
                                                                            <i class="bx bx-plus"></i>
                                                                        </button>
                                                                        <button class="btn-danger" type="button"
                                                                            @click="item_removes(index)">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
import axios from "../../axios_instance";
import { Form } from "vform";
import Select2 from 'v-select2-component';
export default {
    props: {
    },
    components: {
        'Select2': Select2,
        // VueRecaptcha, facebookLogin 
    },
    data() {
        return {
            user: JSON.parse(this.$localStorage.get("user")),
            base_url: window.base_url,
            api_url: window.api_url,
            DepartmentsItems: [],
            token: this.$localStorage.get("d_token"),
            addForm: new Form({
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
            roles: [],
            WingsItems: [],
            CountryAry: [],
        };
    },
    created() {
        this.buyer_enquiry();
        this.dept();
        this.country();
    },
    methods: {
        create() {
            try {
                let loader = this.$loading.show();
                this.addForm.user_id = this.user.id;
                this.addForm.post(this.api_url + "buyer_enquiry_lists", {
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
        dept() {
            this.getDepartments().then(({ data }) => {
                if (data.success) {
                    this.DepartmentsItems = data.data;
                }
            });
        },
        async buyer_enquiry() {
            await axios.get(this.api_url + "buyer_enquiry_lists", {
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
        async country() {
            await axios.get(this.api_url + "country_api_list", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({ data }) => {
                    this.CountryAry = data.data;
                    // this.CountryAry = data.data.filter((item, index) => {
                    //     this.CountryAry[index]['text'] = item.name.common;
                    // });
                    console.log('CountryAry:', this.CountryAry);
                });
        },
        item_add() {
            let newItem = {
                sl: 1,
                column_name: "",
                column_value: "",
            };
            this.addForm.tasks.push(newItem);
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
                        this.addForm.tasks.splice(id, 1);
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
<style></style>