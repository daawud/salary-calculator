<?php

return [
    'taxes' => [
        'mci' => env('MCI', 1731.0),
        'ms' => env('MS', 42500.0),
        'cpc_coef' => env('CPC_COEF', 0.1),
        'cmshi_coef' => env('CMSHI_COEF', 0.02),
        'mshi_coef' => env('MSHI_COEF', 0.02),
        'sd_coef' => env('SD_COEF', 0.035),
        'adjustment_coef' => env('ADJUSTMENT_COEF', 0.9),
        'iit_coef' => env('IIT_COEF', 0.1),
    ]
];