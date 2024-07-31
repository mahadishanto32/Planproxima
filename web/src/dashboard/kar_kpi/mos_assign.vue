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
                        ><i class="bx bx-home-alt"></i>
                      </router-link>
                    </li>
                    <li class="breadcrumb-item active">
                      KRA, KPI and MOS Weightage List
                    </li>
                  </ol>
                </div>
              </div>
              <!-- <div class=" col-sm-3">
                                <router-link class="btn btn-primary add-btn" :to="{ path: '/add_daily_work' }"> <i class="bx bx-add-alt"></i> Add daily work </router-link>
                            </div> -->
            </div>
          </div>
        </div>
        <div class="content-body">
          <section id="basic-datatable">
            <div class="users-list-filter px-1">
              <div class="row border rounded py-2 mb-2">
                <div
                  v-if="deptItems.length > 1"
                  class="col-12 col-sm-6 col-lg-2"
                >
                  <div class="mb-2">
                    <label for="users-list-verified">Department</label>
                    <fieldset class="form-group">
                      <select
                        class="form-control"
                        v-on:change="deptChange()"
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
                </div>
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Wings</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="changeEmployee()"
                      v-model="filterForm.wing_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in WingsItems"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.wing_title }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="teamItem.length > 0 && (role_id == 6 || role_id == 7)"
                >
                  <label for="users-list-verified">Team</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="teamMember()"
                      v-model="filterForm.team_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in teamItem"
                        :key="row.id"
                        :value="row.id"
                      >
                        {{ row.team_name }}
                      </option>
                    </select>
                  </fieldset>
                </div>
                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="teamItem.length > 0 && team_member.length > 0"
                >
                  <label for="users-list-verified">Team Member</label>
                  <fieldset class="form-group">
                    <select
                      class="form-control"
                      v-on:change="getItems()"
                      v-model="filterForm.member_id"
                      id="users-list-verified"
                    >
                      <option value="">Select One</option>
                      <option
                        v-for="row in team_member"
                        :key="row.id"
                        :value="row.userJoin.id"
                      >
                        {{ row.userJoin ? row.userJoin.name : '' }}
                      </option>
                    </select>
                  </fieldset>
                </div>

                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="role_id == 5 || role_id == 6"
                >
                  <label for="users-list-verified">Employee</label>
                  <fieldset class="form-group">
                    <!-- <select class="form-control" v-on:change="getItems()"
                                            v-model="filterForm.user_id" id="users-list-verified">
                                            <option value="">Select One</option>
                                            <option v-for="row in employeeItem" :key="row.id" :value="row.id">
                                                {{ row.employee_id ? row.employee_id + ' : ' : '' }} {{ row.name }}
                                            </option>
                                        </select> -->

                    <Select2
                      placeholder="Select One"
                      v-on:change="getItems()"
                      v-model="filterForm.user_id"
                      :options="employeeItem"
                    />
                  </fieldset>
                </div>

                <div class="col-12 col-sm-6 col-lg-2" v-if="role_id == 5">
                  <label for="users-list-verified">All Employee</label>
                  <fieldset class="form-group">
                    <input type="checkbox" v-model="filterForm.all_emp" />
                  </fieldset>
                </div>

                <div
                  class="col-12 col-sm-6 col-lg-2"
                  v-if="role_id == 5 || role_id == 6 || teamItem.length > 0"
                >
                  <label for="users-list-verified align-items-center"></label>
                  <fieldset class="form-group">
                    <button
                      type="button"
                      class="btn btn-primary add-btn btn-lg d-flex align-items-center"
                    >
                      <a class="text-white" @click="create()">
                        <i class="bx bx-add-alt"></i> Assign</a
                      >
                    </button>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="table-responsive">
                        <div class="">
                          <button
                            type="button"
                            class="btn btn-primary add-btn btn-lg d-flex align-items-center mb-2"
                          >
                            <a class="text-white" @click="demoDownload()">
                              <i class="bx bx-add-alt"></i>Download sample file
                            </a>
                          </button>
                          <button
                            type="button"
                            class="btn btn-primary add-btn btn-lg d-flex align-items-center mb-2"
                          >
                            <a class="text-white" @click="kpiUpload()">
                              <i class="bx bx-add-alt"></i>Upload (MOS)
                            </a>
                          </button>
                        </div>
                        <table class="table table-bordered table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th>KRA</th>
                              <th>KPI</th>
                              <th>KPI Weightage</th>
                              <th colspan="4">MOS</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="item in items">
                              <tr :key="item.id">
                                <td>
                                  {{ item.kra_name }}-({{ item.kra_weight }})
                                </td>
                                <td>{{ item.kpi_name }}</td>
                                <td>{{ item.kpi_weight }}</td>
                                <td>
                                  <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                      <tr>
                                        <th>No</th>
                                        <th>Mos Name</th>
                                        <th>Mos Weightage</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <span v-if="item.mos.length == 0">{{
                                        addFieldMOS(item)
                                      }}</span>
                                      <template
                                        v-for="(mos, index) in item.mos"
                                      >
                                        <tr :key="mos.id">
                                          <td>{{ (index += 1) }}</td>
                                          <td>
                                            <input
                                              type="text"
                                              value=""
                                              v-model="mos.mos_name"
                                            />
                                          </td>
                                          <td>
                                            <input
                                              type="number"
                                              value=""
                                              v-model="mos.weightage"
                                            />
                                          </td>
                                          <td>
                                            <button
                                              v-if="item.mos.length == index"
                                              class="btn-success"
                                              @click="addFieldMOS(item)"
                                            >
                                              <i class="bx bx-plus"></i>
                                            </button>
                                            <button
                                              v-if="item.mos.length != index"
                                              class="btn-danger"
                                              @click="deleteFieldMOS(item, mos)"
                                            >
                                              <i class="bx bx-trash"></i>
                                            </button>
                                          </td>
                                        </tr>
                                      </template>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                        </table>
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
    <modal width="60%" height="250px" style="padding: 50px" name="mosPopup">
      <i @click="hiddenkpiUpload()" class="bx bx-x-circle x-circle"></i>
      <div class="app-content">
        <div class="card">
          <div class="col-sm-6">
            <a
              @click="demoDownload()"
              class="btn-block glow users-list-clear mb-0 download_template"
              >KRA, KPI and MOS Data Upload Format</a
            >
            <br />
          </div>
          <table class="table table-bordered table-striped table-sm">
            <tbody>
              <tr>
                <th class="text-center">
                  <input
                    type="file"
                    accept=".xlsx"
                    class="form-control"
                    ref="file"
                    @change="handleFileObject()"
                  />
                </th>
                <th class="text-center">
                  <button @click="csvUpload()" class="btn btn-success">
                    Save
                  </button>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import Select2 from 'v-select2-component'
