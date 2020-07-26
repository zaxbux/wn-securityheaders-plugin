<?php

namespace Zaxbux\SecurityHeaders\Http\Controllers;

use Request;
use Response;
use Illuminate\Routing\Controller;
use Zaxbux\SecurityHeaders\Classes\Log;

class ReportsController extends Controller {

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function cspEndpoint($action) {
		// Request must be JSON and have a 'csp-report' key and action must be valid
		if (!\in_array(Request::header('Content-Type'), ['application/reports+json', 'application/csp-report']) || !\in_array($action, ['report_only', 'enforce'])) {
			return Response::make(null, 415); // Unsupported media type
		}

		if (Request::header('Content-Type') == 'application/reports+json') {
			$report = Request::input();
		} else {
			$report = @\json_decode(Request::getContent(), true);

			if (json_last_error() != JSON_ERROR_NONE || !isset($report['csp-report'])) {
				return Response::make(null, 415); // Unsupported media type
			}
		}

		Log::CSPReport($action, $report['csp-report'], Request::getContent(), Request::header('User-Agent'));

		return Response::make(null, 204); // No content
	}

}