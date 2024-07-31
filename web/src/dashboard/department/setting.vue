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
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> BPT Achivements(KRA KPI Update)
                                        </li>

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <!-- Zero configuration table -->
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-content">
                                        <div class="card-body card-dashboard">

                                            <div class="btn-group mb-1" role="group" aria-label="Basic example">

                                                <!-- UPDATE -->
                                                <button type="button"
                                                    class="btn btn-primary add-btn btn-lg d-flex align-items-center">
                                                    <a class="text-white" @click="update()"> <i
                                                            class="bx bx-add-alt"></i> Update</a>
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Dept Name</th>
                                                            <th>
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-6 ">
                                                                        <label class="control-label"> Start Date
                                                                        </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="startDateClosedFunction"
                                                                                v-model="start_date" name="start_date"
                                                                                class="form-control"></datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6  ">
                                                                        <label class="control-label">End Date </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="endDateClosedFunction"
                                                                                v-model="end_date" name="end_date"
                                                                                class="form-control"></datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>Jan <input type="checkbox" value="1"
                                                                    @change="check('jan',jan)" id="checkbox"
                                                                    v-model="jan"> </th>
                                                            <th>Feb <input type="checkbox" value="1"
                                                                    @change="check('feb',feb)" id="checkbox"
                                                                    v-model="feb"> </th>
                                                            <th>Mar <input type="checkbox" value="1"
                                                                    @change="check('mar',mar)" id="checkbox"
                                                                    v-model="mar"> </th>
                                                            <th>Apr <input type="checkbox" value="1"
                                                                    @change="check('apr',apr)" id="checkbox"
                                                                    v-model="apr"> </th>
                                                            <th>May <input type="checkbox" value="1"
                                                                    @change="check('may',may)" id="checkbox"
                                                                    v-model="may"> </th>
                                                            <th>Jun <input type="checkbox" value="1"
                                                                    @change="check('jun',jun)" id="checkbox"
                                                                    v-model="jun"> </th>
                                                            <th>Jul <input type="checkbox" value="1"
                                                                    @change="check('jul',jul)" id="checkbox"
                                                                    v-model="jul"> </th>
                                                            <th>Aug <input type="checkbox" value="1"
                                                                    @change="check('aug',aug)" id="checkbox"
                                                                    v-model="aug"> </th>
                                                            <th>Sep <input type="checkbox" value="1"
                                                                    @change="check('sep',sep)" id="checkbox"
                                                                    v-model="sep"> </th>
                                                            <th>Oct <input type="checkbox" value="1"
                                                                    @change="check('oct',oct)" id="checkbox"
                                                                    v-model="oct"> </th>
                                                            <th>Nov <input type="checkbox" value="1"
                                                                    @change="check('nov',nov)" id="checkbox"
                                                                    v-model="nov"> </th>
                                                            <th>Dec <input type="checkbox" value="1"
                                                                    @change="check('dec',dec)" id="checkbox"
                                                                    v-model="dec"> </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template v-for="(row ,index) in items">
                                                        <tr  v-if="row.setting" :key="row.id">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ row.name }}</td>
                                                            <td>
                                                                <div class="row  ">
                                                                    <div class="col-12 col-sm-6 ">
                                                                        <label class="control-label"> Start Date
                                                                        </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="datepickerClosedFunction"
                                                                                v-model="row.setting.start_date"
                                                                                name="start_date" class="form-control">
                                                                            </datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6  ">
                                                                        <label class="control-label">End Date </label>
                                                                        <fieldset class="form-group">
                                                                            <datepicker
                                                                                :disabled-dates="state.disabledDates"
                                                                                @closed="datepickerClosedFunction"
                                                                                v-model="row.setting.end_date"
                                                                                name="end_date" class="form-control">
                                                                            </datepicker>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="checkbox"
                                                                    v-model="row.setting.jan">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.feb">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.mar">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.apr">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.may">
                                                            </td>
                                                            <td>

                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.jun">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" v-model="row.setting.jul">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.aug">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.sep">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.oct">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.nov">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1"
                                                                    v-model="row.setting.dec">
                                                            </td>
                                                        </tr>
                                                    </template>
                                                        <tr v-if="items.length < 1">
                                                            <td colspan="4">Data not found</td>
                                                        </tr>
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
    </div>
</template>
<script>
import { Form } from "vform"; 
import axios from "../../axios_instance";
import Datepicker from 'vuejs-datepicker';
export default {
  props: { 
  },
  components: {
    Datepicker
    // VueRecaptcha, facebookLogin
  },
  data() {
    return {
        state : { 
            disabledDates: {
                to: new Date( new Date().getTime() - 86400000),
                from: new Date( new Date().getTime() + (86400000 * 30))
            }
        },
        start_date: ''  ,
        end_date: '' ,
        jan: false ,
        feb: false ,
        mar: false ,
        apr: false ,
        may: false ,
        jun: false ,
        jul: false ,
        aug: false ,
        sep: false ,
        oct: false ,
        nov: false ,
        dec: false ,
        checked : false ,
        base_url: window.base_url,
        api_url: window.api_url, 
        token: this.$localStorage.get("d_token"),
        items:[],  
        status :  '' ,
        updateForm: new Form({ 
           items : '',   
        }),
    };
  },
  created() {  
    this.getItems();
  },
  methods: {
    check(month, value ){  
        for (let index = 0; index < this.items.length; index++) { 
            this.items[index].setting[month] = value;
        } 
    },
    datepickerClosedFunction(){

    },
    startDateClosedFunction(){
        console.log(this.start_date ); 
        for (let index = 0; index < this.items.length; index++) { 
            this.items[index].setting.start_date = this.start_date  ;
        } 

    },
    endDateClosedFunction(){
        console.log(this.start_date ); 
        for (let index = 0; index < this.items.length; index++) { 
            this.items[index].setting.end_date = this.end_date  ;
        } 
    },
    async statusChange(item , status){
        let editForm =  new Form({    
            status: status,
            name : item.name
        })
        try {
         let loader = this.$loading.show();
            editForm.put(this.api_url + "departments/"+ item.id, {
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
                this.getItems();
            },(error)=>{
            console.log(error);
            loader.hide(); 
            })
        } catch (error) {
            // loader.hide(); 
            console.log(error);
        }
    }, 
    update(){
 
        let loader = this.$loading.show();
        for (let index = 0; index < this.items.length; index++) { 
            if( this.items[index].setting){
                this.items[index].setting.start_date = this.format_Date(this.items[index].setting.start_date)  ;
                this.items[index].setting.end_date = this.format_Date(this.items[index].setting.end_date)  ;
            }
           
        } 
        this.updateForm.items = this.items ;  
        this.updateForm.post(this.api_url + "department_settings_update", {
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
           //this.$router.push('/department');
       },(error)=>{
         console.log(error);
          loader.hide(); 
       })
      
   } ,
    async delete_row(id){ 
        let loader = this.$loading.show();
        try {
            await axios
            .delete(this.api_url + "departments/"+id, {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ res }) => {  
                    this.$toasted.show(res.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                        });
                    this.getItems();
               
                loader.hide();
            });
        } catch (error) {
            loader.hide();
        }
      },
       async getItems(){
           //departments_all
           let loader = this.$loading.show();
           await axios.get(this.api_url + "department_setting", {
                    headers: {
                    "Content-Type": "application/json", 
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
            .then(({ data }) => {  
                this.items =  data.data
                loader.hide();
                console.log(this.WingsItems );   
            }); 
           
      }
  },
  computed: {},
};
</script>
