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
                                            <router-link :to="{ path: '/monthly_report' }"> Monthly Report 
                                            </router-link>
                                        </li>
                                        <li class="breadcrumb-item active">New Monthly Report
                                        </li>

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <section class="basic-datatable">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a v-for="(row, index) in tabs" :key="index" class="nav-item nav-link"
                                    @click="tabsSet(row.id , index )" id="nav-home-tab" data-toggle="tab"
                                    href="#nav-home" role="tab" v-bind:class="{ active: index == 0 }"
                                    aria-controls="nav-home" aria-selected="true">{{ row.name }}</a>
                            </div>
                        </nav>
                        <div class="users-list-filter px-1">
                            <div class="col-md-12">
                                <form @submit.prevent="create()">
                                    <div class="card">

                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="row" v-if="tabs[type_select].id !='wing_report'">
                                                    <div class="col-md-12">
                                                        <div class="card-header">
                                                            <h4 class="card-title">{{ tabs[type_select].name}}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5"
                                                        v-if="tabs[type_select].id =='work_with_plan' ||  tabs[type_select].id =='without_plan' || tabs[type_select].id =='undone'">
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
                                                    <div class="col-md-5"
                                                        v-if="tabs[type_select].id =='work_with_plan' ||  tabs[type_select].id =='without_plan' || tabs[type_select].id =='undone'">
                                                        <div class="form-group">
                                                            <label for="Profession">KPI</label>
                                                            <div class="controls">
                                                                <select id="Profession" name="kpi_id"
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
                                                    <div class="col-md-10"
                                                        v-if="addForm.kra_id =='' && (tabs[type_select].id =='work_with_plan' ||  tabs[type_select].id =='without_plan' || tabs[type_select].id =='undone')">
                                                        <div class="form-group">
                                                            <label for="Profession">KPI</label>
                                                            <div class="controls">
                                                                <input type="text" name="custom_kra"
                                                                    v-model="addForm.custom_kra"
                                                                    :class="{  'is-invalid': addForm.errors.has('custom_kra'),  }"
                                                                    class="form-control"
                                                                    data-validation-required-message="This field is required"
                                                                    placeholder="KRA">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div v-if="tabs[type_select].id =='work_with_plan' ||  tabs[type_select].id =='without_plan' || tabs[type_select].id =='undone'"
                                                        class="col-md-10">
                                                        <div class="form-group">
                                                            <label>Work Details</label>
                                                            <div class="controls">
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="monthly_work" ref="editor"
                                                                    style="height: 600px;"
                                                                    v-model="addForm.monthly_work"></ckeditor>
                                                                <!-- <Vueditor name="monthly_work"  ref="monthly_work" style="min-height: 300px;" v-model="addForm.monthly_work"></Vueditor> -->
                                                                <!-- <vue-editor name="monthly_work" v-model="addForm.monthly_work" :class="{  'is-invalid': addForm.errors.has('monthly_work'),  }" ></vue-editor>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="tabs[type_select].id =='others'" class="col-md-10">
                                                        <div class="form-group">
                                                            <label>Top Priority for {{monthNames[current_month]}} month
                                                            </label>
                                                            <div class="controls">
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="topforcurrentmonth" ref="editor"
                                                                    style="height: 600px;"
                                                                    v-model="addForm.topforcurrentmonth"></ckeditor>
                                                                <!-- <Vueditor name="topforcurrentmonth"  ref="topforcurrentmonth" style="min-height: 300px;" v-model="addForm.topforcurrentmonth"></Vueditor> -->
                                                                <!-- <vue-editor name="topforcurrentmonth" v-model="addForm.topforcurrentmonth" :class="{  'is-invalid': addForm.errors.has('topforcurrentmonth'),  }" ></vue-editor>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="tabs[type_select].id =='others'" class="col-md-10">
                                                        <div class="form-group">
                                                            <label>Man Power Efficiency: </label>
                                                            <div class="controls">
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="topforcurrentmonth" ref="editor"
                                                                    style="height: 600px;"
                                                                    v-model="addForm.man_power_efficiency"></ckeditor>

                                                                <!-- <Vueditor name="man_power_efficiency"  ref="man_power_efficiency" style="min-height: 300px;" v-model="addForm.man_power_efficiency"></Vueditor> -->
                                                                <!-- <vue-editor name="man_power_efficiency" v-model="addForm.man_power_efficiency" :class="{  'is-invalid': addForm.errors.has('man_power_efficiency'),  }" ></vue-editor>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="tabs[type_select].id =='single_page'" class="col-md-10">
                                                        <input type="checkbox" @change="changeCheck()"
                                                            v-model="work_with_plan">Work with plan
                                                        <input type="checkbox" @change="changeCheck()"
                                                            v-model="without_plan"> Without plan
                                                        <input type="checkbox" @change="changeCheck()" v-model="undone">
                                                        Undone
                                                        <input type="checkbox" @change="changeCheck()" v-model="others">
                                                        Others
                                                        <div class="form-group">
                                                            <label>Details</label>
                                                            <div class="controls">
                                                                <ckeditor :editor="editor" :config="editorConfig"
                                                                    name="task" ref="editor" style="height: 600px;"
                                                                    v-model="addForm.task"></ckeditor>
                                                                <!-- <Vueditor name="task"  ref="task" style="min-height: 300px;" v-model="addForm.task"></Vueditor> -->
                                                                <!-- <vue-editor name="task" v-model="addForm.task" :class="{  'is-invalid': addForm.errors.has('task'),  }" ></vue-editor>  -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-10"
                                                        v-if="tabs[type_select].id =='work_with_plan' ||  tabs[type_select].id =='without_plan' || tabs[type_select].id =='undone' || tabs[type_select].id == 'single_page'">
                                                        <ul class="list-group" v-if="file_List.length > 0">
                                                            <li class="list-group-item disabled">Files</li>
                                                            <li v-for="(item , index ) in file_List" :key="index"
                                                                class="list-group-item">
                                                                <img v-if="filelistData[index].type == 'image/png' ||
                                                                    filelistData[index].type ==  'image/jpeg'
                                                                  " width="50px" :src="item" />
                                                                <img v-if="filelistData[index].type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                                                  " width="50px"
                                                                    :src=" base_url + 'assets/app-assets/images/pages/Google sheets.png'" />
                                                                <img v-if="filelistData[index].type == 'application/pdf'
                                                                  " width="50px"
                                                                    :src=" base_url + 'assets/app-assets/images/pages/pdf.png'" />
                                                                <img v-if="filelistData[index].type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                                                  " width="50px"
                                                                    :src=" base_url + 'assets/app-assets/images/pages/docs.png'" />
                                                                <img v-if="filelistData[index].type == 'application/vnd.ms-excel'
                                                                  " width="50px"
                                                                    :src=" base_url + 'assets/app-assets/images/pages/application-vnd.ms-excel.png'" />

                                                                <i> {{filelistData[index].name}}</i>
                                                            </li>

                                                        </ul>

                                                        <div class="form-group">
                                                            <label>Files</label>
                                                            <div class="controls">
                                                                <input type="file" multiple class="form-control"
                                                                    ref="file" @change="handleFileObject()" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>

                                                </div>

                                                <div v-if="  tabs[type_select].id == 'work_with_plan'"
                                                    class="col-md-12">
                                                    <div class="table-responsive">

                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>KRA</th>
                                                                    <th>WORK</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-for="(item, index) in items" :key="index">
                                                                <tr v-if="item.worktype === 'work_with_plan'">
                                                                    <td> {{ item.krajoin ? item.krajoin.kra_name :
                                                                        item.custom_kra }} </td>
                                                                    <td>
                                                                        <p v-html="item.monthly_work"></p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <a class="dropdown-item"
                                                                                    @click="popUp(item)"><i
                                                                                        class="bx bx-edit-alt mr-1"></i>
                                                                                    Update</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_row(item.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>
                                                                                    Delete</a>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-if="  tabs[type_select].id == 'without_plan'" class="col-md-12">
                                                    <div class="table-responsive">

                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>KRA</th>
                                                                    <th>WORK</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-for="(item, index) in items" :key="index">
                                                                <tr v-if="item.worktype === 'without_plan'">
                                                                    <td> {{ item.krajoin ? item.krajoin.kra_name : '' }}
                                                                    </td>
                                                                    <td>
                                                                        <p v-html="item.monthly_work"></p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <a class="dropdown-item"
                                                                                    @click="popUp(item)"><i
                                                                                        class="bx bx-edit-alt mr-1"></i>
                                                                                    Update</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_row(item.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>
                                                                                    Delete</a>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-if="  tabs[type_select].id == 'undone'" class="col-md-12">
                                                    <div class="table-responsive">

                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>KRA</th>
                                                                    <th>WORK</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-for="(item, index) in items" :key="index">
                                                                <tr v-if="item.worktype === 'undone'">
                                                                    <td> {{ item.krajoin ? item.krajoin.kra_name : '' }}
                                                                    </td>
                                                                    <td>
                                                                        <p v-html="item.monthly_work"></p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <a class="dropdown-item"
                                                                                    @click="popUp(item)"><i
                                                                                        class="bx bx-edit-alt mr-1"></i>
                                                                                    Update</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_row(item.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>
                                                                                    Delete</a>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-if="  tabs[type_select].id == 'single_page'" class="col-md-10">
                                                    <br><br>
                                                    <div class="table-responsive animated zoomIn">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Single Page</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-for="(item, index) in items" :key="index">
                                                                <tr v-if="item.worktype === 'single_page'">

                                                                    <td>
                                                                        <p v-html="item.monthly_work"></p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <a class="dropdown-item"
                                                                                    @click="popUp(item)"><i
                                                                                        class="bx bx-edit-alt mr-1"></i>
                                                                                    Update</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_row(item.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>
                                                                                    Delete</a>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div v-if="  tabs[type_select].id == 'others'" class="col-md-10">
                                                    <br><br>
                                                    <div class="table-responsive animated zoomIn">

                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Priority</th>
                                                                    <th scope="col">Manpower</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-for="(item, index) in items" :key="index">
                                                                <tr v-if="item.worktype === 'others'">
                                                                    <td>
                                                                        <p v-html="item.topforcurrentmonth"></p>
                                                                    </td>
                                                                    <td>
                                                                        <p v-html="item.man_power_efficiency"></p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropup">
                                                                            <span
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">
                                                                                <a class="dropdown-item"
                                                                                    @click="popUp(item)"><i
                                                                                        class="bx bx-edit-alt mr-1"></i>
                                                                                    Update</a>
                                                                                <a class="dropdown-item"
                                                                                    @click="delete_row(item.id)"><i
                                                                                        class="bx bx-trash mr-1"></i>
                                                                                    Delete</a>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- Input Validation end -->
                    <modal width="75%" height="96%" style="padding:50px" name="update">
                        <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>

                        <div class="app-content ">
                            <div class="card">
                                <form @submit.prevent="update()">
                                    <div class="row" v-if="item.worktype !='wing_report'">

                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6"
                                                    v-if="item.worktype =='work_with_plan' ||  item.worktype =='without_plan' || item.worktype =='undone'">
                                                    <div class="form-group">
                                                        <label for="Profession">KRA</label>
                                                        <div class="controls">
                                                            <select id="Profession" name="kra_id" @change="getKpi()"
                                                                v-model="editForm.kra_id"
                                                                :class="{  'is-invalid': editForm.errors.has('kra_id'),  }"
                                                                class="form-control">
                                                                <option value="">Select one</option>
                                                                <option v-for="row in kraItem" :key="row.id"
                                                                    :value="row.id">{{ row.kra_name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6"
                                                    v-if="item.worktype =='work_with_plan' || item.typr =='without_plan' || item.worktype=='undone'">
                                                    <div class="form-group">
                                                        <label for="Profession">KPI</label>
                                                        <div class="controls">
                                                            <select id="Profession" name="kpi_id"
                                                                v-model="editForm.kpi_id"
                                                                :class="{  'is-invalid': editForm.errors.has('kpi_id'),  }"
                                                                class="form-control">
                                                                <option value="">Select one</option>
                                                                <option v-for="row in kpiItem" :key="row.id"
                                                                    :value="row.id">{{ row.kpi_name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- // custom_kra -->
                                                <div class="col-md-6"
                                                    v-if="item.worktype =='work_with_plan' || item.typr =='without_plan' || item.worktype=='undone'">
                                                    <div class="form-group">
                                                        <label for="Profession">KPI</label>
                                                        <div class="controls">
                                                            <input type="text" name="custom_kra"
                                                                v-model="editForm.custom_kra"
                                                                :class="{  'is-invalid': addForm.errors.has('custom_kra'),  }"
                                                                class="form-control"
                                                                data-validation-required-message="This field is required"
                                                                placeholder="KRA">
                                                            <!-- custom_kra -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if=" item.worktype=='work_with_plan' ||    item.worktype=='without_plan' ||  item.worktype =='undone'"
                                                    class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Work Details</label>
                                                        <div class="controls">
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="monthly_work" ref="editor" style="height: 600px;"
                                                                v-model="editForm.monthly_work"></ckeditor>
                                                            <!-- <Vueditor name="monthly_work_edit"  ref="monthly_work_edit" style="min-height: 300px;" v-model="editForm.monthly_work_edit"></Vueditor> -->
                                                            <!-- <vue-editor name="monthly_work" v-model="editForm.monthly_work" :class="{  'is-invalid': editForm.errors.has('monthly_work'),  }" ></vue-editor>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if=" item.worktype =='others'" class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Top Priority for {{monthNames[current_month]}} month
                                                        </label>
                                                        <div class="controls">
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="monthly_work" ref="editor" style="height: 600px;"
                                                                v-model="editForm.topforcurrentmonth"></ckeditor>
                                                            <!-- <Vueditor name="topforcurrentmonth"  ref="topforcurrentmonth" style="min-height: 300px;" v-model="editForm.topforcurrentmonth"></Vueditor> -->
                                                            <!-- <vue-editor name="topforcurrentmonth" v-model="editForm.topforcurrentmonth" :class="{  'is-invalid': editForm.errors.has('topforcurrentmonth'),  }" ></vue-editor>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if=" item.worktype =='others'" class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Man Power Efficiency: </label>
                                                        <div class="controls">
                                                            <!-- <Vueditor name="man_power_efficiency"  ref="man_power_efficiency" style="min-height: 300px;" v-model="editForm.man_power_efficiency"></Vueditor> -->
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="monthly_work" ref="editor" style="height: 600px;"
                                                                v-model="editForm.man_power_efficiency"></ckeditor>
                                                            <!-- <vue-editor name="man_power_efficiency" v-model="editForm.man_power_efficiency" :class="{  'is-invalid': editForm.errors.has('man_power_efficiency'),  }" ></vue-editor>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if=" item.worktype=='single_page'" class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Details</label>
                                                        <div class="controls">
                                                            <ckeditor :editor="editor" :config="editorConfig"
                                                                name="monthly_work" ref="editor" style="height: 600px;"
                                                                v-model="editForm.task"></ckeditor>
                                                            <!-- <Vueditor name="task"  ref="task" style="min-height: 300px;" v-model="editForm.task"></Vueditor> -->
                                                            <!-- <vue-editor name="task" v-model="editForm.task" :class="{  'is-invalid': editForm.errors.has('task'),  }" ></vue-editor>  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit"
                                                        class="btn btn-primary add-btn">Update</button>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row"
                                                v-if=" item.worktype=='work_with_plan' ||  item.worktype =='without_plan' ||  item.worktype =='undone' ||  item.worktype == 'single_page'">
                                                <div class="form-group">
                                                    <label>New Files</label>
                                                    <div class="controls">
                                                        <input type="file" multiple class="form-control" ref="file"
                                                            @change="handleFileObject()" />
                                                        <button class="btn btn-primary add-btn"
                                                            @click="newFileUpload()"> <samp class="bx bx-save"></samp>
                                                            Save</button>
                                                    </div>

                                                </div>
                                            </div>

                                            <ul class="list-group">
                                                <li class="list-group-item disabled">Files</li>
                                                <li v-for="file in item.files" :key="file.id" class="list-group-item">

                                                    <img v-if="file.file_type =='pdf'" width="30px"
                                                        :src=" base_url + 'assets/app-assets/images/pages/pdf.png'" />
                                                    <img v-if="file.file_type =='docx'" width="30px"
                                                        :src="base_url + 'assets/app-assets/images/pages/docs.png'" />
                                                    <img v-if="file.file_type =='png'" width="30px"
                                                        :src="base_url + 'assets/app-assets/images/pages/png.png'" />
                                                    <img v-if="file.file_type =='jpeg'" width="30px"
                                                        :src="base_url + 'assets/app-assets/images/pages/jpeg.png'" />
                                                    <img v-if="file.file_type =='jpg'" width="30px"
                                                        :src="base_url + 'assets/app-assets/images/pages/jpg.png'" />
                                                    <img v-if="file.file_type =='xlsx'" width="30px"
                                                        :src="base_url + 'assets/app-assets/images/pages/Google sheets.png'" />
                                                    <i @click="fileDownload(file.id)"> {{file.file_caption}}</i> <a
                                                        class=""></a>
                                                    <a class="dropdown-item" @click="delete_row_file(file.id)"><samp
                                                            class="bx bx-trash file_delete "></samp></a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>


                                </form>
                            </div>
                        </div>
                    </modal>
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
    //import Datepicker from 'vuejs-datepicker';
    //import { VueEditor } from "vue2-editor";
    export default {
        props: {},
        components: {
            // Datepicker,
            //VueEditor
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

                monthNames: ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],

                base_url: window.base_url,
                api_url: window.api_url,
                DepartmentsItems: [],
                token: this.$localStorage.get("d_token"),
                user_data: JSON.parse(this.$localStorage.get("user")),
                tabs: [],
                items: [],
                item: [],
                report_type: "",
                type_select: 0,
                current_month: '',
                addForm: new Form({
                    kra_id: "",
                    kpi_id: "",
                    custom_kra: "",
                    monthly_work: "",
                    topforcurrentmonth: "",
                    man_power_efficiency: "",
                    worktype: "",
                    task: ""

                }),
                editForm: new Form({
                    kra_id: "",
                    kpi_id: "",
                    custom_kra: "",
                    monthly_work: "",
                    topforcurrentmonth: "",
                    man_power_efficiency: "",
                    worktype: ""

                }),
                filterForm: new Form({
                    dept_id: "",
                    month: "",
                    user_id: "",
                }),
                reportFile: [],
                file_List: [],
                filelistData: [],
                reportFileName: null,
                kraItem: [],
                kpiItem: [],
                mosItem: [],
                work_with_plan: false,
                without_plan: false,
                undone: false,
                others: false


            };
        },
        created() {
            const d = new Date(); 
            this.filterForm.dept_id = this.user_data.dept_id;
            this.filterForm.month = d.getMonth();

            this.current_month = d.getMonth();
            this.tabs = [{
                'id': 'work_with_plan',
                'name': ' Work with plan'
            }, {
                'id': 'without_plan',
                'name': 'Without plan'
            }, {
                'id': 'undone',
                'name': 'Undone'
            }, {
                'id': 'others',
                'name': 'Others'
            }, {
                'id': 'single_page',
                'name': 'Single Page'
            }, {
                'id': 'wing_report',
                'name': 'Wing Report'
            }]

            this.getKRA()
            this.dept();
            this.getItems();
        },
        methods: {

            popUp(item) {
                this.item = item;
                this.editForm.kpi_id = this.item.kpi_id;
                this.editForm.kra_id = this.item.kra_id;
                this.editForm.task = this.item.monthly_work;
                this.editForm.monthly_work = this.item.monthly_work;
                this.editForm.topforcurrentmonth = this.item.topforcurrentmonth;
                this.editForm.man_power_efficiency = this.item.man_power_efficiency;
                if (this.editForm.kra_id) {
                    this.getKpi_update(this.editForm.kra_id);
                }
                this.$modal.show("update");
                if (this.item.worktype == 'work_with_plan') {
                    this.$refs.monthly_work_edit.setContent(this.item.monthly_work);
                }
            },
            hide_pop() {
                this.$modal.hide("update");
            },
            tabsSet(id, index) {
                this.report_type = id;
                this.type_select = index;
            },
            async row_delete(id) {
                await axios
                    .delete(this.api_url + "monthly_reports/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then((res) => {
                        console.log(res);

                        if (res.data.success) {

                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }
                        this.getItems();
                        //loader.hide();
                    });

            },
            async delete_row(id) {

                this.$swal({
                        title: "Are you sure?",
                        text: "Are you sure you want to delete?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((data) => {
                        if (data) {
                            this.row_delete(id);
                        } else {
                            // loader.hide(); 
                            this.$swal("Your task status is not change!");
                        }
                    });



                console.log(id);
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "daily_schedules/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then(({
                            res
                        }) => {
                            if (res.data.success) {
                                this.$toasted.show(res.data.message, {
                                    theme: "bubble",
                                    duration: 5000,
                                    position: "bottom-right",
                                });
                            }
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },
            async row_delete_file(id) {
                await axios
                    .delete(this.api_url + "monthly_reports_file/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then((res) => {
                        console.log(res);

                        if (res.data.success) {

                            this.$toasted.show(res.data.message, {
                                theme: "bubble",
                                duration: 5000,
                                position: "bottom-right",
                            });
                        }

                        this.singel(this.item.id);
                        this.getItems();
                        //loader.hide();
                    });

            },
            async singel(id) {
                await axios
                    .get(this.api_url + "monthly_reports/" + id, {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: this.token ? `Bearer ${this.token}` : ""
                        },
                    })
                    .then(({
                        data
                    }) => {
                        if (data.success) {
                            this.item = data.data
                        }

                    });
            },
            async delete_row_file(id) {

                this.$swal({
                        title: "Are you sure?",
                        text: "Are you sure you want to delete?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((data) => {
                        if (data) {
                            this.row_delete_file(id);
                        } else {
                            // loader.hide(); 
                            this.$swal("Your task status is not change!");
                        }
                    });



                console.log(id);
                let loader = this.$loading.show();
                try {
                    await axios
                        .delete(this.api_url + "daily_schedules/" + id, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then(({
                            res
                        }) => {
                            if (res.data.success) {
                                this.$toasted.show(res.data.message, {
                                    theme: "bubble",
                                    duration: 5000,
                                    position: "bottom-right",
                                });
                            }
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            },

            async getItems() {
              //  if (this.filterForm.month && this.filterForm.dept_id) {
                    
                    let where = '?year=' + this.year; 

                    if (this.filterForm.dept_id) {
                        where += '&dept_id=' + this.filterForm.dept_id;
                    }
                    if (this.filterForm.month) {
                       where += '&month=' + this.filterForm.month;
                    }else{
                        let current = new Date();
                        let currentMonth = current.getMonth()+1;
                        let accutualMonth = currentMonth==1?12:current.getMonth()
                        where += '&month=' + accutualMonth;
                    }
                    
                    let loader = this.$loading.show();
                    try {
                        await axios
                            .get(this.api_url + "monthly_reports" + where, {
                                headers: {
                                    "Content-Type": "application/json",
                                    Authorization: this.token ? `Bearer ${this.token}` : ""
                                },
                            })
                            .then(({
                                data
                            }) => {
                                if (data.success) {
                                    this.items = data.data
                                }
                                loader.hide();
                            });
                    } catch (error) {
                        loader.hide();
                    }
               // }
            },
            update() {
                let loader = this.$loading.show();

                this.editForm.worktype = this.item.worktype;
                if (this.item.worktype == 'single_page') {
                    this.editForm.monthly_work = this.editForm.task;
                }
                this.editForm.put(this.api_url + "monthly_reports/" + this.item.id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                }).then((res) => {
                    console.log(res);
                    this.getItems();
                    if (res.data.success) {
                        this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
                    }
                    loader.hide();

                }, (error) => {
                    console.log(error);
                    loader.hide();
                })

            },

            create() {
                let loader = this.$loading.show();
                let formData = new FormData();
                let years =  this.year;
                formData.append("years", years);          
                      
                for (let i = 0; i < this.filelistData.length; i++) {
                    //let file = event.target.files[i];
                    formData.append('reportFile[' + i + ']', this.filelistData[i]);
                }

                formData.append("kra_id", this.addForm.kra_id);
                formData.append("kpi_id", this.addForm.kpi_id);
                formData.append("custom_kra", this.addForm.custom_kra);
                formData.append("monthly_work", this.addForm.monthly_work);
                formData.append("topforcurrentmonth", this.addForm.topforcurrentmonth);
                formData.append("man_power_efficiency", this.addForm.man_power_efficiency);
                formData.append("worktype", this.tabs[this.type_select].id);
                if (this.tabs[this.type_select].id == 'single_page') {
                    formData.append("monthly_work", this.addForm.task);
                }

                axios
                    .post(this.api_url + "monthly_reports", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    })
                    .then((res) => {
                            console.log(res.data.message);
                            loader.hide();
                            this.$modal.hide('file-upload');
                            this.$swal(res.data.message);
                            this.addForm.kpi_id = '';
                            this.addForm.kra_id = '';
                            this.addForm.monthly_work = '';
                            this.addForm.topforcurrentmonth = '';
                            this.addForm.man_power_efficiency = '';
                            this.addForm.task = '';
                            this.addForm.file_List = [];
                            //
                            this.getItems();
                            //this.$router.push('/monthly_report');

                        },
                        (err) => {
                            loader.hide();
                            console.log(err);
                        }
                    );

            },

            changeCheck() {
                let work_with_plan_text = '';
                let without_plan_text = '';
                let undone_text = '';
                let others_text = '';
                let others_text_man = '';
                //if(work_with_plan){ 
                for (let index = 0; index < this.items.length; index++) {
                    if (this.items[index].worktype == 'work_with_plan') {
                        work_with_plan_text += this.items[index].monthly_work + '<br>';
                    } else if (this.items[index].worktype == 'without_plan') {
                        without_plan_text += this.items[index].monthly_work + '<br>';
                    } else if (this.items[index].worktype == 'undone') {
                        undone_text += this.items[index].monthly_work + '<br>';
                    } else if (this.items[index].worktype == 'others') {
                        others_text += this.items[index].topforcurrentmonth + '<br>';
                        others_text_man += this.items[index].man_power_efficiency + '<br>';
                    }
                }
                var tablestr = "";

                if ((this.work_with_plan) && (this.without_plan) && (this.undone)) {
                    tablestr = "<table><tr><td>With Plan</td><td>Without Plan</td><td>Undone</td></tr><tr><td style='width:40%;vertical-align: baseline !important;'>" +
                        work_with_plan_text + "</td><td style='width:30%;vertical-align: baseline !important;'>" +
                        without_plan_text + "</td><td style='width:30%;vertical-align: baseline !important;'>" +
                        undone_text + "</td></tr></table>";

                } else if ((this.work_with_plan) && (this.without_plan)) {

                    tablestr = "<table><tr><td>With Plan</td><td>Without Plan</td></tr><tr><td style='width:40%;vertical-align: baseline !important;'>" +
                        work_with_plan_text + "</td><td style='width:30%;vertical-align: baseline !important;'>" +
                        without_plan_text + "</td></tr></table>";


                } else if ((this.work_with_plan) && (this.undown)) {
                    tablestr = "<table><tr><td>With Plan</td><td>Undone</td></tr><tr><td style='width:40%;vertical-align: baseline !important;'>" +
                        work_with_plan_text + "</td><td style='width:30%;vertical-align: baseline !important;'>" +
                        undone_text + "</td></tr></table>";


                } else if ((this.without_plan) && (this.undone)) {
                    tablestr = "<table><tr><td>Without Plan</td><td>Undone</td></tr><tr><td style='width:30%;vertical-align: baseline !important;'>" +
                        work_with_plan_text + "</td><td style='width:30%;vertical-align: baseline !important;'>" +
                        undone_text + "</td></tr></table>";

                } else if (this.work_with_plan) {
                    tablestr = "<table><tr><td>With Plan</td></tr><tr><td style='width:40%;vertical-align: baseline !important;'>" +
                        work_with_plan_text + "</td></tr></table>";


                } else if (this.without_plan) {
                    tablestr = "<table><tr><td>Without Plan</td></tr><tr><td style='width:30%;vertical-align: baseline !important;'>" +
                        without_plan_text + "</td></tr></table>";


                } else if (this.undone) {
                    tablestr = "<table><tr><td>Undone</td></tr><tr><td style='width:30%;vertical-align: baseline !important;'>" +
                        undone_text + "</td></tr></table>";
                }

                if (this.others) {
                    tablestr += "<table><tr><td>Top Priority</td></tr><tr><td style='width:30%;vertical-align: baseline !important;'>" +
                        others_text + "</td></tr></table>";

                    tablestr += "<table><tr><td>Man Power Efficiency</td></tr><tr><td style='width:30%;vertical-align: baseline !important;'>" +
                        others_text_man + "</td></tr></table>";
                }

                // if(topp){
                // tablestr= tablestr+"<table><tr><td colspan='2'>Top priority for Next month</td></tr><tr><td colspan='2' style='width: 20%; vertical-align: baseline !important;'>"+hidmanpower+"</td></tr><tr><td colspan='2'>Man Power Effective for Next month</td></tr><tr><td colspan='2' style='width: 20%; vertical-align: baseline !important;'>"+hidtopforcurrentmonth+"</td></tr></table>";
                // }
                this.addForm.task = tablestr;
                //  this.items
                //}

                // work_with_plan : false ,
                // without_plan :  false,
                // undone :  false ,
                // others :  false 
            },
            newFileUpload() {
                let loader = this.$loading.show();
                let formData = new FormData();

                for (let i = 0; i < this.filelistData.length; i++) {
                    formData.append('reportFile[' + i + ']', this.filelistData[i]);
                }

                formData.append("id", this.item.id);
                axios
                    .post(this.api_url + "monthly_reports_file_upload", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: this.token ? `Bearer ${this.token}` : "",
                        },
                    })
                    .then((res) => {
                            console.log(res.data.message);
                            loader.hide();
                            this.$modal.hide('file-upload');
                            this.$swal(res.data.message);
                            //
                            this.singel(this.item.id);
                            this.getItems();
                            //this.$router.push('/monthly_report');

                        },
                        (err) => {
                            loader.hide();
                            console.log(err);
                        }
                    );

            },
            handleFileObject() {
                // this.csv = this.$refs.file.files[0];
                // this.reportFile = this.$refs.file.files[0];
                let filelist = event.target.files;
                this.filelistData = filelist;
                console.log(filelist);

                //console.log( filelist);
                for (let i = 0; i < filelist.length; i++) {
                    let file = event.target.files[i];

                    let reader = new FileReader();
                    this.file_List.push(URL.createObjectURL(file));
                    // console.log(this.file_List);
                    reader.readAsDataURL(file);
                    reader.onload = (event) => {
                        console.log(event.target.result);
                        //this.reportFile.push(event.target.result);
                    }
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
                        console.log(this.kraItem);
                    });
            },
            async getKpi_update(id) {
                await axios.get(this.api_url + "k_p_i_s?kra_id=" + id, {
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