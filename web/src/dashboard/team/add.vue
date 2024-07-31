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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/department' }"> Team </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Assign New Member
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
                                    <h4 class="card-title">Assign New Member</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Team Name</label>
                                                        <div class="controls">
                                                            <input type="text" name="name" v-model="addForm.name" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Team Name" readonly>
                                                        </div>
                                                    </div> 
                                                  
                                                    <div class="form-group">
                                                         <label for="Profession">Assign Member</label>
                                                         <div class="controls">
                                                        <select :class="{  'is-invalid': addForm.errors.has('user_id'),  }"
                                                            class="form-control" 
                                                            v-model="addForm.user_id"
                                                            id="users-list-verified">
                                                            <option value="">Select Employee</option>
                                                            <option v-for="row in employeeItem" 
                                                            :key="row.id"
                                                            :value="row.id"
                                                            >
                                                            {{ row.name }}
                                                            </option>
                                                        </select>
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
import { Form } from "vform"; 
import axios from "../../axios_instance";
export default {
  props: { 
  },
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        id : this.$route.params.id,
        base_url: window.base_url,
        api_url: window.api_url,  
        token: this.$localStorage.get("d_token"),
        item: [],
        employeeItem: [],
        addForm: new Form({ 
            department_id: '',
            wings_id: '',
            team_id:'',
            name: '',  
            user_id: ''
        }),
    };
  },
  created() { 
    this.getItems();
  },
  methods: {
    async getItems() {
        this.getItem("teams/"+this.id).then(({ data }) => { 
            if(data.success){
                this.item = data.data;
                this.getEmployee(this.item);
                this.addForm.team_id = this.item.id;
                this.addForm.wings_id = this.item.wingJoin.id;
                this.addForm.name = this.item.team_name;
                this.addForm.department_id = this.item.deptjoin.id;
            } 
        });         
    },

    async getEmployee(item) {
        let where = '?1=1';
        where += '&wing_id=' + item.wingJoin.id;
        
        where += '&dept_id=' + item.wingJoin.dept_id;
        
        await axios.get(this.api_url + "users" + where, {
            headers: {
                "Content-Type": "application/json",
                Authorization: this.token ? `Bearer ${this.token}` : ""
            },
        })
        .then(({ data }) => {
            this.employeeItem = data.data;
        });
    },
    create(){
      try {
        let loader = this.$loading.show();
        console.log('this.addForm',this.addForm);
        this.addForm.post(this.api_url + "team_members", {
            headers: {
              "Content-Type": "application/json", 
              Authorization: this.token ? `Bearer ${this.token}` : ""
            },
        }).then((res) => {
            console.log(res);
            if(res.data.success){
                this.addForm.name  = '';
                this.$toasted.show(res.data.message, {
                    theme: "bubble",
                    duration: 5000,
                    position: "bottom-right",
                });
            } 
            loader.hide(); 
            this.$router.push('/team');
        },(error)=>{
            console.log('error',error);
            // this.$toasted.show(error.employee_id, {
            //     theme: "bubble",
            //     duration: 5000,
            //     position: "bottom-right",
            // });          
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
