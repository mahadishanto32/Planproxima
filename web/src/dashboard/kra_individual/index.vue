<template>
  <div>
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-1 mt-0">
            <div class="row breadcrumbs-top">
              <div class="col-sm-9">
                <div class="breadcrumb-wrapper col-9">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item">
                      <router-link :to="{ path: '/' }"
                        ><i class="bx bx-home-alt"></i
                      ></router-link>
                    </li>
                    <li class="breadcrumb-item active">
                      Individual BPT update status (Monthly)
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="(deptItems.length > 1) || (user_data.dept_id == 6)"
                >
                  <label for="users-list-verified">Department</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="getItems()"
                      v-model="filterForm.dept_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in deptItems"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Year</label>
                  <fieldset class="form-group">
                    <select
                      id="Profession"
                      v-model="filterForm.year"
                      name="year"
                      class="form-control"
                    >
                      <!-- <option value="2019">2019</option> -->
                      <option value="2020">2020</option>
                      <option value="2021">2021</option>
                      <option value="2022">2022</option>
                      <option value="2023">2023</option>
                      <option value="2024">2023-2024</option>
                    </select>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                  <label for="users-list-verified">Month</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="getItems()"
                      v-model="filterForm.month"
                      id="users-list-verified"
                    >
                      <option value="">Select month</option>
                      <option value="1">Jan</option>
                      <option value="2">Feb</option>
                      <option value="3">Mar</option>
                      <option value="4">Apr</option>
                      <option value="5">May</option>
                      <option value="6">Jun</option>
                      <option value="7">Jul</option>
                      <option value="8">Aug</option>
                      <option value="9">Sep</option>
                      <option value="10">Oct</option>
                      <option value="11">Nov</option>
                      <option value="12">Dec</option>
                    </select>
                  </fieldset>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="card">
                        <div class="card-content">
                          <div class="card-body card-dashboard">
                            <div class="table-responsive">
                              <table class="table table-bordered table-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Department name</th>
                                    <th class="text-center">Update Status ( Dept.)</th> 
                                    <th class="text-center">No. of employee</th>
                                    <th class="text-center">Upload KRA</th>
                                    <th class="text-center">Upload KRA Due</th>
                                    <th class="text-center">Achv. Update</th>
                                    <th class="text-center">Achv. Update Due</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                  <tr
                                    v-for="(row, index) in items"
                                    :key="row.id"
                                  >
                                    <td>{{ (index += 1) }}</td>
                                    <td>{{ row.name }}</td>
                                    <td>{{ row.dept_upload_kra > 0 ? 'Updated' : 'Not Updated' }}</td>
                                    <td >{{ row.users }}</td>
                                    <td >{{ row.upload_kra }}</td>
                                    <td @click="pop_up(1,row.id)">
                                      <a href="#">{{ (row.users - row.upload_kra) }}</a>
                                    </td>
                                    <td >{{ row.kra_updated }}</td>
                                    <td @click="pop_up(0,row.id)"><a href="#">{{ row.kra_due }}</a></td>
                                  </tr>
                                  <tr>
                                    <th class="text-center" colspan="2">Total</th>
                                    <th class="text-center"> </th>
                                    <th class="text-center">{{total_emp}}</th>
                                    <th class="text-center">{{total_upload_kra}}</th>
                                    <th class="text-center">{{(total_emp - total_upload_kra)}}</th>
                                    <th class="text-center">{{updated_emp}}</th>
                                    <th class="text-center">{{due_emp}}</th>
                                  </tr>
                                </tbody>
                              </table>

                              <modal width="60%" height="70%" style="padding:50px" name="pop-up" >
                                <i @click="pop_hide()" class="bx bx-x-circle  x-circle"></i>
                                <div class="app-content ">
                                  <div class="card">
                                    <nav>
                                      <div id="nav-tab" role="tablist" class="nav nav-tabs">
                                        <a id="nav-home-tab" data-toggle="tab" href="#nav-home" 
                                        v-if="tab==0"
                                        role="tab" aria-controls="nav-home" 
                                        aria-selected="true" 
                                        class="nav-item nav-link "
                                        :class="{'active': tab==0}"                                       
                                        @click="kra_individual(0 , tabDepItem)">
                                          Achievement Due Employee 
                                        </a>
                                        <a id="nav-home-tab" data-toggle="tab" 
                                        v-else
                                        href="#nav-home" role="tab" aria-controls="nav-home" 
                                        aria-selected="false" class="nav-item nav-link"
                                        :class="{'active': tab==1}"
                                        @click="kra_individual(1 , tabDepItem)">
                                          Uploade KRA KPI Due Employee
                                        </a>
                                      </div>
                                    </nav>                                    
                                    <table class="table table-bordered table-striped table-sm">
                                      <tbody>
                                        <tr>
                                          <th>SL</th>
                                          <th>Employee Name</th>
                                          <th>Employee ID</th>
                                        </tr>
                                        <tr  v-for="(row, index) in pendingItem">
                                          <td>{{ ++index }}</td>
                                          <td>{{ row.name }}</td>
                                          <th>{{ row.employee_id }}</th>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </modal>                                 
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "../../axios_instance";
import { Form } from "vform";

