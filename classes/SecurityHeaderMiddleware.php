<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Route;
use Config;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
		$header = '';

		$policy = Settings::get('csp', []); //var_dump($policy); die();
		foreach ($policy as $directive => $value) {
			if (\in_array($directive, Settings::CSP_FETCH_DIRECTIVES) ||
				$directive == 'base-uri' ||
				$directive == 'form-action') {
					$header .= self::formatCSPFetchDirective($directive, $value, $nonce);
			}

			if ($directive == 'plugin-types') {
				$directiveString = '';


				foreach ($value['types'] as $type) {
					$directiveString .= ' '.$type['value'];
				}

				if (strlen($directiveString) > 0) {
					$header .= sprintf('plugin-types %s; ', $directiveString);
				}
			}

			if ($directive == 'sandbox' && $value) {
				$header .= sprintf('sandbox %s; ', $value);
			}

			if ($directive == 'report-uri' && $value) {
				$header .= sprintf('report-uri %s; ', $value);
			}

			if ($directive == 'upgrade-insecure-requests' && $value) {
				$header .= 'upgrade-insecure-requests; ';
			}

			if ($directive == 'block-all-mixed-content' && $value) {
				$header .= 'block-all-mixed-content; ';
			}
		}

		if ($header) {
			$response->header('Content-Security-Policy', trim($header));
		}
	}

	protected static function formatCSPFetchDirective($directive, $sources, $nonce) {
		$sourceString = '';

		foreach ($sources as $source => $value) {
			if (!$value) {
				continue;
			}

			if ($source == '_sources') {
				$sourceString .= self::formatCSPUserSource($value);
				continue;
			}

			if ($source == 'nonce') {
				$sourceString .= sprintf(" 'nonce-%s'", $nonce);
				continue;
			}

			$sourceString .= sprintf(" '%s'", $source);
		}

		if (strlen($sourceString) > 0) {
			return sprintf('%s %s; ', $directive, $sourceString);
		}

		return '';
	}

	protected static function formatCSPUserSource($sources) {
		$directive = '';

		foreach ($sources as $source) {
			switch ($source['_group']) {
				case 'host':
				case 'scheme':
					$directive .= ' '.$source['value'];
					break;
				case 'hash':
					$directive .= sprintf(" %s", $source['value']);
					break;
			}
		}

		return $directive;
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