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
                                    <li class="breadcrumb-item active"> New Strategic Goal 
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
                                    <h4 class="card-title">New Tour (Strategic Goal)</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                         <label for="Profession">Months</label>
                                                         <div class="controls">
                                                            <select  id="Profession" name="work_station" 
                                                            v-model="addForm.month" :class="{  'is-invalid': addForm.errors.has('month'),  }" 
                                                            class="form-control"
                                                            required>
                                                                <option value="">Select Month</option>
                                                                <option v-for="row in months" :key="row.id" :value="row.id">
                                                                    {{ row.name }}
                                                                  </option>
                                                            </select>
                                                        </div>
                                                    </div> 
                                                </div> 
                                            </div>
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Strategic Goal </label> 
                                                        <!-- <div class="controls"  v-for="(object ,index) in addForm.objectiveslist" :key="index">
                                                            <input type="text" name="work_with" v-model="addForm.objectiveslist[index].objective" placeholder="Objective"  class="form-control"  >
                                                        </div>  -->

                                                        <table class="table table-bordered table-sm">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Strategic Goal </th> 
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                                <template v-for="(row , index) in addForm.objectiveslist">
                                                                    <tr :key="index">
                                                                        <td>{{ index+1 }}</td>
                                                                        <td> 
                                                                            <input placeholder="Strategic Goal" style="width: 100%;" type="text" value=""
                                                                                v-model="addForm.objectiveslist[index].objective" />
                                                                        </td> 
                                                                        <td>
                                                                            <button class="btn-success" v-if="(addForm.objectiveslist.length -1 == index) && addForm.objectiveslist.length < 5" type="button" @click="addNewObject()" ><i class="bx bx-plus"></i></button>
                                                                            <button  class="btn-danger" v-if="index !=0" type="button" @click="removeNewObject(index)"  ><i  class="bx bx-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>

                                                        </table> 
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
//import axios from "../../axios_instance"; 
import { Form } from "vform";  
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
        user_data: JSON.parse(this.$localStorage.get("user")),
        addForm: new Form({   
            month :'',
            objectiveslist : [{
                objective : ''
            }],
            
        }) 
    };
  },
  created() { 
  },
  methods: {
    addNewObject(){
        this.addForm.objectiveslist.push({objective: ''});
    },
    removeNewObject(i){
        this.$swal({
            title: "Are you sure you want to delete?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                this.addForm.objectiveslist.splice(i,1);
            }
        });
    },
     create(){
     
      try {
         let loader = this.$loading.show();
        //this.addForm.data  =  this.format_Date(this.addForm.data);
        this.addForm.post(this.api_url + "tour_entrie_objectives", {
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
