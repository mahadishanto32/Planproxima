<template>
    <div class="app-content content"> 
        <div class="content-wrapper"> 
            <div class="content-body">
              <section id="auth-login" class="row flexbox-container">
                  <div class="col-xl-10 col-12">
                      <div class="card bg-authentication mb-0">
                          <div class="row m-0">
                              <!-- left section-login -->
                              <div class="col-md-6 col-6 px-0">
                                  <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                      <div class="card-header pb-1">
                                          <div class="card-title">
                                              <h4 class="text-center mb-2">Please Update Your Organization Profile </h4>
                                          </div>
                                      </div>
                                      <div class="card-content">
                                          <div class="card-body">
                                              
                                              <div class="divider">
                                                  <div class="divider-text text-uppercase text-muted"><small>Update Organization Profile</small>
                                                  </div>
                                              </div> 
                                              <form @submit.prevent="update_admail()">
                                                  <div class="form-group mb-50">
                                                      <label class="text-bold-600" for="exampleInputEmail1">Email address</label>
                                                      <input type="text" name="email" v-model="loginForm.email" :class="{  'is-invalid': loginForm.errors.has('email'),  }" class="form-control" id="exampleInputEmail1" placeholder="Email address" readonly></div>
                                                  <div class="form-group">
                                                      <label class="text-bold-600" for="exampleInputPassword1">Organization Mail</label>
                                                      <input type="email" name="Admail" v-model="loginForm.admail" :class="{  'is-invalid': loginForm.errors.has('admail'),  }"  class="form-control" id="exampleInputPassword1" placeholder="Admail">
                                                  </div>
                                                  <div class="form-group">
                                                      <label class="text-bold-600" for="exampleInputPassword1">Employee ID</label>
                                                      <input type="number" name="employee_id" v-model="loginForm.employee_id" :class="{  'is-invalid': loginForm.errors.has('employee_id'),  }"  class="form-control" id="exampleInputPassword1" placeholder="employee Code">
                                                  </div>

                                                  <div class="form-group">
                                                      <label class="text-bold-600" for="exampleInputPassword1">Phone Number</label>
                                                      <input type="text" name="employee_id" v-model="loginForm.phone_number" :class="{  'is-invalid': loginForm.errors.has('employee_id'),  }"  class="form-control" id="exampleInputPassword1" placeholder="Phone Number">
                                                  </div>

                                                  <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                      <div class="text-left"> 
                                                      </div>
                                                      <!-- <div class="text-right"><a class="card-link" @click="poup()"><small>Forgot Password?</small></a></div> -->
                                                  </div>
                                                  <button type="submit" class="btn btn-primary glow w-100 position-relative">Update<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                              </form>
                                              <hr> 
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <!-- right section image -->
                              
                          </div>
                      </div>
                  </div>
                  <modal width="65%" height="40%" style="padding:50px" name="popup-singel">
                    <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content "> 
                      <h4>Forgot Password</h4> 
                            <table class="table table-bordered table-striped table-sm">
                               <tbody   >  
                                  <tr v-if="step == 1"> 
                                      <th colspan="4" class="text-center"> 
                                          <input class="form-control"  v-model="forget.email" name="email" placeholder="Username"  > 
                                      </th>
                                      <th class="text-center">
                                        <button @click="forgotPassword()"  class="btn btn-success">Submit</button>
                                     </th> 
                                  </tr>
                                  <tr v-if="step == 2"> 
                                    <th colspan="4" class="text-center"> 
                                        <input class="form-control"  v-model="forget.code" name="code" placeholder="OTP Code"  > 
                                    </th>
                                    <th class="text-center">
                                      <button @click="forgotPasswordCode()"  class="btn btn-success">Submit</button>
                                   </th> 
                                </tr>
                                <tr v-if="step == 3"> 
                                  <th colspan="4" class="text-center"> 
                                      <input class="form-control"  v-model="forget.password" name="code" placeholder="Password"  > 
                                  </th>
                                  <th colspan="4" class="text-center"> 
                                      <input class="form-control"  v-model="forget.confirm_password" name="code" placeholder="Confirm Password"  > 
                                  </th>
                                  <th class="text-center">
                                    <button @click="forgotPasswordNewPass()"  class="btn btn-success">Submit</button>
                                 </th> 
                              </tr>
                               </tbody> 
                            </table> 
                       </div>
                 </modal>
              </section>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "./axios_instance";
