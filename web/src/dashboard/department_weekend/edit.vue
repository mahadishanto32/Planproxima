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
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"
                        ><i class="bx bx-home-alt"></i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/department' }">
                        Department
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">
                      Edit Department ({{ editForm.name }})
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
                    <h4 class="card-title">Edit Department</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <form @submit.prevent="update()">
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label>Name</label>
                              <div class="controls">
                                <input
                                  type="text"
                                  name="name"
                                  v-model="editForm.name"
                                  :class="{
                                    'is-invalid': editForm.errors.has('name'),
                                  }"
                                  class="form-control"
                                  data-validation-required-message="This field is required"
                                  placeholder=" Name"
                                />
                              </div>
                            </div>
                            <div class="form-group">
                              <label>HOD Name</label>
                              <div class="controls">
                                <input
                                  type="text"
                                  name="hod_name"
                                  v-model="editForm.hod_name"
                                  :class="{
                                    'is-invalid': editForm.errors.has('hod_name'),
                                  }"
                                  class="form-control"
                                  data-validation-required-message="This field is required"
                                  placeholder="HOD Email"
                                />
                              </div>
                            </div>
                            <div class="form-group">
                              <label>HOD Email</label>
                              <div class="controls">
                                <input
                                  type="text"
                                  name="hod_email"
                                  v-model="editForm.hod_email"
                                  :class="{
                                    'is-invalid': editForm.errors.has('hod_email'),
                                  }"
                                  class="form-control"
                                  data-validation-required-message="This field is required"
                                  placeholder="HOD Email"
                                />
                              </div>
                            </div>

                            <div class="form-group">
                              <label>CC Mail</label>
                              <div class="controls">
                                <!-- <input
                                  type="text"
                                  name="hod_email"
                                  v-model="editForm.hod_email"
                                  :class="{
                                    'is-invalid': editForm.errors.has('hod_email'),
                                  }"
                                  class="form-control"
                                  data-validation-required-message="This field is required"
                                  placeholder="HOD Email"
                                /> -->

                                <fieldset class="form-group">
                                  <multiselect 
                                  v-model="select_cc_users" 
                                  :options="cc_users" 
                                  :multiple="true" 
                                  placeholder="Select Users" 
                                  :label="'name'" 
                                  track-by="id" 
                                  :searchable="true"
                                  :close-on-select="false"
                                  :show-labels="false" 
                                  >
                                      <template slot="selection" slot-scope="{ values , isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                  </multiselect>
                              </fieldset>
                              </div>
                            </div>
                            

                            <div class="form-group">
                              <label for="Profession">Mail Allow</label>
                              <div class="controls">
                                <select
                                  id="Profession"
                                  name="mail_allow"
                                  v-model="editForm.mail_allow"
                                  :class="{
                                    'is-invalid': editForm.errors.has('mail_allow'),
                                  }"
                                  class="form-control"
                                >
                                  <option value="1">Allow</option>
                                  <option value="0">Not Allow</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Profession">Status</label>
                              <div class="controls">
                                <select
                                  id="Profession"
                                  name="status"
                                  v-model="editForm.status"
                                  :class="{
                                    'is-invalid': editForm.errors.has('status'),
                                  }"
                                  class="form-control"
                                >
                                  <option value="1">Active</option>
                                  <option value="0">Inactive</option>
                                </select>
                              </div>
                            </div>

                            <!--FACTORY LIST-->
                            <label for="Profession">Assign Factory</label><br />
                            <div
                              class="form-check form-check-inline"
                              v-for="(factory, i) in itemsFactorys"
                              :key="i"
                            >
                              <!-- <input class="form-check-input" type="checkbox"
                                                                :id="factory.id" :value="factory.id" v-model="editForm.factory_id"> -->

                              <input
                                type="checkbox"
                                :id="factory.id"
                                :checked="
                                  selected_factory.filter(
                                    (p) => p.factory_id == factory.id
                                  ).length > 0
                                "
                                v-on:change="checkArray(id,factory.id)"                             
                              />
                              <label
                                class="form-check-label"
                                for="inlineCheckbox1"
                              >
                                {{ factory.dis_name }}</label
                              >
                            </div>
                          </div>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">
                          Submit
                        </button>
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
import Multiselect from 'vue-multiselect'; 
export default {
  props: {},
  components: {
    Multiselect
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get("d_token"),
      id: this.$route.params.id,
      item: {}, 
      cc_users:[],   
      select_cc_users : [],
      selected_factory: [],
      editFactory: new Form({
        department_id:'',
        factory_id: '',
      }),

      editForm: new Form({
        name: "",
        hod_email: "",
        hod_name: "",
        status: 1,
        factory_id: [],
        mail_allow : "",
        cc_users : ""
      }),
      itemsFactorys: [],
    };
  },
  created() {
    this.getItem("departments/" + this.id).then(({ data }) => {
      if (data.success) {
        this.item = data.data;
        this.editForm.name = this.item.name;
        this.editForm.hod_email = this.item.hod_email;
        this.editForm.hod_name = this.item.hod_name;
        this.editForm.status = this.item.status; 
        this.editForm.mail_allow = this.item.mail_allow; 
        this.selected_factory = this.item.department_factory;
      }
    });
    this.ccUsers();
    this.getFactorys();
    this.assignccUsers();
  },
  methods: {
    async  ccUsers(){
      await axios
        .get(this.api_url + "cc_users", {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(({ data }) => {
          if (data.success) {
            this.cc_users = data.data; 
          }
        });
    },
    async  assignccUsers(){
      await axios
        .get(this.api_url + "assign_cc_users/"+this.id, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(({ data }) => {
          if (data.success) { 
            this.select_cc_users = data.data; 
          }
        });
    },
    checkArray(department_id,factory_id){
        // console.log(role,menu,event.target.checked ); 
        this.editFactory.department_id =  department_id ;
        this.editFactory.factory_id = factory_id;
        this.editFactory.checkStatus = event.target.checked;
        this.editFactory.post(this.api_url + "department_factory", {
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
        },(error)=>{
            console.log(error); 
        })
    },   
    update() {
      try {
        let loader = this.$loading.show();
        this.editForm.cc_users = this.select_cc_users;
        this.editForm
          .put(this.api_url + "departments/" + this.id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : "",
            },
          })
          .then(
            (res) => {
              console.log(res);
              if (res.data.success) {
                this.$toasted.show(res.data.message, {
                  theme: "bubble",
                  duration: 5000,
                  position: "bottom-right",
                });
              }
              loader.hide();
              this.$router.push("/department");
            },
            (error) => {
              console.log(error);
              loader.hide();
            }
          );
      } catch (error) {
        // loader.hide();
        console.log(error);
      }
    },

    async getFactorys() {
      let where = "?";
      await axios
        .get(this.api_url + "factorys" + where, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(({ data }) => {
          if (data.success) {
            this.itemsFactorys = data.data;

            //console.log(this.itemsFactorys)
          }
        });
    },
  },
  computed: {},
};
</script>