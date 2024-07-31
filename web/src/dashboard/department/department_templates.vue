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
                                   <li class="breadcrumb-item"><router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Department Templates Form
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
                                  <!--  -->
                                <div class="card-content">
                                    <div class="d-inline-flex col-lg-12">
                                        <div class=" col-sm-2 col-lg-2">
                                            <label for="users-list-verified"> </label>
                                            <fieldset class="form-group">
                                                <button type="button" class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="update()" > <i class="bx bx-add-alt"></i> Update</a>
                                                </button>
                                            </fieldset>
                                        </div>                    
                                    </div>
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr> 
                                                        <th>SL</th>
                                                        <th>Dept Name</th>
                                                        <th> Regular Calculation <input type="radio" value="0"  @change="checked_regular()"  id="value_checked" v-model="regular_checked"> </th>
                                                        <th> Buttom TO Top Calculation <input type="radio" value="1"  @change="checked_regular()"  id="value_checked" v-model="regular_checked"> </th>
                                                         
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row ,index) in items" :key="row.id">
                                                        <td>{{ index + 1 }}</td>  
                                                        <td>{{ row.name }}</td>  
                                                        <td align="center">
                                                            <input type="radio"  value="0"  v-model="row.type.type">  
                                                        </td> 
                                                        <td align="center">
                                                            <input type="radio"  value="1"  v-model="row.type.type">  
                                                        </td> 
                                                    </tr> 
                                                    <tr v-if="items.length < 1">
                                                        <td colspan="4">Data not found</td>
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
// import Datepicker from 'vuejs-datepicker';
export default {
  props: { 
  },
  components: {
    // Datepicker
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        start_date: ''  ,
        end_date: '' ,
        regular_checked :  false ,
        buttom_top_checked : false,
        checkbox :  false ,
        base_url: window.base_url,
        api_url: window.api_url, 
        token: this.$localStorage.get("d_token"),
        items:[],  
        status :  '' ,
        updateForm: new Form({ 
           items : '', 
        }),
        state : {
                disabledDates: {
                    to:  new Date(2021, 0, 0), // Disable all dates up to specific date
                    from: new Date(2022, 0, 0)  // Disable all dates after specific date
                    
                }
            }
    };
  },
  created() {  
    this.getItems();
  },
  methods: {
    checked_regular(){  
        for (let index = 0; index < this.items.length; index++) { 
            this.items[index].type.type = this.regular_checked;
        } 
    },

    update(){
        let loader = this.$loading.show();
        this.updateForm.items = this.items ;  
        this.updateForm.post(this.api_url + "templates_updates", {
           headers: {
             "Content-Type": "application/json", 
             Authorization: this.token ? `Bearer ${this.token}` : ""
           },
         }).then((res) => {
            console.log(res);
            if(res.data.success){
                  this.$toasted.show(res.data.message, {
                   theme: "bubble",
                   duration: 5000,
                   position: "bottom-right",
                   });
             } 
           loader.hide(); 
           //this.$router.push('/department');
       },(error)=>{
         console.log(error);
          loader.hide(); 
       })
    },
    
    async getItems(){
        let where = '?'; 

        let loader = this.$loading.show();
        await axios.get(this.api_url + "department_templates" + where, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.items =  data.data
            loader.hide();
            console.log(this.WingsItems );   
        }); 
        
    }
  },
  computed: {},
};
</script>