export default {
  props: {},
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      dueSl: 0,
      base_url: window.base_url,
      api_url: window.api_url,
      tabDepItem: [],
      user_data: JSON.parse(this.$localStorage.get("user")),
      token: this.$localStorage.get("d_token"),
      tabs: [],
      pendingItem:[],
      report_type: "",
      type_select: 0,
      kraItem: [],
      file_List: [],
      deptSingel: [],
      status: "",
      kpiItem: [],
      mosItem: [],
      deptItems: [],
      monthly_button: false,
      items: [],
      tab: 0,
      //year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
      filterForm: new Form({
        dept_id: "",
        year: this.$localStorage.get("year")
          ? this.$localStorage.get("year")
          : new Date().getFullYear(),
        month: "",
        user_id: "",
      }),
      total_emp: 0,
      total_upload_kra: 0,
      updated_emp: 0,
      due_emp: 0,
      year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
    };
  },
  created() {
    this.getDept();
    const d = new Date();
    this.current_month = d.getMonth();
    this.role_id = this.user_data.role_id;
    if (this.role_id == 5) {
      this.daliy_mailForm.dept_id = this.user_data.dept_id;
    }
    if (
      this.role_id == 5 ||
      this.user_data.dept_id != "" ||
      this.user_data.dept_id
    ) {
      this.getDepartment().then(({ data }) => {
        if (data.success) {
          this.deptSingel = data.data;
          this.monthly_button = this.deptSingel.monthly_date_range.status;
          console.log(this.deptSingel.monthly_date_range.status);
        }
      });
    }
    
   // this.getDepartment();
  },
  methods: { 
    async getItems() {
      this.total_dep = 0;
      this.updated_dep = 0;
      this.due_dep = 0;

      this.total_emp = 0;
      this.updated_emp = 0;
      this.due_emp = 0 ;
      this.total_upload_kra = 0;
      //if (this.filterForm.month && this.filterForm.dept_id) {
      if (this.filterForm.month || this.filterForm.dept_id) {
        let where = "?";
        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }
        if (this.filterForm.year) {
          where += "&year=" + this.filterForm.year;
        }
        if (this.filterForm.month) {
          where += "&month=" + this.filterForm.month;
        } else {
          where += "&month=" + new Date().getMonth() + 1;
        }
        let loader = this.$loading.show();
        try {
          await axios
            .get(this.api_url + "kra_individual_list" + where, {
              headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            })
            .then(({ data }) => {
              console.log("this.items", data.data);
              if (data.success) {
                this.items = data.data;
                this.items.forEach((element) => {
                  this.total_emp += element.users;
                  this.updated_emp += element.kra_updated;
                  this.due_emp += element.kra_due;
                  this.total_upload_kra+=element.upload_kra;
                });
              }
              loader.hide();
            });
        } catch (error) {
          loader.hide();
        }
      }
    },
    async getDept() {
        let loader = this.$loading.show();
        let where = "?";
        if (this.filterForm.dept_id) {
          where += "&dept_id=" + this.filterForm.dept_id;
        }
        if (this.filterForm.year) {
          where += "&year=" + this.filterForm.year;
        }        
        this.getDepartments(this.status).then(({ data }) => {
          if (data.success) {
            loader.hide();
            this.deptItems = data.data; 
          } else {
            loader.hide();
          }
        });      
    },
    pop_up(type , dep_id) {
        this.$modal.show("pop-up");  
        this.kra_individual(type , dep_id);     
    },  
    kra_individual(type , dep_id = 0){
      this.tab = type;
      let where = "?";
        where += "&type=" + type;
        where += "&dep_id=" + dep_id;      
        where += "&year=" + this.filterForm.year;   
        where += "&month=" + this.filterForm.month;    

        axios.get(this.api_url + "kra_individual_status" + where, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then(({ data }) => {
          if (data.success) {
            this.pendingItem = data.data.user;
            this.tabDepItem = data.data.department;
          }
          
        }); 
    },    
    pop_hide() {
      this.$modal.hide("pop-up");
    },     
  },
  computed: {},
};
</script>
<style>
.btn.btn-primary.add-btn {
  color: #fff;
}
tr {
  border: darkgray;
}
</style>