<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'salary_gross',
        'iit',
        'cpc',
        'cmshi',
        'mshi',
        'sd',
        'salary_net'
    ];
}
