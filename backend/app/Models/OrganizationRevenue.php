<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationRevenue extends Model
{
    use HasFactory;

    protected $table = 'organization_revenue';

    protected $fillable = ['revenue', 'revenue_year']; // Added revenue_year to fillable attributes
}
