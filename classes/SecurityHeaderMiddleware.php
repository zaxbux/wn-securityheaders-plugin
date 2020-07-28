<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Route;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class SecurityHeaderMiddleware {
	/**
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		$response = $next($request);

		// Only handle default responses (no redirects)
		if (!$this->isRelevant($request, $response)) {
			return $response;
		}
		
		$controller = Route::current()->controller;

		if ($controller instanceof \System\Classes\SystemController) {
			$this->setSystemControllerHeaders($response);
		}

		if ($controller instanceof \Backend\Classes\BackendController) {
			$this->setBackendControllerHeaders($response);
		}

		if ($controller instanceof \Cms\Classes\CmsController) {
			$nonce = $request->get('csp-nonce');

			$this->setCmsControllerHeaders($response, $nonce);
		}

		return $response;
	}

	/**
	 * Set headers for combined assets
	 * 
	 * @param \Illuminate\Http\Response $response
	 */
	private function setSystemControllerHeaders(Response $response) {
		HeaderBuilder::addStrictTransportSecurity($response);
		HeaderBuilder::addFrameOptions($response);
		HeaderBuilder::addContentTypeOptions($response);
		HeaderBuilder::addXSSProtection($response);
	}

	/**
	 * Set headers for combined assets
	 * 
	 * @param \Illuminate\Http\Response $response
	 */
	private function setBackendControllerHeaders(Response $response) {
		$this->setSystemControllerHeaders($response);
	}

	/**
	 * Set headers for combined assets
	 * 
	 * @param \Illuminate\Http\Response $response
	 * @param string $nonce
	 */
	private function setCmsControllerHeaders(Response $response, $nonce) {
		HeaderBuilder::addContentSecurityPolicy($response, $nonce);
		HeaderBuilder::addStrictTransportSecurity($response);
		HeaderBuilder::addReferrerPolicy($response);
		HeaderBuilder::addFrameOptions($response);
		HeaderBuilder::addContentTypeOptions($response);
		HeaderBuilder::addXSSProtection($response);
		HeaderBuilder::addReportTo($response);
	}

	/**
	 * Checks whether the response should be processed
	 * by this middleware.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Http\Response $response
	 *
	 * @return bool
	 */
	protected function isRelevant($request, $response) {
		// Only default responses, no redirects
		if (!$response instanceof Response) {
			return false;
		}

		return true;
	}
}