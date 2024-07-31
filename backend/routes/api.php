<?php

use App\Http\Controllers\API\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
  
    Route::post('mailcheck', [App\Http\Controllers\AuthController::class, 'mailcheck']);
    Route::post('mailcheck_code', [App\Http\Controllers\AuthController::class, 'mailcheck_code']);
    Route::post('reset_password', [App\Http\Controllers\AuthController::class, 'reset_password']);
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
        
        Route::post('user', [App\Http\Controllers\AuthController::class, 'user']);
    });
});
Route::middleware('auth:api')->post('change_password',  [App\Http\Controllers\AuthController::class, 'changePassword'] );

Route::group([
    'middleware' => 'auth:api'
], function () {
 
    Route::resource('country' , App\Http\Controllers\API\CountryController::class);
    Route::resource('users', App\Http\Controllers\API\UserAPIController::class);
    Route::get('users_list', [App\Http\Controllers\API\UserAPIController::class, 'users_list']);
    Route::get('cc_users', [App\Http\Controllers\API\UserAPIController::class, 'ccUsers']);
    Route::get('assign_cc_users/{id}', [App\Http\Controllers\API\UserAPIController::class, 'AssignccUsers']);
    Route::post('dep_transfer', [App\Http\Controllers\API\UserAPIController::class, 'dep_transfer']);
    //dep_transfer_sync
   

    Route::get('autenticate_user', [App\Http\Controllers\API\UserAPIController::class, 'autenticate_user']);
    Route::get('profile_thamnail', [App\Http\Controllers\API\UserAPIController::class, 'profile_thamnail']);
    Route::post('update_admail', [App\Http\Controllers\API\UserAPIController::class, 'update_admail']);
    Route::post('add_tour_user', [App\Http\Controllers\API\UserAPIController::class, 'add_tour_user']);
    Route::get('tour_plan_users', [App\Http\Controllers\API\UserAPIController::class, 'tour_plan_users']);

    // tour 
    Route::get('tour_user_list', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_user_list']);
    //Route::get('tourPlanHelper', [App\Http\Controllers\API\TourUserAPIController::class,'tourPlanHelper']);

    Route::get('tour_plan_not_submit_users', [App\Http\Controllers\API\UserAPIController::class, 'tour_plan_not_submit_users']);
    Route::get('tour_user', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_users']);
    Route::get('tour_user_edit/{id}', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_user_edit']);

    Route::get('tour_designation', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_designation']);
    Route::get('supervisor', [App\Http\Controllers\API\TourUserAPIController::class, 'supervisor']);
    //tour_supervisor
    Route::get('tour_supervisor', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_supervisor']);
    Route::post('users_change', [App\Http\Controllers\API\UserAPIController::class, 'users_change']);
    Route::post('profile_update/{id}', [App\Http\Controllers\API\UserAPIController::class, 'profile_update']);
    Route::get('user_wing', [App\Http\Controllers\API\UserAPIController::class, 'userWing']);
    Route::post('wing_change', [App\Http\Controllers\API\UserAPIController::class, 'wing_change']);

    Route::resource('tests', App\Http\Controllers\API\TestAPIController::class);
    Route::post('department_factory', [App\Http\Controllers\API\DepartmentAPIController::class, 'department_factory']);
    Route::resource('departments', App\Http\Controllers\API\DepartmentAPIController::class);
    Route::resource('dep_weekend', App\Http\Controllers\API\DepartmentWeekendController::class);
    Route::post('dep_weekend_updates', [App\Http\Controllers\API\DepartmentWeekendController::class,'update']);
    Route::post('weekendgroup_add', [App\Http\Controllers\API\DepartmentWeekendController::class,'weekendgroup_add']);
    Route::get('weekend_group', [App\Http\Controllers\API\DepartmentWeekendController::class,'weekend_group']);
    Route::get('weekend_assign', [App\Http\Controllers\API\DepartmentWeekendController::class,'weekend_assign']);
    Route::get('dep_weekend_assign', [App\Http\Controllers\API\DepartmentWeekendController::class, 'dep_weekend_assign']);
    Route::get('departments_all', [App\Http\Controllers\API\DepartmentAPIController::class, 'allDept']);
    Route::get('departments_report', [App\Http\Controllers\API\DepartmentAPIController::class, 'dept_report']);
    Route::get('dept_permission', [App\Http\Controllers\API\DepartmentAPIController::class, 'dept_permission']);
    Route::get('department_setting', [App\Http\Controllers\API\DepartmentAPIController::class, 'department_setting']);
    Route::get('singel_dept/{id}', [App\Http\Controllers\API\DepartmentAPIController::class, 'singel_dept']);
    Route::get('single_permission', [App\Http\Controllers\API\DepartmentAPIController::class, 'single_permission']);
    Route::get('monthly_date_range', [App\Http\Controllers\API\DepartmentAPIController::class, 'monthly_date_range']);
    Route::get('department_templates', [App\Http\Controllers\API\DepartmentAPIController::class, 'department_templates']);
    Route::get('templates_department', [App\Http\Controllers\API\MonthlyDateRangeAPIController::class, 'templates_department']);
    Route::get('task_templates', [App\Http\Controllers\API\DepartmentAPIController::class, 'task_templates']);

    Route::resource('k_r_a_s', App\Http\Controllers\API\KRAAPIController::class);
    Route::get('users_kra', [App\Http\Controllers\API\KRAAPIController::class, 'users_kra']);
    
    Route::get('kra_kpi_mos_tree', [App\Http\Controllers\API\KRAAPIController::class, 'kra_kpi_mos_tree']);
    Route::resource('k_p_i_s', App\Http\Controllers\API\KPIAPIController::class);

    Route::resource('daily_schedules', App\Http\Controllers\API\DailyScheduleAPIController::class);
    //daily_schedules_list
    Route::get('daily_schedules_list', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'daily_schedules_list']);
    //daily_task_list
    Route::get('daily_task_list', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'daily_task_list']);
    Route::get('today_task_list', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'today_task_list']);

    Route::get('my_daily_schedules', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'my_daily_schedules']);
    Route::get('role', [App\Http\Controllers\API\RoleAPIController::class, 'index']);
    Route::get('kar_kpi_mos_chart', [App\Http\Controllers\API\KRAAPIController::class, 'kar_kpi_mos_chart']);
    Route::post('kra_kpi_setting', [App\Http\Controllers\API\KRAAPIController::class, 'kra_kpi_setting']);
    Route::get('kra_kpi_mos', [App\Http\Controllers\API\KRAAPIController::class, 'kra_kpi_mos']);
    Route::get('kra_delete/{id}', [App\Http\Controllers\API\KRAAPIController::class, 'kra_delete']);
    //kra_all_delete
    Route::get('kra_all_delete', [App\Http\Controllers\API\KRAAPIController::class, 'kra_all_delete']);
    Route::get('kpi_delete/{id}', [App\Http\Controllers\API\KRAAPIController::class, 'kpi_delete']);
    Route::get('mos_delete/{id}', [App\Http\Controllers\API\KRAAPIController::class, 'mos_delete']);
    Route::resource('performance_report', App\Http\Controllers\API\MonthlyPerformanceReportController::class);
    Route::get('kra_kpi_mos_list', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_list']);
    Route::get('kra_kpi_mos_score_list', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_score_list']);
    Route::get('target_permission_list', [App\Http\Controllers\API\MOSAPIController::class, 'target_permission_list']);
    Route::resource('kra_individual_list', App\Http\Controllers\API\KraIndividualUpdateController::class);
    Route::get('kra_individual_status', [App\Http\Controllers\API\KraIndividualUpdateController::class, 'create']);
    Route::get('assign_mos_list', [App\Http\Controllers\API\MOSAPIController::class, 'assign_mos_list']);
    Route::get('kra_kpi_mos_list_unassign', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_list_unassign']);
    Route::post('assign_kra', [App\Http\Controllers\API\MOSAPIController::class, 'assign_kra']);
    //mos_modification_permission
    Route::post('mos_modification_permission', [App\Http\Controllers\API\MOSAPIController::class, 'mos_modification_permission']);
    //mos_achievement_permission
    Route::post('mos_achievement_permission', [App\Http\Controllers\API\MOSAPIController::class, 'mos_achievement_permission']);
    //mos_modification_permission_approved
    Route::post('mos_modification_permission_approved', [App\Http\Controllers\API\MOSAPIController::class, 'mos_modification_permission_approved']);
    Route::post('mos_modification_permission_acknowledge', [App\Http\Controllers\API\MOSAPIController::class, 'mos_modification_permission_acknowledge']);


    Route::post('assign_mos', [App\Http\Controllers\API\MOSAPIController::class, 'assign_mos']);
    Route::get('kra_kpi_mos_list2', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_list2']);
    Route::get('kra_details/{id}', [App\Http\Controllers\API\KRAAPIController::class, 'kra_details']);
    Route::get('kpi_details/{id}', [App\Http\Controllers\API\KPIAPIController::class, 'kpi_details']);
    Route::get('bpt_report', [App\Http\Controllers\API\KRAAPIController::class, 'bpt_report']);
    //Route::get('get_task', App\Http\Controllers\API\DailyScheduleAPIController::class, 'getTask');
    Route::resource('monthly_reports', App\Http\Controllers\API\MonthlyReportAPIController::class);
    Route::get('summay_report_update', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'summay_report_update']);
    Route::post('monthly_report_comment', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_report_comment']);
    Route::post('monthly_report_permission', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_report_permission']);
    Route::get('monthly_report_mail/{dept}/{month}', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_report_mail']);
    Route::post('monthly_reports_file_upload', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'new_file']);
    Route::resource('monthly_reports_file', App\Http\Controllers\API\MonthlyReportFileAPIController::class);
    Route::resource('m_o_s', App\Http\Controllers\API\MOSAPIController::class);
    Route::post('mos_update', [App\Http\Controllers\API\MOSAPIController::class, 'mos_update']);
    Route::resource('m_o_s', App\Http\Controllers\API\MOSAPIController::class);
    Route::resource('mos_datas', App\Http\Controllers\API\MosDataAPIController::class);
    Route::resource('tour_users', App\Http\Controllers\API\TourUserAPIController::class);
    //Manufacturer 
    Route::resource('manufacturer', App\Http\Controllers\ManufacturerController::class);
    Route::post('manufacturer-file-upload', [App\Http\Controllers\KRAAPIController::class, 'kar_kpi_mos_chart']);
    Route::resource('factorys', App\Http\Controllers\FactoryController::class);
    // Cost
    Route::post('cost-file-upload', [App\Http\Controllers\CostController::class, 'fileUpload']);
    Route::resource('cost', App\Http\Controllers\CostController::class);
    Route::resource('areas', App\Http\Controllers\ManufacturerController::class);
    //repost
    Route::resource('manufacture-report', App\Http\Controllers\ManufacturerController::class);
    Route::post('summary_list', [App\Http\Controllers\ManufacturerReportController::class, 'summaryList']);
    Route::post('production_report_5watt', [App\Http\Controllers\ManufacturerReportController::class, 'production_report_5watt']);

    Route::post('production_report', [App\Http\Controllers\ManufacturerReportController::class, 'production_report']);
    // Route::post('production_report_5watt', [App\Http\Controllers\ManufacturerReportController::class, 'production_report_5watt']);
    Route::get('yearly_report', [App\Http\Controllers\ManufacturerReportController::class, 'yearly_report']);
    //wastage
    Route::post('wastage-file-upload', [App\Http\Controllers\WastageController::class, 'fileUpload']);
    Route::resource('wastage', App\Http\Controllers\WastageController::class);
    Route::get('wastage_summary', [App\Http\Controllers\WastageController::class, 'wastage_summary']);
    Route::get('wastage_summary_group/{id}', [App\Http\Controllers\API\Wastege_relationAPIController::class, 'WastageSummaryGroup']);
    Route::get('wastage_summary_details/{id}', [App\Http\Controllers\WastageController::class, 'wastage_summary_details']);
    //product 
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::get('product_singel/{id}', [App\Http\Controllers\ProductController::class, 'product_singel']);
    Route::get('consumption_material/{id}', [App\Http\Controllers\ProductController::class, 'consumption_material']);
    //Route::post('product-file-upload', [App\Http\Controllers\ProductController::class, 'fileUpload']);
    // cost_center
    Route::resource('cost_center', App\Http\Controllers\CostCenterController::class);

    //production_wastage_daft
    Route::resource('production-wastage-daft', App\Http\Controllers\ProductionWastageDaftController::class);
    Route::post('production-wastage-daft-file-upload', [App\Http\Controllers\ProductionWastageDaftController::class, 'fileUpload']);
    Route::post('kpi-mos-upload', [App\Http\Controllers\API\KRAAPIController::class, 'fileUpload']);
    //mos-upload-csv
    Route::post('mos-upload-csv', [App\Http\Controllers\API\KRAAPIController::class, 'fileUploadCsv']);
    Route::post('achiv-upload-csv', [App\Http\Controllers\API\KRAAPIController::class, 'achivUploadCsv']);


    Route::post('download_mos_file_format', [App\Http\Controllers\API\KRAAPIController::class, 'download_mos_file_format']);
    Route::post('achiv_download_mos_file_format', [App\Http\Controllers\API\KRAAPIController::class, 'achiv_download_mos_file_format']);

    Route::get('sync', [App\Http\Controllers\ProductionWastageDaftController::class, 'sync']);
    Route::resource('production_wastage_daft', App\Http\Controllers\ManufacturerController::class);
    Route::get('production-wastage-daft-seen', [App\Http\Controllers\ProductionWastageDaftController::class, 'seen']);


    //production_wastage_daft
    Route::resource('production-target', App\Http\Controllers\ManufacturerController::class);
    Route::get('production-target-add', [App\Http\Controllers\ProductionTargetController::class, 'production_target_add']);
    Route::post('production-target-submit', [App\Http\Controllers\ProductionTargetController::class, 'production_target_add_submit']);

    //Consumtion
    Route::resource('consumtion', App\Http\Controllers\ConsumtionController::class);
    Route::resource('delivery', App\Http\Controllers\DeliveryController::class);
    Route::get('get_costcnter_list', [App\Http\Controllers\ManufacturerReportController::class, 'get_summary_list']);

    // Wings
    Route::resource('wings', App\Http\Controllers\API\WingAPIController::class);
    Route::resource('costs_drafts', App\Http\Controllers\API\CostsDraftAPIController::class);
    Route::post('costs_draft-file-upload', [App\Http\Controllers\API\CostsDraftAPIController::class, 'fileUpload']);
    Route::get('costs_draft-sync', [App\Http\Controllers\API\CostsDraftAPIController::class, 'sync']);
    Route::resource('factory_standards', App\Http\Controllers\API\FactoryStandardAPIController::class);

    //team
    Route::resource('teams', App\Http\Controllers\API\TeamAPIController::class);
    Route::resource('team_members', App\Http\Controllers\API\TeamMemberAPIController::class);
    //factory_standards-file-upload
    Route::post('factory_standards-file-upload', [App\Http\Controllers\API\FactoryStandardAPIController::class, 'fileUpload']);
    Route::resource('factory_capacities', App\Http\Controllers\API\FactoryCapacityAPIController::class);
    Route::post('factory_capacities-file-upload', [App\Http\Controllers\API\FactoryCapacityAPIController::class, 'fileUpload']);
    Route::resource('production_plans', App\Http\Controllers\API\ProductionPlansAPIController::class);
    Route::post('production_plans-file-upload', [App\Http\Controllers\API\ProductionPlansAPIController::class, 'fileUpload']);
    Route::resource('production_targets', App\Http\Controllers\API\ProductionTargetAPIController::class);
    Route::post('production_targets-file-upload', [App\Http\Controllers\API\ProductionTargetAPIController::class, 'fileUpload']);
    Route::get('dashboard', [App\Http\Controllers\API\DashboardAPIController::class, 'index']);
    Route::resource('monthly_report_files', App\Http\Controllers\API\monthly_report_fileAPIController::class);

    Route::get('department_wise_monthly_activity', [App\Http\Controllers\API\DepartmentAPIController::class, 'monthly_activity']);
    Route::get('daliy_not_update', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'daliy_not_update']);

    Route::get('daliy_task_report', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'daliy_task_report']);
    Route::post('download_daily_task', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'download_file']);    
    Route::post('daliy_mail', [App\Http\Controllers\API\DailyScheduleAPIController::class, 'daliy_mail']);
    Route::post('monthly_mail', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_mail']);
    Route::get('monthly_not_update', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_not_update']);
    Route::post('monthly_not_update_mail', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_not_update_mail']);
    Route::resource('tour_entries', App\Http\Controllers\API\TourEntryAPIController::class);
    Route::get('tour_entrie_list', [App\Http\Controllers\API\TourEntryAPIController::class, 'tour_entrie_list']);
    Route::post('download_tour_list', [App\Http\Controllers\API\TourEntryAPIController::class, 'download_file']);
    
    Route::get('tour_entrie_month_list', [App\Http\Controllers\API\TourEntryAPIController::class, 'tour_entrie_month_list']);

    Route::get('get_products_list', [App\Http\Controllers\ProductionReportController::class, 'get_products_list']);
    Route::get('get_sub_products_list', [App\Http\Controllers\ProductionReportController::class, 'get_sub_products_list']);
    Route::any('rpt_view_all', [App\Http\Controllers\ProductionReportController::class, 'rpt_view_all']);
    Route::post('pro_target_insert', [App\Http\Controllers\ProductionController::class, 'pro_target_insert']);
    Route::get('pro_target_entry', [App\Http\Controllers\ProductionController::class, 'pro_target_entry']);
    Route::get('select_area_data_cost', [App\Http\Controllers\ProductionController::class, 'select_area_data_cost']);
    Route::post('pro_cost_entry', [App\Http\Controllers\ProductionController::class, 'pro_cost_entry']);
    Route::get('pro_entry_bill_cost', [App\Http\Controllers\ProductionController::class, 'pro_entry_bill_cost']);
    Route::get('select_area_data', [App\Http\Controllers\ProductionController::class, 'select_area_data']);
    Route::resource('mos_feadbacks', App\Http\Controllers\API\MosFeadbackAPIController::class);
    Route::resource('department_assigns', App\Http\Controllers\API\DepartmentAssignAPIController::class);
    Route::post('dept_permission', [App\Http\Controllers\API\DepartmentAssignAPIController::class, 'dept_permission']);
    Route::resource('sap_files', App\Http\Controllers\API\SapFilesAPIController::class);
    Route::get('sap_files_delete/{id}',  [App\Http\Controllers\API\SapFilesAPIController::class, 'sap_files_delete']);
    Route::resource('department_settings', App\Http\Controllers\API\DepartmentSettingAPIController::class);
    Route::post('department_settings_update', [App\Http\Controllers\API\DepartmentSettingAPIController::class, 'department_settings_update']);
    Route::resource('monthly_date_ranges', App\Http\Controllers\API\MonthlyDateRangeAPIController::class);
    Route::post('department_monthly_report_update', [App\Http\Controllers\API\MonthlyDateRangeAPIController::class, 'department_monthly_report_update']);
    Route::post('templates_updates', [App\Http\Controllers\API\MonthlyDateRangeAPIController::class, 'templates_updates']);
    Route::resource('production_feedbacks', App\Http\Controllers\API\ProductionFeedbackAPIController::class);
    Route::resource('daily_schedule_comments', App\Http\Controllers\API\Daily_schedule_commentAPIController::class);
    Route::post('achivement_notification', [App\Http\Controllers\API\Daily_schedule_commentAPIController::class, 'achivement_notification']);
    Route::post('achivement_update', [App\Http\Controllers\API\Daily_schedule_commentAPIController::class, 'achivement_update']);
    Route::post('achievement_approval', [App\Http\Controllers\API\MOSAPIController::class, 'achievement_approval']);
    Route::resource('daily_schedule_headers', App\Http\Controllers\API\Daily_schedule_headerAPIController::class);
    Route::resource('production_emps', App\Http\Controllers\API\Production_empAPIController::class);
    Route::get('production_emps_factory', [App\Http\Controllers\API\Production_empAPIController::class, 'production_emps_factory']);
    Route::get('get_products_list', [App\Http\Controllers\API\Production_empAPIController::class, 'get_products_list']);
    Route::get('get_iending_emp', [App\Http\Controllers\API\Production_empAPIController::class, 'get_iending_emp']);
    Route::resource('follow_ups', App\Http\Controllers\API\Follow_upAPIController::class);
    Route::put('follow_ups_status/{id}', [App\Http\Controllers\API\Follow_upAPIController::class, 'follow_ups_status']);
    Route::resource('follow_up_depts', App\Http\Controllers\API\FollowUpDeptAPIController::class);
    Route::get('follow_up_dept', [App\Http\Controllers\API\FollowUpDeptAPIController::class, 'follow_up_dept']);
    Route::resource('notifications', App\Http\Controllers\API\NotificationAPIController::class);
    Route::get('get-notification/', [App\Http\Controllers\API\NotificationAPIController::class, 'getNotification']);
    Route::get('read-notification/', [App\Http\Controllers\API\NotificationAPIController::class, 'readNotification']);

    //TOUR BUSINESS TYPE ROUTE
    Route::get('tour_business_types', [App\Http\Controllers\API\TourEntryAPIController::class, 'tour_business_types']);

    //SAVE OR DELETE QUICK LINK
    Route::post('save-or-delete-quick-link', [App\Http\Controllers\API\UserAPIController::class, 'saveOrDeleteQuickLink']);
    Route::get('quick-link-list/{userId}', [App\Http\Controllers\API\UserAPIController::class, 'quickLinkList']);
    Route::resource('product_drafts', App\Http\Controllers\API\ProductDraftAPIController::class);
    Route::post('product-file-upload', [App\Http\Controllers\API\ProductDraftAPIController::class, 'fileUpload']);
    Route::get('product_sync', [App\Http\Controllers\API\ProductDraftAPIController::class, 'product_sync']);
    //COPY KRA KPI MOS ROUTE
    Route::get('copy_kra_kpi_mos', [App\Http\Controllers\API\KRAAPIController::class, 'copy_kra_kpi_mos']);
    //DEPARTMENT WISE MOS LIST
    Route::get('departmentWiseMosList', [App\Http\Controllers\API\MOSAPIController::class, 'departmentWiseMosList']);
    Route::get('userWiseMosList/{user_id?}', [App\Http\Controllers\API\MOSAPIController::class, 'userWiseMosList']);
    Route::get('departmentWiseUserList', [App\Http\Controllers\API\MOSAPIController::class, 'departmentWiseUserList']);
    Route::post('mosSettings', [App\Http\Controllers\API\MOSAPIController::class, 'mosSettings']);
    Route::resource('daily_schedule_types', App\Http\Controllers\API\DailyScheduleTypeAPIController::class);
    Route::resource('tour_entrie_objectives', App\Http\Controllers\API\TourEntrieObjectiveAPIController::class);
    Route::get('tour_entrie_month_objectives', [App\Http\Controllers\API\TourEntrieObjectiveAPIController::class, 'month_objectives']);
    Route::get('tour_territory', [App\Http\Controllers\API\TourEntrieObjectiveAPIController::class, 'tour_territory']);
    Route::post('territory_details', [App\Http\Controllers\API\TourEntrieObjectiveAPIController::class, 'territory_details']);
    Route::get('tour_point', [App\Http\Controllers\API\TourEntrieObjectiveAPIController::class, 'tour_point']);
    Route::get('point_fo', [App\Http\Controllers\API\TourEntrieObjectiveAPIController::class, 'point_fo']);
    Route::resource('user_manuals', App\Http\Controllers\API\UserManualAPIController::class);
    Route::resource('m_o_s_achievement_permissions', App\Http\Controllers\API\MOSAchievementPermissionAPIController::class);
    Route::post('m_o_s_achievement_permissions_update', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'm_o_s_achievement_permissions_update']);
    //department_mos_setting
    Route::get('department_mos_setting', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'department_mos_setting']);
    //department_mos_permission_settings_update
    Route::post('all_dept_mos_permission_update', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'all_dept_mos_permission_update']);
    ///m_o_s_permission
    Route::get('m_o_s_permission/{id}', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'm_o_s_permission']);

    Route::resource('uaer_shares', App\Http\Controllers\API\UaerShareAPIController::class);
    Route::get('share_kra_kpi/{user_id}', [App\Http\Controllers\API\UaerShareAPIController::class,'share_kra_kpi']);
    //share_kra_kpi_permission
    Route::post('share_kra_kpi_permission', [App\Http\Controllers\API\UaerShareAPIController::class,'share_kra_kpi_permission']);
    //Note: Projects
    Route::resource('projects', App\Http\Controllers\API\projectsAPIController::class);
    Route::resource('buyer_enquiry_lists', App\Http\Controllers\API\BuyerEnquiryListAPIController::class);
    Route::get('country_api_list', [App\Http\Controllers\API\BuyerEnquiryListAPIController::class , 'country_api_list']);
    Route::get('dept_user', [App\Http\Controllers\API\BuyerEnquiryListAPIController::class , 'dept_user']);
    Route::get('all_dept', [App\Http\Controllers\API\BuyerEnquiryListAPIController::class , 'all_dept']);
    Route::resource('buyer_contact_shares',App\Http\Controllers\API\BuyerContactShareAPIController::class);
    Route::get('buyer_contact_assign', [App\Http\Controllers\API\BuyerContactShareAPIController::class , 'contact_assign']);
    Route::post('loginWithToken', [App\Http\Controllers\AuthController::class, 'loginWithToken']);
    Route::resource('priority_tasks', App\Http\Controllers\API\PriorityTaskAPIController::class); 

    Route::get('priority_tasks_show_quarter/{id}', [App\Http\Controllers\API\PriorityTaskAPIController::class,'show_quarter']); 

    Route::get('priority_task_logs', [App\Http\Controllers\API\PriorityTaskAPIController::class,'priority_task_logs']); 
    
    
    Route::resource('priority_task_items', App\Http\Controllers\API\PriorityTaskItemAPIController::class);
    Route::post('priority_task_items_update',[App\Http\Controllers\API\PriorityTaskAPIController::class, 'priority_task_items_update']);

    Route::get('priority_task_not_update',[App\Http\Controllers\API\PriorityTaskAPIController::class, 'priority_task_not_update']);


    Route::resource('priority_task_comments', App\Http\Controllers\API\PriorityTaskCommentsAPIController::class);

});
Route::get('monthly_report_download/{id}', [App\Http\Controllers\API\MonthlyReportAPIController::class, 'monthly_report_download']);
Route::resource('production_product_names', App\Http\Controllers\API\Production_product_nameAPIController::class);
Route::get('sap_files/{id}', [App\Http\Controllers\API\SapFilesAPIController::class, 'sap_files_download']);
//Route::get('get-notification/', [App\Http\Controllers\API\NotificationAPIController::class,'getNotification']);
Route::get('syncdev', [App\Http\Controllers\ProductionWastageDaftController::class, 'sync']);
Route::get('product_sync_dev', [App\Http\Controllers\API\ProductDraftAPIController::class, 'product_sync']);
Route::resource('monthly_comments', App\Http\Controllers\API\Monthly_commentAPIController::class);
Route::resource('wastege_relations', App\Http\Controllers\API\Wastege_relationAPIController::class);
Route::resource('consumption_relations', App\Http\Controllers\API\Consumption_relationAPIController::class);

Route::get('kra-kpi-mos', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_hris']);
//KRA, KPI , MOS
Route::get('kra-kpi-mos-weightage', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_hris_weightage']);

//kra-kpi-mos-weightage_2022 
Route::get('kra-kpi-mos-weightage_2022', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_hris_weightage_2022']);
Route::get('kra-kpi-mos-weightage_2023', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_mos_hris_weightage_2023']);
Route::get('kra-kpi-delete', [App\Http\Controllers\API\MOSAPIController::class, 'kra_kpi_delete']);
//KRA
Route::get('kra-weightage', [App\Http\Controllers\API\MOSAPIController::class, 'kra_hris_weightage']);
// KPI
//Route::get('kpi-weightage', [App\Http\Controllers\API\MOSAPIController::class, 'kpi_hris_weightage']);
// MOS
Route::get('mos-weightage', [App\Http\Controllers\API\MOSAPIController::class, 'mos_hris_weightage']);
Route::get('dept-sync', [App\Http\Controllers\API\MOSAPIController::class, 'dept_sync']);
Route::resource('cost_summary_groups', App\Http\Controllers\API\CostSummaryGroupAPIController::class);
Route::resource('cost_relations', App\Http\Controllers\API\CostRelationAPIController::class);
Route::get('monthly_permission', [App\Http\Controllers\API\MonthlyDateRangeAPIController::class, 'monthly_permission']);
Route::resource('department_c_cmails', App\Http\Controllers\API\DepartmentCCmailAPIController::class);
Route::resource('divisions', App\Http\Controllers\API\DivisionAPIController::class);
Route::get('get_departments', [App\Http\Controllers\API\DepartmentAPIController::class, 'get_departments']);
Route::get('get_wings', [App\Http\Controllers\API\WingAPIController::class, 'get_wings']);
Route::get('get_wings_users/{wing_id}', [App\Http\Controllers\API\UserAPIController::class, 'get_wings_users']);
Route::get('get_dept_users/{dept_id}', [App\Http\Controllers\API\UserAPIController::class, 'get_dept_users']);
//modification_permission_approved
Route::get('modification_permission_acknowledge', [App\Http\Controllers\API\MOSAPIController::class, 'modification_permission_acknowledge']);
Route::get('modification_permission_approved', [App\Http\Controllers\API\MOSAPIController::class, 'modification_permission_approved']);
Route::get('download_mos_file_format_2', [App\Http\Controllers\API\KRAAPIController::class, 'download_mos_file_format']);
Route::get('download_mos_file_format_2', [App\Http\Controllers\API\KRAAPIController::class, 'download_mos_file_format']);
Route::resource('mos_data_logs', App\Http\Controllers\API\MosDataLogAPIController::class);
Route::get('get_achievement', [App\Http\Controllers\API\MOSAPIController::class, 'get_achievement']);
Route::get('get_rep_mos', [App\Http\Controllers\API\MOSAPIController::class, 'get_rep_mos']);
Route::post('submit_rep_mos', [App\Http\Controllers\API\MOSAPIController::class, 'submit_rep_mos']);
Route::post('assign_mos_submit', [App\Http\Controllers\API\MOSAPIController::class, 'assign_mos_submit']);
Route::get('mail_test', [App\Http\Controllers\API\MOSAPIController::class, 'mail_test']);
//
Route::get('achievements_panel', [App\Console\Commands\AchievementsPanel::class, 'handle']);

//dept_alert  monthlyOn(4, '00:01'); 
Route::get('dept_alert', [App\Console\Commands\DeptAlert::class, 'handle']);
//dept_alert  monthlyOn(5, '00:01'); 
//Panel will close
Route::get('dept_achievements_alert', [App\Console\Commands\DeptAchievementsAlert::class, 'handle']);


//monthlyOn(1, '00:01'); 
Route::get('monthly_report_permission', [App\Console\Commands\MonthlyReportPermission::class, 'handle']);
//daily  mail 
Route::get('daily_mail_send', [App\Console\Commands\DailyMail::class, 'handle']);

//MonthlyAchievementPermissions


Route::get('achievement_permissions', [App\Console\Commands\MonthlyAchievementPermissions::class, 'handle']);

/// send mail 
Route::get('achievements_not_update', [App\Console\Commands\AchievmentNotUpdate::class, 'handle']);



Route::get('achievement_permission_approved', [App\Http\Controllers\API\MOSAPIController::class, 'achievement_permission_approved']);
Route::get('production_report_api/{date}', [App\Http\Controllers\API\BptAPIController::class, 'production_report_api']);
// Route::post('production_report', [App\Http\Controllers\ManufacturerReportController::class, 'production_report']);
Route::resource('daily_schedule_items', App\Http\Controllers\API\DailyScheduleItemAPIController::class);
Route::get('tour_user_lists', [App\Http\Controllers\API\TourUserAPIController::class, 'tour_user_list']);
Route::resource('user_manual_files', App\Http\Controllers\API\UserManualFileAPIController::class);
Route::get('user_manual', [App\Http\Controllers\API\UserManualAPIController::class, 'userManual']);


//confirmation
Route::get('confirmation-mos-weightage', [App\Http\Controllers\API\MOSAPIController::class, 'ConfirmationMosWeightage']);

Route::get('m_o_s_achievement_permissions_sync', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'm_o_s_achievement_permissions_sync']);

Route::get('mos_permission_approved', [App\Http\Controllers\API\MOSAchievementPermissionAPIController::class, 'mos_permission_approved']);
Route::get('mos_modification_permission_acknowledge', [App\Http\Controllers\API\MOSAPIController::class, 'mos_modification_permission_acknowledge']);

//FO Data sync
Route::get('fo_performance_month_wise', [App\Http\Controllers\API\MOSAPIController::class, 'fo_performance_month_wise']);
Route::get('fo_performance_month_data_sync', [App\Console\Commands\FoAchivAndTarget::class, 'handle']);
Route::get('fo_tsm_achiv_target_check', [App\Console\Commands\FoTsmAchivAndTargetCheck::class, 'handle']);
Route::get('user_info_sync_with_hrr', [App\Http\Controllers\API\UserAPIController::class, 'user_info_sync_with_hr']);

Route::post('new_user', [App\Http\Controllers\API\UserAPIController::class,'store']);
//Route::resource('new_user', App\Http\Controllers\API\UserAPIController::class);
Route::resource('buyer_enquiry_columns', App\Http\Controllers\API\buyerEnquiryColumnAPIController::class);

Route::get('dep_transfer_sync', [App\Http\Controllers\API\UserAPIController::class, 'dep_transfer_sync']);
Route::get('user_designation_syncc', [App\Http\Controllers\API\UserAPIController::class, 'user_designation_sync']);
Route::post('year-lock', [App\Http\Controllers\API\YearLockController::class, 'edit']);

// tsm_works_with_fo for ssforce  
Route::get('tsm_works_with_fo', [App\Http\Controllers\API\TourEntryAPIController::class, 'tsm_works_with_fo']);
//route_accuracy
Route::get('route_accuracy_sync', [App\Console\Commands\TourEntryAccuracy::class, 'handle']);

//PriorityTasksQuarterly


Route::get('priority_tasks_quarter_sync', [App\Console\Commands\PriorityTasksQuarterly::class, 'handle']);