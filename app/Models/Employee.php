<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    public const EMPLOYMENT_TYPE_LABOUR_CONTRACT = 1;
    public const EMPLOYMENT_TYPE_CIVIL_CONTRACT = 2;
    
    public function underLabourContract(): bool
    {
        return $this->employment_type === self::EMPLOYMENT_TYPE_LABOUR_CONTRACT;
    }
}
