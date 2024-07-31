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
                        ><i class="bx bx-home-alt"></i
                      ></router-link>
                    </li>
                    <li class="breadcrumb-item active">MOS Settings</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body">
          <section class="input-validation">
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <!-- <select
                    v-model="selectedUserId"
                    @change="selectedUser($event)"
                    class="form-select form-control"
                    aria-label="Default select example"
                  >
                    <option value="">Select User</option>
                    <option
                      :key="row.id"
                      :value="row.id"
                      v-for="row in userLists"
                    >
                      {{ row.name }} ( {{ row.email }} )
                    </option>
                  </select> -->
                  <select class="form-control" v-on:change="getEmployee()"  v-model="selectedWingsId"  id="users-list-verified" >
                      <option value="">Select Wing</option>
                      <option v-for="row in WingsItems" :key="row.id" :value="row.id" >
                      {{ row.wing_title }}
                      </option>
                  </select> 
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <select class="form-control" @change="selectedUser($event)"  v-model="selectedUserId"  id="users-list-verified" >
                      <option value="">Select Wings Employee</option>
                      <option v-for="row in employeeItem" :key="row.id" :value="row.id" >
                      {{ row.name }} ( {{ row.email }} )
                      </option>
                  </select> 
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>KRA</th>
                        <th>KPI</th>
                        <th>MOS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <tr v-for="mosList in mosLists" :key="mosList.id">
                        <td>{{ mosList.krajoin.kra_name }}</td>
                        <td>{{ mosList.kpijoin.kpi_name }}</td>
                        <td>
 
                          {{ mosList.mos_name }}
                        </td>
                      </tr> -->
                      <template v-for="(mosList, index) in mosLists">
                        <tr :key="index">
                           
                          <td :rowspan="mosList.kra_count"
                            v-if="
                              mosLists[index > 0 ? index - 1 : 0].kra_id !=
                                mosList.kra_id || index == 0
                            " >
                            {{ mosList.krajoin ? mosList.krajoin.kra_name : "" }}
                            <input
                                type="checkbox"
                                :checked="
                                  selected_mos_list.filter(
                                    (p) => p.mos_id == mosList.id
                                  ).length > 0
                                "
                                id="checkbox"
                                @click="setUserMos(mosList.krajoin.id,'kra',$event)"
                            />  

                          </td>
                       
                          <td :rowspan="mosList.kpi_count"
                            v-if="
                              mosLists[index > 0 ? index - 1 : 0].kpi_id !=
                                mosList.kpi_id || index == 0
                            " >
                            {{ mosList.kpijoin ? mosList.kpijoin.kpi_name : "" }} 
                            <input
                              type="checkbox"
                              :checked="
                                selected_mos_list.filter(
                                  (p) => p.mos_id == mosList.id
                                ).length > 0
                              "
                              id="checkbox"
                              @click="setUserMos(mosList.kpijoin.id,'kpi',$event)"
                            />  

                          </td>
 
                          <td>
                          <input
                              type="checkbox"
                              :checked="
                                selected_mos_list.filter(
                                  (p) => p.mos_id == mosList.id
                                ).length > 0
                              "
                              id="checkbox"
                              @click="setUserMos(mosList.id,'mos',$event)"
                            />                            
                            {{ mosList.mos_name }}
                            </td>
                        </tr>
                      </template>
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
  </div>
</template>


<script>
import axios from "../../axios_instance";
import { Form } from "vform";

