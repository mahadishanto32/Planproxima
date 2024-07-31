<template>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item"><router-link :to="{ path: '/users' }">Change Password</router-link></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section class="input-validation">
            <div class="row">
              <div class="col-md-12 center">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Change Password</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      
                      <form>
                        <div class="col-md-5">
                          <div class="row">
                            <i class="bx bx-lock mr-1" style="font-size: 150px;"></i>
                          </div>
                          <span>Note: Password should be at least 6 characters long.</span>
                        </div>
                        <br>
                        <div class="col-md-5">
                          <div v-if="apiErrors.length > 0" class="alert alert-danger">
                              <ul>
                                <li v-for="error in apiErrors" :key="error">{{ error }}</li>
                              </ul>
                            </div>
                            <div v-if="responseMessage" class="alert alert-success">{{ responseMessage }}</div>
                            

                          <div class="form-group">
                            <label>Old Password</label>
                            <div class="controls">
                              <input
                                type="password"
                                name="old_password"
                                v-model="editForm.old_password"
                                :class="{ 'is-invalid': editForm.errors.has('old_password') }"
                                class="form-control"
                                data-validation-required-message="This field is required"
                                placeholder="Old Password"
                              />
                            </div>
                            <div class="invalid-feedback">{{ editForm.errors.old_password }}</div>
                          </div>
                          <div class="form-group">
                            <label>Password</label>
                            <div class="controls">
                              <input
                                type="password"
                                name="password"
                                v-model="editForm.password"
                                :class="{ 'is-invalid': editForm.errors.has('password') }"
                                class="form-control"
                                data-validation-required-message="This field is required"
                                placeholder="Password"
                              />
                            </div>
                            <div class="invalid-feedback">{{ editForm.errors.password }}</div>
                          </div>
                          <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="controls">
                              <input
                                type="password"
                                name="password_confirmation"
                                v-model="editForm.password_confirmation"
                                :class="{ 'is-invalid': editForm.errors.has('password_confirmation') }"
                                class="form-control"
                                data-validation-required-message="This field is required"
                                placeholder="Confirm Password"
                              />
                            </div>
                            <div class="invalid-feedback">{{ editForm.errors.password_confirmation }}</div>
                          </div>
                          <a @click="update()" class="btn btn-primary">Submit</a>
                        </div>
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
  </template>
<script>
import { Form } from "vform";
import axios from "axios";

export default {
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      user_data: JSON.parse(this.$localStorage.get("user")),
      editForm: new Form({
        old_password: "",
        password: "",
        password_confirmation: "", // Make sure this field is present
      }),
      apiErrors: [], // To store the response message
      responseMessage: "", // To store success or error message
    };
  },

  methods: {
    update() {
      // Clear previous error messages
      this.apiErrors = [];
      this.responseMessage = "";

      // Prepare the data to be sent to the server
      const data = {
        old_password: this.editForm.old_password,
        password: this.editForm.password,
        password_confirmation: this.editForm.password_confirmation, // Include confirmation field
      };

      // Make an HTTP POST request to change the password
      axios
        .post(`${this.api_url}change_password`, data, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((response) => {
          // Check for success or display success message
          if (response.data.message) {
            this.responseMessage = response.data.message;
          }
          setTimeout(() => {
            this.$localStorage.remove("user");
            this.$localStorage.remove("d_token");
            this.$router.push("/login");
          }, 2000);
         
        })
        .catch((error) => {
          console.error(error);
          // Handle validation errors
          if (error.response && error.response.status === 422) {

            if(error.response.data.error){
                this.apiErrors = this.apiErrors.concat(
                    error.response.data.error 
                  );
            }
            if (error.response.data.errors) {
              for (const key in error.response.data.errors) {
                if (error.response.data.errors.hasOwnProperty(key)) {
                  this.apiErrors = this.apiErrors.concat(
                    error.response.data.errors[key]
                  );
                }
              }
            }
          }
        });
    },
  },
};
</script>