<?php

namespace App\Models;

use App\Models\KRA;
use App\Models\KPI;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

/**
 * Class MOS
 * @package App\Models
 * @version April 19, 2021, 5:57 pm UTC
 *
 * @property integer $dept_id
 * @property integer $kra_id
 * @property integer $kpi_id
 * @property string $mos_name
 */
class MOS extends Model
{
  use SoftDeletes;

  use HasFactory;

  public $table = 'm_o_s';


  protected $dates = ['deleted_at'];

  public $fillable = [
    'dept_id',
    'kra_id',
    'kpi_id',
    'rep_id',
    'weightage',
    'modification_type',
    'modification_status',
    'start_date',
    'end_date',
    'modification_months',
    'year',
    'piscal_year',
    'isvalorper',
    'mos_calculation',
    'report_type',
    'previous_id',
    'mos_name'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'dept_id' => 'integer',
    'kra_id' => 'integer',
    'kpi_id' => 'integer',
    'mos_name' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
    'kpi_id' => 'required',
    'mos_name' => 'required'
  ];

  public function mostargetjoin($request = NULL)
  {
    $query =  MosData::where('mos_id', $this->id);
    // if ($request->year) {
    //   $query->where('year', $request->year);
    // }
    return $result  =  $query->where('type', 'target')->first();

    //return $this->belongsTo(MosData::class, 'id' , 'mos_id')->where('type','target');
  }
  public function mosmodulejoin($request = NULL)
  {
    $query =  MosData::where('mos_id', $this->id);
    // if ($request->year) {
    //   $query->where('year', $request->year);
    // }
    return $result  =  $query->where('type', 'module')->first();
    //return $this->belongsTo(MosData::class, 'id' , 'mos_id')->where('type','module');
  }

  public function mosachievementjoin($request = NULL)
  {
    $query =  MosData::where('mos_id', $this->id);
    // if ($request->year) {
    //   $query->where('year', $request->year);
    // }
    return $result  =  $query->where('type', 'achievement')->first();
    // if($request->year){
    //     $query->where('year', $request->year);
    // }
    //return $query->where('type','achievement');
  }

  public function feadback($request = NULL)
  {

    $query = MosFeadback::where('mos_id', $this->id);
   // $query->whereYear('date', $this->year);
    $result = $query->get();
    return $result;
  }

  public function getCountKraAttribute()
  {
    return MOS::where('kra_id', $this->kra_id)->where('dept_id', $this->dept_id)->count();
  }
  public function getCountKpiAttribute()
  {
    return MOS::where('kpi_id', $this->kpi_id)->where('dept_id', $this->dept_id)->count();
  }

  public function getCountKraunAttribute()
  {
    return MOS::where('kra_id', $this->kra_id)->where('dept_id', $this->dept_id)->count();
    // return MOS::where('kra_id',$this->kra_id)
    // ->where('dept_id',$this->dept_id)
    // ->whereNotIn('m_o_s.id',KPI::select('k_p_i_s.rep_id')
    // ->where('k_p_i_s.rep_id','!=',0)
    // ->where('k_p_i_s.dept_id',  $this->dept_id  )
    // ->get())    
    // ->count();
  }
  public function getCountKpiunAttribute()
  {
    return MOS::where('kpi_id', $this->kpi_id)->where('dept_id', $this->dept_id)->count();
    // return MOS::where('kpi_id',$this->kpi_id)
    // ->whereNotIn('m_o_s.id',KPI::select('k_p_i_s.rep_id')
    // ->where('k_p_i_s.rep_id','!=',0)
    // ->where('k_p_i_s.dept_id',  $this->dept_id  )
    // ->get())             
    // ->where('dept_id',$this->dept_id)
    // ->count();
  }

  public function krajoin()
  {
    return $this->belongsTo(KRA::class, 'kra_id');
  }
  public function kpijoin()
  {
    return $this->belongsTo(KPI::class, 'kpi_id');
  }

  public function mostarget_join()
  {
    return $this->belongsTo(MosData::class, 'id', 'mos_id')->where('type', 'target');
  }