export default {
  data() {
    return {
      token: this.$localStorage.get("d_token"),
      base_url: window.base_url,
      api_url: window.api_url,
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id: "",
      user: JSON.parse(this.$localStorage.get("user")),
      is_login: false,
      user_type: null,
      addForm: new Form({
        user_id: "",
        mos_id: "",
      }),
      mosLists: [],
      userLists: [],
      selected_mos_list: [],
      selectedUserId: "",
      selectedWingsId:'',
      selectedEmployee:"",
      WingsItems:[],
      employeeItem:[],
    };
  },

  methods: {
    selectedUser(event) {
      //console.log('event.target.value',event.target.value);
      //GET USER SELECTED MOS LIST
      axios
        .get(this.api_url + "userWiseMosList?user_id=" + event.target.value, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((response) => {
          this.selected_mos_list = response.data.data;
        });
    },

    selectedWingsData(event) {
      console.log('event.target.value',);
      axios
        .get(this.api_url + "userWiseMosList?wings_id=" + event, {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((response) => {
          this.selected_mos_list = response.data.data;
          console.log("response this from wings", this.selected_mos_list);
          //console.log("response", this.selected_mos_list.filter(p => p.mos_id == 1082).length);
        });
    },

    //SAVE USER MOS
    setUserMos: async function (mosId,type,event) {
      //CHECK USER ID
      if (!this.selectedUserId) {
        this.$swal({
          title: "Sorry!",
          text: "Select user",
          icon: "warning",
          dangerMode: true,
        });
        return false;
      }

      //POST DATA
      var postData = {
        mos_id: mosId,
        user_id: this.selectedUserId,
        data_type: type,
        checked: event.target.checked,
      };
      //API CONFIG
      let axiosConfig = {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : "",
        },
      };

      //CALL API
      axios
        .post(this.api_url + "mosSettings", postData, axiosConfig)
        .then((res) => {
          this.selected_mos_list = res.data;
          console.log("RESPONSE RECEIVED: from api ", this.selected_mos_list);
          console.log(
            "data 123",
            this.selected_mos_list.filter((p) => p.mos_id == 87).length > 0
          );
        })
        .catch((err) => {
          console.log("AXIOS ERROR: ", err);
        });
    },

    async getWing(){ 
        await axios.get(this.api_url + "wings", {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.WingsItems =  data.data ;
            console.log(this.WingsItems );   
        });
    }, 

    async getEmployee(){ 
        this.selectedWingsData(this.selectedWingsId);
        let where = '';
        if(this.selectedWingsId){
            where = '&wing_id=' + this.selectedWingsId;  
        }
        await axios.get(this.api_url + "users?role_id=7"+where , {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            this.employeeItem =  data.data ;  
            // selectedUserId 
        });
    }, 
  },

  created() {
    this.getWing();
    this.getEmployee();
    this.role_id = this.user_data.role_id;
    if (this.$localStorage.get("d_token")) {
      this.is_login = true;
      this.user_type = this.user.type;
    } else {
      this.is_login = false;
    }

    this.selected_mos_list = [];
    let where ; 
     where = '?year='+ (this.year ? this.year : new Date().getFullYear()); 
    if (this.user_data.dept_id) {
        where += '&dept_id=' + this.user_data.dept_id;
    }
    //GET MOS LIST
    axios
      .get(this.api_url + "kra_kpi_mos_list"+where, {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : "",
        },
      })
      .then((response) => {
        //console.log("response", response.data);
        this.mosLists = response.data.data;
        console.log("dataTest", this.mosLists);
      });

    //GET USER LIST
    axios
      .get(this.api_url + "departmentWiseUserList/", {
        headers: {
          "Content-Type": "application/json",
          Authorization: this.token ? `Bearer ${this.token}` : "",
        },
      })
      .then((response) => {
        //console.log("response", response.data.data);
        this.userLists = response.data.data;
      });

    //GET USER SELECTED MOS LIST
    // axios
    //     .get(this.api_url + "userWiseMosList?user_id="+ this.selectedUserId, {
    //     headers: {
    //       "Content-Type": "application/json",
    //       Authorization: this.token ? `Bearer ${this.token}` : "",
    //     },
    //   })
    //   .then((response) => {
    //       this.selected_mos_list = response.data.data;
    //     //console.log("response", this.selected_mos_list);
    //     //console.log("response", this.selected_mos_list.filter(p => p.mos_id == 1082).length);

    //   });
  },
  computed: {},
};
</script>

<style scoped>
</style>