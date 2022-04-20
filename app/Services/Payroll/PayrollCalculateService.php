<?php

declare(strict_types = 1);

namespace App\Services\Payroll;

use JetBrains\PhpStorm\ArrayShape;

final class PayrollCalculateService
{
    /**
     * @param float $mci Monthly calculation index
     * @param float $cpcCoef
     * @param float $cmshiCoef
     * @param float $mshiCoef
     * @param float $sdCoef
     * @param float $adjustmentCoef
     * @param float $iitCoef
     */
    public function __construct(
        private float $mci,
        private float $cpcCoef,
        private float $cmshiCoef,
        private float $mshiCoef,
        private float $sdCoef,
        private float $adjustmentCoef,
        private float $iitCoef,
    ) {}
    
    /**
     * @param array $data
     * @param float $ms Minimum salary
     * @return array
     */
    #[ArrayShape([
        'salary_gross' => "float",
        'salary_net' => "float",
        'iit' => "float",
        'cpc' => "float",
        'cmshi' => "float",
        'mshi' => "float",
        'sd' => "float"
    ])]
    public function calculate(array $data, float $ms): array
    {
        $responseStructure = $this->getResponseStructure();
        $taxes = [];
    
        $responseStructure['salary_gross'] = $this->getSalaryGross($data['salary'], $data['norm'] ?? 22, $data['worked']);
        $taxes['cpc'] = $this->getCpc($responseStructure['salary_gross']);
        $taxes['cmshi'] = $this->getCmshi($responseStructure['salary_gross']);
        $taxes['mshi'] = $this->getMshi($responseStructure['salary_gross']);
        $taxes['sd'] = $this->getSd($responseStructure['salary_gross'], $taxes['cpc']);
        $adjustment = $this->getAdjustment($responseStructure['salary_gross'], $taxes['cpc'], $taxes['cmshi'], $ms);
        $taxes['iit'] = $this->getIit($responseStructure['salary_gross'], $taxes['cpc'], $taxes['cmshi'], $adjustment, $ms);
    
        $privileges = $this->getPrivileges($taxes, $data['salary'], $data['is_pensioner'], $data['disability_group']);
    
        $calculated = array_merge($responseStructure, $privileges);
        $calculated['salary_net'] = $this->getSalaryNet(...$calculated);
    
        return $calculated;
    }
    
    public function getSalaryGross(float $salary, int $norm, int $worked): float
    {
        return round($salary / $norm * $worked,2);
    }
    
    // compulsory pension contributions
    public function getCpc(float $salary_gross): float
    {
        return $salary_gross * $this->cpcCoef;
    }
    
    // contributions to MSHI
    public function getCmshi(float $salary_gross): float
    {
        return round($salary_gross * $this->cmshiCoef, 2);
    }
    
    // mandatory social health insurance
    public function getMshi(float $salary_gross): float
    {
        return round($salary_gross * $this->mshiCoef, 2);
    }
    
    // social deductions
    public function getSd(float $salary_gross, float $cpc): float
    {
        return round(($salary_gross - $cpc) * $this->sdCoef,2);
    }
    
    public function getAdjustment(float $salary_gross, float $cpc, float $cmshi, float $ms): float
    {
        if ($salary_gross > ($this->mci * 25)) {
            return 0.0;
        }
        
        return ($salary_gross - $cpc - $ms - $cmshi) * $this->adjustmentCoef;
    }
    
    // individual income tax
    public function getIit(float $salary_gross, float $cpc, float $cmshi, float $adjustment, float $ms): float
    {
        return round(($salary_gross - $cpc - $ms - $cmshi - $adjustment) * $this->iitCoef,2);
    }
    
    public function getSalaryNet(float $salary_gross, float $iit, float $cpc, float $cmshi, float $mshi, float $sd): float
    {
        return round($salary_gross - $iit - $cpc - $cmshi - $mshi - $sd,2);
    }
    
    #[ArrayShape([
        'salary_gross' => "float",
        'iit' => "float",
        'cpc' => "float",
        'cmshi' => "float",
        'mshi' => "float",
        'sd' => "float"
    ])]
    private function getResponseStructure(): array
    {
        return [
            'salary_gross' => 0.0,
            'iit' => 0.0,
            'cpc' => 0.0,
            'cmshi' => 0.0,
            'mshi' => 0.0,
            'sd' => 0.0,
        ];
    }
    
    private function pensionerTaxes($hasDisability): array
    {
        if ($hasDisability) {
            return [];
        }
        
        return ['iit'];
    }
    
    private function disabledTaxes(float $salary, int $disability_group): array
    {
        if ($salary > ($this->mci * 882)) {
            return ['iit', 'cpc', 'sd'];
        }
        
        if ($disability_group === 3) {
            return ['cpc', 'sd'];
        }
        
        return ['sd'];
    }
    
    private function getPrivileges(array $taxes, float $salary, bool $is_pensioner, ?int $disability_group): array
    {
        if (!$is_pensioner && !$disability_group) {
            return  $taxes;
        }
        
        if ($is_pensioner) {
            $specialConditions = $this->pensionerTaxes($disability_group);
        } elseif ($disability_group) {
            $specialConditions = $this->disabledTaxes($salary, $disability_group);
        }
    
        return array_filter(
            $taxes,
            function ($key) use ($specialConditions) {
                return in_array($key, $specialConditions);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}