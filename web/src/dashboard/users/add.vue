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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/users' }"> Users </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> New User
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
                                                        <label for="Profession">Role</label>
                                                        <div class="controls">
                                                            <select  id="Profession" name="role_id"   v-model="addForm.role_id" :class="{  'is-invalid': addForm.errors.has('type'),  }" class="form-control">
                                                                <option value="">Select one</option>
                                                                <option  v-for="row in roles" :key="row.id" :value="row.id">{{ row.title}}</option>  
                                                            </select>
                                                        </div>
                                                    </div> 
                                                 
                                                    <div v-if="addForm.role_id == 5 || addForm.role_id == 6 || addForm.role_id == 7" class="form-group">
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
                                                    <div v-if="addForm.role_id == 6 || addForm.role_id == 7" class="form-group">
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
                                                        <label>Name</label>
                                                        <div class="controls">
                                                            <input type="text" name="name" v-model="addForm.name" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Name">
                                                        </div>
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Uaername</label>
                                                        <div class="controls">
                                                            <input type="text" name="email" v-model="addForm.email" :class="{  'is-invalid': addForm.errors.has('email'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Username">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <div class="controls">
                                                            <input type="password" name="password" v-model="addForm.password" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>AD Mail</label>
                                                        <div class="controls">
                                                            <input type="text" name="ad_mail" v-model="addForm.ad_mail" :class="{  'is-invalid': addForm.errors.has('ad_mail'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="AD Mail (example@ssgbd.com)">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Employee ID (HRIS User ID)</label>
                                                        <div class="controls">
                                                            <input type="text" name="employee_id" v-model="addForm.employee_id" :class="{  'is-invalid': addForm.errors.has('ad_mail'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Employee ID (HRIS ID)">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <div class="controls">
                                                            <input type="text" name="phone" v-model="addForm.phone" :class="{  'is-invalid': addForm.errors.has('phone'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Phone">
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
        this.addForm.post(this.api_url + "users", {
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
            this.$router.push('/users');
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
