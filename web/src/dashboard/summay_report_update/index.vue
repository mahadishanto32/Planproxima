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
                      Monthly BPT Update Report (Department)
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
                      <option value="2024">2024</option>
                      <option value="2025">2025</option>
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
                              <h3 class="text-center breadcrumb-item active">
                                Department Wise List
                              </h3>
                              <table class="table table-bordered table-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Update Status</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                  <tr
                                    v-for="(row, index) in items"
                                    :key="row.id"
                                  >
                                    <td>{{ (index += 1) }}</td>
                                    <td>{{ row.name }}</td>
                                    <td>
                                      {{
                                        row.status ? "Updated" : "Not Update"
                                      }}
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <h3 class="text-center breadcrumb-item active">
                                Monthly Summary
                              </h3>
                              <table class="table table-bordered table-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th class="text-center">
                                      Total Department
                                    </th>
                                    <th class="text-center">Updated</th>
                                    <th class="text-center">Not Updated</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                  <tr>
                                    <td>{{ total_dep }}</td>
                                    <td>{{ updated_dep }}</td>
                                    <td>{{ due_dep }}</td>
                                  </tr>
                                </tbody>
                              </table>
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
      base_url: window.base_url,
      api_url: window.api_url,
      DepartmentsItems: [],
      user_data: JSON.parse(this.$localStorage.get("user")),
      token: this.$localStorage.get("d_token"),
      tabs: [],
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
      //year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
      filterForm: new Form({
        dept_id: "",
        year: this.$localStorage.get("year")
          ? this.$localStorage.get("year")
          : new Date().getFullYear(),
        month: "",
        user_id: "",
      }),
      total_dep: 0,
      updated_dep: 0,
      due_dep: 0,
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
   
    this.getDepartment();
  },
  methods: {
    async dept() {
      this.getDepartments().then(({ data }) => {
        if (data.success) {
          this.DepartmentsItems = data.data;
        }
      });
    },
    async getItems() {
      this.total_dep = 0;
      this.updated_dep = 0;
      this.due_dep = 0;
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
            .get(this.api_url + "summay_report_update" + where, {
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
                  this.total_dep += 1;
                  element.status
                    ? (this.updated_dep += 1)
                    : (this.due_dep += 1);
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
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          //loader.hide();
          this.deptItems = data.data; 
        } else {
          //loader.hide();
        }
      });
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