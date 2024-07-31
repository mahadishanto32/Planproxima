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
                                    <li class="breadcrumb-item active"> Costs
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
                    <div  class="users-list-filter px-1"> 
                        <div class="row border rounded py-2 mb-2">
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Factory</label>
                                <fieldset class="form-group">
                                <select class="form-control"  v-on:change="summaryList()"  v-model="filterForm.factory_id"  id="users-list-verified" >
                                    <option value="">Select One</option>
                                    <option v-for="row in itemsFactorys" :key="row.id" :value="row.id" >
                                    {{ row.dis_name }}
                                    </option>
                                </select>
                                
                                </fieldset>
                            </div>
                            <div   class="col-12 col-sm-6 col-lg-2">
                                <label for="users-list-verified">Product group</label>
                                <fieldset class="form-group">
                                <select required="" class="form-control"  v-on:change="getItems()"  v-model="filterForm.summary_group_id">
                                    <option value="">Select Product group</option>
                                    <option  v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id" >{{ row.description }}</option>
                                        
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

                                        <div class="table-responsive">
                                            <table class="table table-striped"> 
                                                    <thead>  
                                                      <tr>
                                                        <th>Sl</th>   
                                                        <th>cost</th>
                                                        <th>Date</th> 
                                                        <th>Cost GL</th> 
                                                        <th>Summary Group</th>
                                                        <th>Factory</th>
                                                        <th>Remarks</th> 
                                                        
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                      <tr  v-for="(item , index ) in items" :key="item.id" > 
                                                        <td>{{ index + 1 }}</td>  
                                                        <td class="text-right"><strong>{{ item.cost }}</strong> </td>
                                                        <td>{{ item.date }} </td>  
                                                        <td>{{ item.gljoin ? item.gljoin.gl_name  : ''}} </td>  
                                                        <td>{{ item.summary_groupjoin ? item.summary_groupjoin.code  : ''}} </td>  
                                                        <td>{{ item.factoryjoin ? item.factoryjoin.fac_name  : ''}} </td>  
                                                        <td>{{ item.remarks }} </td>   
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
import { Form } from "vform"; 
import axios from "../../axios_instance";
export default {
    props: {},
    components: {
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            itemsFactorys : [],
            itemsSummaryGroup : [],
            base_url: window.base_url,
            api_url: window.api_url,
            token: this.$localStorage.get("d_token"),
            items: [],   
            status: '',
            filterForm: new Form({ 
                factory_id: "",    
                summary_group_id : "" 
            }),
        };
    },
    created() { 
        this.getFactorys(); 
       
    },
    methods: { 
        async getFactorys() {
            let where = '?';   
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
                        this.getItems(); 
                        this.itemsFactorys = data.data
                    }
                });
              
        },
        async summaryList(){
            //summary_list
            this.filterForm.post(this.api_url + "summary_list", {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            }).then((res) => {
                console.log(res);
                this.itemsSummaryGroup =  res.data.data ;  
                
            },(error)=>{
            console.log(error); 
            })
        },
        async getItems() {
            let where = '?1=1';  
            let loader = this.$loading.show();
            if(this.filterForm.factory_id){
                where += '&factory_id='+this.filterForm.factory_id ;
            }
            if(this.filterForm.summary_group_id){
                where += '&summary_group_id='+this.filterForm.summary_group_id ;
            }
            try {
                await axios
                    .get(this.api_url + "cost" + where, {
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
