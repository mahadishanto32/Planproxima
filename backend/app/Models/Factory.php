<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
	protected $table 			= "factory";

	public function user()
	{
		return $this->belongsTo('App\User', 'fac_owner');
	}
}
