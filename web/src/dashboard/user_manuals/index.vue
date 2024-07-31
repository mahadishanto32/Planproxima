<template>
    <div>
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-12 mb-1 mt-0">
                        <div class="row breadcrumbs-top">
                            <div class="col-sm-9">
                                <div class="breadcrumb-wrapper col-9">
                                    <ol class="breadcrumb p-0 mb-0" >
                                        <li class="breadcrumb-item">
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> User Manuals
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

                                            <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                                <!-- NEW DEPARTMENT ENTRY -->
                                                <button type="button" v-if="role_id == 1"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <router-link class="text-white" :to="{ path: '/new_user_manuals' }"><i
                                                            class="bx bx-add-alt"></i> New Manual
                                                    </router-link>
                                                </button>
                                            </div> 
                                            <div class="barber_section layout_padding">
                                                <div class="custom-card" v-for="(row , index) in items" :key="row.id">
                                                  <div class="card-header ">
                                                   {{ row.title }} 
                                                    <div class="dropup float-right"  v-if="role_id == 1">
                                                      <span aria-expanded="false" aria-haspopup="true" class="
                                                        bx bx-dots-vertical-rounded
                                                        font-medium-3
                                                        dropdown-toggle
                                                        nav-hide-arrow
                                                        cursor-pointer
                                                      " data-toggle="dropdown" role="menu">
                                                      </span>
                                                      <div class="dropdown-menu dropdown-menu-right">                
                                                        
                                                        <router-link  :to="{
                                                            path: '/edit_user_manuals/' + row.id,
                                                          }" class="dropdown-item">
                                                              <i class="bx bx-edit-alt mr-1"> </i>
                                                              edit
                                                            </router-link>

                                                        <!-- <a @click="comment_show(row)" class="dropdown-item">
                                                          <i class="bx bx-edit-alt mr-1"> </i>
                                                          edit
                                                        </a> -->

                                                        <a @click="delete_row(row.id)"  class="dropdown-item">
                                                          <i class="bx bx-trash mr-1"> </i>
                                                          Delete
                                                        </a>
                                                      </div>
                                                    </div> 
                                                  </div>
                                                  <div class="card-body"> 
                                                    <div v-html="row.details"></div> 
                                                  </div>
                                                </div>
                                                 
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
                items: [],
                dept_id: '',
                role_id: '',
                manual: new Form({
                    title: '',
                    details: ''
                }),


            };
        },
        created() {
            this.dept_id = this.user.dept_id;
            this.role_id = this.user.role_id;
            this.getItems();

        },
        methods: {
            async getItems() {
                await axios.get(this.api_url + "user_manuals", {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({ data }) => {
                    this.items = data.data;
                });
            },
            comment_show(item) {
                //this.item = item;
                console.log('ssss', item)
                this.$modal.show("comment");
            },
            async delete_row(id) {
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "user_manuals/" + id, {
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
                            this.getItems();

                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
        },
        computed: {},
    };
</script>
<style>
    .custom-card{
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #FFFFFF;
        background-clip: border-box;
        border: 1px solid #dfe3e7;
        border-radius: 0.267rem;
        margin-top:25px
    }
    .card-header {
      background-color: #f2f2f2;
    }
</style>