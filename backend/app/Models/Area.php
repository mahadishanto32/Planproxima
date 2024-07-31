<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
	protected $table 			= "areas";

	public function factory()
	{
		return $this->belongsTo('App\models\Factory', 'fact_id');
	}
}