  public function mosachivement_join()
  {
    return $this->belongsTo(MosData::class, 'id', 'mos_id')->where('type', 'achievement');
  }

  public function total_target($type , $request = [])
  {
    $kra_data =  KRA::find($this->kra_id);
    //$achievement =  MosData::where('type','achievement')->where('mos_id',$this->id)->first();
    $target =  MosData::where('type', 'target')->where('mos_id', $this->id)->first();
    if(isset($request->start_date) && isset($request->end_date)){
      $start_month = strtotime($request->start_date);
      $end_month = strtotime($request->end_date);
      
      $current_month = $start_month;
      $months = [];
      while ($current_month <= $end_month) {
          $month_name = strtolower(date('F', $current_month)); 
          array_push($months,($month_name));
          $current_month = strtotime('+1 month', $current_month);
      }
    }else{
      $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    }    
    
    $target_value = 0;
    if ($kra_data->role_id == 5) {
      foreach ($months as $key => $value) {
        $target_value  += $target[$value];
      }
    } else {
      foreach ($months as $key => $value) {
        $target_value  += $target[$value];
      }
      //$target_value = $target->total ;
    }
    return round($target_value, 2);
  }
  public function total_achievement($type , $request = [])
  {
    $kra_data =  KRA::find($this->kra_id);
    $achievement =  MosData::where('type', 'achievement')->where('mos_id', $this->id)->first();
    if(empty($achievement)){
      return 0;
    }
    if(isset($request->start_date) && isset($request->end_date)){
      $start_month = strtotime($request->start_date);
      $end_month = strtotime($request->end_date);
      
      $current_month = $start_month;
      $months = [];
      while ($current_month <= $end_month) {
          $month_name = strtolower(date('F', $current_month)); // Get the month name in lowercase
          array_push($months,($month_name));
          $current_month = strtotime('+1 month', $current_month);
      }
    }else{
      $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    }

    $achievement_value = 0;
    if ($kra_data->role_id == 5) {
      foreach ($months as $key => $value) {
        $achievement_value  += $achievement[$value];
      }
    } else {
      foreach ($months as $key => $value) {
        $achievement_value  += $achievement[$value];
      }
      // $achievement_value = $achievement->total ; 
    }
    return  number_format($achievement_value, 2);
  }

  public function total_achievementNew($type, $half , $request = [])
  {
    $kra_data =  KRA::find($this->kra_id);
    $achievement =  MosData::where('type', 'achievement')->where('mos_id', $this->id)->first();

    if(isset($request->start_date) && isset($request->end_date)){
      $start_month = strtotime($request->start_date);
      $end_month = strtotime($request->end_date);
      
      $current_month = $start_month;
      $months = [];
      while ($current_month <= $end_month) {
          $month_name = strtolower(date('F', $current_month));
          array_push($months,($month_name));
          $current_month = strtotime('+1 month', $current_month);
      }      
    }else{
      if ($half == 'first') {
        if($kra_data->dept_id==1 || $kra_data->dept_id==40 || $kra_data->dept_id==41 || $kra_data->dept_id==42){
          $months = array('january', 'february', 'march', 'april', 'may', 'june');
        }else{
          $months = array('january', 'february', 'march', 'april', 'may');
        }
      } else {
        if($kra_data->dept_id==1 || $kra_data->dept_id==40 ||$kra_data->dept_id==41 || $kra_data->dept_id==42){
          $months =  $arrayName = array('july', 'august', 'september', 'october', 'november', 'december');
        }else{
          $months =  $arrayName = array( 'june', 'july', 'august', 'september', 'october', 'november', 'december');
        }
      }
    }

    $achievement_value = 0;
    if ($kra_data->role_id == 5) {
      foreach ($months as $key => $value) {
        $achievement_value  += $achievement[$value];
      }
    } else {
      foreach ($months as $key => $value) {
        $achievement_value  += $achievement[$value];
      }
      // $achievement_value = $achievement->total ; 
    }
    return round($achievement_value, 2);
  }

