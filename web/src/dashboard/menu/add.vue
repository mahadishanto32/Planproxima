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
                                            <router-link :to="{ path: '/' }">
                                                <i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <router-link :to="{ path: '/menu_setup' }"> New Menu </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> New Menu </li>
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
                                        <h4 class="card-title">New Menu</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create">
                                                <div class="row">
                                                    <div class="col-md-8">


                                                        <div class="form-group">
                                                            <label>Menu Name</label>
                                                            <div class="controls">
                                                                <input type="text" name="menu_name"
                                                                    v-model="addForm.menu_name"
                                                                    :class="{ 'is-invalid': addForm.errors.has('menu_name') }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Menu Name" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="Wing">Parent Menu</label>
                                                            <div class="controls">
                                                                <select class="form-control" v-model="addForm.parent_id"
                                                                    id="users-list-verified">
                                                                    <option value="0">Select One</option>
                                                                    <option v-for="row in memus" :key="row.id"
                                                                        :value="row.id">
                                                                        {{ row.menu_name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Menu URL</label>
                                                            <div class="controls">
                                                                <input type="text" name="menu_url"
                                                                    v-model="addForm.menu_url"
                                                                    :class="{ 'is-invalid': addForm.errors.has('menu_url') }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Menu URL" />
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label>Menu hints</label>
                                                            <div class="controls">
                                                                <input type="text" name="menu_hints"
                                                                    v-model="addForm.menu_hints"
                                                                    :class="{ 'is-invalid': addForm.errors.has('menu_hints') }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Menu hints" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Order List</label>
                                                            <div class="controls">
                                                                <input type="number" name="sort" v-model="addForm.sort"
                                                                    :class="{ 'is-invalid': addForm.errors.has('sort') }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Menu order list" />
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "../../axios_instance";
    import { Form } from "vform";

    export default {
        data() {
            return {
                base_url: window.base_url,
                api_url: window.api_url,
                DepartmentsItems: [],
                token: this.$localStorage.get("d_token"),
                addForm: new Form({
                    'parent_id': 0,
                    'menu_name': '',
                    'menu_url': '',
                    'sort': 1,
                    'status': 1,
                    'menu_hints': '',

                }),
                memus: []
            };
        },
        created() {
            this.loadInitialData();

            this.getMainMenu();
        },
        methods: {
            async loadInitialData() {
                try {
                    await this.getRole();
                    await this.dept();
                } catch (error) {
                    console.error("Error loading initial data:", error);
                }
            },
            async create() {
                //  
                try {
                    const response = await this.addForm.post(this.api_url + "menu", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    });

                    if (response.data.success) {
                        this.$toasted.show(response.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right"
                        });
                        this.$router.push('/menu_setup');
                    }
                } catch (error) {
                    console.error("Error creating menu:", error);
                } finally {
                    // 
                }
            },
            async getMainMenu() {


                try {
                    await axios
                        .get(this.api_url + "main_menu", {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : "",
                            },
                        })
                        .then((res) => {
                            console.log(res);
                            this.memus = res.data.data;

                        });
                } catch (error) {
                }


            },


            getHeaders() {
                return {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                };
            },

        }
    };
</script>