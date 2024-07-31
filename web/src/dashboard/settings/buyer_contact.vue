<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-9">
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active"> Buyer Contact List
                    </li>
                  </ol>
                </div>
              </div>
              <div class=" col-sm-3">
                <router-link class="btn btn-primary add-btn" :to="{ path: '/new_contact' }"><i class="bx bx-add-alt"></i>
                  Add New
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section class="input-validation">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Company</th>
                        <th scope="col">Product Type</th>
                        <th scope="col">Country Origin</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">Email</th>
                        <th scope="col">Project</th>
                        <th scope="col">Season</th>
                        <th scope="col">Owner</th>
                        <th scope="col" colspan="3">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- WORK SCHEDULE -->
                      <tr v-for="(row, index) in item" :key="index">
                        <td scope="col">{{ row.company }}</td>
                        <td scope="col">{{ row.product_type }}</td>
                        <td scope="col">{{ row.country_origin }}</td>
                        <td scope="col">{{ row.designation }}</td>
                        <td scope="col">{{ row.contact_person }}</td>
                        <td scope="col">{{ row.mobile_number }}</td>
                        <td scope="col">{{ row.email }}</td>
                        <td scope="col">{{ row.project }}</td>
                        <td scope="col">{{ row.season }}</td>
                        <td scope="col">{{ row.name }} </td>
                        <td scope="col">                        
                          <button class="btn btn-primary " @click="modalShow(row)">
                            <i class="bx bx-add-alt"></i>
                            Share
                          </button>                      
                        </td>
                        <td>
                          <router-link class="dropdown-item" :to="{ path: '/contact_details/' + row.id }"><i
                                  class="btn btn-success"> Details</i>
                          </router-link>                             
                        </td>
                        <td scope="col" v-if="row.user_id == user_data.id">
                          <div class="dropup">
                            <span
                              class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                            </span>
                            <div class="dropdown-menu dropdown-menu-right">
                              <router-link class="dropdown-item" :to="{ path: '/edit_contact/' + row.id }"><i
                                  class="bx bx-edit-alt mr-1"></i> Edit
                              </router-link>                              
                              <a class="dropdown-item" @click="delete_row(row.id)"><i class="bx bx-trash mr-1"></i>
                                Delete</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <!-- Input Validation end -->
        </div>
      </div>
    </div>
    <div>
      <!-- <quasar-tiptap v-bind="options" @update="onUpdate" /> -->
    </div>
    <modal width="60%" height="600px" style="padding:50px" name="modalPopup">
      <i @click="hiddenPopup()" class="bx bx-x-circle  x-circle"></i>
      <div class="app-content">
        <div class="card">
          <div class="content-body">
            <div class="users-list-filter px-1">
              <div class="col-sm-4 col-lg-2" >
                <label for="users-list-verified">Department</label>
                <fieldset class="form-group">
                  <select class="form-control" v-on:change="depUserList()" v-model="filterForm.dept_id"
                    id="users-list-verified">
                    <option value="">Select One</option>
                    <option v-for="row in deptItems" :key="row.id" :value="row.id">
                      {{ row.name }}
                    </option>
                  </select>
                </fieldset>
              </div>              
            </div>
          </div>          
          <div class="col-sm-6">
          </div>
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th class="text-center">
                  User
                </th>
                <th>
                  Asssign
                </th>
              </tr>
              <tr v-for="(row, index) in userList" :key="index">
                <td>{{row.name}}</td>
                <td><input type="checkbox" @change="ShareCheck($event , row)"
                  :checked="checkeFunction(row)"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>    
  </div>
</template>


<script>
import axios from "../../axios_instance";
import { Form } from "vform";
export default {
  props: {},
  components: {
  },
  data() {
    return {
      deptItems: [],
      token: this.$localStorage.get("d_token"),
      base_url: window.base_url,
      api_url: window.api_url,
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: '',
      user: JSON.parse(this.$localStorage.get("user")),
      is_login: false,
      user_type: null,
      route_list: [],
      selected_route_list: [],
      item: [],
      userList: [],
      modalItem:[],
      shareIdList:[],
      filterForm: new Form({
        'dept_id' : ''
      }),
      updateForm: new Form({
        'user_id':'',
        'b_id':'',
        'status': ''
      })
    };
  },
  created() {
    this.getItems();
    this.getDepartmentsAll();
  },
  methods: {
    async getItems() {
      await axios.get(this.api_url + "buyer_enquiry_lists", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
        .then(({ data }) => {
          this.item = data.data;
          // console.log(data.data );   
        });
    },
    async delete_row(id) {
      console.log(id);
      let loader = this.$loading.show();
      try {
        await axios
          .delete(this.api_url + "buyer_enquiry_lists/" + id, {
            headers: {
              "Content-Type": "application/json",
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
          })
          .then(({
            res
          }) => {
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: "bubble",
                duration: 5000,
                position: "bottom-right",
              });
            }
            this.getItems();
            loader.hide();
          });
      } catch (error) {
        loader.hide();
      }
    },
    async getDepartmentsAll() {
      // console.log('dddddeeetttyyyuuuuuuu' );
      let where = "?";
      if (this.filterForm.dept_id) {
        where += "dept_id=" + this.filterForm.dept_id;
      }
      await axios.get(this.api_url + "all_dept" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })      
      .then(({ data }) => {
        this.deptItems = data.data;
        // console.log(data.data );   
      });
    },    
    modalShow(item){
      this.modalItem = item;
      this.$modal.show("modalPopup");  
      this.depUserList();    
    },
    async depUserList(){
      let where = '?1=1';
      if(this.filterForm.dept_id){
        where += "dept_id=" + this.filterForm.dept_id;
      }else{
        where += '&dept_id=' + this.user_data.dept_id;
      }      
      where += '&b_id=' + this.modalItem.id;
      await axios.get(this.api_url + "dept_user" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
      .then(({ data }) => {
        this.userList = data.data.dept_data;
        this.shareIdList = data.data.contactShare;
      });
    },
    hiddenPopup(){
      this.$modal.hide('modalPopup');
    },
    async ShareCheck(e , item){ 
      let where = '?1=1';
      where += '&user_id=' + item.id;
      where += '&b_id=' + this.modalItem.id;;
      where += '&status=' + e.target.checked;
      
      await axios.get(this.api_url + "buyer_contact_assign" + where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : ""
        },
      })
      .then(({ data }) => {
        this.$toasted.show('Contact Share Updated', {
                    theme: "bubble",
                    duration: 5000,
                    position: "bottom-right",
        });
      });               
    },
    checkeFunction(data){
      let check = false;
      this.shareIdList.filter(function (item, index) { 
        if(item.user_id == data.id){
          console.log(item.user_id  , data.id);
          check =  true
        }
      })
      return check;
    }
  },
  computed: {},
};
</script>

<style scoped></style>