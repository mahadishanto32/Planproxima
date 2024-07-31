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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/projects' }"> Projects </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> New Projects
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
                                    <h4 class="card-title">New user</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    

                                                 
                                                    <div  class="form-group">
                                                        <label for="Profession">Department</label>
                                                            <div class="controls">
                                                                <select class="form-control"  v-on:change="getWing()"  v-model="addForm.dept_id"  id="users-list-verified" >
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in DepartmentsItems" :key="row.id" :value="row.id" >
                                                                    {{ row.name }}
                                                                    </option>
                                                                </select>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="Profession">Wing</label>
                                                            <div class="controls">
                                                                <select class="form-control"  v-model="addForm.wing_id"  id="users-list-verified" >
                                                                    <option value="">Select One</option>
                                                                    <option v-for="row in WingsItems" :key="row.id" :value="row.id" >
                                                                    {{ row.wing_title }}
                                                                    </option>
                                                                </select>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Project Name</label>
                                                        <div class="controls">
                                                            <input type="text" name="name" v-model="addForm.name" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Name">
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
        DepartmentsItems : [],
        token: this.$localStorage.get("d_token"),
        addForm: new Form({ 
            name: "",  
            email : "",   
            dept_id : "",  
            password : "",
            phone : "",
            role_id : "",
            wing_id : "",

        }),
        roles: [] ,  
        WingsItems : [] ,
    };
  },
  created() { 
    this.getRole();
    this.dept();
  },
  methods: { 
    create(){ 
      try {
        let loader = this.$loading.show();
        this.addForm.post(this.api_url + "projects", {
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
            this.$router.push('/projects');
        },(error)=>{
          console.log(error);
            loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    },
    dept() {
      this.getDepartments().then(({ data }) => {
        if (data.success) {
          this.DepartmentsItems = data.data;
        }
      });
    }, 
 
    async getRole(){ 
        await axios.get(this.api_url + "role", {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.roles =  data.data ;
            console.log(this.roles );   
        });
    },
 
    async getWing(){ 
        await axios.get(this.api_url + "wings?dept_id="+this.addForm.dept_id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.WingsItems =  data.data ;
            console.log(this.WingsItems );   
        });
    }
  },
  computed: {},
};
</script>
