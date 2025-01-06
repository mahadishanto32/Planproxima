import { mdbDatatable2 } from 'mdbvue'
import moment from 'moment'
import { QuasarTiptapPlugin } from 'quasar-tiptap'
import 'sweetalert2/dist/sweetalert2.min.css'
import Vue from 'vue'
import VModal from 'vue-js-modal'
import VueLoaders from 'vue-loaders'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import VueLocalStorage from 'vue-localstorage'
import vueNiceScrollbar from 'vue-nice-scrollbar'
import VueSwal from 'vue-swal'
import VueSweetalert2 from 'vue-sweetalert2'
import Toasted from 'vue-toasted'
import App from './App.vue'
import axios from './axios_instance'
import router from './router'
//import { RichTextEditorPlugin } from "@syncfusion/ej2-vue-richtexteditor";
import 'quill/dist/quill.bubble.css' // for bubble theme
import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import VCalendar from 'v-calendar'
import Viewer from 'v-viewer'
import 'viewerjs/dist/viewer.css'
//import VueQuillEditor from 'vuejs-quill'
//import CKEditor from '@ckeditor/ckeditor5-vue2';
import CKEditor from '@ckeditor/ckeditor5-vue2'
import vClickOutside from 'v-click-outside'
import Clipboard from 'v-clipboard'
import VTooltip from 'v-tooltip'
import VueExcelXlsx from 'vue-excel-xlsx'
import VueQuillEditor from 'vue-quill-editor'
import VueRouterBackButton from 'vue-router-back-button'
import Vueditor from 'vueditor'
import 'vueditor/dist/style/vueditor.min.css'
import Vuex from 'vuex'
Vue.use(VTooltip)
Vue.use(vClickOutside)
//import { QuasarTiptapPlugin, RecommendedExtensions } from 'quasar-tiptap'
//import CKEditor from '@ckeditor/ckeditor5-vue';
// import Swal from 'sweetalert2';
Vue.use(vueNiceScrollbar)
Vue.config.productionTip = false
Vue.use(VueExcelXlsx)
Vue.use(Viewer)
Vue.use(VCalendar)

//BASE URL CONFIG
if (window.location.origin == 'http://localhost:8080') {
  window.base_url = 'http://localhost:8080/'
  window.api_url = 'http://127.0.0.1:8000/api/'
  window.backend_url = 'http://127.0.0.1:8000/'
} else if (window.location.origin == 'https://planproxima.humanx.ltd') {
  window.base_url = 'https://planproxima.humanx.ltd/'
  window.api_url = 'https://planproxima.humanx.ltd/backend/public/api/'
  window.backend_url = 'https://planproxima.humanx.ltd/backend/public/'
} else if (window.location.origin == 'https://planproxima.humanx.ltd') {
  window.base_url = 'https://planproxima.humanx.ltd/'
  window.api_url = 'https://planproxima.humanx.ltd/backend/public/api/'
  window.backend_url = 'https://planproxima.humanx.ltd/backend/public/'
} else if (window.location.origin == 'https://planproxima.humanx.ltd') {
  window.base_url = 'https://planproxima.humanx.ltd/'
  window.api_url = 'https://planproxima.humanx.ltd/backend/public/api/'
  window.backend_url = 'https://planproxima.humanx.ltd/backend/public/'
}else if (window.location.origin == 'http://192.168.0.182') {
  window.base_url = 'http://192.168.0.182/'
  window.api_url = 'http://192.168.0.182/backend/public/api/'
  window.backend_url = 'http://192.168.0.182/backend/public/'
}
 else {
  window.base_url = 'http://localhost:8080/'
  window.api_url = 'http://127.0.0.1:8000/api/'
  window.backend_url = 'http://localhost/svn_bpt/backend/'
}
//  window.base_url = 'http://192.168.0.211/backend/public/';
//  window.api_url = 'http://192.168.0.211/backend/public/api/';
//  window.backend_url = 'http://192.168.0.211/backend/public/backend/';

// window.base_url = 'https://planproxima.humanx.ltd/';
// window.api_url = 'https://planproxima.humanx.ltd/backend/public/api/';
// window.backend_url = 'https://planproxima.humanx.ltd/backend/public/';


