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
                                            <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i>
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item  ">
                                            <router-link :to="{ path: '/tour_plan_entry' }"> Tour </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> New Tour
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
                                        <h4 class="card-title">New Tour</h4>
                                    </div>
                                    <br><br><br>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="addForm.date" name="date"
                                                                    class="form-control"></datepicker>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Territory</label>
                                                            <div class="controls">
                                                                <!-- <select id="territory" required="required" name="territory"
                                                                    v-model="addForm.territory"
                                                                    :class="{  'is-invalid': addForm.errors.has('territory'),  }"
                                                                    class="form-control"
                                                                    v-on:change="point()">
                                                                    <option value="">Select Territory</option>
                                                                    <option v-for="row in territoryList" :key="row.id"
                                                                        :value="row.id">
                                                                        {{ row.name }}
                                                                    </option>
                                                                </select>     -->
                                                                <fieldset class="form-group">
                                                                    <multiselect 
                                                                    v-model="addForm.territory_details" 
                                                                    :options="territoryList" 
                                                                    :multiple="true" 
                                                                    placeholder="Select territoy" 
                                                                    :label="'name'" 
                                                                    track-by="id" 
                                                                    :searchable="true"
                                                                    :close-on-select="false"
                                                                    :show-labels="false"
                                                                    @input="territoyDetaisSelect" 
                                                                    >
                                                                        <template slot="selection" slot-scope="{ values , isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect> 
                                                                </fieldset>                                                                   
                                                                
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Point</label>
                                                            <div class="controls">
                                                                <fieldset class="form-group">
                                                                    <multiselect 
                                                                    v-model="addForm.point_details" 
                                                                    :options="pointList" 
                                                                    :multiple="true" 
                                                                    placeholder="Select Point" 
                                                                    :label="'point_name'" 
                                                                    track-by="point_id" 
                                                                    :searchable="true"
                                                                    :close-on-select="false"
                                                                    :show-labels="false"
                                                                    @input="PointDetaisSelect" 
                                                                    >
                                                                    <template slot="option" slot-scope="{ option }">
                                                                        <span>{{ option.point_name }} ({{ option.sap_code }})</span>
                                                                    </template>                                                                    
                                                                        <template slot="selection" slot-scope="{ values , isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>

                                                                <!-- <select id="point_id" required="required" name="point_id"
                                                                    v-model="addForm.point_id"
                                                                    :class="{ 'is-invalid': addForm.errors.has('point_id'),  }"
                                                                    class="form-control"
                                                                    @change="fo(), concatenateValues($event)">
                                                                    <option value="">Select point</option>
                                                                    <option v-for="row in pointList" :key="row.point_id"
                                                                        :value="row.point_id">
                                                                        {{ row.point_name }} ({{ row.sap_code }})
                                                                    </option>
                                                                </select>                                                                     -->
                                                                </fieldset>                                                                   
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Route</label>
                                                            <div class="controls">
                                                                <!-- <input type="text" name="route_name"
                                                                    v-model="addForm.route_name" placeholder="Route"
                                                                    :class="{  'is-invalid': addForm.errors.has('route_name'),  }"
                                                                    class="form-control"> -->
                                                                <!-- <select id="route_name" required="required" name="route_name"
                                                                    v-model="addForm.route_name"
                                                                    :class="{ 'is-invalid': addForm.errors.has('route_name'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select Route</option>
                                                                    <option v-for="row in routeList" :key="row.id"
                                                                        :value="concatenateValuesFo(row)">
                                                                        {{ row.route_name }}
                                                                    </option>
                                                                </select>     
                                                                 -->
                                                                <fieldset class="form-group">
                                                                    <multiselect 
                                                                    v-model="addForm.route_details" 
                                                                    :options="routeList" 
                                                                    :multiple="true" 
                                                                    placeholder="Select Route" 
                                                                    :label="'route_name'" 
                                                                    track-by="route_id" 
                                                                    :searchable="true"
                                                                    :close-on-select="false"
                                                                    :show-labels="false" 
                                                                    @input="RouteDetaisSelect" 
                                                                    >
                                                                        <template slot="selection" slot-scope="{ values , isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>
                                                                </fieldset>                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>
                                                                Work With
                                                            </label>
                                                            <div class="controls">
                                                                <!-- <input type="text" name="work_with"
                                                                    v-model="addForm.work_with" placeholder="Work with"
                                                                    class="form-control"> -->

                                                                <!-- <select id="work_with" required="required" name="work_with"
                                                                    v-model="addForm.work_with_id"
                                                                    :class="{ 'is-invalid': addForm.errors.has('work_with'),  }"
                                                                    class="form-control"
                                                                    @change="concatenateValuesFo($event)">
                                                                    <option value="">Select FO</option>
                                                                    <option v-for="row in fotList" :key="row.id"
                                                                        :value="(row.email)">
                                                                        {{ row.display_name }} ({{ row.email }})
                                                                    </option>
                                                                </select> -->
                                                                <fieldset class="form-group">
                                                                    <multiselect 
                                                                    v-model="addForm.work_with_details" 
                                                                    :options="fotList" 
                                                                    :multiple="true" 
                                                                    placeholder="Select FO" 
                                                                    :label="'display_name'" 
                                                                    track-by="email" 
                                                                    :searchable="true"
                                                                    :close-on-select="false"
                                                                    :show-labels="false"
                                                                    @input="WorkWithDetaisSelect" 
                                                                    >
                                                                    <template slot="option" slot-scope="{ option }">
                                                                        <span>{{ option.display_name }} ({{ option.email }})</span>
                                                                    </template>                                                                        
                                                                        <template slot="selection" slot-scope="{ values , isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>
                                                                </fieldset>                                                                  
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Common Objective </label>
                                                            <div class="controls">
                                                                <input type="text" name="objectives"
                                                                    v-model="addForm.objectives"
                                                                    :class="{  'is-invalid': addForm.errors.has('route_name'),  }"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>
                                                                Special Objective
                                                            </label>
                                                            <div class="controls">
                                                                <input type="text" name="specia_objective"
                                                                    v-model="addForm.specia_objective"
                                                                    placeholder="Specia objective" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label for="Profession">Work Station</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="work_station"
                                                                    v-model="addForm.work_station"
                                                                    :class="{  'is-invalid': addForm.errors.has('work_station'),  }"
                                                                    class="form-control">
                                                                    <option value="Head Quarter">Head Quarter</option>
                                                                    <option value="EX Head Quarter">EX Head Quarter
                                                                    </option>
                                                                    <option value="Out Station">Out Station</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Strategic Goal </label>
                                                            <!-- <div class="controls"  v-for="(object ,index) in addForm.objectiveslist" :key="index">
                                                            <input type="text" name="work_with" v-model="addForm.objectiveslist[index].objective" placeholder="Objective"  class="form-control"  >
                                                        </div>  -->

                                                            <fieldset class="form-group">
                                                                <select id="Profession" required="required" name="work_station"
                                                                    v-model="addForm.objective_id"
                                                                    :class="{  'is-invalid': addForm.errors.has('month'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select Strategic Goal</option>
                                                                    <option v-for="row in objectiveList" :key="row.id"
                                                                        :value="row.id">
                                                                        {{ row.objective }}
                                                                    </option>
                                                                </select>
                                                            </fieldset>

                                                            <!-- <table class="table table-bordered table-sm">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Objective </th> 
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                                <template v-for="(row , index) in addForm.objectiveslist">
                                                                    <tr :key="index">
                                                                        <td>{{ index+1 }}</td>
                                                                        <td> 
                                                                            <input placeholder="Objective" style="width: 100%;" type="text" value=""
                                                                                v-model="addForm.objectiveslist[index].objective" />
                                                                        </td> 
                                                                        <td>
                                                                            <button class="btn-success" v-if="(addForm.objectiveslist.length -1 == index) && addForm.objectiveslist.length < 5" type="button" @click="addNewObject()" ><i class="bx bx-plus"></i></button>
                                                                            <button  class="btn-danger" v-if="index !=0" type="button" @click="removeNewObject(index)"  ><i  class="bx bx-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>

                                                        </table> -->

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Feedback </label>
                                                            <div class="controls">
                                                                <vue-editor name="remarks" v-model="addForm.remarks"
                                                                    :class="{  'is-invalid': addForm.errors.has('remarks'),  }">
                                                                </vue-editor>
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
    import axios from "../../axios_instance";
    import { Form } from "vform";
    import Datepicker from 'vuejs-datepicker';
    import { VueEditor } from "vue2-editor";
    import Multiselect from 'vue-multiselect'; 
    export default {
        props: {
        },
        components: {
            Datepicker,
            VueEditor,
            Multiselect
            // VueRecaptcha, facebookLogin
        },
        data() {
            return {
                base_url: window.base_url,
                api_url: window.api_url,
                token: this.$localStorage.get("d_token"),
                objectiveList: [],
                territoryList: [],
                pointList: [],
                fotList: [],
                routeList: [],
                user_data: JSON.parse(this.$localStorage.get("user")),
                addForm: new Form({
                    point: "Details",
                    work_with_details:"",
                    point_name: "",
                    point_id: "",
                    point_details:"",
                    sap_code: "",
                    territory: "",
                    territory_details: "",
                    route_name: "Details",
                    route_details: "",
                    objectives: "",
                    objective_id: "",
                    specia_objective: "",
                    contactperson: "",
                    hq: "",
                    work_with: "",
                    work_with_id: "",
                    work_station: "",
                    remarks: "",
                    feedback: "",
                    status: 0,
                    approval: 0,
                    date: new Date(),
                })
            };
        },
        created() {
            this.getObjective();
            this.getTerritory();
        },
        methods: {
            addNewObject() {
                this.addForm.objectiveslist.push({ objective: '' });
            },
            removeNewObject(i) {
                this.$swal({
                    title: "Are you sure you want to delete?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        this.addForm.objectiveslist.splice(i, 1);
                    }
                });
            },
            create() {
                try {
                    let loader = this.$loading.show();
                    //this.addForm.data  =  this.format_Date(this.addForm.data);
                    this.addForm.post(this.api_url + "tour_entries", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.$router.push('/tour_plan_entry');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async getObjective() { 
                let where = '?1=1';
                where += '&user_id=' + this.user_data.id;

                await axios.get(this.api_url + "tour_entrie_month_objectives" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.objectiveList = data.data
                        } 
                    });
            },
            async getTerritory() {
                let where = '?1=1';
                where += '&empid=' + this.user_data.employee_id;

                await axios.get(this.api_url + "tour_territory" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    if (data.success) {
                        this.territoryList = data.data
                    } 
                });
            },
            point(territory) {
                let where = '?1=1';
                where += '&terri_id=' + territory;
                axios.get(this.api_url + "tour_point" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    if (data.success) {
                        // this.fotList = [];
                        // this.routeList = [];
                        // this.addForm.route_details = [];
                        // this.pointList = data.data
                        if (Array.isArray(data.data)) {
                            this.pointList.push(...data.data);
                        } else {
                            this.pointList.push(data.data);
                        }
                    } 
                });
            }, 
            fo(point_id) {
                let where = '?1=1';
                where += '&point_id=' + point_id;
                axios.get(this.api_url + "point_fo" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({
                    data
                }) => {
                    if (data.success) {
                        if (Array.isArray(data.data.fo_list)) {
                            this.fotList.push(...data.data.fo_list);
                        } else {
                            this.fotList.push(data.data.fo_list);
                        }  
                        
                        if (Array.isArray(data.data.route_list)) {
                            this.routeList.push(...data.data.route_list);
                        } else {
                            this.routeList.push(data.data.route_list);
                        }                            
                        // this.fotList = data.data.fo_list;
                        // this.routeList = data.data.route_list;
                    } 
                });
            },
            concatenateValues() { 
                const selectedOptionIndex = event.target.selectedIndex;
                const selectedRow = this.pointList[selectedOptionIndex-1];
                this.addForm.point = selectedRow.point_name;
                this.addForm.sap_code = selectedRow.sap_code;               
            },  
            concatenateValuesFo() {
                const selectedOptionIndex = event.target.selectedIndex;
                const selectedRow = this.fotList[selectedOptionIndex-1];               
                this.addForm.work_with = selectedRow.display_name;
                // this.addForm.work_with_id = selectedRow.email;
            },  
            territoyDetaisSelect(selectedValues) {
                let lastTerriData = selectedValues[selectedValues.length - 1];
                // console.log('territoyDetaisSelect' , lastTerriData);
                this.point(lastTerriData.id);
            },           
            PointDetaisSelect(selectedValues) {
                let lastData = selectedValues[selectedValues.length - 1];
                // console.log('PointDetaisSelect' , lastData);
                this.fo(lastData.point_id); 
            },    
            WorkWithDetaisSelect(selectedValues) {
                let WorkWithlastData = selectedValues[selectedValues.length - 1];
                // console.log('WorkWithDetaisSelect' , WorkWithlastData);
            },   
            RouteDetaisSelect(selectedValues) {
                let RouteDetaislastData = selectedValues[selectedValues.length - 1];
                // console.log('RouteDetaisSelect' , RouteDetaislastData);
            },                   
            
        },
        computed: {
                   
        },
    };
</script>