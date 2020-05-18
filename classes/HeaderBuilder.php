<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Cache;
use Illuminate\Http\Response;
use Zaxbux\SecurityHeaders\Models\Settings;
use Zaxbux\SecurityHeaders\Classes\HttpHeader;

class HeaderBuilder {

	const CACHE_KEY_CONTENT_SECURITY_POLICY   = "zaxbux_securityheaders_csp";
	const CACHE_KEY_STRICT_TRANSPORT_SECURITY = "zaxbux_securityheaders_hsts";
	const CACHE_KEY_REFERRER_POLICY           = "zaxbux_securityheaders_ref_policy";
	const CACHE_KEY_FRAME_OPTIONS             = "zaxbux_securityheaders_frame_options";
	const CACHE_KEY_CONTENT_TYPE_OPTIONS      = "zaxbux_securityheaders_content_type";
	const CACHE_KEY_XSS_PROTECTION            = "zaxbux_securityheaders_xss";


	/**
	 * Add the Content-Security-Policy or Content-Security-Policy-Report-Only header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addContentSecurityPolicy(Response $response, $nonce) {
		$header = Cache::rememberForever(self::CACHE_KEY_CONTENT_SECURITY_POLICY, function() {
			$policy = Settings::get('csp');

			if (!$policy['enabled']) {
				return false;
			}

			return self::buildContentSecurityPolicyHeader($policy);
		});

		if ($header) {
			$response->header($header->getName(), \sprintf($header->getValue(), $nonce));
		}
	}

	/**
	 * Add the Strict-Transport-Security header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addStrictTransportSecurity(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_STRICT_TRANSPORT_SECURITY, function() {
			if (!Settings::get('hsts_enable')) {
				return false;
			}

			$value = sprintf('max-age=%d', Settings::get('hsts_max_age'));

			if (Settings::get('hsts_subdomains')) {
				$value .= '; includeSubDomains';
			}
	
			if (Settings::get('hsts_preload')) {
				$value .= '; preload';
			}

			return new HttpHeader('Strict-Transport-Security', $value);
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	/**
	 * Add the Referrer-Policy header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addReferrerPolicy(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_REFERRER_POLICY, function() {
			if ($value = Settings::get('referrer_policy')) {
				return new HttpHeader('Referrer-Policy', $value);
			}

			return false;
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	/**
	 * Add the Frame-options header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addFrameOptions(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_FRAME_OPTIONS, function() {
			if (Settings::get('frame_options')) {
				return new HttpHeader('X-Frame-Options', 'nosniff');
			}

			return false;
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	/**
	 * Add the X-Content-Type-Options header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addContentTypeOptions(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_CONTENT_TYPE_OPTIONS, function() {
			if ($value = Settings::get('content_type_options')) {
				return new HttpHeader('X-Content-Type-Options', $value);
			}

			return false;
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	/**
	 * Add the X-XSS-Protection header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addXSSProtection(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_XSS_PROTECTION, function() {
			$value = Settings::get('xss_protection');

			switch ($value) {
				case 'disable':
					$value = '0';
					break;
				case 'enable':
					$value = '1';
					break;
				case 'block':
					$value = '1; mode=block';
					break;
				default:
					return false;
			}

			return new HttpHeader('X-XSS-Protection', $value);
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	private static function buildContentSecurityPolicyHeader($policy) {
		$header = new HttpHeader('Content-Security-Policy');

		$directives = [];

		if ($policy['report-only']) {
			$header->setName('Content-Security-Policy-Report-Only');
		}

		foreach ($policy as $directive => $value) {
			if (\in_array($directive, array_merge(Settings::CSP_FETCH_DIRECTIVES, Settings::CSP_NAVIGATION_DIRECTIVES, ['base-uri']))) {
				$directives[] = self::parseCSPDirectiveSources($directive, $value);
			}

			if ($directive == 'plugin-types') {
				$types = [];

				foreach ($value['types'] as $type) {
					$types[] = $type['value'];
				}

				if (count($types) > 0) {
					$directives[] = sprintf('plugin-types %s;', \join(' ', $types));
				}
			}

			if ($directive == 'sandbox' && $value) {
				$directives[] = \sprintf('sandbox %s;', $value);
			}

			if ($directive == 'upgrade-insecure-requests' && $value == true) {
				$directives[] = 'upgrade-insecure-requests;';
			}

			if ($directive == 'block-all-mixed-content' && $value == true) {
				$directives[] = 'block-all-mixed-content;';
			}
		}

		if (count(array_filter($directives)) > 0) {
			return $header->setValue(\join(' ', $directives));
		}
		
		return false;
	}

	private static function parseCSPDirectiveSources($directive, $sourceData) {
		$sources = [];

		foreach ($sourceData as $source => $data) {
			// User-provided URIs and hashes
			if ($source == '_sources') {
				foreach ($data as $value) {
					if (!empty($value['value'])) {
						$sources[] = $value['value'];
					}
				}

				continue;
			}

			if ($source == 'nonce' && $data == true) {
				// %1$s is replaced with the nonce on every response
				$sources[] = "'nonce-%1\$s'";

				continue;
			}

			// For checkboxes
			if ($data == true) {
				$sources[] = \sprintf("'%s'", $source);
			}
		}

		if (count($sources) > 0) {
			return \sprintf('%s %s;', $directive, \join(' ', $sources));
		}
	}
}