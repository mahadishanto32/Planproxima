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
                                            <router-link :to="{ path: '/daily_work' }"> Daily Work </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Add daily work
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
                                    <!-- <div class="card-header">
                                        <h4 class="card-title">Add daily work</h4>
                                    </div> -->
                                    <div class="card-content">
                                        <div class="card-body">
                                            <br><br>
                                            <Prioroty_task v-if="role_id < 6"/> 
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="addForm.date" name="date"
                                                                    :disabled-dates="state.disabledDates"
                                                                    class="form-control"></datepicker>

                                                                <!-- <input type="text" name="date" v-model="addForm.date" :class="{  'is-invalid': addForm.errors.has('date'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Date"> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">Start Time</label>
                                                            <div class="controls">
                                                                <input type="time" name="start_time"
                                                                    v-model="addForm.start_time"
                                                                    :class="{  'is-invalid': addForm.errors.has('start_time'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Start Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">End Time</label>
                                                            <div class="controls">
                                                                <input type="time" name="name"
                                                                    v-model="addForm.end_time"
                                                                    :class="{  'is-invalid': addForm.errors.has('end_time'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="End Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <div class="row">
                                                    
                                                    <div class="col-md-3"
                                                        v-if="role_id == 5 || role_id == 6 || role_id == 7">
                                                        <div class="form-group">
                                                            <label for="Profession">KRA</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kra_id" @change="getKpi()"
                                                                    v-model="addForm.kra_id"
                                                                    :class="{  'is-invalid': addForm.errors.has('kra_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kraItem" :key="row.id"
                                                                        :value="row.id">{{ row.kra_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"
                                                        v-if="role_id == 5 || role_id == 6 || role_id == 7">
                                                        <div class="form-group">
                                                            <label for="Profession">KPI</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kpi_id" @change="getMos()"
                                                                    v-model="addForm.kpi_id"
                                                                    :class="{  'is-invalid': addForm.errors.has('kpi_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in kpiItem" :key="row.id"
                                                                        :value="row.id">{{ row.kpi_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3"
                                                        v-if="role_id == 5 || role_id == 6 || role_id == 7">
                                                        <div class="form-group">
                                                            <label for="Profession">MOS</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="mos_id"
                                                                    v-model="addForm.mos_id"
                                                                    :class="{  'is-invalid': addForm.errors.has('mos_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in mosItem" :key="row.id"
                                                                        :value="row.id">{{ row.mos_name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row schedule_row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label>Work Details</label>
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="task" ref="editor" 
                                                                v-model="addForm.task"></ckeditor>
                                                            <!-- <Vueditor name="task"  ref="editor" style="min-height: 800px;" v-model="addForm.task"></Vueditor> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" v-if=" role_id == 5 ">
                                                        <div class="form-group"> 
                                                            <label for="Profession"></label>
                                                            <div class="controls custom_controls">
                                                               
                                                               <input v-model="addForm.top_priority" style="border: 1px solid #efefef ; border-radius:  10px;" type="checkbox"
                                                                    name="top_priority" value="1" ><label style="padding-left:5px ;" for="Profession">Top priority </label> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div v-if="scheduleTypes_allow == true">
                                                    <div class="row schedule_row"  v-for="(schedule ,index) in addForm.othersSchedules" :key="index">
                                                        <div class="item_removes"  ><i v-on:click="item_removes(index)" class="bx bxs-trash"></i></div>
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label>Work Details :  {{ scheduleTypesName(addForm.othersSchedules[index].schedule_type_id) }} </label>
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="task" ref="editor" style="height: 400px;"
                                                                    v-model="addForm.othersSchedules[index].schedule_details"></ckeditor> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="Profession">Schedule Type</label>
                                                                <div class="controls">
                                                                    <select id="Profession" name="mos_id"
                                                                        v-model="addForm.othersSchedules[index].schedule_type_id"
                                                                        :class="{  'is-invalid': addForm.errors.has('mos_id'),  }"
                                                                        class="form-control">
                                                                        <option value="">Select one</option>
                                                                        <option v-for="row in scheduleTypes" :key="row.id"
                                                                            :value="row.id">{{ row.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="scheduleTypes.length > addForm.othersSchedules.length " class="row">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-success add-more-task" v-on:click="item_add()" ><i class="bx bx-add-to-queue" style="padding-right: 5px;"></i>Add New</button>
                                                        
                                                    </div>
                                                </div> -->

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
        <div>
            <!-- <quasar-tiptap v-bind="options" @update="onUpdate" /> -->
        </div>
    </div>

</template>

<script>
    import axios from "../../axios_instance";
    import {
        Form
    } from "vform";
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Datepicker from 'vuejs-datepicker';
    import Prioroty_task from '../priority_task/priority_task.vue';
//  import Table from '@ckeditor/ckeditor5-table/src/table';
//  import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar';
    //import Font from '@ckeditor/ckeditor5-font/src/font';

    // import { Editor, EditorContent } from '@tiptap/vue-2'
    // import StarterKit from '@tiptap/starter-kit'

    //import StarterKit from '@tiptap/starter-kit'
    // import { VueEditor } from "vue2-editor"; 
    // import { QuasarTiptap, RecommendedExtensions } from 'quasar-tiptap'
    // import 'quasar-tiptap/lib/index.css' 
    //import { VueEditor } from 'vue2-quill-editor';
    //import { quillEditor } from 'vue-quill-editor'

    export default {
        props: {},
        components: {
            Prioroty_task,
            // EditorContent,
            Datepicker, 
            // VueEditor,
            //  QuasarTiptap
            //quillEditor
            // VueRecaptcha, facebookLogin 
        },
        data() {
            return {
                year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),

                editor: ClassicEditor,
                editorData: '',
                //plugins: [ Font ],
  
                editorConfig: {
                    //plugins: [ Table, TableToolbar ],
                    indent_style: 'tab',
                    tab_width: 4,
                    charset: 'utf-8',
                    end_of_line: 'lf',
                    trim_trailing_whitespace: true,
                    insert_final_newline: true, 
                    colorButton_enableAutomatic: false,
                    colorButton_enableMore: false,
                    colorButton_colors: '000000,167951,93C0BD,F48E00',
                    fontSize: {
                        options: [
                            9,
                            11,
                            13,
                            'default',
                            17,
                            19,
                            21
                        ]
                    },
                    toolbar: [
                         { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                        'heading', '|', 'bold', 'italic','|', 'link', 'bulletedList', 'numberedList', '|', 'indent', 'outdent','|', 'blockQuote', 'insertTable', '|',  'undo', 'redo' ,'|' ,'image',

                    ]
                    // table: {
                    //     contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    // },
                    // toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ,'tables' ]
                },
                // colorButton_colors : 'CF5D4E,454545,FFF,DDD,CCEAEE,66AB16',
                // customToolbar: [
                //     ["bold", "italic", "underline"],
                //     [{
                //         list: "ordered"
                //     }, {
                //         list: "bullet"
                //     }],
                //     ["image", "tables", "code-block"]
                // ],

                base_url: window.base_url,
                api_url: window.api_url,
                DepartmentsItems: [],
                token: this.$localStorage.get("d_token"),
                user_data: JSON.parse(this.$localStorage.get("user")),
                role_id: '',
                scheduleTypes : [],
                scheduleTypes_allow : false ,
                addForm: new Form({
                    task: "",
                    dept_id: "",
                    kra_id: "",
                    kpi_id: "",
                    mos_id: "",
                    top_priority: 0,
                    date: new Date(),
                    start_time: "08:30:00",
                    end_time: "17:15:00",
                    user_id: 1,
                    othersSchedules : [
                        {
                            schedule_type_id : 1  ,
                            schedule_details : ""
                        } 
                    ]

                }),
                kraItem: [],
                kpiItem: [],
                mosItem: [],  
                state: {
                    disabledDates: {
                        to: new Date(this.getToDate()),
                        //from: new Date(this.getFromDate()),
                    }
                }
            }
        },

        created() {
            this.role_id = this.user_data.role_id;
            this.getKRA()
            this.dept();  
            if(this.role_id == 1 || this.role_id == 2 ||  this.role_id == 3 || this.role_id == 4 | this.role_id == 5){ 
                this.scheduleTypes_allow =  true ;
                this.dailyScheduleTypes();
            }
        },
        methods: {
            getToDate() {
                var date = new Date();
                date.setDate(date.getDate() - 3);
                var finalDate = date.getFullYear() + ', ' + (date.getMonth() + 1) + ', ' + date.getDate();
                return finalDate; 
                //console.log('lastDate',finalDate);
            },
            async dailyScheduleTypes(){
                await axios.get(this.api_url + "daily_schedule_types", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.scheduleTypes = data.data;
                        console.log(this.scheduleTypes);
                    });
            },
            scheduleTypesName(id){  
                for (let index = 0; index < this.scheduleTypes.length; index++) { 
                    if(this.scheduleTypes[index].id == id){
                        return this.scheduleTypes[index].name ; 
                    }
                }
            },
            item_add(){
 
                let newItem = {
                        schedule_type_id : ""  ,
                        schedule_details : ""
                    }; 
                this.addForm.othersSchedules.push(newItem);
            },
            item_removes(id){
                console.log(id);
                //alert(id); 
                //this.addForm.othersSchedules
                this.$swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this item!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) { 
                            this.addForm.othersSchedules.splice(id, 1);
                            // this.$swal("Your item has been deleted!", {
                            // icon: "success",
                            // });
                        }  
                    });
            },

            getFromDate() {
                var date = new Date();
                date.setDate(date.getDate() + 1);
                var finalDate = date.getFullYear() + ', ' + (date.getMonth() + 1) + ', ' + date.getDate();
                return finalDate;

                //console.log('lastDate',finalDate);
            },


            onUpdate({
                getJSON,
                getHTML
            }) {
                this.json = getJSON()
                this.html = getHTML()
                console.log('html', this.html)
            },
            create() {
                try {
                    let loader = this.$loading.show();
                    // this.addForm.task = this.$refs.editor.getContent();
                    this.addForm.post(this.api_url + "daily_schedules", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        console.log(console.log(res.headers));
                        if (res.data.success) {
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.$router.push('/task');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async dept() {
                this.getDepartments().then(({
                    data
                }) => {
                    if (data.success) {
                        this.DepartmentsItems = data.data;
                    }
                });
            },

            async getKRA() {

                await axios.get(this.api_url + "k_r_a_s?year=" + this.year, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.kraItem = data.data;
                    });
            },
            async getKpi() {
                console.log(this.addForm.kra_id);
                await axios.get(this.api_url + "k_p_i_s?kra_id=" + this.addForm.kra_id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.kpiItem = data.data;
                        console.log(this.roles);
                    });
            },
            async getMos() {
                await axios.get(this.api_url + "m_o_s?kpi_id=" + this.addForm.kpi_id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.mosItem = data.data;
                        console.log(this.roles);
                    });
            },
            async getRole() {
                await axios.get(this.api_url + "role", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.roles = data.data;
                        console.log(this.roles);
                    });
            },
        },
        computed: {},
    };
</script>
<style>

</style>