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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/tour_plan' }"> Tour </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Edit Tour 
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
                                    <h4 class="card-title">Edit Tour</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">
                                            <div class="row">
                                               
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Point</label>
                                                        <div class="controls">
                                                            <input type="text" name="point" v-model="editForm.point"  class="form-control" data-validation-required-message="This field is required" placeholder="Point">
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Route</label> 
                                                        <div class="controls">
                                                            <input type="text" name="route_name" v-model="editForm.route_name"   placeholder="Route"  class="form-control"  >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Common Objective  </label> 
                                                        <div class="controls">
                                                            <input type="text" name="objectives" v-model="editForm.objectives"      class="form-control"  >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> 
                                                            Special Objective
                                                             </label> 
                                                        <div class="controls">
                                                            <input type="text" name="specia_objective" v-model="editForm.specia_objective"   placeholder="Specia objective"  class="form-control"  >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> 
                                                            Work With
                                                             </label> 
                                                        <div class="controls">
                                                            <input type="text" name="work_with" v-model="editForm.work_with"   placeholder="Work with"  class="form-control"  >
                                                        </div>
                                                    </div> 
                                                </div>
                                              
                                                <div class="col-md-6">
                                                  
                                                    <div class="form-group">
                                                         <label for="Profession">Work Station</label>
                                                         <div class="controls">
                                                            <select  id="Profession" name="work_station" v-model="editForm.work_station" :class="{  'is-invalid': editForm.errors.has('work_station'),  }" class="form-control">
                                                                <option value="Head Quarter">Head Quarter</option>
                                                                <option value="EX Head Quarter">EX Head Quarter</option>
                                                                <option value="Out Station">Out Station</option> 
                                                            </select>
                                                        </div>
                                                    </div> 
                                                </div> 
                                                <div class="col-md-6">

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Strategic Goal </label>  
                                                        <fieldset class="form-group">
                                                            <select id="Profession" required="required" name="work_station"
                                                                v-model="editForm.objective_id"
                                                                :class="{  'is-invalid': editForm.errors.has('month'),  }"
                                                                class="form-control">
                                                                <option value="">Select Strategic Goal</option>
                                                                <option v-for="row in objectiveList" :key="row.id"
                                                                    :value="row.id">
                                                                    {{ row.objective }}
                                                                </option>
                                                            </select>
                                                        </fieldset> 
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label> Feedback  </label> 
                                                        <div class="controls">
                                                            <vue-editor name="remarks" v-model="editForm.remarks" :class="{  'is-invalid': editForm.errors.has('remarks'),  }" ></vue-editor> 
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
//import Datepicker from 'vuejs-datepicker';
import { VueEditor } from "vue2-editor";
export default {
  props: { 
  },
  components: {
   // Datepicker,
    VueEditor
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        base_url: window.base_url,
        api_url: window.api_url,  
        token: this.$localStorage.get("d_token"),
        id : this.$route.params.id,
        user_data: JSON.parse(this.$localStorage.get("user")),
        item : [],
        objectiveList: [],
        editForm: new Form({   
            point : "",
            route_name : "",
            objectives : "",
            specia_objective : "", 
            contactperson : "",
            hq : "",
            work_with : "",
            work_station : "",
            remarks : "",
            feedback : "",
            status : 0,
            objective_id : 0 ,
            approval : 0, 
        }) 
    };
  },
  created() { 
    this.getObjective();
    this.getItem("tour_entries/"+this.id).then(({ data }) => {  
        if(data.success){
            this.item =  data.data ; 
             this.editForm.point = this.item.point ;
             this.editForm.route_name = this.item.route_name ;
             this.editForm.objectives = this.item.objectives ;
             this.editForm.specia_objective = this.item.specia_objective ; 
             this.editForm.contactperson = this.item.contactperson ;
             this.editForm.hq = this.item.hq ;
             this.editForm.work_with = this.item.work_with ;
             this.editForm.work_station = this.item.work_station ;
             this.editForm.remarks = this.item.remarks ;
             this.editForm.feedback = this.item.feedback ;
             this.editForm.status = this.item.status ;
             this.editForm.approval = this.item.approval ;
             this.editForm.objective_id = this.item.objective_id ;
            
        } 
    }); 
  },
  methods: {
    async getObjective() {  
                let where = '?1=1';
                where += '&user_id=' + this.user_data.id; 
                await axios.get(this.api_url + "tour_entrie_month_objectives" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.objectiveList = data.data
                        } 
                    });


            },
    addNewObject(){
        this.editForm.objectiveItem.push({objective: ''});
    },
    removeNewObject(i,item){
        this.$swal({
            title: "Are you sure you want to delete?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {


            if (willDelete) {
                this.editForm.objectiveItem.splice(i,1);
                if(item.id){
                    this.delete_row(item.id);
                }
               
            }
        });
    },
    async delete_row(id) { 
            await axios
                .delete(this.api_url + "tour_entrie_objectives/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    res
                }) => {
                    
                    if (res.data.success) {
                        // this.getItems();
                        // this.$toasted.show(res.data.message, {
                        //     theme: "bubble",
                        //     duration: 5000,
                        //     position: "bottom-right",
                        // });
                    } 
                });
             
        },


     create(){
     
      try {
         let loader = this.$loading.show();
        //this.editForm.data  =  this.format_Date(this.editForm.data);
        this.editForm.put(this.api_url + "tour_entries/"+ this.id , {
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
            this.$router.push('/tour_plan_entry');
        },(error)=>{
          console.log(error);
           loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    } ,
    async getItems() {  
        await axios
            .get(this.api_url + "tour_entries/"+this.id  , {
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
            });
            
        },
    
  },
  computed: {},
};
</script>
