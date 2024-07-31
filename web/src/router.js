import Vue from "vue";
import Router from "vue-router";
import FrontendLayout from "./FrontendLayout.vue";
import login from "./login.vue";
import adupdate from "./adupdate.vue";
import Dashboard from "./dashboard/Dashboard.vue";
//import Department from "./dashboard/department/index.vue";
import DepartmentAdd from "./dashboard/DepartmentAdd.vue";

// department
import production_emps from "./dashboard/production_emps/index.vue";
import production_emps_new from "./dashboard/production_emps/add.vue";
import work_follow_up from "./dashboard/work_follow_up/index.vue";
import department from "./dashboard/department/index.vue";
import department_weekend from "./dashboard/department_weekend/index.vue";
import follow_up_done_list from "./dashboard/work_follow_up/follow_up_done_list.vue";
import new_follow_up from "./dashboard/work_follow_up/add.vue";
import edit_follow_up from "./dashboard/work_follow_up/edit.vue";
import dept_permission from "./dashboard/department/dept_permission.vue";
import kra_kpi_permission from "./dashboard/department/kra_kpi_permission.vue";

import department_setting from "./dashboard/department/setting.vue";
import m_o_s_achievement_permissions from "./dashboard/kar_kpi/m_o_s_achievement_permissions.vue";
//m_o_s_dept_setting
import m_o_s_dept_setting from "./dashboard/kar_kpi/m_o_s_dept_setting.vue";
import monthly_report_update from "./dashboard/department/monthly_report_update.vue";
import monthly_report_update_modifie from "./dashboard/department/monthly_report_update_modifie.vue";
import department_templates from "./dashboard/department/department_templates.vue";
import departmentAdd from "./dashboard/department/add.vue";
import departmentEdit from "./dashboard/department/edit.vue";

// wings
import team from "./dashboard/team/index.vue";
import assign_team from "./dashboard/team/add.vue";

import wings from "./dashboard/wings/index.vue";
import edit_wing from "./dashboard/wings/edit.vue";
//users
import users from "./dashboard/users/index.vue";
import tour_plan_users from "./dashboard/users/tour_plan_users.vue";
import tour_plan_not_submit_users from "./dashboard/users/tour_plan_not_submit_users.vue";
import add_tour_user from "./dashboard/users/add_tour_user.vue";
import tour_user_edit from "./dashboard/users/tour_user_edit.vue";
import userAdd from "./dashboard/users/add.vue";
import userEdit from "./dashboard/users/edit.vue";
import profile from "./dashboard/users/profile.vue";
import change_password from "./dashboard/users/change_password.vue";

import dept_transfer from "./dashboard/users/dept_transfer.vue";
// Daily Woark 
import daily_work from "./dashboard/daily_work/index.vue";
import daily_work_calendar from "./dashboard/daily_work/calendar.vue";
import daliy_not_update from "./dashboard/daily_work/daliy_not_update.vue";
import add_daily_work from "./dashboard/daily_work/add_daily_work.vue";
import add_fac_work from "./dashboard/daily_work/add_fac_work.vue";
import edit_daily_work from "./dashboard/daily_work/edit_daily_work.vue";

//new_task
import new_task from "./dashboard/daily_work/new_task.vue";
import new_task_dept from "./dashboard/daily_work/new_task_dept.vue";
import task from "./dashboard/daily_work/task.vue";
import edit_task from "./dashboard/daily_work/edit_task.vue";
import task_not_update from "./dashboard/daily_work/task_not_update.vue"
task_not_update
// Daily Woark 
import monthly_report from "./dashboard/monthly_report/index.vue";
import add_monthly_report from "./dashboard/monthly_report/add.vue";
import monthly_not_update from "./dashboard/monthly_report/monthly_not_update.vue";

import summay_report_update from "./dashboard/summay_report_update/index.vue";
//import edit_monthly_report from "./dashboard/monthly_report/edit.vue" ;