// moment.tz.setDefault('Asia/Dhaka');
Vue.use(VueSwal)
// Vue.use(Swal);
//Vue.use(RichTextEditorPlugin);
Vue.use(VueSweetalert2)
Vue.use(Toasted)
Vue.use(VueLocalStorage)
Vue.use(VueLoaders)
Vue.use(VueSweetalert2)
Vue.use(VModal)
Vue.use(Clipboard)
Vue.use(QuasarTiptapPlugin, {
  language: 'en-us',
  spellcheck: true,
})

Vue.use(CKEditor)
//Vue.use( CKEditor );
Vue.use(VueQuillEditor)

Vue.use(
  Loading,
  {
    color: '#ec6523 ',
    loader: 'spinner',
    width: 64,
    height: 64,
    backgroundColor: '#ffffff',
    opacity: 0.5,
    zIndex: 999,
  },
  {}
)

Vue.filter('formatDate', function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD')
  }
})
Vue.filter('formatMonth', function (value) {
  if (value) {
    return moment(String(value)).format('MMM')
  }
})

let config = {
  toolbar: [
    'removeFormat',
    'undo',
    '|',
    'elements',
    'fontSize',
    'foreColor',
    'backColor',
    'divider',
    'bold',
    'italic',
    'underline',
    'strikeThrough',
    'links',
    'divider',
    'subscript',
    'superscript',
    '|',
    'picture',
    'tables',
    'divider',
    'justifyLeft',
    'justifyCenter',
    'justifyRight',
    'justifyFull',
    '|',
    'indent',
    'outdent',
    'insertOrderedList',
    'insertUnorderedList',
    '|',
    'switchView',
  ],
  fontName: [
    { val: 'arial black' },
    { val: 'times new roman' },
    { val: 'Courier New' },
  ],
  fontSize: [
    '12px',
    '14px',
    '16px',
    '18px',
    '0.8rem',
    '1.0rem',
    '1.2rem',
    '1.5rem',
    '2.0rem',
  ],
  uploadUrl: '',
}

Vue.use(Vuex)
Vue.use(Vueditor, config)
Vue.use(VueRouterBackButton, { router })

