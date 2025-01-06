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
                                        <li class="breadcrumb-item active">Settings KRA, KPI and MOS
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <transition>
                        <section class="input-validation">
                            <div class="users-list-filter px-1">
                                <div class="row border rounded py-2 mb-2">
                                    <div class="col-sm-6 col-lg-2"
                                        v-if="deptItems.length > 1 && user_data.email != 'cost'">
                                        <label for="users-list-verified">Department </label>
                                        <fieldset class="form-group">
                                            <select class="form-control" v-on:change="deptChange()"
                                                v-model="filterForm.dept_id" id="users-list-verified">
                                                <option value="">Select One</option>
                                                <option v-for="row in deptItems" :key="row.id" :value="row.id">
                                                    {{ row.name }}
                                                </option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="ccol-sm-6 col-lg-2" v-if="role_id == 1 || role_id == 5 || role_id == 6">
                                        <label for="users-list-verified">Wings</label>
                                        <fieldset class="form-group">
                                            <select class="form-control" v-on:change="changeEmployee()"
                                                v-model="filterForm.wing_id" id="users-list-verified">
                                                <option value="">Select One</option>
                                                <option v-for="row in WingsItems" :key="row.id" :value="row.id">
                                                    {{ row.wing_title }}
                                                </option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6 col-lg-2" v-if="role_id == 1 || role_id == 5 || role_id == 6">
                                        <label for="users-list-verified">Employee</label>
                                        <fieldset class="form-group">
                                            <Select2 placeholder="Select One" v-on:change="getItemsData()"
                                                v-model="filterForm.user_id" :options="employeeItem" />
                                        </fieldset>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">


                                        <div class="card-header">
                                            <h4 class="card-title">Settings KRA, KPI and MOS</h4>
                                        </div>
                                        <div class="card-content">
                                            <form @submit.prevent="create()">
                                                <div class="card-body">
                                                    <label class="text-gray-600 font-semibold text-lg">KRA</label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <input type="text" v-model="kar_kpi_mos.name"
                                                                        class="form-control" placeholder="Entry KRA">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <input type="number" @keypress="onlyNumber"
                                                                        v-model="kar_kpi_mos.kra_weight"
                                                                        class="form-control"
                                                                        placeholder="KRA WEIGHTAGE">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <button type="button"
                                                                        @click="addFieldKPI(kar_kpi_mos.children)"
                                                                        class="btn btn-primary">Add KPI</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div v-for="(item, index) in kar_kpi_mos.children"
                                                            :key="`phoneInput-${index}`"
                                                            class="input wrapper flex items-center label_2">
                                                            <label class="text-gray-600 font-semibold text-lg">KPI {{
                                                                    index + 1
                                                            }}</label>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <input type="text" v-model="item.name"
                                                                                class="form-control"
                                                                                placeholder="Entry KPI">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <input type="number" @keypress="onlyNumber"
                                                                                v-model="item.kpi_weight"
                                                                                class="form-control"
                                                                                placeholder="KPI WEIGHTAGE">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="controls">
                                                                            <button type="button"
                                                                                @click="addFieldMOS(item)"
                                                                                class="btn btn-primary">Add MOS</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-for="(row, m ) in kar_kpi_mos.children[index].children"
                                                                :key="`phoneInput-${m}`"
                                                                class="input wrapper flex items-center label_3">
                                                                <label class="text-gray-600 font-semibold text-lg">MOS
                                                                    {{ m + 1 }}</label>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <div class="controls">
                                                                                <input type="text" v-model="row.name"
                                                                                    class="form-control"
                                                                                    placeholder="Enter MOS Name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">

                                                                        <div class="form-group">
                                                                            <div class="controls">
                                                                                <input type="text" v-model="row.weightage"
                                                                                    class="form-control"
                                                                                    placeholder="MOS WEIGHTAGE">
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    

                                                                    
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>

                                        
                                    </div>

                                </div>

                            </div>

                        </section>
                    </transition>



                </div>
                <div class="content-body">
                    <section id="basic-datatable">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>KRA</th>
                                                            <th>Weightage</th>
                                                            <th>KPI</th>
                                                            <th>MOS</th>
                                                            <th>Jan</th>
                                                            <th>Feb</th>
                                                            <th>Mar</th>
                                                            <th>Apr</th>
                                                            <th>May</th>
                                                            <th>Jun</th>
                                                            <th>Jul</th>
                                                            <th>Aug</th>
                                                            <th>Sep</th>
                                                            <th>Oct</th>
                                                            <th>Nov</th>
                                                            <th>Dec</th>
                                                            <th v-if="role_id == 5 || role_id == 6">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template v-for="(item, index ) in itemsData">

                                                            <tr :key="item.id">
                                                                <!-- <td :rowspan="item.kra_count"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0 ].kra_id != item.kra_id || index ==0">
                                                                    {{ item.krajoin ? item.krajoin.kra_name : '' }}</td>
                                                                <td :rowspan="item.kra_count"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0 ].kra_id != item.kra_id || index ==0">
                                                                    {{ item.krajoin ? item.krajoin.kra_weight : '' }}
                                                                </td>
                                                                <td :rowspan="item.kpi_count"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0 ].kpi_id != item.kpi_id || index ==0">
                                                                    {{ item.kpijoin ? item.kpijoin.kpi_name : '' }}</td>
                                                                <td>{{ item.mos_name }}</td> -->

                                                                <td :rowspan="rowVisible(index, item, 'kra')"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0].kra_id != item.kra_id || index == 0">
                                                                    {{ item.krajoin ? item.krajoin.kra_name : '' }}

                                                                </td>
                                                                <td :rowspan="rowVisible(index, item, 'kra')"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0].kra_id != item.kra_id || index == 0">
                                                                    {{ item.krajoin ? item.krajoin.kra_weight : '' }}
                                                                </td>
                                                                <td :rowspan="rowVisible(index, item, 'kpi')"
                                                                    v-if="itemsData[index > 0 ? index - 1 : 0].kpi_id != item.kpi_id || index == 0">
                                                                    {{ item.kpijoin ? item.kpijoin.kpi_name : '' }}

                                                                </td>
                                                                <!-- <td v-if="filterForm.show_mos==1">{{ item.mos_name }}  ({{ Number(moduleTotal(item)).toFixed(2) }}) </td> -->
                                                                <td>{{ item.mos_name }}

                                                                </td>

                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.january : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.february : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.march : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.april : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.may : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.june : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.july : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.august : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.september : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.october : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.november : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td><i v-if="item.mostargetjoin ? item.mostargetjoin.december : 0 > 0"
                                                                        class="bx bx-map"></i> </td>
                                                                <td v-if="role_id == 5 || role_id == 6">
                                                                    <div class="dropup">
                                                                        <span
                                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false" role="menu">
                                                                        </span>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <!-- <a class="dropdown-item" @click="add_kpi(item )"><i class="bx bxs-comment-add mr-1"></i> Add || Edit KPI </a>   -->
                                                                            <router-link class="dropdown-item"
                                                                                :to="{ path: '/measure_of_success/' + item.kpi_id }">
                                                                                <i class="bx bx-edit-alt mr-1"></i> MOS
                                                                            </router-link>

                                                                        </div>
                                                                    </div>


                                                                </td>
                                                            </tr>

                                                        </template>
                                                    </tbody>

                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <modal width="60%" height="70%" style="padding:50px" name="popup-singel">
                        <i @click="hide_pop()" class="bx bx-x-circle  x-circle"></i>
                        <div class="app-content ">
                            <div class="card">
                                <table class="table table-bordered table-striped table-sm">
                                    <tbody>
                                        <tr>
                                            <td>KRA Name </td>
                                            <td>{{ item.krajoin ? item.krajoin.kra_name : '' }} </td>
                                        </tr>
                                        <tr>
                                            <td>KPI Name </td>
                                            <td>{{ item.kpijoin ? item.kpijoin.kpi_name : '' }} </td>
                                        </tr>
                                        <tr>
                                            <td>MOS Name </td>
                                            <td>{{ item.mos_name }} </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </modal>
                </div>

                <div class="content-body" v-if="items.length > 0">
                    <section id="basic-datatable">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">


                                            <button type="button" class="btn btn-info" data-toggle="collapse"
                                                data-target="#demo">Previous Settings</button>


                                            <div class="row settings collapse" id="demo">
                                                <div class="row">
                                                    <div  class="col-12">
                                                        <div class="row">
                                                        <div class="col-md-9">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn-sm btn btn-danger"
                                                            @click="delete_All_kra()">Delete All {{year}}  (KRA, KPI and MOS) </button>
                                                        </div>

                                                       </div>

                                                    </div> 
                                                    <div class="col-12">
                                                        <ul>
                                                            <li class="main" v-for="item  in items" :key="item.id">
                                                                <div> <strong> {{ item.kra_name }}</strong>

                                                                    <div style="float: inline-end;" class="dropup">
                                                                        <span aria-expanded="false" aria-haspopup="true"
                                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                            data-toggle="dropdown" role="menu">
                                                                        </span>
                                                                        <div class="dropdown-menu dropdown-menu-right">

                                                                            <!-- KPI ADD -->
                                                                            <a @click="addNewKPI(item)"
                                                                                class="dropdown-item">
                                                                                <i class="bx bx-edit-alt mr-1">
                                                                                </i>
                                                                                KPI ADD
                                                                            </a>


                                                                            <a @click="editKra(item)"
                                                                                class="dropdown-item">
                                                                                <i class="bx bx-edit-alt mr-1">
                                                                                </i>
                                                                                KRA Edit
                                                                            </a>
                                                                            <a @click="delete_kra(item.id)"
                                                                                class="dropdown-item">
                                                                                <i class="bx bx-trash mr-1">
                                                                                </i>
                                                                                KRA Delete
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <ul v-if="item.kpijoin">
                                                                    <li class="sub" v-for="item2  in item.kpijoin"
                                                                        :key="item2.id">
                                                                        {{ item2.kpi_name }}
                                                                        <div style="float: inline-end;" class="dropup">
                                                                            <span aria-expanded="false"
                                                                                aria-haspopup="true"
                                                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                data-toggle="dropdown" role="menu">
                                                                            </span>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-right">

                                                                                <!-- MOS ADD -->
                                                                                <a @click="addNewMOS(item2)"
                                                                                    class="dropdown-item">
                                                                                    <i class="bx bx-edit-alt mr-1">
                                                                                    </i>
                                                                                    MOS ADD
                                                                                </a>

                                                                                <a @click="editKpi(item2)"
                                                                                    class="dropdown-item">
                                                                                    <i class="bx bx-edit-alt mr-1">
                                                                                    </i>
                                                                                    KPI Edit
                                                                                </a>
                                                                                <a @click="delete_kpi(item2.id)"
                                                                                    class="dropdown-item">
                                                                                    <i class="bx bx-trash mr-1">
                                                                                    </i>
                                                                                    KPI Delete
                                                                                </a>

                                                                            </div>
                                                                        </div>
                                                                        <ul v-if="item2.mosjoin">
                                                                            <li class="sub_sub"
                                                                                v-for="item3  in item2.mosjoin"
                                                                                :key="item3.id">
                                                                                {{ item3.mos_name }}
                                                                                <div style="float: inline-end;"
                                                                                    class="dropup">
                                                                                    <span aria-expanded="false"
                                                                                        aria-haspopup="true"
                                                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                                        data-toggle="dropdown"
                                                                                        role="menu">
                                                                                    </span>
                                                                                    <div
                                                                                        class="dropdown-menu dropdown-menu-right">
                                                                                        <a @click="editMos(item3)"
                                                                                            class="dropdown-item">
                                                                                            <i
                                                                                                class="bx bx-edit-alt mr-1">
                                                                                            </i>
                                                                                            MOS Edit
                                                                                        </a>
                                                                                        <a @click="delete_mos(item3.id)"
                                                                                            class="dropdown-item">
                                                                                            <i class="bx bx-trash mr-1">
                                                                                            </i>
                                                                                            MOS Delete
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </section>
                </div>



                <!-- ADD NEW MOS -->
                <modal width="60%" height="70%" style="padding:50px" name="mosAdd">
                    <i @click="hiddenMos()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                        <div class="card">

                            <form @submit.prevent="AddNewMos()">
                                <div class="card-body">

                                    <div class="form-group ">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">MOS Name </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="mos_item.mos_name"
                                                            class="form-control" placeholder="MOS Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">MOS Weightage
                                                    </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="mos_item.weightage"
                                                            class="form-control" placeholder="MOS WEIGHTAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </modal>


                <modal width="60%" height="70%" style="padding:50px" name="mosedit">
                    <i @click="hiddenMos()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                        <div class="card">

                            <form @submit.prevent="updateMos()">
                                <div class="card-body">

                                    <div class="form-group ">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">MOS Name </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="mos_item.mos_name"
                                                            class="form-control" placeholder="MOS Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">MOS Weightage
                                                    </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="mos_item.weightage"
                                                            class="form-control" placeholder="KPI WEIGHTAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </modal>
                <modal width="60%" height="70%" style="padding:50px" name="kpiedit">
                    <i @click="hiddenMos()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                        <div class="card">

                            <form @submit.prevent="updateKpi()">
                                <div class="card-body">

                                    <div class="form-group ">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KPI Name </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kpi_item.kpi_name"
                                                            class="form-control" placeholder="KPI Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KPI Weightage
                                                    </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kpi_item.kpi_weight"
                                                            class="form-control" placeholder="KPI WEIGHTAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </modal>

                <!-- KPI ADD -->
                <modal width="60%" height="70%" style="padding:50px" name="kpiAdd">
                    <i @click="hiddenMos()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                        <div class="card">

                            <form @submit.prevent="AddNewKpi()">
                                <div class="card-body">

                                    <div class="form-group ">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KPI Name </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kpi_item.kpi_name"
                                                            class="form-control" placeholder="KPI Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KPI Weightage
                                                    </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kpi_item.kpi_weight"
                                                            class="form-control" placeholder="KPI WEIGHTAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </modal>

                <modal width="60%" height="70%" style="padding:50px" name="kraedit">
                    <i @click="hiddenMos()" class="bx bx-x-circle  x-circle"></i>
                    <div class="app-content ">
                        <div class="card">

                            <form @submit.prevent="updateKra()">
                                <div class="card-body">

                                    <div class="form-group ">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KRA Name </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kra_item.kra_name"
                                                            class="form-control" placeholder="KRA Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="text-gray-600 font-semibold text-lg">KRA Weightage
                                                    </label>
                                                    <div class="controls">
                                                        <input type="text" v-model="kra_item.kra_weight"
                                                            class="form-control" placeholder="KRA WEIGHTAGE">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>
