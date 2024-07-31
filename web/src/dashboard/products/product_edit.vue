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
                                     <li class="breadcrumb-item"><router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/products' }"> Products List </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Edit Product 
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
                                    <h4 class="card-title">Edit Product</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body"> 
                                        <form @submit.prevent="create()">
                                                <div class="row"> 
                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label for="Profession">Factory *</label>
                                                            <select class="form-control"  v-on:change="summaryList()"   v-model="filterForm.factory_id"  id="users-list-verified" >
                                                                <option value="">Select One</option>
                                                                <option v-for="row in itemsFactorys" :key="row.id" :value="row.id" >
                                                                {{ row.dis_name }}
                                                                </option>
                                                            </select>
                                                        </div> 
                                                    </div> 
                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label for="Profession">Product group *</label>
                                                            <div class="controls">
                                                                <select required="" v-on:change="getItems()" class="form-control" v-model="editForm.summary_group_id">
                                                                    <option value="">Select Product group</option>
                                                                    <option  v-for="row in itemsSummaryGroup" :key="row.id" :value="row.id" >{{ row.description }}</option> 
                                                                </select> 
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    
                                                </div>
                                                <div class="row"> 
                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label for="Profession">Material Code * </label>
                                                            <div class="controls">
                                                                <input type="text" name="material_code" v-model="editForm.material_code" :class="{  'is-invalid': editForm.errors.has('material_code'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Material Code">
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label for="Profession">Material Group </label>
                                                            <div class="controls">
                                                                <input type="text" name="product_group" v-model="editForm.product_group" :class="{  'is-invalid': editForm.errors.has('product_group'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Material Group">
                                                            </div>
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
                                                                                    <th>Group Name</th>
                                                                                    <th>Wastage Material</th> 
                                                                                    <th>Consumption</th>   
                                                                                    <th>Action</th>   
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody v-for="item in itemConsumption" :key="item.id"  > 
                                                                                    <tr > 
                                                                                        <td> {{ item.group_name }}</td> 
                                                                                        <td> {{ item.wastage_product_code }}  </td>
                                                                                        <td> {{ item.consumtion_material_code }} </td>  
                                                                                        <td class="text-center"> 
                                                                                            <a @click="delete_consumtion_materia(item.id)" class="dropdown-item">
                                                                                                <i class="bx bx-trash mr-1"> </i> 
                                                                                            </a>
                                                                                        </td>  
                                                                                    </tr>  
                                                                                </tbody>
                                                                              </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <!-- <div class="col-md-2"> 
                                                        <div class="form-group">
                                                           
                                                            <label for="Profession">Consumption Material</label> 
                                                                <input type="checkbox" name="is_consumption_material" v-model="editForm.is_consumption_material"  id="is_consumption_material"  value="1">  
                                                        </div> 
                                                    </div>   -->
                                                </div>
                                                <!-- <div v-if="editForm.is_consumption_material">
                                                    <div class="row"> 
                                                        <div class="col-md-6"> 
                                                            <div class="form-group">
                                                                <label for="Profession">Wastage Summary Group *</label>
                                                                <select class="form-control"  v-on:change="sumummarySubGroup()"   v-model="editForm.wastage_summary_id"  id="users-list-verified" >
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in wastage_summary" :key="row.id" :value="row.id" >
                                                                        {{ row.group_name }} ({{row.scrap_material}})
                                                                    </option>
                                                                </select>
                                                            </div> 
                                                        </div> 
                                                        <div class="col-md-6" v-if="wastage_summary_sub.length > 0"> 
                                                            <div class="form-group">
                                                                <label for="Profession">Wastage Summary Sub Group *</label>
                                                                <select class="form-control"  v-model="editForm.wastage_summary_sub_id"  id="users-list-verified" >
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in wastage_summary_sub" :key="row.id" :value="row.id" >
                                                                    {{ row.group_name }} ({{row.scrap_material}})
                                                                    </option>
                                                                </select>
                                                            </div> 
                                                        </div> 
                                                    </div>
                                                    
                                                   
                                                </div> -->
                                                    
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
//import Multiselect from 'vue-multiselect'; 
export default {
  props: { 
  },
  components: { 
   // Multiselect
    // VueRecaptcha, facebookLogin 
  },
  data() {
    return {
        items : [],
        itemConsumption : [],
        item : [],
        consumption_selects : [],
        base_url: window.base_url,
        api_url: window.api_url,    
        DepartmentsItems : [],
        id : this.$route.params.id , 
        token: this.$localStorage.get("d_token"), 
        editForm: new Form({  
            summary_group_id: '',
            is_consumption_material : false,
            wastage_summary_id : '',
            wastage_summary_sub_id : '',
            consumption_material : []
        }),
        filterForm: new Form({  
                factory_id: "",     
        }),
        itemsFactorys: [],   
        itemsSummaryGroup: [],   
        wastage_summary: [],   
        wastage_summary_sub: [],   
    };
  },
  created() {
    this.getItemSingel();
    this.getFactorys();  
  },
  methods: { 
  async getItemSingel(){ 
        await axios.get(this.api_url + "product_singel/"+this.id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then((data ) => {  
            this.item =  data.data.data ;  
            this.editForm.factory_id =  this.item.plant_id ;
            this.editForm.summary_group_id =  this.item.summary_group_id ;
            this.editForm.material_code =  this.item.material_code ;
            this.editForm.product_group =  this.item.product_group ;
            this.filterForm.factory_id =  this.item.plant_id ;
            this.summaryList();
            this.getConsumptionMaterial();
        }); 
    },
  async getConsumptionMaterial(){ 
        await axios.get(this.api_url + "consumption_material/"+this.id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then((data ) => {  
            this.itemConsumption =  data.data.data ;    
        }); 
    },
    create(){ 
      try {
        let loader = this.$loading.show();
        this.editForm.factory_id = this.filterForm.factory_id ;
        this.editForm.consumption_material = this.consumption_selects ;
        this.editForm.post(this.api_url + "products", {
            headers: {
              "Content-Type": "application/json", 
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          }).then((res) => {
              console.log(res);
              if(res.data.success){
                if(res.data.data == 1){
                    this.$swal(res.data.message, {
                            icon: "success",
                    });
                    this.editForm.material_code ='' ;    
                }else{
                    this.$toasted.show(res.data.message, {
                    theme: "bubble",
                    duration: 5000,
                    position: "bottom-right",
                });
                } 
              } 
            loader.hide();  
            //this.$router.push('/daily_work');
        },(error)=>{
          console.log(error);
           loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    },
    async getItems() {
            if(this.editForm.summary_group_id   ){ 
                let where = '?1=1';  
                if(this.editForm.summary_group_id){
                    where +='&summary_group_id='+this.editForm.summary_group_id ;
                }
                if(this.filterForm.factory_id){
                    where +='&factory_id='+this.filterForm.factory_id ;
                }
                if(this.filterForm.material_code){
                    where +='&material_code='+this.filterForm.material_code ;
                }
                let loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "products" + where, {
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
            }
        },
    async wastage_summary_group(){
               let where = '?';   
                if(this.filterForm.factory_id){
                    where +='&plant_id='+this.filterForm.factory_id ;
                    where +='&type=0';
                }
                await axios
                    .get(this.api_url + "wastage_summary" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.wastage_summary = data.data
                    }
                         
        }); 
    },
    async sumummarySubGroup(){
               let where = '?1=1';   
                if(this.editForm.wastage_summary_id){
                    where +='&grouping_id='+this.editForm.wastage_summary_id ;
                    where +='&type=1';
                }
                await axios
                    .get(this.api_url + "wastage_summary" + where, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.wastage_summary_sub = data.data
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
                this.getItems();
                
            },(error)=>{
            console.log(error); 
            })
            this.wastage_summary_group();
        },
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
                            this.itemsFactorys = data.data
                        }
                         
                    }); 
        },
 
  },
  computed: {},
};
</script>