  public function total_targetNew($type, $half , $request = [])
  {
    $kra_data =  KRA::find($this->kra_id);
    //$achievement =  MosData::where('type','achievement')->where('mos_id',$this->id)->first();
    $target =  MosData::where('type', 'target')->where('mos_id', $this->id)->first();
    if(isset($request->start_date) && isset($request->end_date)){
      $start_month = strtotime($request->start_date);
      $end_month = strtotime($request->end_date);
      
      $current_month = $start_month;
      $months = [];
      while ($current_month <= $end_month) {
          $month_name = strtolower(date('F', $current_month)); // Get the month name in lowercase
          array_push($months,($month_name));
          $current_month = strtotime('+1 month', $current_month);
      }
    }else{
      if ($half == 'first') {
        if($kra_data->dept_id==1 || $kra_data->dept_id==40 || $kra_data->dept_id==41 || $kra_data->dept_id==42 ){
          $months =  $arrayName = array('january', 'february', 'march', 'april', 'may' , 'june');
        }else{
          $months =  $arrayName = array('january', 'february', 'march', 'april', 'may');
        }
      } else {
        if($kra_data->dept_id==1 ||  $kra_data->dept_id==40  || $kra_data->dept_id==41 || $kra_data->dept_id==42){
          $months =  $arrayName = array('july', 'august', 'september', 'october', 'november', 'december');
        }else{
          $months =  $arrayName = array('jun','july', 'august', 'september', 'october', 'november', 'december');
        }
      }
    }


    $target_value = 0;
    if ($kra_data->role_id == 5) {
      foreach ($months as $key => $value) {
        $target_value  += $target[$value];
      }
    } else {
      foreach ($months as $key => $value) {
        $target_value  += $target[$value];
      }
      //$target_value = $target->total ;
    }
    return round($target_value, 2);
  }
  public function weightageCalculation($weightage, $firstHalfTarget, $totalTarget)
  {
    if (!is_numeric($weightage) || !is_numeric($firstHalfTarget) || !is_numeric($totalTarget)) {
      return 0;
    }
    if ($totalTarget == 0) {
      return 0;
    }
    $weightageCal = ($weightage * $firstHalfTarget) / $totalTarget;
    return round($weightageCal, 2);
  }

  public function total_score($type , $request = [])
  {
    $kra_data =  KRA::find($this->kra_id);
    $mosData = MosData::where('type', 'achievement')->where('mos_id', $this->id)->first();
    if(empty($mosData)){
      return $this->id;
    }
    $mosDataTarget =  MosData::where('type', 'target')->where('mos_id', $this->id)->first();
    
    if(isset($request->start_date) && isset($request->end_date)){
      $start_month = strtotime($request->start_date);
      $end_month = strtotime($request->end_date);
      
      $current_month = $start_month;
      $months = [];
      while ($current_month <= $end_month) {
          $month_name = strtolower(date('F', $current_month)); // Get the month name in lowercase

          array_push($months,($month_name));
          $current_month = strtotime('+1 month', $current_month);
      }
    }else{
      $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    }    
    
    $achievement =  0;
    $target =  0;
    if ($kra_data->role_id == 5) {
      foreach ($months as $key => $value) {
        $achievement  += $mosData[$value];
        $target  += $mosDataTarget[$value];
      }
    } else {
      foreach ($months as $key => $value) {
        $achievement  += $mosData[$value];
        $target  += $mosDataTarget[$value];
      }
      // $achievement = $mosData->total ;
      // $target =  $mosDataTarget->total ;
    }
    //$achievement_value = 0 ;
    //$assigning_months = 0 ;  

    if (($target > 0 && $achievement > 0) && $this->weightage > 0) {

      if ($this->mos_calculation == 0) {
        $achievement_value = (($achievement / $target) * $this->weightage);
      } else if ($this->mos_calculation == 1) {

        $achievement_value = (($target / $achievement) * $this->weightage);
      } else if ($this->mos_calculation == 2) {

        $achievement_value = (($achievement / $target) * $this->weightage);
      } else if ($this->mos_calculation == 3) {

        $achievement_value = (($target / $achievement) * $this->weightage);
      } else {
        $achievement_value = (($achievement / $target) * $this->weightage);
      }
    } else {
      $achievement_value = 0;
    }
    if ($achievement_value > $this->weightage) {
      $achievement_value = $this->weightage;
    }
    return  number_format($achievement_value, 2);
  }

