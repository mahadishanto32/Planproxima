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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/tour_plan_objectives' }">  Objective </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Edit objective ({{editForm.objective}})
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
                                    <h4 class="card-title">Edit objective</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="update()">
                                            <div class="row">
                                               
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Month</label>
                                                        <div class="controls">
                                                            <fieldset class="form-group">
                                                                <select  id="Profession" name="work_station" v-model="editForm.month" :class="{  'is-invalid': editForm.errors.has('month'),  }" class="form-control">
                                                                    <option value="">Select Month</option>
                                                                    <option v-for="row in months" :key="row.id" :value="row.id">
                                                                        {{ row.name }}
                                                                      </option>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                    </div> 
                                                </div> 
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>objective</label>
                                                        <div class="controls">
                                                            <input type="text" name="objectives" v-model="editForm.objective" :class="{  'is-invalid': editForm.errors.has('objective'),  }" class="form-control" data-validation-required-message="This field is required" placeholder=" Name">
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
import { Form } from "vform"; 
//import axios from "../../axios_instance";
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
        id : this.$route.params.id,
        item : {}, 
        dept_id : '',
        WingsUser : [],
        editForm: new Form({ 
            month: "", 
            objective : ""
            
        })
    };
  },
  created() {  
    this.getItem("tour_entrie_objectives/"+this.id).then(({ data }) => {  
       
         if(data.success){
              this.item =  data.data ;  
              this.editForm.month = this.item.month ; 
              this.editForm.objective = this.item.objective ; 
          } 
     }); 
     
 
  },
  methods: {  
    
    update(){ 
      try {
         let loader = this.$loading.show();
        //this.editForm.dept_id  =  40 ; 
        this.editForm.put(this.api_url + "tour_entrie_objectives/"+this.id, {
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
            this.$router.push('/tour_plan_objectives');
        },(error)=>{
          console.log(error);
           loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    }
  },
  computed: {},
};
</script>