// kra and kpi  
import kra_kpi_mos from "./dashboard/kar_kpi/kra_kpi_mos.vue";
import kra_kpi_mos_list from "./dashboard/kar_kpi/kra_kpi_mos_list.vue";
import kpi_score from "./dashboard/kar_kpi/kpi_score.vue";
import performance_report from "./dashboard/kar_kpi/performance_report.vue";
import bpt_report_tree from "./dashboard/kar_kpi/bpt_report_tree.vue";
import bpt_report from "./dashboard/kar_kpi/bpt_report.vue";
import winghead_achivement from "./dashboard/kar_kpi/winghead_achivement.vue";
import bpt_report_dev from "./dashboard/kar_kpi/bpt_report_dev.vue";
import bpt_report_details from "./dashboard/kar_kpi/bpt_report_details.vue";
import weightage_list from "./dashboard/kar_kpi/weightage_list.vue";
//target_permission_list
import target_permission_list from "./dashboard/kar_kpi/target_permission_list.vue";
import target from "./dashboard/kar_kpi/target.vue";
import achievement from "./dashboard/kar_kpi/achievement.vue";
import achievement_mos from "./dashboard/kar_kpi/achievement_mos.vue";

import achievement_topbutton from "./dashboard/kar_kpi/achievement_topbutton.vue";
import measure_of_success from "./dashboard/kar_kpi/measure_of_success.vue";
import kra_kpi from "./dashboard/kar_kpi/index.vue";
import kra_individual_update_list from "./dashboard/kra_individual/index.vue";
import kra_kpi_setting from "./dashboard/kar_kpi/kra_kpi_setting.vue";
import kra_kpi_assign from "./dashboard/kar_kpi/kra_kpi_assign.vue";
import mos_assign from "./dashboard/kar_kpi/mos_assign.vue";
//products
import manufacture_report from "./dashboard/products/manufacture_report.vue";
import manufacture_report_dev from "./dashboard/products/manufacture_report_dev.vue";
import products from "./dashboard/products/index.vue";
import product_daft from "./dashboard/products/product_daft.vue";
import new_product from "./dashboard/products/add.vue";
import cost_center from "./dashboard/products/cost_center.vue";
import cost from "./dashboard/products/cost.vue";
import costs_draft from "./dashboard/products/costs_draft.vue";
import factory from "./dashboard/products/factory.vue";
import production_wastage_daft from "./dashboard/products/production_wastage_daft.vue";
import summary_group from "./dashboard/products/summary_group.vue";
import wastage_summary from "./dashboard/products/wastage_summary.vue";
import wastage_summary_details from "./dashboard/products/wastage_summary_details.vue";
import cost_gl from "./dashboard/products/cost_gl.vue";
import standard_cost from "./dashboard/products/standard_cost.vue";
import factory_capacity from "./dashboard/products/factory_capacity.vue";
import productionplans from "./dashboard/products/productionplans.vue";
import production_targets from "./dashboard/products/production_targets.vue";
import sap_files from "./dashboard/products/sap_files.vue";
import manufacturer from "./dashboard/products/manufacturer.vue";
import delivery from "./dashboard/products/delivery.vue";
import wastage from "./dashboard/products/wastage.vue";
import product_edit from "./dashboard/products/product_edit.vue";
// pro_target_entry 
import pro_target_entry from "./dashboard/old_production/pro_target_entry.vue";
import pro_entry_bill_cost from "./dashboard/old_production/pro_entry_bill_cost.vue";
import pro_wastage_entry from "./dashboard/old_production/pro_wastage_entry.vue";
import rpt_view_all from "./dashboard/old_production/rpt_view_all.vue";

import tour_plan from "./dashboard/tour_plan/index.vue";
import tour_plan_edit from "./dashboard/tour_plan/tour_plan_edit.vue";
import tour_plan_update from "./dashboard/tour_plan/tour_plan_update.vue";
import new_tour from "./dashboard/tour_plan/add.vue";
import add_new_tour from "./dashboard/tour_plan/add_tour.vue";
import dept_follow_up from "./dashboard/daily_work/dept_follow_up.vue";