  public function total_scoreNew($target, $achievement, $weightage)
  {
    if (($target > 0 && $achievement > 0) && $weightage > 0) {
      if ($this->mos_calculation == 0) {
        $achievement_value = (($achievement / $target) * $weightage);
      } else if ($this->mos_calculation == 1) {
        $achievement_value = (($target / $achievement) * $weightage);
      } else if ($this->mos_calculation == 2) {
        $achievement_value = (($achievement / $target) * $weightage);
      } else if ($this->mos_calculation == 3) {
        $achievement_value = (($target / $achievement) * $weightage);
      } else {
        $achievement_value = (($achievement / $target) * $weightage);
      }
    } else {
      $achievement_value = 0;
    }
    if ($achievement_value > $weightage) {
      $achievement_value = $weightage;
    }
    return round($achievement_value, 2);
  }

  public function achievement($type, $weightage, $target, $achievement, $month, $mos_calculation)
  {
    $target = $target[$month];
    $achievement = $achievement[$month];
    if ($target > 0 && $achievement > 0) {
      if ($mos_calculation == 0) {
        return (($achievement / $target) * ($type == 'achievement' ? 100 : $weightage));
      } else if ($mos_calculation == 1) {

        return (($target / $achievement) *  ($type == 'achievement' ? 100 : $weightage));
      } else if ($mos_calculation == 2) {

        return (($achievement / $target) *  ($type == 'achievement' ? 100 : $weightage));
      } else if ($mos_calculation == 3) {

        return (($target / $achievement) *  ($type == 'achievement' ? 100 : $weightage));
      } else {
        return (($achievement / $target) *  ($type == 'achievement' ? 100 : $weightage));
      }
    } else {
      return 0;
    }
  }

  public function mos_kpi()
  {
    return $this->hasMany(KPI::class, 'rep_id', 'id');
  }

  public function working_member()
  {
    //working_member
    return  KPI::select('users.name', 'k_p_i_s.rep_id', DB::raw('SUM(rep_per) AS rep_per'))
      ->join('k_r_a_s', 'k_r_a_s.id', 'k_p_i_s.kra_id')
      ->join('users', 'users.id', 'k_r_a_s.user_id')
      ->join('m_o_s', 'm_o_s.kpi_id', 'k_p_i_s.id')
      ->where('k_p_i_s.rep_id', $this->id)
      ->groupBy('k_p_i_s.rep_id')
      ->groupBy('users.name')
      ->orderBy('users.employee_id', 'ASC')
      ->get();
  }
  public function mos_user()
  {
    //working_member
    return  KRA::select('users.name')
      ->join('users', 'users.id', 'k_r_a_s.user_id')
      ->join('m_o_s', 'm_o_s.kra_id', 'k_r_a_s.id')
      ->where('m_o_s.id', $this->id)
      ->first();
  }


  public function mos_working_member()
  {
    return MOS::select('users.name', 'm_o_s.rep_id', DB::raw('SUM(rep_per) AS rep_per'))
      ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
      ->join('users', 'users.id', 'k_r_a_s.user_id')
      ->where('m_o_s.rep_id', $this->id)
      ->groupBy('m_o_s.rep_id')
      ->groupBy('users.name')
      ->orderBy('users.employee_id', 'ASC')
      ->get();
  }

  public function working_memberJoin()
  {
    return $this->hasMany(KPI::class, 'rep_id', 'id')
      ->with('mos')
      ->with('krajoin.userJoin');
  }

  public function mos_working_memberJoin()
  {
    return $this->hasMany(MOS::class, 'rep_id', 'id')->with('krajoin.userJoin');
  }
}
