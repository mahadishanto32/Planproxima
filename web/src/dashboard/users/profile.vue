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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/users' }"> Profile Update </router-link>
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
                                    <h4 class="card-title">Profile Update</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form  >
                                            <div class="row">
                                                <div class="col-md-8">
                                                   
                                                 
                                                    
                                                   
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <div class="controls">
                                                            <input type="text" name="name" v-model="editForm.name" :class="{  'is-invalid': editForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Name">
                                                        </div>
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Username</label>
                                                        <div class="controls">
                                                            <input readonly type="text" name="email" v-model="editForm.email" :class="{  'is-invalid': editForm.errors.has('email'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Email">
                                                        </div>
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Phone</label>
                                                        <div class="controls">
                                                            <input type="text" name="phone" v-model="editForm.phone" :class="{  'is-invalid': editForm.errors.has('phone'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Phone">
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label>Password</label>
                                                        <div class="controls">
                                                            <input type="password" name="password" v-model="editForm.password" :class="{  'is-invalid': editForm.errors.has('password'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Confirm Password</label>
                                                        <div class="controls">
                                                            <input type="password" name="con_password" v-model="editForm.con_password" :class="{  'is-invalid': editForm.errors.has('con_password'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Confirm Password">
                                                        </div>
                                                    </div>                                                     -->

                                                    <div class="form-group">
                                                        <label>AD Mail</label>
                                                        <div class="controls">
                                                            <input type="text" name="ad_mail" v-model="editForm.ad_mail" :class="{  'is-invalid': editForm.errors.has('ad_mail'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="AD Mail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Employee ID (HRIS User ID)</label>
                                                        <div class="controls">
                                                            <input type="text" name="employee_id" v-model="editForm.employee_id" :class="{  'is-invalid': editForm.errors.has('ad_mail'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="employee id">
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <a @click="update()" class="btn btn-primary">Submit</a>
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
        user_data: JSON.parse(this.$localStorage.get("user")),
        id : this.$route.params.id,
        roles : [],
        WingsItems : [],
        DepartmentsItems : [],
        editForm: new Form({ 
            name: "",  
            phone :"",
            // password : "",
            // con_password : "",
            ad_mail : "",
            employee_id : "",

        }),
      
    };
  },
  created() {  
    this.getItem("users/"+this.user_data.id).then(({ data }) => {  
        console.log(data);
         if(data.success){
            this.item =  data.data 
            this.editForm.name =  this.item.name ;
            this.editForm.dept_id =  this.item.dept_id ;
            this.editForm.role_id =  this.item.role_id ;  
            this.editForm.phone =  this.item.phone ;  
            this.editForm.ad_mail =  this.item.ad_mail ;  
            this.editForm.employee_id =  this.item.employee_id ;  
            this.editForm.email =  this.item.email ;  
            this.editForm.wing_id =  this.item.wing_id ;  
          } 
     }); 
      
    
  },
  methods: {
        
 
    update(){
     
      try {
        let loader = this.$loading.show();
        this.editForm.post(this.api_url + "profile_update/"+this.user_data.id, {
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
           // this.$router.push('/users');
           console.log('test Here',res.data);
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
