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
                                        <li class="breadcrumb-item active"> Add fac Task
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
                                        <h4 class="card-title">Add daily factory work</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="create()">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="addForm.date" name="date"
                                                                    class="form-control"></datepicker>
                                                                <!-- <input type="text" name="date" v-model="addForm.date" :class="{  'is-invalid': addForm.errors.has('date'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Date"> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="Profession">Start Time</label>
                                                            <div class="controls">
                                                                <input type="text" name="start_time"
                                                                    v-model="addForm.start_time"
                                                                    :class="{  'is-invalid': addForm.errors.has('start_time'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Start Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">End Time</label>
                                                            <div class="controls">
                                                                <input type="text" name="name"
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
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="Profession">Factory Format</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="mos_id"
                                                                    v-model="addForm.factory_format_id"
                                                                    :class="{  'is-invalid': addForm.errors.has('mos_id'),  }"
                                                                    class="form-control">
                                                                    <option value="">Select one</option>
                                                                    <option v-for="row in factory_formats" :key="row.id"
                                                                        :value="row.id">{{ row.headname}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <div class="form-group">
                                                            <label>Work Details</label>
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="task" ref="editor" style="height: 600px;"
                                                                v-model="addForm.task"></ckeditor>
                                                            <!-- <Vueditor name="task"  ref="editor" style="min-height: 300px;" v-model="addForm.task"></Vueditor> -->
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
    import Datepicker from 'vuejs-datepicker';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    // import { Editor, EditorContent } from '@tiptap/vue-2'
    // import StarterKit from '@tiptap/starter-kit'

    //import StarterKit from '@tiptap/starter-kit'
    //import { VueEditor } from "vue2-editor"; 
    // import { QuasarTiptap, RecommendedExtensions } from 'quasar-tiptap'
    // import 'quasar-tiptap/lib/index.css' 
    //import { VueEditor } from 'vue2-quill-editor';
    //import { quillEditor } from 'vue-quill-editor'
    export default {
        props: {},
        components: {
            // EditorContent,
            Datepicker,
            // VueEditor,
            //  QuasarTiptap
            //quillEditor
            // VueRecaptcha, facebookLogin 
        },
        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {},
                customToolbar: [
                    ["bold", "italic", "underline"],
                    [{
                        list: "ordered"
                    }, {
                        list: "bullet"
                    }],
                    ["image", "tables", "code-block"]
                ],

                base_url: window.base_url,
                api_url: window.api_url,
                DepartmentsItems: [],
                token: this.$localStorage.get("d_token"),
                user_data: JSON.parse(this.$localStorage.get("user")),
                role_id: '',
                addForm: new Form({
                    task: "",
                    dept_id: "",
                    kra_id: "",
                    kpi_id: "",
                    mos_id: "",
                    factory_format_id: "",
                    top_priority: 0,
                    date: new Date(),
                    start_time: "09:30:00",
                    end_time: "18:30:00",
                    user_id: 1,

                }),
                factory_formats: [],
                //     options: {
                //     content: '',
                //     editable: true,
                //     extensions: [
                //       ...RecommendedExtensions,
                //       // other extenstions
                //       // name string, or custom extension
                //     ],
                //     toolbar: [
                //     //   'add-more',
                //     //   'separator',
                //     //   'bold',
                //     //   'italic',
                //     //   'underline',
                //       // other toolbar buttons
                //       // name string
                //     ]
                //   },
                //   json: '',
                //   html: ''
            }

        },

        created() {
            this.role_id = this.user_data.role_id;
            this.getFac()
            this.dept();
        },
        methods: {
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
                    //this.addForm.task = this.$refs.editor.getContent();
                    this.addForm.post(this.api_url + "daily_schedules", {
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

            async getFac() {
                await axios.get(this.api_url + "daily_schedule_headers", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.factory_formats = data.data;
                        console.log(this.factory_formats);
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