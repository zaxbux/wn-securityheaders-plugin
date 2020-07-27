<?php

namespace Zaxbux\SecurityHeaders\Classes;

//use Route;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CSPEndpointMiddleware {
	/**
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		// Check for valid content-type
		$validContentTypes = [
			'application/json',
			'application/reports+json',
			'application/csp-report'
		];
		if (!\in_array($request->header('Content-Type'), $validContentTypes)) {
			return response()->json(['error' => 'Invalid Content-Type'], 415);
		}

		// Check for valid action parameter
		$validActions = [
			\Zaxbux\SecurityHeaders\Http\Controllers\ReportsController::ACTION_REPORT,
			\Zaxbux\SecurityHeaders\Http\Controllers\ReportsController::ACTION_ENFORCE,
		];
		if (!\in_array($request->route('action'), $validActions)) {
			return response()->json(['error' => 'Invalid action'], 400);
		}

		// Check for a 'csp-report' key
		if (!$request->json()->has('csp-report')) {
			return response()->json(['error' => 'Invalid JSON'], 400);
		}

		return $next($request);
	}
}