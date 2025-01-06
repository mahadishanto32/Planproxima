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
                                        <li class="breadcrumb-item  "> <router-link :to="{ path: '/user_group' }">
                                                User Group </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> New User Group
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
                                        <h4 class="card-title">New user Group</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>name</label>
                                                            <div class="controls">
                                                                <input type="text" v-model="addForm.name"
                                                                    :class="{  'is-invalid': addForm.errors.has('name'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <div class="controls">
                                                                <input type="text" v-model="addForm.title"
                                                                    :class="{  'is-invalid': addForm.errors.has('title'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Name">
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
                token: this.$localStorage.get("d_token"),
                addForm: new Form({
                    name: "",
                    title: "",
                    guard_name: "web"

                }),

            };
        },
        created() {
        },
        methods: {
            create() {
                try {

                    this.addForm.post(this.api_url + "usergroup", {
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
                        //   
                        this.$router.push('/user_group');
                    }, (error) => {
                        console.log(error);
                        //  
                    })
                } catch (error) {
                    // //  
                    console.log(error);
                }
            },

        },
        computed: {},
    };
</script>