Vue.mixin({
  data: function () {
    // Get the current year
    var year = new Date().getFullYear()
    const currentDate = new Date()
    const currentMonth = currentDate.getMonth()
    if (currentMonth > 5) {
      year = year + 1
    }

    return {
      getCurrentMonth() {
        const currentDate = new Date()
        const monthName = currentDate.toLocaleString('default', {
          month: 'short',
        })
        return monthName.toLowerCase()
      },
      year: this.$localStorage.get('year')
        ? this.$localStorage.get('year')
        : year,
      months: [
        { name: 'Jan', id: 'jan' },
        { name: 'Feb', id: 'feb' },
        {
          name: 'Mar',
          id: 'mar',
        },
        { name: 'Apr', id: 'apr' },
        { name: 'May', id: 'may' },
        {
          name: 'Jun',
          id: 'jun',
        },
        { name: 'Jul', id: 'jul' },
        { name: 'Aug', id: 'aug' },
        {
          name: 'Sep',
          id: 'sep',
        },
        { name: 'Oct', id: 'oct' },
        { name: 'Nov', id: 'nov' },
        { name: 'Dec', id: 'dec' },
      ],
      months_old: [
        { name: 'Jan', id: '1' },
        { name: 'Feb', id: '2' },
        { name: 'Mar', id: '3' },
        {
          name: 'Apr',
          id: '4',
        },
        { name: 'May', id: '5' },
        { name: 'Jun', id: '6' },
        { name: 'Jul', id: '7' },
        {
          name: 'Aug',
          id: '8',
        },
        { name: 'Sep', id: '9' },
        { name: 'Oct', id: '10' },
        { name: 'Nov', id: '11' },
        {
          name: 'Dec',
          id: '12',
        },
      ],
      getCurrentQuarterId() {
        const currentDate = new Date()
        const currentMonth = currentDate.getMonth() + 1 // Months are zero-based

        // Determine the quarter based on the current month
        let quarterId
        if (currentMonth >= 1 && currentMonth <= 3) {
          quarterId = 3
        } else if (currentMonth >= 4 && currentMonth <= 6) {
          quarterId = 4
        } else if (currentMonth >= 7 && currentMonth <= 9) {
          quarterId = 1
        } else {
          quarterId = 2
        }
        return quarterId
      },
      quarter_months: [
        { name: '1st Quarter', id: '1' },
        { name: '2nd Quarter', id: '2' },
        { name: '3rd Quarter', id: '3' },
        { name: '4th Quarter', id: '4' },
      ],
      quarter_months_2nd: [
        { name: '1st quarter', id: '3' },
        { name: '2nd quarter', id: '4' },
        {
          name: '3rd quarter',
          id: '1',
        },
        { name: '4th quarter', id: '2' },
      ],
      companis: [
        { id: 1100, name: 1100 },
        { id: 1200, name: 1200 },
        { id: 1300, name: 1300 },
        {
          id: 1400,
          name: 1400,
        },
        { id: 1700, name: 1700 },
        { id: 1800, name: 1800 },
      ],
      dateValidation(data) {
        var currentDate = new Date()
        var endDate = new Date(data)
        if (currentDate <= endDate) {
          return true
        } else {
          false
        }
      },
      dateRangeValidation(startDate, endDate) {
        var currentDate = new Date()
        var startDate2 = new Date(startDate)
        var endDate2 = new Date(endDate)
        endDate2.setTime(endDate2.getTime() + 23 * 60 * 60 * 1000)

        if (currentDate >= startDate2 && currentDate <= endDate2) {
          return true
        } else {
          false
        }
      },
      dateRangeValidationMOS(startDate, endDate) {
        var currentDate = new Date()
        var startDate2 = new Date(startDate)
        var endDate2 = new Date(endDate)
        endDate2.setTime(endDate2.getTime() + 23 * 60 * 60 * 1000)

        if (currentDate >= startDate2 && currentDate <= endDate2) {
          return true
        } else {
          false
        }
      },
      formatAMPM(date) {
        // var hours = date.getHours();
        // var minutes = date.getMinutes();
        const myArr = date.split(':')
        var hours = myArr[0]
        var ampm = hours >= 12 ? 'PM' : 'AM'
        hours = hours % 12
        var minutes = myArr[1]
        hours = hours ? hours : 12 // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes
        var strTime = hours + ':' + minutes + '' + ampm
        return strTime
      },
      factory_old: [
        { id: 50, name: 'Factory (Brass Rod)' },
        { id: 110, name: 'Factory (Cable)' },
        { id: 27, name: 'Factory (Electronics)' },
        { id: 25, name: 'Factory (Fan)' },
        { id: 24, name: 'Factory (IR Bulb)' },
        { id: 69, name: 'Factory Operation (Access, Brass)' },
        { id: 30, name: 'Factory (Tissue)' },
      ],

      format_Date(value) {
        return moment(String(value)).format('YYYY-MM-DD')
      },
      format_Date_month_name(value) {
        return moment(String(value)).format('F')
      },

      amountConvert(num, digits) {
        var si = [
          { value: 1, symbol: '' },
          { value: 1e3, symbol: 'k' },
          { value: 1e6, symbol: 'M' },
          {
            value: 1e9,
            symbol: 'G',
          },
          { value: 1e12, symbol: 'T' },
          { value: 1e15, symbol: 'P' },
          { value: 1e18, symbol: 'E' },
        ]
        var rx = /\.0+$|(\.[0-9]*[1-9])0+$/
        var i
        for (i = si.length - 1; i > 0; i--) {
          if (num >= si[i].value) {
            break
          }
        }
        return (
          (num / si[i].value).toFixed(digits).replace(rx, '$1') + si[i].symbol
        )
      },
      getUserType() {
        if (this.$localStorage.get('d_token')) {
          let user = JSON.parse(this.$localStorage.get('user'))
          return user.type
        }
        return false
      },
      getReferenceId() {
        if (this.$localStorage.get('d_token')) {
          let user = JSON.parse(this.$localStorage.get('user'))
          return user.reference_id
        }
        return false
      },
      permission(e) {
        if (this.$localStorage.get('d_token')) {
          if (e) {
            let user = JSON.parse(this.$localStorage.get('user'))
            let role = user.role_id
            // alert(role);

            //console.log('user', user)
            //CHECK DEPARTMENT ID
            if (user.dept_id > 0) {
              if (
                role == 1 &&
                (e == 'dashboard' ||
                  e == 'work_follow_up' ||
                  e == 'entry_title' ||
                  e == 'priority_task' ||
                  e == 'production_menu_title' ||
                  e == 'products' ||
                  e == 'cost_center' ||
                  e == 'factory' ||
                  e == 'kra_kpi_setting' ||
                  e == 'report_permission' ||
                  e == 'summary_group' ||
                  e == 'wastage_summary' ||
                  e == 'costs' ||
                  e == 'cost_gl' ||
                  e == 'productionplans' ||
                  e == 'production_targets' ||
                  e == 'standard_cost' ||
                  e == 'factory_capacity' ||
                  e == 'production_wastage_daft' ||
                  e == 'manufacture_report' ||
                  e == 'production_emps' ||
                  e == 'department' ||
                  e == 'department_setting' ||
                  e == 'operation_menu_title' ||
                  e == 'users' ||
                  e == 'kra_kpi_mos' ||
                  e == 'entry_title' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'wings' ||
                  e == 'weightage_list' ||
                  e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'tour_plan' ||
                  e == 'tour_plan_users' ||
                  e == 'tour_plan_not_submit_users' ||
                  e == 'add_tour_user' ||
                  e == 'monthly_report' ||
                  e == 'monthly_not_update' ||
                  e == 'monthly_report_update' ||
                  e == 'monthly_summary_update' ||
                  e == 'summay_report_update' ||
                  e == 'kra_individual_update_list' ||
                  e == 'performance_report' ||
                  e == 'old_production' ||
                  e == 'pro_target_entry' ||
                  e == 'rpt_view_all' ||
                  e == 'pro_wastage_entry' ||
                  e == 'pro_entry_bill_cost' ||
                  e == 'manufacturer' ||
                  e == 'delivery' ||
                  e == 'wastage' ||
                  e == 'work_follow_up' ||
                  e == 'sap_files' ||
                  e == 'settings' ||
                  e == 'department_templates' ||
                  e == 'target_permission_list' ||
                  e == 'department_weekend')
              ) {
                // System administrator
                return true
              } else if (
                role == 8 &&
                // Chairman's Office
                (e == 'dashboard' ||
                  e == 'work_follow_up' ||
                  e == 'chairman_office' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'monthly_not_update' ||
                  e == 'mos_target_permission' ||
                  e == 'report_permission' ||
                  e == 'production_menu_title' ||
                  // e == "products" ||
                  //e == "manufacture_report" ||
                  (e == 'manufacture_report' &&
                    (user.deptjoin.is_factory == 1 ||
                      user.deptjoin.is_factory == 2)) ||
                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  // e == "kra_kpi_mos"||
                  // e == "kra_kpi_mos_list" ||
                  //  e == "weightage_list" ||
                  e == 'report_menu_title' ||
                  e == 'work_follow_up' ||
                  e == 'bpt_report' ||
                  e == 'dept_follow_up' ||
                  e == 'daliy_not_update' ||
                  e == 'monthly_report')
              ) {
                // System administrator
                return true
              } else if (
                role == 2 &&
                (e == 'department' || e == 'column_dept')
              ) {
                e == 'dashboard' ||
                  e == 'chairman_office' ||
                  e == 'work_follow_up' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  //e == "manufacture_report" ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  (e == 'manufacture_report' &&
                    (user.deptjoin.is_factory == 1 ||
                      user.deptjoin.is_factory == 2)) ||
                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  e == 'report_menu_title' ||
                  e == 'work_follow_up' ||
                  e == 'bpt_report' ||
                  e == 'monthly_report'
                return true
              } else if (
                role == 3 &&
                (e == 'chairman_office' ||
                  e == 'dashboard' ||
                  e == 'priority_task' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'manufacture_report' ||
                  //(e == "manufacture_report" && (user.deptjoin.is_factory == 1 || user.deptjoin.is_factory == 2)) ||

                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'monthly_not_update' ||
                  //e == "tour_plan" ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  (e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  e == 'old_production' ||
                  e == 'rpt_view_all' ||
                  e == 'work_follow_up' ||
                  e == 'monthly_report' ||
                  e == 'settings' ||
                  e == 'monthly_summary_update')
              ) {
                return true
              } else if (
                role == 4 &&
                (e == 'dashboard' ||
                  e == 'priority_task' ||
                  e == 'monthly_budget_list' ||
                  e == 'production_menu_title' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  //e == "tour_plan" ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  (e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  e == 'daily_work' ||
                  e == 'dept_follow_up' ||
                  e == 'work_follow_up' ||
                  e == 'add_daily_work')
              ) {
                // Cost center
                return true
              } else if (
                role == 5 &&
                (e == 'entry_title' ||
                  e == 'work_schedule_menu' ||
                  e == 'dashboard' ||
                  e == 'daily_work' ||
                  e == 'priority_task' ||
                  e == 'dept_follow_up' ||
                  e == 'add_daily_work' ||
                  e == 'kra_kpi_mos' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'wings' ||
                  e == 'team' ||
                  e == 'new_wing' ||
                  e == 'settings' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'wing_kra_kpi_upload' ||
                  e == 'weightage_list' ||
                  e == 'report_menu_title' ||
                  e == 'production_menu_title' ||
                  e == 'target_permission_list' ||
                  e == 'achievement_approval' ||
                  //e == "manufacture_report" ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  (e == 'manufacture_report' &&
                    (user.deptjoin.is_factory == 1 ||
                      user.deptjoin.is_factory == 2)) ||
                  //e == "costs" ||
                  (e == 'costs' && user.deptjoin.is_factory == 1) ||
                  e == 'cost_gl' ||
                  //e == "productionplans" ||
                  //e == "production_emps" ||
                  (e == 'production_emps' && user.deptjoin.is_factory == 1) ||
                  //e == "production_targets" ||
                  //e == "standard_cost" ||
                  //e == "factory_capacity" ||
                  //e == "production_wastage_daft" ||
                  (e == 'production_wastage_daft' &&
                    user.deptjoin.is_factory == 1) ||
                  e == 'old_production' ||
                  //e == "rpt_view_all" ||
                  (e == 'rpt_view_all' && user.deptjoin.is_factory == 1) ||
                  e == 'bpt_report' ||
                  e == 'monthly_report' ||
                  e == 'users' ||
                  e == 'operation_menu_title' ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  //e == "tour_plan" ||

                  (e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  //e == "tour_plan_users" ||

                  (e == 'tour_plan_users' && user.deptjoin.is_tour == 1) ||
                  (e == 'tour_plan_not_submit_users' &&
                    user.deptjoin.is_tour == 1) ||
                  e == 'add_tour_user' ||
                  // e == "work_follow_up" ||
                  //e == "delivery" ||
                  (e == 'delivery' && user.deptjoin.is_factory == 1) ||
                  //e == "wastage" ||
                  (e == 'wastage' && user.deptjoin.is_factory == 1) ||
                  //e == "products" ||
                  (e == 'products' && user.deptjoin.is_factory == 1) ||
                  //e == "cost_center" ||
                  (e == 'cost_center' && user.deptjoin.is_factory == 1) ||
                  //e == "standard_cost" ||
                  (e == 'standard_cost' && user.deptjoin.is_factory == 1) ||
                  //e == "factory_capacity" ||
                  (e == 'factory_capacity' && user.deptjoin.is_factory == 1) ||
                  //e == "productionplans" ||
                  (e == 'productionplans' && user.deptjoin.is_factory == 1) ||
                  //e == "production_targets" ||
                  (e == 'production_targets' &&
                    user.deptjoin.is_factory == 1) ||
                  //e == "summary_group" ||
                  (e == 'summary_group' && user.deptjoin.is_factory == 1) ||
                  //e == "wastage_summary" ||
                  (e == 'wastage_summary' && user.deptjoin.is_factory == 1) ||
                  e == 'kra_kpi_setting')
              ) {
                // HOD
                return true
              } else if (
                role == 6 &&
                (e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'user_dashboard' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'add_daily_work' ||
                  e == 'monthly_report' ||
                  e == 'users' ||
                  e == 'wings' ||
                  e == 'wing_kra_kpi_upload' ||
                  e == 'team' ||
                  e == 'target_permission_list' ||
                  e == 'achievement_approval' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'mos_target_permission' ||
                  //report_permission
                  (e == 'report_permission' &&
                    user.deptjoin.report_permission == 1) ||
                  (e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  e == 'entry_title' ||
                  e == 'weightage_list' ||
                  e == 'kra_kpi_mos' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'expenses')
              ) {
                //Wings tour_plan
                return true
              } else if (
                role == 7 &&
                (e == 'work_schedule_menu' ||
                  e == 'team' ||
                  e == 'user_dashboard' ||
                  e == 'daily_work' ||
                  e == 'add_daily_work' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'weightage_list' ||
                  e == 'report_menu_title' ||
                  e == 'mos_target_permission' ||
                  e == 'wing_kra_kpi_upload' ||
                  (e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  e == 'bpt_report')
              ) {
                return true
              } else if (
                role == 9 &&
                //e == "tour_plan" ||
                ((e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  //e == "add_tour_user" ||
                  (e == 'add_tour_user' && user.deptjoin.is_tour == 1) ||
                  e == 'mos_target_permission' ||
                  (e == 'tour_plan_users' && user.deptjoin.is_tour == 1) ||
                  (e == 'tour_plan_not_submit_users' &&
                    user.deptjoin.is_tour == 1) ||
                  (e == 'tour_users' && user.deptjoin.is_tour == 1))
              ) {
                return true
              } else if (
                role == 10 &&
                //e == "tour_plan" ||
                ((e == 'tour_plan' && user.deptjoin.is_tour == 1) ||
                  e == 'dashboard' ||
                  e == 'work_schedule_menu' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'daily_work' ||
                  e == 'report_menu_title' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'bpt_report' ||
                  e == 'entry_title' ||
                  e == 'mos_target_permission' ||
                  e == 'weightage_list' ||
                  e == 'kra_kpi_mos' ||
                  e == 'add_daily_work' ||
                  e == 'tour_users')
              ) {
                return true
              } else {
                return false
              }
              //WITHOUT DEPT ID
            } else {
              if (
                role == 1 &&
                (e == 'dashboard' ||
                  e == 'priority_task' ||
                  e == 'entry_title' ||
                  e == 'production_menu_title' ||
                  e == 'products' ||
                  e == 'report_permission' ||
                  e == 'cost_center' ||
                  e == 'factory' ||
                  e == 'kra_kpi_setting' ||
                  e == 'summary_group' ||
                  e == 'wastage_summary' ||
                  e == 'costs' ||
                  e == 'cost_gl' ||
                  e == 'productionplans' ||
                  e == 'production_targets' ||
                  e == 'standard_cost' ||
                  e == 'factory_capacity' ||
                  e == 'production_wastage_daft' ||
                  e == 'manufacture_report' ||
                  e == 'production_emps' ||
                  e == 'department' ||
                  e == 'department_setting' ||
                  e == 'operation_menu_title' ||
                  e == 'users' ||
                  e == 'kra_kpi_mos' ||
                  e == 'entry_title' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  e == 'kra_kpi_mos_list' ||
                  e == 'wings' ||
                  e == 'weightage_list' ||
                  e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'tour_plan' ||
                  e == 'tour_plan_users' ||
                  e == 'tour_plan_not_submit_users' ||
                  e == 'add_tour_user' ||
                  e == 'monthly_report' ||
                  e == 'monthly_not_update' ||
                  e == 'monthly_report_update' ||
                  e == 'monthly_summary_update' ||
                  e == 'summay_report_update' ||
                  e == 'kra_individual_update_list' ||
                  e == 'performance_report' ||
                  e == 'old_production' ||
                  e == 'pro_target_entry' ||
                  e == 'rpt_view_all' ||
                  e == 'pro_wastage_entry' ||
                  e == 'pro_entry_bill_cost' ||
                  e == 'manufacturer' ||
                  e == 'delivery' ||
                  e == 'wastage' ||
                  e == 'work_follow_up' ||
                  e == 'sap_files' ||
                  e == 'settings' ||
                  e == 'department_templates' ||
                  e == 'target_permission_list' ||
                  e == 'department_weekend')
              ) {
                // System administrator
                return true
              } else if (
                role == 8 &&
                // Chairman's Office
                (e == 'dashboard' ||
                  e == 'chairman_office' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'monthly_not_update' ||
                  // e == "products" ||
                  e == 'production_menu_title' ||
                  // e == "products" ||
                  e == 'manufacture_report' ||
                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  // e == "kra_kpi_mos"||
                  // e == "kra_kpi_mos_list" ||
                  //  e == "weightage_list" ||
                  e == 'report_menu_title' ||
                  e == 'work_follow_up' ||
                  e == 'bpt_report' ||
                  e == 'mos_target_permission' ||
                  e == 'dept_follow_up' ||
                  e == 'daliy_not_update' ||
                  e == 'monthly_report')
              ) {
                // System administrator
                return true
              } else if (
                role == 2 &&
                (e == 'department' || e == 'column_dept')
              ) {
                e == 'dashboard' ||
                  e == 'chairman_office' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  e == 'manufacture_report' ||
                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  e == 'report_menu_title' ||
                  e == 'work_follow_up' ||
                  e == 'bpt_report' ||
                  e == 'monthly_report'
                return true
              } else if (
                role == 3 &&
                (e == 'chairman_office' ||
                  e == 'dashboard' ||
                  e == 'priority_task' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'daliy_not_update' ||
                  e == 'dept_follow_up' ||
                  e == 'add_daily_work' ||
                  e == 'production_menu_title' ||
                  e == 'manufacture_report' ||
                  e == 'department' ||
                  e == 'operation_menu_title' ||
                  e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'monthly_not_update' ||
                  e == 'tour_plan' ||
                  e == 'old_production' ||
                  e == 'rpt_view_all' ||
                  e == 'work_follow_up' ||
                  e == 'monthly_report' ||
                  e == 'settings' ||
                  e == 'monthly_summary_update')
              ) {
                return true
              } else if (
                role == 4 &&
                (e == 'dashboard' ||
                  e == 'monthly_budget_list' ||
                  e == 'production_menu_title' ||
                  e == 'priority_task' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'tour_plan' ||
                  e == 'daily_work' ||
                  e == 'dept_follow_up' ||
                  e == 'work_follow_up' ||
                  e == 'add_daily_work')
              ) {
                // Cost center
                return true
              } else if (
                role == 5 &&
                (e == 'entry_title' ||
                  e == 'work_schedule_menu' ||
                  e == 'dashboard' ||
                  e == 'priority_task' ||
                  e == 'daily_work' ||
                  e == 'dept_follow_up' ||
                  e == 'add_daily_work' ||
                  e == 'kra_kpi_mos' ||
                  // e == "kra_kpi_mos_list" ||
                  e == 'settings' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'wings' ||
                  e == 'team' ||
                  e == 'weightage_list' ||
                  e == 'report_menu_title' ||
                  e == 'production_menu_title' ||
                  e == 'manufacture_report' ||
                  e == 'costs' ||
                  e == 'cost_gl' ||
                  e == 'productionplans' ||
                  e == 'production_emps' ||
                  e == 'production_targets' ||
                  e == 'standard_cost' ||
                  e == 'factory_capacity' ||
                  e == 'production_wastage_daft' ||
                  e == 'old_production' ||
                  e == 'rpt_view_all' ||
                  e == 'bpt_report' ||
                  e == 'monthly_report' ||
                  e == 'users' ||
                  e == 'operation_menu_title' ||
                  e == 'tour_plan' ||
                  e == 'tour_plan_users' ||
                  e == 'tour_plan_not_submit_users' ||
                  e == 'add_tour_user' ||
                  e == 'work_follow_up' ||
                  e == 'delivery' ||
                  e == 'wastage' ||
                  e == 'products' ||
                  e == 'cost_center' ||
                  e == 'standard_cost' ||
                  e == 'factory_capacity' ||
                  e == 'productionplans' ||
                  e == 'production_targets' ||
                  e == 'summary_group' ||
                  e == 'wastage_summary' ||
                  e == 'kra_kpi_setting')
              ) {
                // HOD
                return true
              } else if (
                role == 6 &&
                (e == 'report_menu_title' ||
                  e == 'bpt_report' ||
                  e == 'user_dashboard' ||
                  e == 'work_schedule_menu' ||
                  e == 'daily_work' ||
                  e == 'add_daily_work' ||
                  e == 'monthly_report' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'users' ||
                  e == 'tour_plan' ||
                  e == 'entry_title' ||
                  e == 'weightage_list' ||
                  e == 'mos_target_permission' ||
                  e == 'kra_kpi_mos' ||
                  e == 'team' ||
                  e == 'expenses')
              ) {
                console.log('menu', role)
                //Wings tour_plan
                return true
              } else if (
                role == 7 &&
                (e == 'work_schedule_menu' ||
                  e == 'user_dashboard' ||
                  e == 'daily_work' ||
                  e == 'wing_kra_kpi_upload' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'team' ||
                  e == 'mos_target_permission' ||
                  e == 'add_daily_work')
              ) {
                return true
              } else if (
                role == 9 &&
                (e == 'tour_plan' ||
                  e == 'add_tour_user' ||
                  e == 'tour_plan_users' ||
                  e == 'mos_target_permission' ||
                  e == 'tour_plan_not_submit_users' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'tour_users')
              ) {
                return true
              } else if (
                role == 10 &&
                (e == 'tour_plan' ||
                  e == 'dashboard' ||
                  e == 'work_schedule_menu' ||
                  e == 'mos_target_permission' ||
                  e == 'daily_work' ||
                  e == 'm_o_s_achievement_permissions' ||
                  e == 'settings' ||
                  e == 'bpt_report' ||
                  e == 'report_menu_title' ||
                  e == 'add_daily_work' ||
                  e == 'tour_users')
              ) {
                return true
              } else {
                return false
              }
            }
          } else {
            return false
          }
        } else {
          return false
        }
      },
      formatPrice(val) {
        val = Number(val)
        if (val) {
          val = val.toFixed(0)
          return val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,')
        } else {
          return 0
        }
      },
      async getMonth() {
        return await axios.get(this.api_url + 'months', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.$localStorage.get('d_token')
              ? `Bearer ${this.$localStorage.get('d_token')}`
              : '',
          },
        })
      },

      async getDepartments(status = null) {
        let where = '?'

        if (status) {
          where += 'status=' + status
        }
        return await axios.get(this.api_url + 'departments' + where, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.$localStorage.get('d_token')
              ? `Bearer ${this.$localStorage.get('d_token')}`
              : '',
          },
        })
      },

      async getDepartment(dept_id = null) {
        let deptid
        if (dept_id) {
          deptid = dept_id
        } else {
          let user_data = JSON.parse(this.$localStorage.get('user'))
          deptid = user_data.dept_id
        }
        return await axios.get(this.api_url + 'singel_dept/' + deptid, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.$localStorage.get('d_token')
              ? `Bearer ${this.$localStorage.get('d_token')}`
              : '',
          },
        })
      },

      async getItem(url) {
        return await axios.get(this.api_url + url, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: this.$localStorage.get('d_token')
              ? `Bearer ${this.$localStorage.get('d_token')}`
              : '',
          },
        })
      },
    }
  },
})

new Vue({
  router,
  mdbDatatable2,
  render: (h) => h(App),
}).$mount('#app')
