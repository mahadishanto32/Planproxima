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
                                    <li class="breadcrumb-item  "> <router-link :to="{ path: '/production_emps' }">  Employee Turnover </router-link>
                                    </li>
                                    <li class="breadcrumb-item active"> Employee Turnover Entry
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
                                    <h4 class="card-title">Employee Turnover Entry</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form @submit.prevent="create()">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Factory</label>
                                                        <div class="controls"> 
                                                            <select v-on:change="get_products_list()"  id="Profession" name="role_id"   v-model="addForm.factory_id" class="form-control">
                                                                <option value="">Select one</option>
                                                                <option  v-for="row in factoris" :key="row.id" :value="row.id">{{ row.name}}</option>  
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Product</label>
                                                        <div class="controls"> 
                                                            <select v-on:change="get_iending_emp()"  id="Profession" name="product_id"   v-model="addForm.product_id" class="form-control">
                                                                <option value="">Select one</option>
                                                                <option  v-for="row in products" :key="row.id" :value="row.id">{{ row.product_name}}</option>  
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Year</label>
                                                        <div class="controls">
                                                            <select   id="Profession" name="year"   v-model="addForm.year" class="form-control">
                                                                <option value="2021">2021</option>
                                                                <option value="2020">2020</option>
                                                                <option value="2019">2019</option>
                                                                <option value="2018">2018</option>
                                                                <option value="2017">2017</option>
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Month</label>
                                                        <div class="controls">
                                                            <select   id="Profession" name="month"   v-model="addForm.month" class="form-control">
                                                            <option value="">Select month</option>
                                                            <!-- <option value="1">Jan</option>
                                                            <option value="2">Feb</option>
                                                            <option value="3">Mar</option>
                                                            <option value="4">Apr</option>
                                                            <option value="5">May</option> -->
                                                            <option value="6">Jun</option>
                                                            <option value="7">Jul</option>
                                                            <option value="8">Aug</option>
                                                            <option value="9">Sep</option>
                                                            <option value="10">Oct</option>
                                                            <option value="11">Nov</option>
                                                            <option value="12">Dec</option>
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="form-group">
                                                        <label>Begining Employee</label>
                                                        <div class="controls">
                                                            <input readonly type="text" name="begining_emp" v-model="addForm.begining_emp" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Begining Employee">
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Number of Resignatio</label>
                                                        <div class="controls">
                                                            <input  v-on:change="changeEvent()"  type="text" name="number_of_resig" v-model="addForm.number_of_resig" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Number of Resignatio">
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Number of New Join</label>
                                                        <div class="controls">
                                                            <input type="text" v-on:change="changeEvent()" name="number_of_join" v-model="addForm.number_of_join" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Number of New Join">
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Ending Employee</label>
                                                        <div class="controls">
                                                            <input readonly type="text" name="ending_emp" v-model="addForm.ending_emp" :class="{  'is-invalid': addForm.errors.has('name'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Ending Employee">
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Remarks</label>
                                                        <div class="controls">
                                                            <ckeditor :editor="editor" :config="editorConfig" name="task"  ref="editor" style="height: 600px;" v-model="addForm.remarks"></ckeditor>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic' ;
export default {
  props: { 
  },
  components: {
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        editor: ClassicEditor,
        editorData: '',
        editorConfig: {},
        customToolbar: [
            ["bold", "italic", "underline"], 
            [{ list: "ordered" }, { list: "bullet" }], ["image", "tables", "code-block"]],     

        base_url: window.base_url,
        api_url: window.api_url,  
        token: this.$localStorage.get("d_token"),
        factoris : [],
        products : [],
        addForm: new Form({ 
            factory_id: "",  
            product_id: "",
            year: '2021',
            month: 7,
            number_of_join : 0, 
            number_of_resig : 0, 
            begining_emp : "", 
            ending_emp : "",   
            remarks: "",
            active: 1, 
        }),

    };
  },
  created() { 
      this.getFactory();

      this.addForm.ending_emp =  (this.addForm.ending_emp +   this.addForm.number_of_join ) - this.addForm.number_of_resig ;
       
  },
  methods: {
    async getFactory(){
        let loader = this.$loading.show();
        await axios.get(this.api_url + "production_emps_factory", {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            loader.hide(); 
            this.factoris =  data.data ;  
        }); 
    },
    changeEvent(){ 
      this.addForm.ending_emp =  (Number(this.addForm.begining_emp) +   Number(this.addForm.number_of_join) ) - Number(this.addForm.number_of_resig) ;  
    },
    async get_iending_emp(){
        let loader = this.$loading.show();
        await axios.get(this.api_url + "get_iending_emp?product_id="+this.addForm.product_id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            loader.hide();  
             
            this.addForm.begining_emp =  data.data ?  data.data.ending_emp : 0  ;  
            this.addForm.ending_emp =  data.data ?  data.data.ending_emp : 0  ;  
            this.changeEvent();
        }); 
    },
    async get_products_list(){
        let loader = this.$loading.show();
        await axios.get(this.api_url + "get_products_list?factory_id="+ this.addForm.factory_id, {
                headers: {
                "Content-Type": "application/json", 
                Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
        .then(({ data }) => {  
            loader.hide(); 
            this.products =  data.data ;  
           
        }); 
    },
     create(){
     
      try {
         let loader = this.$loading.show();
        this.addForm.post(this.api_url + "production_emps", {
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
            this.$router.push('/production_emps');
        },(error)=>{
          console.log(error);
           loader.hide(); 
        })
      } catch (error) {
         // loader.hide(); 
        console.log(error);
      }
    } 
  },
  computed: {
      
  },
};
</script>
