<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Route;
use Config;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Zaxbux\SecurityHeaders\Classes\CSPHeaderBuilder;
use Zaxbux\SecurityHeaders\Models\Settings;

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
		self::setHeader_StrictTransportSecurity($response);
		self::setHeader_FrameOptions($response);
		self::setHeader_ContentTypeOptions($response);
		self::setHeader_XSSProtection($response);
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
		self::setHeader_ContentSecurityPolicy($response, $nonce);
		self::setHeader_StrictTransportSecurity($response);
		self::setHeader_ReferrerPolicy($response);
		self::setHeader_FrameOptions($response);
		self::setHeader_ContentTypeOptions($response);
		self::setHeader_XSSProtection($response);
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

	protected static function setHeader_ContentSecurityPolicy(Response $response, $nonce) {
		$header = CSPHeaderBuilder::getHeader($nonce);

		if ($header) {
			$response->header($header->name, $header->value);
		}
	}
	
	protected static function setHeader_StrictTransportSecurity(Response $response) {
		if (!Settings::get('hsts_enable')) {
			return;
		}

		$header = sprintf('max-age=%d', Settings::get('hsts_max_age'));

		if (Settings::get('hsts_subdomains')) {
			$header .= '; includeSubDomains';
		}

		if (Settings::get('hsts_preload')) {
			$header .= '; preload';
		}

		$response->header('Strict-Transport-Security', $header);
	}

	protected static function setHeader_ReferrerPolicy(Response $response) {
		$header = Settings::get('referrer_policy');

		if ($header) {
			$response->header('Referrer-Policy', $header);
		}
	}

	protected static function setHeader_FrameOptions(Response $response) {
		$header = Settings::get('frame_options');

		if ($header) {
			$response->header('X-Frame-Options', 'nosniff');
		}
	}
	
	protected static function setHeader_ContentTypeOptions(Response $response) {
		$header = Settings::get('content_type_options');

		if ($header) {
			$response->header('X-Content-Type-Options', $header);
		}
	}
	
	protected static function setHeader_XSSProtection(Response $response) {
		$header = Settings::get('xss_protection');

		if ($header) {
			switch ($header) {
				case 'disable':
					$header = '0';
				case 'enable':
					$header = '1';
				case 'block':
					$header = '1; mode=block';
				default:
					return;
			}

			$response->header('X-XSS-Protection', $header);
		}
	}
}