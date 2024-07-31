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
                                    <li class="breadcrumb-item active">Wastage summary group
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
                                                        <th>Group Name</th>
                                                        <th>Scrap Material</th>
                                                        <th>Plant</th>  
                                                        <th>Consumption</th>   
                                                    </tr>
                                                    </thead>
                                                    <tbody v-for="item in items" :key="item.id"  > 
                                                        <tr > 
                                                            <td> {{ item.group_name  }}</td> 
                                                            <td> {{ item.scrap_material }}</td>
                                                            <td> {{ item.plant }}</td>  
                                                            <td>
                                                                <table class="table table-striped"> 
                                                                    
                                                                    <tr v-for="row in item.consumption" :key="row.id"   >   
                                                                        <td> {{row.material_code}}</td> 
                                                                        <td> {{row.description}}</td> 
                                                                    </tr>  
                                                                </table>
                                                            </td>  
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
            id : this.$route.params.id,
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
         
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "wastage_summary_details/" + this.$route.params.id , {
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
        async delete_row(id) {
            console.log(id);
            let loader = this.$loading.show();
            try {
                await axios
                    .get(this.api_url + "sap_files_delete/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        res
                    }) => {
                        if (res.data.success) {
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
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
