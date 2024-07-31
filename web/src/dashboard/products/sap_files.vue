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
                                    <li class="breadcrumb-item active">SAP Files
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
                                                        <th>Name</th>
                                                        <th>Create by</th>
                                                        <th>Date</th>
                                                        <th>Created_at</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ item.file_name }}</td> 
                                                        <td>{{ item.userjoin ? item.userjoin.name : ''}} </td>
                                                        <td>{{ item.date }} </td>
                                                        <td>{{ item.created_at }} </td> 
                                                        <td> 
                                                            <div class="dropup">
                                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                </span>
                                                                <div class="dropdown-menu dropdown-menu-right"> 
                                                                    <!-- <a class="dropdown-item" @click="getdownload(item.id)">Delete</a> -->
                                                                    <a class="dropdown-item" @click="delete_row(item.id)"><i class="bx bx-trash mr-1"></i> Delete</a>
                                                                </div> 
                                                            </div>
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
                    .get(this.api_url + "sap_files" + where, {
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
        
        getdownload(id) {
            window.open(this.api_url + "sap_files_download/" + id, "_blank");
        },
        async deleteFile(id){
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
                    console.log(res);
                    //loader.hide(); 
                    this.$swal("Delete this file data", {
                        icon: "success",
                    });
                    
                },(error)=>{
                console.log(error);
                // loader.hide(); 
                })
        },
        async delete_row(id) {

            this.$swal({
                title: "Are you sure?",
                text:  'This file delete',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                     
                    this.deleteFile(id);
 
                } else {
                   // loader.hide(); 
                    this.$swal("Not delete this file data");
                }
                });


        },
    
    },
    computed: {},
};
</script>
