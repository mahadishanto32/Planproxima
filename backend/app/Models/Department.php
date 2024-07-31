<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\MosData;
use App\Models\MOS;
use App\Models\DepartmentTemplates;
use App\Models\MonthlyReport;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB ;
/**
 * Class Department
 * @package App\Models
 * @version April 19, 2021, 11:51 am UTC
 *
 * @property string $name
 * @property integer $status
 */
class Department extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'departments';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'iskra',
        'hod_name',
        'hod_email',
        'status',
        'is_factory',
        'mail_allow',
        'report_permission',
        'template_setting',
        'is_tour'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'integer',
        'is_factory' => 'integer',
        'is_tour' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'status' => 'required'
    ];
    public function ccmail(){
        return $this->hasMany(DepartmentCCmail::class,'dept_id','id')
        ->join('users','users.id','department_c_cmails.user_id');
        // ->select('name','users.ad_mail');
    }     
    public function setting(){
        return $this->belongsTo(DepartmentSetting::class, 'id' ,'dept_id');
    } 
    public function monthly_date_ranges(){
        return $this->belongsTo(MonthlyDateRange::class, 'id' ,'dept_id');
    } 
    public function templates(){
        return $this->belongsTo(DepartmentTemplates::class, 'id' ,'dept_id')
        ->select('type');
    }     
    public function target(){
       // $current_month =  strtolower(date("F")) ;
        $target_biulder = MOS::select(DB::Raw('SUM(january) as january, SUM(february) as february
        , SUM(march) as march, SUM(april) as april, SUM(may) as may, SUM(june) as june, SUM(july) as july
        , SUM(august) as august, SUM(september) as september, SUM(october) as october, SUM(november) as november
        , SUM(december) as december'))
        ->join('mos_datas', 'mos_datas.mos_id', 'm_o_s.id'); 
        $target_biulder->where('m_o_s.dept_id', $this->id); 
        $target = $target_biulder->where('mos_datas.type', 'target')->first();  
        return   $target ;
       // return  $target->$current_month ? $target->$current_month : 0 ;

    }
    public function achievement(){
        //$current_month =  strtolower(date("F")) ;
        $target_biulder = MOS::select(DB::Raw('SUM(january) as january, SUM(february) as february
        , SUM(march) as march, SUM(april) as april, SUM(may) as may, SUM(june) as june, SUM(july) as july
        , SUM(august) as august, SUM(september) as september, SUM(october) as october, SUM(november) as november
        , SUM(december) as december'))
        ->join('mos_datas', 'mos_datas.mos_id', 'm_o_s.id'); 
        $target_biulder->where('m_o_s.dept_id', $this->id); 
        $target = $target_biulder->where('mos_datas.type', 'achievement')->first();
        return $target ;   
       // return   $target->$current_month ? $target->$current_month : 0  ;
    }

    public function monthly_report($dept_id,$year, $month){
        $countStatus = MonthlyReport::where('dept_id',$dept_id)
        ->where('month',$month)
        ->where('year',$year)
        ->count();

        return $countStatus>0 ? 1 :0;
    }

    public function permissionCheak(){
        $items  = MOSAchievementPermission::where('dept_id', $this->id)
        ->where('role_id' , 5 )
        ->orderBy('end_date','desc')
        ->where('year','2022')
        ->get()->toArray();
        
       
        $totla_mos  =  count($items);

        // $dateData = MOSAchievementPermission::where('dept_id', $this->id)
        //     ->where('role_id' , 5 )
        //     ->orderBy('end_date','desc')
        //     ->first();

        return array( 
            'start_date' => $totla_mos > 0 ?  $items[0]['start_date'] : null,
            'end_date' => $totla_mos > 0 ?  $items[0]['end_date'] : null,
            'only_dept' => false ,
            'all_user' => false ,
            'totla_mos' => $totla_mos , 
            'jan_on' => $this->checkTrue($items,'jan'),
            'jan' => $this->checkTrue($items,'jan') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'feb_on' => $this->checkTrue($items,'feb'),
            'feb' => $this->checkTrue($items,'feb') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'mar_on' => $this->checkTrue($items,'mar'),
            'mar' => $this->checkTrue($items,'mar') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'apr_on' => $this->checkTrue($items,'apr'),
            'apr' => $this->checkTrue($items,'apr') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'may_on' => $this->checkTrue($items,'may'),
            'may' => $this->checkTrue($items,'may') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'jun_on' => $this->checkTrue($items,'jun'),
            'jun' => $this->checkTrue($items,'jun') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'jul_on' => $this->checkTrue($items,'jul'),
            'jul' => $this->checkTrue($items,'jul') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'aug_on' => $this->checkTrue($items,'aug'),
            'aug' => $this->checkTrue($items,'aug') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'sep_on' => $this->checkTrue($items,'sep'),
            'sep' => $this->checkTrue($items,'sep') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'oct_on' => $this->checkTrue($items,'oct'),
            'oct' => $this->checkTrue($items,'oct') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'nov_on' => $this->checkTrue($items,'nov'),
            'nov' => $this->checkTrue($items,'nov') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            'dec_on' => $this->checkTrue($items,'dec'),
            'dec' => $this->checkTrue($items,'dec') == $totla_mos &&  $totla_mos > 1 ? true : false  ,
            
            'data' => $items 
        );
    } 
    public function checkTrue($items,$month){
        $on = 0 ;
        foreach ($items as $key => $item) {
            if($item[$month]) {
                $on = $on + 1 ; 
            }
            # code...
        }
        return  $on  ;

    }
    
}