//QUICK LINK SETTINGS COMPONENT
import quick_link_settings from "./dashboard/settings/quick_link_settings.vue";
import buyer_contact from "./dashboard/settings/buyer_contact.vue";
import new_contact from "./dashboard/settings/new_contact.vue";
import edit_contact from "./dashboard/settings/edit_contact.vue";
import contact_details from "./dashboard/settings/contact_details.vue";
//MOS SETTINGS
import mos_settings from "./dashboard/settings/mos_settings.vue";
import privacy_policy from "./dashboard/settings/privacy_policy.vue";

//tour_users
import tour_users from "./dashboard/tour_users/tour_users.vue";
import tour_plan_entry from "./dashboard/tour_users/tour_plan_entry.vue";
import tour_plan_entry_calendar from "./dashboard/tour_users/tour_plan_entry_calendar.vue";
import tour_plan_objectives from "./dashboard/tour_users/tour_plan_objectives.vue";
import new_tour_sbjectives from "./dashboard/tour_users/new_tour_sbjectives.vue";
import objectives_edit from "./dashboard/tour_users/objectives_edit.vue"
//import { localStorageService } from "./helper.js";

//user_manuals
import new_user_manuals from "./dashboard/user_manuals/add.vue";
import user_manuals from "./dashboard/user_manuals/index.vue";
import edit_user_manuals from "./dashboard/user_manuals/edit.vue";
//Note: Projects 
import projects from "./dashboard/projects/index.vue";
import projectAdd from "./dashboard/projects/add.vue";

