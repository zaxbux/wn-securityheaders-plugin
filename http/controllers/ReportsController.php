<?php

namespace Zaxbux\SecurityHeaders\Http\Controllers;

use Request;
use Response;
use Illuminate\Routing\Controller;
use Zaxbux\SecurityHeaders\Classes\Log;

class ReportsController extends Controller {

	const ACTION_REPORT  = 'report_only';
	const ACTION_ENFORCE = 'enforce';

	/**
	 * Process the CSP report
	 *
	 * @return Response
	 */
	public function cspEndpoint($action) {
		Log::CSPReport($action, Request::json()->all(), Request::getContent(), Request::header('User-Agent'));

		return Response::make(null, 204); // No content
	}


}