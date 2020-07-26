<?php

Route::post('/_/reports/csp-endpoint/{action}', [
	'as'   => 'zaxbux.securityheaders.reports.csp_endpoint',
	'uses' => 'Zaxbux\SecurityHeaders\Http\Controllers\ReportsController@cspEndpoint'
])->middleware('web');