import { Form } from 'vform'
import Vue from 'vue'
import axios from '../../axios_instance'

export default {
  props: {},
  components: {
    Select2: Select2,
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
      base_url: window.base_url,
      api_url: window.api_url,
      token: this.$localStorage.get('d_token'),
      user_data: JSON.parse(this.$localStorage.get('user')),
      role_id: '',
      teamItem: [],
      team_member: [],
      items: [],
      status: '',
      deptItems: [],
      WingsItems: [],
      employeeItem: [],
      kra_checked: '',
      kpi_checked: '',
      filterForm: new Form({
        dept_id: '',
        wing_id: '',
        team_id: '',
        member_id: '',
        user_id: '',
        item: '',
        all_emp: 0,
      }),
    }
  },
  created() {
    this.filterForm.dept_id = this.user_data.dept_id
    this.filterForm.year = this.year
    this.role_id = this.user_data.role_id
    if (this.filterForm.dept_id) {
      this.getWing()
      this.filterForm.wing_id = this.user_data.wing_id
        ? this.user_data.wing_id
        : ''
    }
    if (
      this.role_id == 1 ||
      this.role_id == 2 ||
      this.role_id == 3 ||
      this.role_id == 4 ||
      this.role_id == 5
    ) {
      this.getEmployee()
      this.deptChange()
    } else {
      //this.getItems();
      this.getItems()
    }
  },
  methods: {
    kpiUpload() {
      this.$modal.show('mosPopup')
    },
    hiddenkpiUpload() {
      this.$modal.hide('mosPopup')
    },
    handleFileObject() {
      this.csv = this.$refs.file.files[0]
      console.log(this.csv)
      this.csvName = this.csv.name
    },
    csvFile() {
      let file = event.target.files[0]
      let reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onload = (event) => {
        this.csvform.csv = event.target.result
      }
    },
    csvUpload() {
      let formData = new FormData()
      formData.append('csvFile', this.csv)
      formData.append('year', this.year)
      let loader = this.$loading.show()
      axios
        .post(this.api_url + 'mos-upload-csv', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            // console.log(res.data.message);
            loader.hide()
            this.hiddenkpiUpload()
            this.$modal.hide('file-upload')
            this.$swal({
              title: res.data.message,
              icon: 'success',
            })
            this.getItems()
          },
          (err) => {
            loader.hide()
          }
        )
    },
    async getWing() {
      await axios
        .get(this.api_url + 'wings?dept_id=' + this.filterForm.dept_id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.getEmployee()
          //this.getTeam();
          this.WingsItems = data.data
        })
    },
    async changeEmployee() {
      this.getEmployee()
    },

    async getTeam() {
      let where = '?1=1'
      if (this.role_id == 7) {
        where += '&team_leader=' + this.user_data.id
      } else {
        if (this.filterForm.wing_id) {
          where += '&wings_id=' + this.filterForm.wing_id
        }
        if (this.filterForm.dept_id) {
          where += '&dept_id=' + this.filterForm.dept_id
        }
      }
      await axios
        .get(this.api_url + 'teams' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.teamItem = data.data
        })
    },
    async demoDownload() {
      let formData = new FormData()
      if (this.filterForm.user_id) {
        formData.append('user_id', this.filterForm.user_id)
      } else {
        formData.append('user_id', this.user_data.id)
      }

      if (this.filterForm.all_emp) {
        formData.append('all_check', 1)
      }

      if (this.filterForm.dept_id) {
        formData.append('dept_id', this.filterForm.dept_id)
      }

      // formData.append("user_id", this.filterForm.user_id);
      formData.append('year', this.year)
      await axios
        .post(this.api_url + 'download_mos_file_format', formData, {
          responseType: 'arraybuffer',
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]))
          var fileLink = document.createElement('a')
          fileLink.href = fileURL
          fileLink.setAttribute('download', 'mos_file_upload_format.xlsx')
          document.body.appendChild(fileLink)
          fileLink.click()
        })
    },
    async teamMember() {
      let where = '?1=1'
      if (this.filterForm.team_id) {
        where += '&team_id=' + this.filterForm.team_id
      }
      await axios
        .get(this.api_url + 'team_members' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.team_member = data.data
        })
    },
    async getEmployee() {
      //this.getTeam();
      // if(this.filterForm.wing_id){
      let where = '?1=1'
      if (this.filterForm.wing_id) {
        where += '&wing_id=' + this.filterForm.wing_id
      }
      if (this.filterForm.dept_id) {
        where += '&dept_id=' + this.filterForm.dept_id
      }
      await axios
        .get(this.api_url + 'users' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(({ data }) => {
          this.employeeItem = data.data
        })
      // }
    },
    async getItems(load = false) {
      //this.getWing();
      if (this.filterForm.dept_id != '') {
        let where = '?year=' + this.year
        if (this.role_id == 7) {
          if (this.filterForm.member_id) {
            where += '&user_id=' + this.filterForm.member_id
          }
        } else {
          if (this.filterForm.user_id) {
            where += '&user_id=' + this.filterForm.user_id
          }
        }
        if (this.filterForm.dept_id) {
          where += '&dept_id=' + this.filterForm.dept_id
        }
        if (this.filterForm.wing_id) {
          where += '&wing_id=' + this.filterForm.wing_id
        }

        let loader
        if (load) {
          loader = this.$loading.show()
        }

        try {
          await axios
            .get(this.api_url + 'assign_mos_list' + where, {
              headers: {
                'Content-Type': 'application/json',
                Authorization: this.token ? `Bearer ${this.token}` : '',
              },
            })
            .then(({ data }) => {
              if (data.success) {
                this.items = data.data
              }
              if (load) {
                loader.hide()
              }
            })
        } catch (error) {
          if (load) {
            loader.hide()
          }
        }
      }
    },
    async deptChange() {
      this.getDept()
      this.getWing()
      this.getItems(true)
    },
    async getDept() {
      let loader = this.$loading.show()
      this.getDepartments(this.status).then(({ data }) => {
        if (data.success) {
          loader.hide()
          this.deptItems = data.data
        } else {
          loader.hide()
        }
      })
    },
    addFieldMOS(array) {
      const count_kpi = array.mos.reduce(
        (acc, item) => acc + parseInt(item.weightage),
        0
      )
      if (array.kpi_weight > count_kpi) {
        array.mos.push({
          id: array.mos.length + 1,
          mos_name: '',
          weightage: '',
        })
      }
    },

    deleteFieldMOS(item, mos) {
      if (mos.kpi_id) {
        this.$swal({
          title: 'Are you sure you want to delete?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            this.deleteMos(mos.id)
          }
        })
      } else {
        Vue.delete(item.mos, mos.id)
      }
    },

    async deleteMos(id) {
      await axios
        .get(this.api_url + 'mos_delete/' + id, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then((data) => {
          this.$toasted.show(data.data.message, {
            theme: 'bubble',
            duration: 5000,
            position: 'bottom-right',
          })
          this.getItems()
        })
    },

    create() {
      let loader = this.$loading.show()
      this.filterForm.item = this.items
      this.filterForm.year = this.year
      this.filterForm
        .post(this.api_url + 'assign_mos', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.token ? `Bearer ${this.token}` : '',
          },
        })
        .then(
          (res) => {
            if (res.data.success) {
              this.$toasted.show(res.data.message, {
                theme: 'bubble',
                duration: 5000,
                position: 'bottom-right',
              })
            }
            loader.hide()
            this.items()
          },
          (error) => {
            loader.hide()
          }
        )
    },
  },
  computed: {},
}
</script>