import { Form } from "vform";
export default {
  name: "App",
  base_url: window.base_url,
  components: { 
  },
  data() {
    return { 
      api_url: window.api_url,
      base_url: window.base_url,
      step : 1 ,
      token: this.$localStorage.get("d_token"),
      loginForm: new Form({ 
        email: "", 
        password: "", 
        admail : "",
        employee_id : "",
        phone_number : "",
      }), 
      forget: new Form({ 
        email: "",
        code : "",
        password : "",
        confirm_password : "" ,
        admail : "",
        employee_id : "",
      }), 
      items : [],
    };
  },
  methods: {
    poup(){
        this.$modal.show("popup-singel"); 
    },
    hide_pop() {
      this.step = 1; 
        this.$modal.hide("popup-singel");
    },
    async getItems() {
        let where = '?1=1';
        let loader = this.$loading.show();
        try {
            await axios
                .get(this.api_url + "autenticate_user" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    if (data.success) {
                        this.items = data.data;
                        this.loginForm.admail = this.items.ad_mail;
                        this.loginForm.email = this.items.email;
                        this.loginForm.employee_id = this.items.email;
                        this.loginForm.phone_number = this.phone;
                        console.log('user list',this.items);
                    }
                    loader.hide();
                });
        } catch (error) {
            loader.hide();
        }
    },
    update_admail(){
      try {
         let loader = this.$loading.show();
        this.loginForm.post(this.api_url + "update_admail",{
                headers: {
                  "Content-Type": "application/json",  
                  Authorization: this.token ? `Bearer ${this.token}` : ""
                  }, 
                }).then((res) => {
                console.log('reposnce',res.data);
          if(res.data.status == 0){
            this.$toasted.show(res.data.message, {
              theme: "outline",
              duration: 5000,
              position: "top-right",
            }); 
            // this.$localStorage.set("d_token", res.data.access_token);
            // this.$localStorage.set("user", JSON.stringify(res.data.user)); 
            this.getTargetAchievement();
           // console.log(res.data.user);
            this.$router.push("/home/login"); 
            //this.$router.go("/home/l");
          }
          if(res.data.status == 1){
            this.$toasted.show(res.data.message, {
              theme: "outline",
              duration: 5000,
              position: "top-right",
            }); 
            // this.$localStorage.set("d_token", res.data.access_token);
            // this.$localStorage.set("user", JSON.stringify(res.data.user)); 
            this.getTargetAchievement();
            // console.log(res.data.user);
            // this.$router.push("/home/login"); 
            //this.$router.go("/home/l");
          }
          loader.hide(); 
        },(error)=>{
          console.log(error);
           loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    },
    async getTargetAchievement(){  
                let  where = '?' ;
                let token =  this.$localStorage.get("d_token")
                
                    await axios
                        .get(this.api_url + "dashboard" + where, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: token ? `Bearer ${token}` : ""
                            },
                        })
                        .then(({
                            data
                        }) => {
                            if (data.success) {  
                                    let achievement = data.data.achievement ;
                                    let target = data.data.target ; 
                                    this.$localStorage.set("achievement", JSON.stringify(achievement) );
                                    this.$localStorage.set("target", JSON.stringify(target) );
                                 
                            }
                            
                        });
                
           // }, 3000);
    },
  },
  created() {
    this.getItems();
  },
};
</script>

<style>
input.form-control.input_custom_2.is-invalid {
  border-color: #f00;
}
</style>
