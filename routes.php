<?php

Route::post(\Zaxbux\SecurityHeaders\Plugin::CSP_REPORT_URI, [
	'as'   => 'zaxbux.securityheaders.reports.csp_endpoint',
	'uses' => 'Zaxbux\SecurityHeaders\Http\Controllers\ReportsController@cspEndpoint'
])->middleware([\Zaxbux\SecurityHeaders\Classes\CSPEndpointMiddleware::class]);
