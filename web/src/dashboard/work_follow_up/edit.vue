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
                                            <router-link :to="{ path: '/work_follow_up' }"> Follow UP </router-link>
                                        </li>
                                        <li class="breadcrumb-item active"> Edit Follow UP
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
                                        <h4 class="card-title">Edit Follow UP</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form @submit.prevent="update()">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="Profession">Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="editForm.date" name="date"
                                                                    class="form-control"></datepicker>
                                                                <!-- <input type="text" name="date" v-model="editForm.date" :class="{  'is-invalid': editForm.errors.has('date'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Date"> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="Profession">Complete Date</label>
                                                            <div class="controls">
                                                                <datepicker v-model="editForm.complete" name="complete"
                                                                    class="form-control"></datepicker>
                                                                <!-- <input type="text" name="date" v-model="editForm.date" :class="{  'is-invalid': editForm.errors.has('date'),  }" class="form-control" data-validation-required-message="This field is required" placeholder="Date"> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="Profession">Dept</label>
                                                            <div class="controls">
                                                                <fieldset class="form-group">
                                                                    <multiselect v-model="dept_selects" :options="items"
                                                                        :multiple="true" placeholder="Select(Dept)"
                                                                        :label="'name'" track-by="id" :searchable="true"
                                                                        :close-on-select="false" :show-labels="false">
                                                                        <template slot="selection"
                                                                            slot-scope="{ values , isOpen }"><span
                                                                                class="multiselect__single"
                                                                                v-if="values.length &amp;&amp; !isOpen">{{
                                                                                values.length }} options
                                                                                selected</span></template>
</multiselect>
</fieldset>
</div>
</div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Work Details</label>
            <ckeditor :editor="editor" :config="editorConfig" name="details" ref="editor" style="height: 600px;" v-model="editForm.details"></ckeditor>
            <!-- <Vueditor name="task"  ref="editor" style="min-height: 800px;" v-model="editForm.task"></Vueditor> -->
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
    import {
        Form
    } from "vform";
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';
    export default {
        props: {},
        components: {
            Datepicker,
            Multiselect
            // VueRecaptcha, facebookLogin
        },
        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                items: [],
                dept_selects: [],
                id: this.$route.params.id,
                editorConfig: {
                    indent_style: 'tab',
                    tab_width: 4,
                    charset: 'utf-8',
                    end_of_line: 'lf',
                    trim_trailing_whitespace: true,
                    insert_final_newline: true
                },
                base_url: window.base_url,
                api_url: window.api_url,
                token: this.$localStorage.get("d_token"),
                editForm: new Form({

                }),

            };
        },
        created() {
            this.getItem("follow_ups/" + this.id).then(({
                data
            }) => {
                if (data.success) {
                    this.item = data.data;
                    //`date`, `details`, `complete`
                    this.editForm.date = this.item.date;
                    this.editForm.complete = this.item.complete;
                    this.editForm.details = this.item.details;
                }
            });
            this.getItems();
            this.getDeptSelect();

        },
        methods: {
            create() {

                try {
                    let loader = this.$loading.show();
                    this.editForm.dept_selects = this.dept_selects;
                    this.editForm.post(this.api_url + "follow_ups", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    }).then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            this.editForm.name = '';
                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        loader.hide();
                        this.$router.push('/work_follow_up');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async getItems() {
                //departments_all
                let loader = this.$loading.show();
                await axios.get(this.api_url + "departments", {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.items = data.data;
                        loader.hide();
                        console.log(this.WingsItems);
                    });

            },
            update() {
                try {
                    let loader = this.$loading.show();
                    this.editForm.dept_selects = this.dept_selects;
                    this.editForm.date = this.editForm.date ? this.format_Date(this.editForm.date) : '';
                    this.editForm.complete = this.editForm.complete ? this.format_Date(this.editForm.complete) : '';
                    this.editForm.put(this.api_url + "follow_ups/" + this.id, {
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
                        this.$router.push('/work_follow_up');
                    }, (error) => {
                        console.log(error);
                        loader.hide();
                    })
                } catch (error) {
                    // loader.hide(); 
                    console.log(error);
                }
            },
            async getDeptSelect() {
                //departments_all
                let loader = this.$loading.show();
                await axios.get(this.api_url + "follow_up_dept?activity_id=" + this.id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        this.dept_selects = data.data
                        loader.hide();
                        console.log(this.WingsItems);
                    });

            },
        },
        computed: {},
    };
</script>