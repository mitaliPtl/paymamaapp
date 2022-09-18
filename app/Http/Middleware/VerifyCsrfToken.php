<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/payment/status*',
        '/online_payment_status*',
        '/get_operator_mobile_info',
        '/get_operator_rech_pln',
        '/get_dth_plan_info',
        '/get_121_offers_info'
    ];
}
