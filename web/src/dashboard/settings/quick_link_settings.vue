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
                      <router-link :to="{ path: '/' }"><i class="bx bx-home-alt"></i></router-link>
                    </li>
                    <li class="breadcrumb-item active"> Quick Link Settings
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
                  <table class="table table-bordered">
                    <thead>
                    <tr>
                      <th scope="col">Select</th>
                      <th scope="col">Module</th>
                      <th scope="col">Menu</th>
                    </tr>
                    </thead>
                    <tbody>

                    <!-- WORK SCHEDULE -->
                    <tr v-if="permission('add_daily_work')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('New Work Schedule','Work Schedule', 'add_daily_work')" id="add_daily_work" :checked="this.selected_route_list.filter(p => p.route == 'add_daily_work').length > 0 ">
                          <label class="custom-control-label" for="add_daily_work"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('work_schedule_menu')"><span> Work Schedule </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('add_daily_work')">
                          <router-link :to="{ path: '/add_daily_work' }"><i class="bx bx-list-plus"></i><span
                              class="menu-item" data-i18n="Analytics"> New Work Schedule </span></router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('daily_work')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Work Schedule List','Work Schedule', 'daily_work')" id="daily_work" :checked="this.selected_route_list.filter(p => p.route == 'daily_work').length > 0 ">
                          <label class="custom-control-label" for="daily_work"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('work_schedule_menu')"><span> Work Schedule </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('daily_work')">
                        <router-link :to="{ path: '/daily_work' }"> <i class="bx bx-building"></i><span
                            class="menu-item" data-i18n="Analytics">{{
                            role_id == 1 || role_id == 2 || role_id == 3 || role_id == 8 ? 'Dept. Follow Up' : 'Work Schedule List'
                          }}</span> </router-link>
                      </span>
                      </td>
                    </tr>

                    <tr v-if="permission('daliy_not_update')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" v-on:input="saveOrDeleteQuickLink('Daily Not Update', 'Work Schedule', 'daliy_not_update')" id="daliy_not_update" :checked="this.selected_route_list.filter(p => p.route == 'daliy_not_update').length > 0 ">
                          <label class="custom-control-label" for="daliy_not_update"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('work_schedule_menu')"><span> Work Schedule </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('daliy_not_update')" >
                          <router-link :to="{ path: '/daliy_not_update' }"> <i class="bx bx-list-plus"></i><span class="menu-item" data-i18n="Analytics">Daliy Not Update</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <!-- ENTRY -->
                    <tr v-if="permission('kra_kpi_mos_list')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('KRA KPI List','Entry', 'kra_kpi_mos_list')" id="kra_kpi_mos_list" :checked="this.selected_route_list.filter(p => p.route == 'kra_kpi_mos_list').length > 0 ">
                          <label class="custom-control-label" for="kra_kpi_mos_list"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('kra_kpi_mos_list')"><span> Entry </span>
                        </span>
                      </td>
                      <td>
                        <span v-if="permission('kra_kpi_mos_list')"  class="nav-item ">
                          <router-link :to="{ path: '/kra_kpi_mos_list' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"> KRA KPI List  </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('kra_kpi_setting')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('KRA & KPI Settings','Entry', 'kra_kpi_setting')" id="kra_kpi_setting" :checked="this.selected_route_list.filter(p => p.route == 'kra_kpi_setting').length > 0 ">
                          <label class="custom-control-label" for="kra_kpi_setting"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('kra_kpi_mos_list')"><span> Entry </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('kra_kpi_setting')" >
                          <router-link :to="{ path: '/kra_kpi_setting' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">KRA & KPI Settings </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('weightage_list')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Weightage Lists','Entry', 'weightage_list')" id="weightage_list" :checked="this.selected_route_list.filter(p => p.route == 'weightage_list').length > 0 ">
                          <label class="custom-control-label" for="weightage_list"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('kra_kpi_mos_list')"><span> Entry </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('weightage_list')" >
                          <router-link :to="{ path: '/weightage_list' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Weightage List</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('kra_kpi_mos_list')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Second Layer','Entry', 'wings')" id="wings" :checked="this.selected_route_list.filter(p => p.route == 'wings').length > 0 ">
                          <label class="custom-control-label" for="wings"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('kra_kpi_mos_list')"><span> Entry </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('wings')" >
                          <router-link :to="{ path: '/wings' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Second Layer</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <!-- FACTORY REPORT OLD -->
                    <tr v-if="permission('rpt_view_all')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Product Wise Report','Factory Report (old)', 'rpt_view_all')" id="rpt_view_all" :checked="this.selected_route_list.filter(p => p.route == 'rpt_view_all').length > 0 ">
                          <label class="custom-control-label" for="rpt_view_all"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('rpt_view_all')"><span> Factory Report (old) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('rpt_view_all')" >
                          <router-link :to="{ path: '/rpt_view_all' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"></span>  Product Wise Report </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('pro_target_entry')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input"  @click="saveOrDeleteQuickLink('Production Entry','Factory Report (old)', 'pro_target_entry')" id="pro_target_entry" :checked="this.selected_route_list.filter(p => p.route == 'pro_target_entry').length > 0 ">
                          <label class="custom-control-label" for="pro_target_entry"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('rpt_view_all')"><span> Factory Report (old) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('pro_target_entry')" >
                          <router-link :to="{ path: '/pro_target_entry' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"></span> Production Entry </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('pro_entry_bill_cost')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Cost Entry','Factory Report (old)', 'pro_entry_bill_cost')" id="pro_entry_bill_cost" :checked="this.selected_route_list.filter(p => p.route == 'pro_entry_bill_cost').length > 0 ">
                          <label class="custom-control-label" for="pro_entry_bill_cost"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('rpt_view_all')"><span> Factory Report (old) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('pro_entry_bill_cost')" >
                          <router-link :to="{ path: '/pro_entry_bill_cost' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"> Cost Entry </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('pro_wastage_entry')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Wastage Entry','Factory Report (old)', 'pro_wastage_entry')" id="pro_wastage_entry" :checked="this.selected_route_list.filter(p => p.route == 'pro_wastage_entry').length > 0 ">
                          <label class="custom-control-label" for="pro_wastage_entry"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('rpt_view_all')"><span> Factory Report (old) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('pro_wastage_entry')" >
                          <router-link :to="{ path: '/pro_wastage_entry' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"> Wastage Entry </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <!-- FACTORY REPORT NEW -->
                    <tr v-if="permission('manufacture_report')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Product Wise Report','Factory Report (new)', 'manufacture_report')" id="manufacture_report" :checked="this.selected_route_list.filter(p => p.route == 'manufacture_report').length > 0 ">
                          <label class="custom-control-label" for="manufacture_report"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('manufacture_report')" >
                          <router-link :to="{ path: '/manufacture_report' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics"> Product Wise Report</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('sap_files')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('SAP Files','Factory Report (new)', 'sap_files')" id="sap_files" :checked="this.selected_route_list.filter(p => p.route == 'sap_files').length > 0 ">
                          <label class="custom-control-label" for="sap_files"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('sap_files')" >
                          <router-link :to="{ path: '/sap_files' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">SAP Files</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('production_wastage_daft')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Production wastage daft','Factory Report (new)', 'production_wastage_daft')" id="production_wastage_daft" :checked="this.selected_route_list.filter(p => p.route == 'production_wastage_daft').length > 0 ">
                          <label class="custom-control-label" for="production_wastage_daft"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('production_wastage_daft')" >
                          <router-link :to="{ path: '/production_wastage_daft' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Production wastage daft</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('manufacturer')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Manufacturer','Factory Report (new)', 'manufacturer')" id="manufacturer" :checked="this.selected_route_list.filter(p => p.route == 'manufacturer').length > 0 ">
                          <label class="custom-control-label" for="manufacturer"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('manufacturer')" >
                          <router-link :to="{ path: '/manufacturer' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Manufacturer</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('delivery')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Delivery','Factory Report (new)', 'delivery')" id="delivery" :checked="this.selected_route_list.filter(p => p.route == 'delivery').length > 0 ">
                          <label class="custom-control-label" for="delivery"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('delivery')" >
                          <router-link :to="{ path: '/delivery' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Delivery</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('wastage')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Wastage','Factory Report (new)', 'wastage')" id="wastage" :checked="this.selected_route_list.filter(p => p.route == 'wastage').length > 0 ">
                          <label class="custom-control-label" for="wastage"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('wastage')" >
                          <router-link :to="{ path: '/wastage' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Wastage</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('products')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Products','Factory Report (new)', 'products')" id="products" :checked="this.selected_route_list.filter(p => p.route == 'products').length > 0 ">
                          <label class="custom-control-label" for="products"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('products')" >
                          <router-link :to="{ path: '/products' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Products</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('cost_center')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Cost Center','Factory Report (new)', 'cost_center')" id="cost_center" :checked="this.selected_route_list.filter(p => p.route == 'cost_center').length > 0 ">
                          <label class="custom-control-label" for="cost_center"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('cost_center')" >
                          <router-link :to="{ path: '/cost_center' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Cost Center</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('standard_cost')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Standard Cost','Factory Report (new)', 'standard_cost')" id="standard_cost" :checked="this.selected_route_list.filter(p => p.route == 'standard_cost').length > 0 ">
                          <label class="custom-control-label" for="standard_cost"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('standard_cost')" >
                          <router-link :to="{ path: '/standard_cost' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Standard Cost</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('factory_capacity')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Capacity','Factory Report (new)', 'factory_capacity')" id="factory_capacity" :checked="this.selected_route_list.filter(p => p.route == 'factory_capacity').length > 0 ">
                          <label class="custom-control-label" for="factory_capacity"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('factory_capacity')" >
                          <router-link :to="{ path: '/factory_capacity' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Capacity</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('productionplans')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Production Plans','Factory Report (new)', 'productionplans')" id="productionplans" :checked="this.selected_route_list.filter(p => p.route == 'productionplans').length > 0 ">
                          <label class="custom-control-label" for="productionplans"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('productionplans')" >
                          <router-link :to="{ path: '/productionplans' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Production Plans</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('production_targets')" >
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Production Targets','Factory Report (new)', 'production_targets')" id="production_targets" :checked="this.selected_route_list.filter(p => p.route == 'production_targets').length > 0 ">
                          <label class="custom-control-label" for="production_targets"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('production_targets')" >
                          <router-link :to="{ path: '/production_targets' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Production Targets</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('costs')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Costs','Factory Report (new)', 'costs')" id="costs" :checked="this.selected_route_list.filter(p => p.route == 'costs').length > 0 ">
                          <label class="custom-control-label" for="costs"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('costs')" >
                          <router-link :to="{ path: '/costs' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Costs</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('costs')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Costs Draft','Factory Report (new)', 'costs_draft')" id="costs_draft" :checked="this.selected_route_list.filter(p => p.route == 'costs_draft').length > 0 ">
                          <label class="custom-control-label" for="costs_draft"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('costs')" >
                          <router-link :to="{ path: '/costs_draft' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Costs Draft</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('factory')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Factory','Factory Report (new)', 'factory')" id="factory" :checked="this.selected_route_list.filter(p => p.route == 'factory').length > 0 ">
                          <label class="custom-control-label" for="factory"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('factory')" >
                          <router-link :to="{ path: '/factory' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Factory</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('production_emps')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Employee Turnover','Factory Report (new)', 'production_emps')" id="production_emps" :checked="this.selected_route_list.filter(p => p.route == 'production_emps').length > 0 ">
                          <label class="custom-control-label" for="production_emps"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('production_emps')" >
                          <router-link :to="{ path: '/production_emps' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Employee Turnover </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('summary_group')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Summary Group','Factory Report (new)', 'summary_group')" id="summary_group" :checked="this.selected_route_list.filter(p => p.route == 'summary_group').length > 0 ">
                          <label class="custom-control-label" for="summary_group"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('summary_group')" >
                          <router-link :to="{ path: '/summary_group' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Summary Group</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('wastage_summary')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Wastage Summary','Factory Report (new)', 'wastage_summary')" id="wastage_summary" :checked="this.selected_route_list.filter(p => p.route == 'wastage_summary').length > 0 ">
                          <label class="custom-control-label" for="wastage_summary"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('manufacture_report')"><span> Factory Report (new) </span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('wastage_summary')" >
                          <router-link :to="{ path: '/wastage_summary' }"> <i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Wastage Summary</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <!-- REPORT -->
                    <tr v-if="permission('bpt_report')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('BPT Report','Report', 'bpt_report')" id="bpt_report" :checked="this.selected_route_list.filter(p => p.route == 'bpt_report').length > 0 ">
                          <label class="custom-control-label" for="bpt_report"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('report_menu_title')"><span> Report</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('bpt_report')" >
                          <router-link :to="{ path: '/bpt_report' }"> <i class="bx bx-bar-chart"></i><span class="menu-item" data-i18n="Analytics">BPT Report</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <!-- OPERATION -->
                    <tr v-if="permission('monthly_report')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Monthly Report','Operation', 'monthly_report')" id="monthly_report" :checked="this.selected_route_list.filter(p => p.route == 'monthly_report').length > 0 ">
                          <label class="custom-control-label" for="monthly_report"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('monthly_report')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('monthly_report')" >
                          <router-link :to="{ path: '/monthly_report' }"> <i class="bx bx-bar-chart"></i><span class="menu-item" data-i18n="Analytics">Monthly Report</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('monthly_not_update')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Monthly Not Update','Operation', 'monthly_not_update')" id="monthly_not_update" :checked="this.selected_route_list.filter(p => p.route == 'monthly_not_update').length > 0 ">
                          <label class="custom-control-label" for="monthly_not_update"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('monthly_report')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item "  v-if="permission('monthly_not_update')" >
                          <router-link :to="{ path: '/monthly_not_update' }"> <i class="bx
                        bx-spreadsheet"></i><span class="menu-item" data-i18n="Analytics">Monthly Not Update</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('department')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Department','Operation', 'department')" id="department" :checked="this.selected_route_list.filter(p => p.route == 'department').length > 0 ">
                          <label class="custom-control-label" for="department"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('department')" >
                          <router-link :to="{ path: '/department' }"> <i class="bx bx-server"></i><span class="menu-item" data-i18n="Analytics">Department</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('department_setting')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('BPT 2021 Update','Operation', 'department_setting')" id="department_setting" :checked="this.selected_route_list.filter(p => p.route == 'department_setting').length > 0 ">
                          <label class="custom-control-label" for="department_setting"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('department_setting')" >
                          <router-link :to="{ path: '/department_setting' }"> <i class="bx bx-spreadsheet"></i><span class="menu-item" data-i18n="Analytics"> BPT 2021 Update </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('monthly_report_update')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Monthly report update','Operation', 'monthly_report_update')" id="monthly_report_update" :checked="this.selected_route_list.filter(p => p.route == 'monthly_report_update').length > 0 ">
                          <label class="custom-control-label" for="monthly_report_update"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('monthly_report_update')" >
                          <router-link :to="{ path: '/monthly_report_update' }"> <i class="bx bx-spreadsheet"></i><span class="menu-item" data-i18n="Analytics"> Monthly report update </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('tour_plan_users')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Tour Plan Users','Operation', 'tour_plan_users')" id="tour_plan_users" :checked="this.selected_route_list.filter(p => p.route == 'tour_plan_users').length > 0 ">
                          <label class="custom-control-label" for="tour_plan_users"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('tour_plan_users')" >
                          <router-link :to="{ path: '/tour_plan_users' }"> <i class="bx bx-sitemap"></i><span class="menu-item" data-i18n="Analytics">Tour Plan Users</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('tour_plan')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Tour Plan','Operation', 'tour_plan')" id="tour_plan" :checked="this.selected_route_list.filter(p => p.route == 'tour_plan').length > 0 ">
                          <label class="custom-control-label" for="tour_plan"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span class="nav-item " v-if="permission('tour_plan')" >
                          <router-link :to="{ path: '/tour_plan' }"> <i class="bx bx-git-compare"></i><span class="menu-item" data-i18n="Analytics">Tour Plan</span> </router-link>
                        </span>
                      </td>
                    </tr>

                    <tr v-if="permission('users')">
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" @click="saveOrDeleteQuickLink('Users','Operation', 'users')" id="users" :checked="this.selected_route_list.filter(p => p.route == 'users').length > 0 ">
                          <label class="custom-control-label" for="users"></label>
                        </div>
                      </td>
                      <td>
                        <span class=" navigation-header"
                              v-if="permission('operation_menu_title')"><span> Operation</span>
                        </span>
                      </td>
                      <td>
                        <span itemprop="" class="nav-item "  v-if="permission('users')" >
                          <router-link :to="{ path: '/users' }"> <i class="bx bxs-group"></i><span class="menu-item" data-i18n="Analytics">Users </span> </router-link>
                        </span>
                      </td>
                    </tr>

                    </tbody>
                  </table>
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
// import axios from "../../axios_instance";
// import {Form} from "vform";
import axios from "../../axios_instance";
export default {
  props: {},
  components: {
  },
  data() {
    return {
      token: this.$localStorage.get("d_token"),
      base_url: window.base_url,
      api_url: window.api_url,
      user_data: JSON.parse(this.$localStorage.get("user")),
      role_id : '',
      user: JSON.parse(this.$localStorage.get("user")),
      is_login: false,
      user_type: null,
      route_list: [],
      selected_route_list: [],
    };
  },
  methods: {

    //SAVE OR DELETE QUICK LINK
   saveOrDeleteQuickLink: async function (title, module, route) {

     //POST DATA
     var postData = {
       title: title,
       module: module,
       route: route,
       user_id: this.user_data.id,
     };

     //API CONFIG
     let axiosConfig = {
       headers: {
         "Content-Type": "application/json",
         Authorization: this.token ? `Bearer ${this.token}` : ""
       }
     };

     //CALL API
     axios.post(this.api_url + "save-or-delete-quick-link", postData, axiosConfig)
         .then((res) => {
           this.route_list = res.data;
           console.log("RESPONSE RECEIVED: ", res);
         })
         .catch((err) => {
           console.log("AXIOS ERROR: ", err);
         })
    },
  },

  created() {
    this.role_id  =  this.user_data.role_id ;
    if (this.$localStorage.get("d_token")) {
      this.is_login = true;
      this.user_type = this.user.type;
    } else {
      this.is_login = false;
    }

    //GET QUICK LINK LIST
    axios
        .get(this.api_url + "quick-link-list/" + this.user_data.id , {
          headers: {
            "Content-Type": "application/json",
            Authorization: this.token ? `Bearer ${this.token}` : "",
          },
        })
        .then((response) => {
          this.selected_route_list = response.data;
          console.log('data 123',this.selected_route_list.flat(Infinity));
        });

  },
  computed: {},
};
</script>

<style scoped>

</style>