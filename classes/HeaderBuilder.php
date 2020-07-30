<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Url;
use Cache;
use Config;
use Illuminate\Http\Response;
use Zaxbux\SecurityHeaders\Classes\HttpHeader;
use Zaxbux\SecurityHeaders\Classes\CSPFormBuilder;
use Zaxbux\SecurityHeaders\Models\CSPSettings;
use Zaxbux\SecurityHeaders\Models\HSTSSettings;
use Zaxbux\SecurityHeaders\Models\MiscellaneousHeaderSettings;

class HeaderBuilder {

	const CACHE_KEY_CONTENT_SECURITY_POLICY   = "zaxbux_securityheaders_csp";
	const CACHE_KEY_STRICT_TRANSPORT_SECURITY = "zaxbux_securityheaders_hsts";
	const CACHE_KEY_REFERRER_POLICY           = "zaxbux_securityheaders_ref_policy";
	const CACHE_KEY_FRAME_OPTIONS             = "zaxbux_securityheaders_frame_options";
	const CACHE_KEY_CONTENT_TYPE_OPTIONS      = "zaxbux_securityheaders_content_type";
	const CACHE_KEY_XSS_PROTECTION            = "zaxbux_securityheaders_xss";
	const CACHE_KEY_REPORT_TO                 = "zaxbux_securityheaders_report_to";

	const CSP_REPORT_TO_GROUP = 'csp-endpoint';

	/**
	 * Add the Content-Security-Policy or Content-Security-Policy-Report-Only header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addContentSecurityPolicy(Response $response, $nonce) {
		$header = Cache::rememberForever(self::CACHE_KEY_CONTENT_SECURITY_POLICY, function() {
			if (!CSPSettings::get('enabled')) {
				return false;
			}

			return self::buildContentSecurityPolicyHeader();
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
			if (!HSTSSettings::get('enabled')) {
				return false;
			}

			$value = sprintf('max-age=%d', HSTSSettings::get('max_age'));

			if (HSTSSettings::get('subdomains')) {
				$value .= '; includeSubDomains';
			}
	
			if (HSTSSettings::get('preload')) {
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
			if ($value = MiscellaneousHeaderSettings::get('referrer_policy')) {
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
			if ($value = MiscellaneousHeaderSettings::get('frame_options')) {
				return new HttpHeader('X-Frame-Options', $value);
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
			if ($value = MiscellaneousHeaderSettings::get('content_type_options')) {
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
			$value = MiscellaneousHeaderSettings::get('xss_protection');

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

	/**
	 * Add the X-XSS-Protection header to the response
	 * 
	 * @param Illuminate\Http\Response
	 */
	public static function addReportTo(Response $response) {
		$header = Cache::rememberForever(self::CACHE_KEY_REPORT_TO, function() {
			if (!MiscellaneousHeaderSettings::get('report_to')) {
				return false;
			}

			$action = CSPSettings::get('report_only') ? \Zaxbux\SecurityHeaders\Http\Controllers\ReportsController::ACTION_REPORT : \Zaxbux\SecurityHeaders\Http\Controllers\ReportsController::ACTION_ENFORCE;

			$value = [
				'group' => self::CSP_REPORT_TO_GROUP,
				'max_age' => 2592000,
				'endpoints' => [
					['url' => Url::route('zaxbux.securityheaders.reports.csp_endpoint', ['action' => $action])]
				]
			];

			return new HttpHeader('Report-To', \json_encode($value));
		});

		if ($header) {
			$response->header($header->getName(), $header->getValue());
		}
	}

	private static function buildContentSecurityPolicyHeader() {
		$header = new HttpHeader('Content-Security-Policy');

		$directives = [];

		if (CSPSettings::get('report_only')) {
			$header->setName('Content-Security-Policy-Report-Only');
		}

		// Fetch directives, navigation directives, and base-uri directive
		$sourceBasedDirectives = ['base-uri'];

		foreach (CSPFormBuilder::CSP_DIRECTIVES['fetch'] as $directive) {
			$sourceBasedDirectives[] = $directive['name'];
		}

		foreach (CSPFormBuilder::CSP_DIRECTIVES['navigation'] as $directive) {
			$sourceBasedDirectives[] = $directive['name'];
		}

		//var_dump($sourceBasedDirectives); die();

		foreach ($sourceBasedDirectives as $sourceBasedDirective) {
			if ($sourceData = CSPSettings::get(\str_replace('-', '_', $sourceBasedDirective))) {
				$directive = self::parseCSPDirectiveSources($sourceBasedDirective, $sourceData);

				if ($directive) {
					$directives[] = $directive;
				}
			}
		}

		// Plugin Types
		$pluginTypes = [];

		foreach (CSPSettings::get('plugin_types', []) as $typeGroup) {
			$pluginTypes[] = $typeGroup['value'];
		}

		if (count($pluginTypes) > 0) {
			$directives[] = sprintf('plugin-types %s;', \join(' ', $pluginTypes));
		}

		// Sandbox
		if ($sandbox = CSPSettings::get('sandbox')) {
			$directives[] = \sprintf('sandbox %s;', \implode(' ', $sandbox));
		}

		// Upgrade Insecure Requests
		if (CSPSettings::get('upgrade_insecure_requests')) {
			$directives[] = 'upgrade-insecure-requests;';
		}

		// Block All Mixed Content
		if (CSPSettings::get('block_all_mixed_content')) {
			$directives[] = 'block-all-mixed-content;';
		}

		// Policy violation logging
		if (CSPSettings::get('log_violations') == true) {
			$action = CSPSettings::get('report_only') ? 'report_only' : 'enforce';

			$reportUri = Url::route('zaxbux.securityheaders.reports.csp_endpoint', ['action' => $action]);

			$directives[] = \sprintf('report-uri %s;', $reportUri);
		}

		// Report-To support
		if (MiscellaneousHeaderSettings::get('report_to')) {
			$directives[] = \sprintf('report-to %s', self::CSP_REPORT_TO_GROUP);
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
			if ($source == '_user_sources') {
				foreach ($data as $value) {
					if (!empty($value['value'])) {
						$sources[] = $value['value'];
					}
				}

				continue;
			}

			if ($source == 'nonce_source' && $data == true) {
				// %1$s is replaced with the nonce on every response
				$sources[] = "'nonce-%1\$s'";

				continue;
			}

			// For checkboxes
			if ($data == true) {
				$sources[] = \sprintf("'%s'", \str_replace('_', '-', $source));
			}
		}

		if (count($sources) > 0) {
			return \sprintf('%s %s;', $directive, \join(' ', $sources));
		}
	}
}