<script>
import { Form } from "vform";
import axios from "../../axios_instance";
import Select2 from 'v-select2-component';
//import VueTree from '@ssthouse/vue-tree-chart'; 
export default {
    props: {
    },
    components: {
        'Select2': Select2,
        //'vue-tree': VueTree
        // VueRecaptcha, facebookLogin
    },
    data() {
        return {
            itemsData: [],
            items: [],
            item: [],
            kar_kpi_mos: {
                name: '',
                kra_weight: 0,
                children: [{
                    name: '',
                    kpi_weight: 0,
                    children: [{ name: '', weightage : ''}],
                },
                ],
            },
            chart: [],
            base_url: window.base_url,
            api_url: window.api_url,
            WingsItems: [],
            deptItems: [],
            employeeItem: [],
            token: this.$localStorage.get("d_token"),
            user_data: JSON.parse(this.$localStorage.get("user")),
            role_id: '',
            sampleData: [],
            filterForm: new Form({
                dept_id: "",
            }),
            treeConfig: { nodeWidth: 200, nodeHeight: 100, levelHeight: 170 },
            addForm: new Form({
                arrayData: ""
            }),

            //ADD NEW MOS
            addMosForm: new Form({
            }),

            editMosForm: new Form({
            }),

            //ADD NEW KPI
            addKpiForm: new Form({
            }),

            editKpiForm: new Form({
            }),
            editKraForm: new Form({
            }),
            mos_item: [],
            kpi_item: [],
            kra_item: [],
            year: this.$localStorage.get('year') ? this.$localStorage.get('year') : new Date().getFullYear(),
            optionFromYearValue: '',
            optionToYearValue: '',
            fromYear: 2022,
            toYear: 2023,
            filterForm: new Form({
                dept_id: this.$route.query.dept_id ? this.$route.query.dept_id : "",
                wing_id: "",
                user_id: "",

            }),
        };
    },
    created() {
        this.role_id = this.user_data.role_id;
        this.filterForm.dept_id = this.user_data.dept_id;
        this.getDept();
        this.getWing();
        //this.getItems();
        /////this.getYearWiseKraKpiMosItems();
    },
    methods: {
        getItemsData() {
            this.getItems();
            this.getYearWiseKraKpiMosItems();
        },
        async getDept() {

            this.getDepartments(this.status).then(({ data }) => {
                if (data.success) {
                    this.deptItems = data.data;
                }
            });
        },
        async getWing() {
            await axios.get(this.api_url + "wings?dept_id=" + this.filterForm.dept_id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({ data }) => {
                    this.getEmployee();
                    this.WingsItems = data.data;
                });
        },
        async getEmployee() {
            //if (this.filterForm.wing_id) {
            let where = '?1=1';
            if (this.filterForm.wing_id) {
                where += '&wing_id=' + this.filterForm.wing_id;
            }
            if (this.filterForm.dept_id) {
                where += '&dept_id=' + this.filterForm.dept_id;
            }
            await axios.get(this.api_url + "users" + where, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then(({ data }) => {
                    this.employeeItem = data.data;
                });
            //}

        },
        async changeEmployee() {
            this.getEmployee();
            // this.getItems();

        },
        onlyNumber($event) {
            //console.log($event.keyCode); //keyCodes value
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                $event.preventDefault();
            }
        },

        //ADD NEW MOS
        async addNewMOS(item) {
            console.log(item);
            this.$modal.show("mosAdd");
            this.kpi_item = item;
        },

        async editMos(item) {
            console.log(item);
            this.$modal.show("mosedit");
            this.mos_item = item;
        },

        //ADD NEW KPI
        async addNewKPI(item) {
            console.log(item);
            this.$modal.show("kpiAdd");
            this.kra_item = item;
        },

        async editKpi(item) {
            console.log(item);
            this.$modal.show("kpiedit");
            this.kpi_item = item;
        },

        async editKra(item) {
            console.log(item);
            this.$modal.show("kraedit");
            this.kra_item = item;
        },

        //ADD NEW MOS
        async AddNewMos() {
            this.addMosForm.kra_id = this.kpi_item.kra_id;
            this.addMosForm.kpi_id = this.kpi_item.id;
            this.addMosForm.dept_id = this.user_data.dept_id;
            this.addMosForm.mos_name = this.mos_item.mos_name;
            this.addMosForm.weightage = this.mos_item.weightage;
            this.addMosForm.year = this.year;
            this.addMosForm.post(this.api_url + "m_o_s", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();

                    this.$modal.hide("mosAdd");
                    console.log(data);
                })
        },

        async updateMos() {
            this.editMosForm.id = this.mos_item.id;
            this.editMosForm.mos_name = this.mos_item.mos_name;
            this.editMosForm.weightage = this.mos_item.weightage;
            this.editMosForm.kpi_id = this.mos_item.kpi_id;
            this.editMosForm.put(this.api_url + "m_o_s/" + this.mos_item.id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })
        },

        //ADD NEW KPI
        async AddNewKpi() {
            this.addKpiForm.kra_id = this.kra_item.id;
            this.addKpiForm.kpi_name = this.kpi_item.kpi_name;
            this.addKpiForm.kpi_weight = this.kpi_item.kpi_weight;
            this.addKpiForm.dept_id = this.user_data.dept_id;
            this.addKpiForm.year = this.year;
            this.addKpiForm.post(this.api_url + "k_p_i_s", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();

                    this.$modal.hide("kpiAdd");
                    console.log(data);
                })

        },

        async updateKpi() {
            this.editKpiForm.id = this.kpi_item.id;
            this.editKpiForm.kpi_name = this.kpi_item.kpi_name;
            this.editKpiForm.kpi_weight = this.kpi_item.kpi_weight;
            this.editKpiForm.put(this.api_url + "k_p_i_s/" + this.kpi_item.id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })

        },

        async updateKra() {
            this.editKraForm.id = this.kra_item.id;
            this.editKraForm.kra_name = this.kra_item.kra_name;
            this.editKraForm.kra_weight = this.kra_item.kra_weight;
            this.editKraForm.put(this.api_url + "k_r_a_s/" + this.kra_item.id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: this.token ? `Bearer ${this.token}` : ""
                },
            })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })

        },
        async hiddenMos() {
            this.$modal.hide("mosedit");
            this.$modal.hide("kpiedit");
            this.$modal.hide("kraedit");

        },

        async delete_All_kra() {
            this.$swal({
                title: "Are you sure you want to delete?",
                text: '',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.deleteAllKra()
                    }
                })
        },
        async deleteAllKra() {
            let where = "";

            where = "?year=" + (this.year ? this.year : new Date().getFullYear());

            if (this.filterForm.dept_id) {
                where += "&dept_id=" + this.filterForm.dept_id;
            }
            if (this.filterForm.wing_id) {
                where += '&wing_id=' + this.filterForm.wing_id;
            }
            if (this.filterForm.user_id) {
                where += '&user_id=' + this.filterForm.user_id;
            }


            await axios
                .get(this.api_url + "kra_all_delete" + where, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItemsData(); 
                })

        },


        async deleteKra(id) {
            await axios
                .get(this.api_url + "kra_delete/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })

        },
        async delete_kra(id) {
            this.$swal({
                title: "Are you sure you want to delete?",
                text: '',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.deleteKra(id)
                    }
                })
        },
        async delete_kpi(id) {
            this.$swal({
                title: "Are you sure you want to delete?",
                text: '',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.deleteKpi(id)
                    }
                })
        },

        async deleteKpi(id) {
            await axios
                .get(this.api_url + "kpi_delete/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })

        },
        async delete_mos(id) {
            this.$swal({
                title: "Are you sure you want to delete?",
                text: '',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.deleteMos(id)
                    }
                })
        },

        async deleteMos(id) {
            await axios
                .get(this.api_url + "mos_delete/" + id, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                })
                .then((data) => {
                    this.$toasted.show(data.data.message, {
                        theme: "bubble",
                        duration: 5000,
                        position: "bottom-right",
                    });
                    this.getItems();
                    console.log(data);
                })

        },
        addFieldKPI(array) {
            array.push({
                children: [{ name: '', kpi_weight: 100 }],
            });
            // this.chart  = this.kar_kpi_mos ;
        },
        addFieldMOS(array) {
            array.children.push({ name: '' });
            console.log(this.kar_kpi_mos);
            // this.chart  = this.kar_kpi_mos ;
        },
        addField(value, fieldType) {
            fieldType.push({ value: "" });
            console.log(this.kar_kpi_mos);
        },
        removeField(index, fieldType) {
            console.log(fieldType);
            fieldType.splice(index, 1);
        },
        async getItems() {
            if (this.filterForm.dept_id != "") {
                let where = "";
                //ADD YEAR PARAM
                where = "?year=" + (this.year ? this.year : new Date().getFullYear());

                if (this.filterForm.dept_id) {
                    where += "&dept_id=" + this.filterForm.dept_id;
                }
                if (this.filterForm.wing_id) {
                    where += '&wing_id=' + this.filterForm.wing_id;
                }
                if (this.filterForm.user_id) {
                    where += '&user_id=' + this.filterForm.user_id;
                }
                let loader;
                loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "kra_kpi_mos" + where, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : "",
                            },
                        })
                        .then(({ data }) => {
                            if (data.success) {
                                this.items = data.data;
                            }
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            }
            // this.getWing();
        },


        //YEAR WISE KRA KPI MOS DATA
        async getYearWiseKraKpiMosItems() {
            if (this.filterForm.dept_id != '') {
                let where = "";
                //ADD YEAR PARAM
                where = "?year=" + (this.year ? this.year : new Date().getFullYear());

                if (this.filterForm.dept_id) {
                    where += "&dept_id=" + this.filterForm.dept_id;
                }
                if (this.filterForm.wing_id) {
                    where += '&wing_id=' + this.filterForm.wing_id;
                }
                if (this.filterForm.user_id) {
                    where += '&user_id=' + this.filterForm.user_id;
                }
                let loader = this.$loading.show();
                try {
                    await axios
                        .get(this.api_url + "kra_kpi_mos_list" + where, {
                            headers: {
                                "Content-Type": "application/json",
                                Authorization: this.token ? `Bearer ${this.token}` : ""
                            },
                        })
                        .then(({
                            data
                        }) => {
                            if (data.success) {
                                this.itemsData = data.data;
                                // console.log( this.yearWiseKraKpiMosItems );
                            }
                            loader.hide();
                        });
                } catch (error) {
                    loader.hide();
                }
            }
        },


        //COPY KRA KPI MOS DATA FROM YEAR TO YEAR
        async copyKraKpiMos() {

            this.$swal({
                title: "Are you sure?",
                text: "COPYING DETAILS KRA, KPI and MOS!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        let where = "";
                        //ADD YEAR PARAM
                        where = "?year=" + (this.year ? this.year : new Date().getFullYear());

                        if (this.filterForm.dept_id) {
                            where += "&dept_id=" + this.filterForm.dept_id;
                        }
                        if (this.filterForm.wing_id) {
                            where += '&wing_id=' + this.filterForm.wing_id;
                        }
                        if (this.filterForm.user_id) {
                            where += '&user_id=' + this.filterForm.user_id;
                        }

                        if (this.fromYear) {
                            where += '&fromYear=' + this.fromYear;
                        }

                        if (this.toYear) {
                            where += '&toYear=' + this.toYear;
                        }

                        try {
                            axios
                                .get(this.api_url + "copy_kra_kpi_mos" + where, {
                                    headers: {
                                        "Content-Type": "application/json",
                                        Authorization: this.token ? `Bearer ${this.token}` : ""
                                    },
                                })
                                .then(({
                                    data
                                }) => {

                                    if (data.success) {
                                        this.$toasted.show(data.message, {
                                            theme: "bubble",
                                            duration: 5000,
                                            position: "bottom-right",
                                        });

                                        //GET LATEST YEAR DATA
                                        //this.$localStorage.set('year', '2022'),
                                            this.getItemsData();
                                    }  

                                    loader.hide();
                                });
                        } catch (error) {
                            loader.hide();
                        }


                    }
                });

        },

        rowVisible(index, item, type) {
            let crount = 0;
            this.itemsData.filter(row => {
                if (type == 'kra') {
                    if (row.kra_id === item.kra_id) {
                        crount += 1;
                    }
                } else if (type == 'kpi') {
                    if (row.kpi_id === item.kpi_id) {
                        crount += 1;
                    }
                }

            })
            return crount;
        },

        create() {
            let loader = this.$loading.show();
            try {

                console.log(this.kar_kpi_mos);
                this.addForm.arrayData = this.kar_kpi_mos;
                this.addForm.year = this.year;
                this.addForm.post(this.api_url + "kra_kpi_setting", {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: this.token ? `Bearer ${this.token}` : ""
                    },
                }).then((res) => {
                    console.log(res);
                    if (res.data.success) {
                        this.addForm.name = '';
                        this.$toasted.show(res.data.message, {
                            theme: "bubble",
                            duration: 5000,
                            position: "bottom-right",
                        });
                        this.getItems();
                    }
                    loader.hide();

                })
            } catch (error) {
                loader.hide();
                console.log(error);
            }

        },

        checkConditionKra(length, kpi_index, mos_index) {
            if (kpi_index == 0 && mos_index == 0) {
                return true;
            } else {
                return false;
            }
        },
        checkConditionKpi(length, mos_index) {
            if (mos_index == 0) {
                return true;
            } else {
                return false;
            }
        },
    },



    computed: {},
};
</script>
<style>
.input.wrapper.flex.items-center.label_2 {
    margin: 0 0 0 100px;
}

.input.wrapper.flex.items-center.label_3 {
    margin: 0 0 0 100px;
}
</style>


<style scoped>
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.rich-media-node {
    width: 180px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    color: white;
    background-color: #f7c616;
    border-radius: 4px;
}

.settings ul {
    list-style: none;
    line-height: 33px;
}

.settings ul ul li {
    border-bottom: 1px solid #c1bbbb;
}

.main {
    background: #efefef;
    padding: 14px 11px 17px 16px;
    margin-top: 20px;
    margin-right: 47px;
}
</style>