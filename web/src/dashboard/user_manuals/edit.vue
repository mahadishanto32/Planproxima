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
                                            <router-link :to="{ path: '/user_manuals' }"> User Manuals </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Edit User Manuals
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
                                        <h4 class="card-title">Edit User Manuals </h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="submitForm()">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <div class="controls">
                                                                <input type="text" name="title" v-model="addForm.title"
                                                                    :class="{  'is-invalid': addForm.errors.has('title'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="Title">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Details</label>
                                                            <div class="controls">
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="task" ref="editor" v-model="addForm.details">
                                                                </ckeditor>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label>Thumbnail</label> <br> 
                                                                <div class="controls">
                                                                    <input type="file" multiple class="form-control"
                                                                        ref="file" @change="handleFileObject()" />
                                                                </div>

                                                                <div class="fileinput fileinput-new" data-provides="fileinput" style="position: relative;">
                                                                    <input type="file" name="..." @change="onImageChange"/></span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="Profession">Status</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="status"
                                                                    v-model="addForm.status"
                                                                    :class="{  'is-invalid': addForm.errors.has('status'),  }"
                                                                    class="form-control">
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
        props: {
        },
        components: {
            // VueRecaptcha, facebookLogin
        },
        data() {
            return {
                base_url: window.base_url,
                api_url: window.api_url,
                token: this.$localStorage.get("d_token"),
                addForm: new Form({
                    title: "",
                    details: "",
                    status: 1,
                    thumbnail:''
                }),
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
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        'heading', '|', 'bold', 'italic', '|', 'link', 'bulletedList', 'numberedList', '|', 'indent', 'outdent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo', '|', 'image',

                    ]
                    // table: {
                    //     contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    // },
                    // toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ,'tables' ]
                },
                item:'',
                imagePreview:'',
            };
        },
        created() {
            this.getEditData(); 
        },
        methods: {
            async getEditData() {
                await axios.get(this.api_url + "user_manuals/"+this.$route.params.id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then(({ data }) => {
                    this.item = data.data;
                    this.addForm.title = data.data.title
                    this.addForm.details = data.data.details
                    this.addForm.status = data.data.status
                });
            },
            onImageChange(e) {
                this.addForm.thumbnail = e.target.files[0]
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;   
                this.createImage(files[0]); 
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => { 
                    vm.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            handleFileObject() {
                // this.csv = this.$refs.file.files[0];
                // this.reportFile = this.$refs.file.files[0];
                let filelist = event.target.files;
                this.filelistData = filelist;
                console.log(filelist);

                //console.log( filelist);
                //for (let i = 0; i < filelist.length; i++) {
                    let file = event.target.files[0];

                    let reader = new FileReader();
                    this.addForm.thumbnail = URL.createObjectURL(file);
                    // console.log(this.file_List);
                    reader.readAsDataURL(file);
                    reader.onload = (event) => {
                        console.log(event.target.result);
                        //this.reportFile.push(event.target.result);
                    }
                //}
            },

            submitForm: function(e) {  
                let loader = this.$loading.show(); 
                const formData = new FormData();   
                formData.append('title', this.addForm.title);   
                formData.append('details', this.addForm.details);   
                formData.append('status', this.addForm.status);   
                formData.append('_method', "PUT");   
                //this.addForm.thumbnail ? formData.append('thumbnail', this.addForm.thumbnail, this.addForm.thumbnail.name) : ''; 
                var postEvent = axios.post(this.api_url+'user_manuals/'+this.$route.params.id, formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    });                     

                postEvent.then(res => { 
                    if (res.data.success) {
                        this.addForm.name = '';
                        this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
                    }
                    loader.hide();
                    //this.$router.push('/user_manuals');
                }).catch(err => { 
                    loader.hide();
                });
            }
        },
        computed: {},
    };
</script>