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
                                   <li class="breadcrumb-item"><router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Department Transfer
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
                                    <h4 class="card-title">New Department Transfer</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">

                                        <div class="users-list-filter px-1">
                                            <div class="row border rounded py-2 mb-2">
                                            <div class="col-sm-4 col-lg-2" :v-if="user_item.tour_permission==1">
                                                <label for="users-list-verified">Tour Information</label>
                                                <fieldset class="form-group">
                                                     <input type="checkbox" name="" value="" v-model="addForm.tour_data" />
                                                </fieldset>
                                            </div>

                                            <div class="col-sm-4 col-lg-2"  >
                                                <label for="users-list-verified">Daily Entry </label>
                                                <fieldset class="form-group">
                                                    <input type="checkbox" name="" value="" v-model="addForm.daily_data" />
                                                </fieldset>
                                            </div>
                                            <div class="ccol-sm-4 col-lg-2" >
                                                <label for="users-list-verified">Kra/Kpi </label>
                                                <fieldset class="form-group">
                                                     <input type="checkbox" name="" value="" v-model="addForm.kra_data" />
                                                </fieldset>
                                            </div>
                                            </div>
                                        </div>  

                                        <div class="row">
                                            <div class="col-md-8 content-body" >
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <div class="controls">
                                                        <input class="form-control"  :v-model="addForm.user_id = user_item.id"  type="text" readonly name="" :value="user_item.name" />
                                                    </div>
                                                </div>                                                                                        
                                                <!-- <div class="form-group">
                                                    <label>Current Department</label>
                                                    <div class="controls">
                                                        <select class="form-control"  v-model="addForm.current_dept" id="users-list-verified">
                                                            <option :value="items.id">{{ items.name }}</option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                                    <div class="form-group">
                                                    <label>New Department</label>
                                                    <div class="controls">
                                                        <select class="form-control"  v-model="addForm.new_dept" id="users-list-verified">
                                                            <option value="">Select One</option>
                                                            <option v-for="row in deptItems" :key="row.id" :value="row.id">
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
//import axios from "../../axios_instance";
// import Multiselect from 'vue-multiselect'; 
export default {
    props: { 
    },
    components: {
        // Multiselect
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            base_url: window.base_url,
            api_url: window.api_url, 
            token: this.$localStorage.get("d_token"),
            //items:[], 
            item : [], 
            dept_users : [],
            dept_selects : [],
            user_id : this.$route.params.user_id, 
            user_item : [],
            dept_permission: new Form({  
                dept_selects : "", 
            }),
            addForm: new Form({ 
                current_dept: "",  
                new_dept: "",
                tour_data: "",
                daily_data: "",
                kra_data: "",
                user_id: "",
            }),   
            deptItems: [],  
        };
    },
    created() { 
        this.getDept(); 
       // this.getItems(); 
        this.userInfo();
    },
    methods: { 
        // async getItems(){
        //     //departments_all
        //    // let loader = this.$loading.show();
        //     await axios.get(this.api_url + "dept_permission?user_id="+this.user_id, {
        //             headers: {
        //             "Content-Type": "application/json", 
        //             Authorization: this.token ? `Bearer ${this.token}` : ""
        //             },
        //         })
        //     .then((data) => {  
        //         console.log('dept_permission',data);
        //         if(data.data[0]){
        //             this.items = data.data[0];
        //             this.addForm.current_dept = data.data[0].id;
        //         } 
        //         //loader.hide(); 
        //         console.log('items',this.items);
        //     },(err)=>{
        //         console.log(err);
        //         //loader.hide(); 
        //     }); 
            
        // },
        async dept() {
            this.getDepartments().then(({ data }) => {
                if (data.success) {
                    this.DepartmentsItems = data.data;
                }
            });
        }, 
        async getDept() { 
            this.getDepartments(1).then((data) => { 
                this.deptItems = data.data.data;
                console.log('this.deptItems',  this.deptItems);
                 
            });
        }, 
        async userInfo(){
            this.getItem("users/"+this.user_id).then(({ data }) => {
                if(data.success){
                    this.user_item =  data.data;
                    this.addForm.current_dept =  this.user_item.dept_id ;
                    console.log( 'user_item' ,this.user_item );
                } 
            });             
        },           
        create(){ 
            try {
                let loader = this.$loading.show();
                this.addForm.post(this.api_url + "dep_transfer", {
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
                    this.$router.push('/users');
                    console.log(res.data);
                },(error)=>{
                    console.log(error);
                    // loader.hide(); 
                })
            }catch (error) {
                // loader.hide(); 
                console.log(error);
            }
        },        
    },
  computed: {},
};
</script>