//Priority 
import priority_tasks from "./dashboard/priority_task/index.vue";
import priority_task_add from "./dashboard/priority_task/add.vue";
import priority_task_edit from "./dashboard/priority_task/edit.vue";
import priority_task_not_update from "./dashboard/priority_task/not_update.vue";
Vue.use(Router);
export default new Router({
  mode: "history",
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes: [
    {
      path: "/",
      name: "FrontendLayout",
      beforeEnter: requireAuth,
      component: FrontendLayout,
      children: [
        { path: "/", name: "Dashboard", component: Dashboard },
        {
          path: "/department-add",
          name: "department-add",
          beforeEnter: requireAuth,
          component: DepartmentAdd,
        },
        { path: "/home/:type", name: "Dashboard", component: Dashboard },
        {
          path: "/department-add",
          name: "department-add",
          beforeEnter: requireAuth,
          component: DepartmentAdd,
        },

        //department
        { path: "/department", name: "department", component: department },
        { path: "/dept_permission/:user_id", name: "dept_permission", component: dept_permission },
        //kra_kpi_permission
        { path: "/kra_kpi_permission/:user_id", name: "kra_kpi_permission", component: kra_kpi_permission },
        { path: "/dept_transfer/:user_id", name: "dept_transfer", component: dept_transfer },
        {
          path: "/new_department",
          name: "new_department",
          component: departmentAdd,
        },
        //department weekend
        { path: "/department_weekend", name: "department_weekend", component: department_weekend },
        
        {
          path: "/edit_department/:id",
          name: "edit_department",
          component: departmentEdit,
        },
        {
          path: "/department_setting/",
          name: "department_setting",
          component: department_setting,
        },

        //department
        { path: "/new_user_manuals", name: "new_user_manuals", component: new_user_manuals },
        { path: "/user_manuals", name: "user_manuals", component: user_manuals },
        { path: "/edit_user_manuals/:id", name: "edit_user_manuals", component: edit_user_manuals },

        {
          path: "/work_follow_up/",
          name: "work_follow_up",
          component: work_follow_up,
        },
        {
          path: "/follow_up_done_list/",
          name: "follow_up_done_list",
          component: follow_up_done_list,
        },
        {
          path: "/dept_follow_up/",
          name: "dept_follow_up",
          component: dept_follow_up,
        },
        {
          path: "/new_follow_up/",
          name: "new_follow_up",
          component: new_follow_up,
        },
        {
          path: "/copy_follow_up/:id",
          name: "copy_follow_up",
          component: new_follow_up,
        },
        {
          path: "/edit_follow_up/:id",
          name: "edit_follow_up",
          component: edit_follow_up,
        },


        {
          path: "/monthly_report_update",
          name: "monthly_report_update",
          component: monthly_report_update,
        },
        {
          path: "/monthly_report_update_modifie",
          name: "monthly_report_update_modifie",
          component: monthly_report_update_modifie,
        },
        {
          path: "/department_templates/",
          name: "department_templates",
          component: department_templates,
        },

        // users
        { path: "/production_emps", name: "production_emps", component: production_emps },
        { path: "/production_emps_new", name: "production_emps_new", component: production_emps_new },
        // { path: "/new_user", name: "new_user", component: userAdd },
        // { path: "/edit_user/:id", name: "edit_user",  component: userEdit},
        // { path: "/profile", name: "profile",  component: profile},
        { path: "/users", name: "users", component: users },
        { path: "/new_user", name: "new_user", component: userAdd },
        { path: "/edit_user/:id", name: "edit_user", component: userEdit },
        { path: "/profile", name: "profile", component: profile },
        { path: "/change_password", name: "change_password", component: change_password }, 
        // daily_work
        { path: "/daily_work_old", name: "daily_work", component: daily_work },
        { path: "/new_task", name: "new_task", component: new_task },
        { path: "/new_task_dept", name: "new_task_dept", component: new_task_dept },
        { path: "/edit_task/:id", name: "edit_task", component: edit_task },

        { path: "/task", name: "task", component: task },
        { path: "/daily_work", name: "daily_work", component: task },
        { path: "/task_not_update", name: "task_not_update", component: task_not_update },


        //daily_work_calendar
        { path: "/daily_work_calendar", name: "daily_work_calendar", component: daily_work_calendar },
        { path: "/daliy_not_update", name: "daliy_not_update", component: daliy_not_update },
        { path: "/add_daily_work", name: "add_daily_work", component: add_daily_work },
        { path: "/add_fac_work", name: "add_fac_work", component: add_fac_work },
        { path: "/edit_daily_work/:id", name: "edit_daily_work", component: edit_daily_work },

        // kra_kp 
        { path: "/kra_kpi", name: "kra_kpi", component: kra_kpi },
        { path: "/m_o_s_achievement_permissions", name: "m_o_s_achievement_permissions", component: m_o_s_achievement_permissions },
        { path: "/m_o_s_achievement_permissions/:id", name: "m_o_s_achievement_permissions", component: m_o_s_achievement_permissions },
        //m_o_s_dept_setting
        { path: "/m_o_s_dept_setting", name: "m_o_s_dept_setting", component: m_o_s_dept_setting },
        { path: "/kra_individual_update_list", name: "kra_individual_update_list", component: kra_individual_update_list },
        { path: "/kra_kpi_mos", name: "kra_kpi_mos", component: kra_kpi_mos },
        { path: "/kra_kpi_mos_list", name: "kra_kpi_mos_list", component: kra_kpi_mos_list },
        { path: "/performance_report", name: "performance_report", component: performance_report },
        { path: "/kpi_score", name: "kpi_score", component: kpi_score },
        { path: "/bpt_report_tree", name: "bpt_report_tree", component: bpt_report_tree },
        { path: "/bpt_report", name: "bpt_report", component: bpt_report },
        { path: "/winghead_achivement/:id", name: "winghead_achivement", component: winghead_achivement },
        { path: "/winghead_achivement", name: "winghead_achivement_all", component: winghead_achivement },
        { path: "/bpt_report_filter/:quarter/:month/:dept_id/:kra_id/:kpi_id", name: "bpt_report", component: bpt_report },
        { path: "/bpt_report_dev", name: "bpt_report_dev", component: bpt_report_dev },
        { path: "/bpt_report_details/:id", name: "bpt_report_details", component: bpt_report_details },
        { path: "/weightage_list", name: "weightage_list", component: weightage_list },
        { path: "/target_permission_list", name: "target_permission_list", component: target_permission_list },
        { path: "/measure_of_success/:id", name: "kra_kpi_mos", component: measure_of_success },
        { path: "/target/:id", name: "target", component: target },
        { path: "/achievement/:id", name: "achievement", component: achievement },
        //achievement_mos
        { path: "/achievement_mos/:id", name: "achievement_mos", component: achievement_mos },
        { path: "/achievement_topbutton/:id", name: "achievement_topbutton", component: achievement_topbutton },
        { path: "/achievement_update/:id/:quarter || null /:month || null /:dept_id : null /:kra_id || null /:kpi_id || null", name: "achievement", component: achievement },
        //{ path : "/achievement_update/:id/:quarter/:month/:dept_id/:kra_id/:kpi_id", name : "achievement" , component : achievement},
        { path: "/kra_kpi_setting", name: "kra_kpi_setting", component: kra_kpi_setting },
        { path: "/kra_kpi_assign", name: "kra_kpi_assign", component: kra_kpi_assign },
        { path: "/mos_assign", name: "mos_assign", component: mos_assign },
        //wings 
        { path: "/wings", name: "wings", component: wings },
        { path: "/edit_wing/:id", name: "edit_wing", component: edit_wing },
        //Team 
        { path: "/team", name: "team", component: team },
        { path: "/assign_team/:id", name: "assign_team", component: assign_team },
        //monthly_report
        { path: "/monthly_report", name: "monthly_report", component: monthly_report },
        { path: "/add_monthly_report", name: "add_monthly_report", component: add_monthly_report },
        { path: "/monthly_not_update", name: "monthly_not_update", component: monthly_not_update },
        { path: "/summay_report_update", name: "summay_report_update", component: summay_report_update },
        //{ path: "/edit_monthly_report/:id", name: "edit_monthly_report", component: monthly_report },

        //products
        { path: "/manufacture_report", name: "manufacture_report", component: manufacture_report },
        { path: "/manufacture_report_dev", name: "manufacture_report_dev", component: manufacture_report_dev },

        // { path : "/manufacture_report", name : "manufacture_report_dev" , component : manufacture_report_dev},
        // { path : "/manufacture_report_dev", name : "manufacture_report" , component : manufacture_report},

        { path: "/production_wastage_daft", name: "production_wastage_daft", component: production_wastage_daft },
        { path: "/production_wastage_daft", name: "production_wastage_daft", component: production_wastage_daft },
        { path: "/products", name: "products", component: products },
        { path: "/product_daft", name: "product_daft", component: product_daft },
        { path: "/new_product", name: "new_product", component: new_product },
        { path: "/cost_center", name: "cost_center", component: cost_center },
        { path: "/costs", name: "cost", component: cost },
        { path: "/costs_draft", name: "costs_draft", component: costs_draft },
        { path: "/factory", name: "factory", component: factory },
        { path: "/summary_group", name: "summary_group", component: summary_group },
        { path: "/wastage_summary", name: "wastage_summary", component: wastage_summary },
        { path: "/wastage_summary_details/:id", name: "wastage_summary_details", component: wastage_summary_details },
        { path: "/cost_gl", name: "cost_gl", component: cost_gl },
        { path: "/standard_cost", name: "standard_cost", component: standard_cost },
        { path: "/factory_capacity", name: "factory_capacity", component: factory_capacity },
        { path: "/productionplans", name: "productionplans", component: productionplans },
        { path: "/production_targets", name: "production_targets", component: production_targets },
        { path: "/sap_files", name: "sap_files", component: sap_files },
        { path: "/manufacturer", name: "manufacturer", component: manufacturer },
        { path: "/manufacturer_search/:factory_id/:summary_group_id/:start_date/:end_date", name: "manufacturer", component: manufacturer },
        { path: "/delivery", name: "delivery", component: delivery },
        { path: "/wastage", name: "wastage", component: wastage },
        { path: "/product_edit/:id", name: "product_edit", component: product_edit },

        //tour_plan
        { path: "/tour_plan", name: "tour_plan", component: tour_plan },
        { path: "/tour_plan_users", name: "tour_plan_users", component: tour_plan_users },
        { path: "/tour_plan_not_submit_users", name: "tour_plan_not_submit_users", component: tour_plan_not_submit_users },
        { path: "/add_tour_user", name: "add_tour_user", component: add_tour_user },
        { path: "/tour_user_edit/:id", name: "tour_user_edit", component: tour_user_edit },
        { path: "/new_tour", name: "new_tour", component: new_tour },
        { path: "/add_new_tour", name: "add_new_tour", component: add_new_tour },
        { path: "/tour_plan_edit/:id", name: "tour_plan_edit", component: tour_plan_edit },
        { path: "/tour_plan_update/:id", name: "tour_plan_update", component: tour_plan_update },

        // pro_target_entry 
        { path: "/pro_target_entry", name: "pro_target_entry", component: pro_target_entry },
        { path: "/pro_entry_bill_cost", name: "pro_entry_bill_cost", component: pro_entry_bill_cost },
        { path: "/pro_wastage_entry", name: "pro_wastage_entry", component: pro_wastage_entry },
        { path: "/rpt_view_all", name: "rpt_view_all", component: rpt_view_all },

        //QUICK LINK SETTINGS
        { path: "/quick_link_settings", name: "quick_link_settings", component: quick_link_settings },
        { path: "/buyer_contact", name: "buyer_contact", component: buyer_contact },
        { path: "/new_contact", name: "new_contact", component: new_contact },
        {
          path: "/edit_contact/:id",
          name: "edit_contact",
          component: edit_contact,
        }, 
        {
          path: "/contact_details/:id",
          name: "contact_details",
          component: contact_details,
        },                
        { path: "/mos_settings", name: "mos_settings", component: mos_settings },
        // tour_users
        { path: "/tour_users", name: "tour_users", component: tour_users },
        //tour_plan_entry 
        { path: "/tour_plan_entry", name: "tour_plan_entry", component: tour_plan_entry },
        { path: "/tour_plan_entry_calendar", name: "tour_plan_entry_calendar", component: tour_plan_entry_calendar },
        { path: "/tour_plan_objectives", name: "tour_plan_objectives", component: tour_plan_objectives },
        { path: "/new_tour_sbjectives", name: "new_tour_sbortjectives", component: new_tour_sbjectives },
        { path: "/objectives_edit/:id", name: "objectives_edit", component: objectives_edit }, 
        //Note: Projects
        { path: "/projects", name: "projects", component: projects },  
        { path: "/new_projects", name: "new_projects", component: projectAdd },
        //priority_tasks  
        { path: "/priority_tasks", name: "priority_tasks", component: priority_tasks },  
        { path: "/priority_task_add", name: "priority_task_add", component: priority_task_add },    
        { path: "/priority_task_edit/:id", name: "priority_task_edit", component: priority_task_edit },   
        { path: "/priority_task_not_update", name: "priority_task_not_update", component: priority_task_not_update },   
         
      ],
    },
    {
      path: "/login",
      name: "login",
      component: login,
    },
    {
      path: "/bpt_report_api",
      name: "login",
      component: bpt_report,
    },
    {
      path: "/privacy_policy",
      name: "privacy_policy",
      component: privacy_policy,
    },
    {
      path: "/adupdate",
      name: "adupdate",
      component: adupdate,
    },

  ],
});
function requireAuth(to, from, next) {
  if (localStorage.d_token) {
    next();
  }
  else {
    localStorage.clear();
    window.location.href = "/login";
  }
}