<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollCalculateRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\Payroll\PayrollCalculateService;
use JetBrains\PhpStorm\ArrayShape;

class PayrollController extends Controller
{
    #[ArrayShape([
        'salary_gross' => "float",
        'salary_net' => "float",
        'iit' => "float",
        'cpc' => "float",
        'cmshi' => "float",
        'mshi' => "float",
        'sd' => "float"
    ])]
    public function calculate(
        PayrollCalculateRequest $request,
        PayrollCalculateService $calculateService
    ): array {
        $data = $request->validated();
        $employee = Employee::query()->findOrFail($data['employee_id']);
        
        if (!$employee->underLabourContract()) {
            abort(400, 'Employee has not a labour contract.');
        }
        
        return $calculateService->calculate(
            $data,
            $data['tax_deduction'] ? config('common.taxes.ms') : 0.0
        );
    }
    
    public function create(
        PayrollCalculateRequest $request,
        PayrollCalculateService $calculateService
    ): PayrollResource {
        $data = $request->validated();
        $employee = Employee::query()->findOrFail($data['employee_id']);
        
        if (!$employee->underLabourContract()) {
            abort(400, 'Employee has not a labour contract.');
        }
    
        $calculated = $calculateService->calculate(
            $data,
            $data['tax_deduction'] ? config('common.taxes.ms') : 0.0
        );
        $calculated['employee_id'] = $employee->id;
        $calculated['year'] = $data['year'];
        $calculated['month'] = $data['month'];
    
        try {
            $payroll = Payroll::query()->create($calculated);
        } catch (\Throwable $exception) {
            throw new \DomainException($exception->getMessage());
        }
        
        return PayrollResource::make($payroll);
    }
}
