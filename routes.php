<?php

Route::post(\Zaxbux\SecurityHeaders\Plugin::REPORT_URI, [
	'as'   => 'zaxbux.securityheaders.reports.csp_endpoint',
	'uses' => 'Zaxbux\SecurityHeaders\Http\Controllers\ReportsController@cspEndpoint'
])->middleware([\Zaxbux\SecurityHeaders\Classes\CSPEndpointMiddleware::class]);