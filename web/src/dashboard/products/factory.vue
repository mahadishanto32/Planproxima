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
                                        <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Factory
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class=" col-sm-3">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
           
                <section id="basic-datatable">
                    <div class="row"> 
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard"> 
                                        <div class="table-responsive">
                                            <table class="table table-striped"> 
                                                <thead>  
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Factory code</th>
                                                        <th>Factory Name</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ item.fac_code }}</td>  
                                                        <td>{{ item.fac_name }} </td> 
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
import axios from "../../axios_instance";
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
            items: [],   
            status: '',
        };
    },
    created() { 
        this.getItems(); 
    },
    methods: { 
        async getItems() {
            let where = '?';  
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "factorys" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.items = data.data
                        }
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
