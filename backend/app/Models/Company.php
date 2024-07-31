<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Company extends Model
{
    protected $table = "companies";
    protected $fillable = array('id', 
    'company_code', 
    'company_name'); 
}
 
 