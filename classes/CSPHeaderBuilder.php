<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Cache;
use Zaxbux\SecurityHeaders\Models\Settings;

class CSPHeaderBuilder {
	const CACHE_KEY = 'zaxbux_securityheaders_csp_header';

	public static function getHeader($nonce) {
		$header = Cache::rememberForever(self::CACHE_KEY, function() {
			return self::makeHeader();
		});

		if ($header) {
			// Add nonce
			$header->value = \sprintf($header->value, $nonce);

			return $header;
		}
	}

	private static function makeHeader() {
		$policy = Settings::get('csp', []);

		if (!$policy['enabled']) {
			return;
		}

		$header     = '';
		$headerName = 'Content-Security-Policy';

		foreach ($policy as $directive => $value) {
			if (\in_array($directive, Settings::CSP_FETCH_DIRECTIVES) ||
				$directive == 'base-uri' ||
				$directive == 'form-action') {
					$header .= self::formatCSPFetchDirective($directive, $value);
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

			if ($directive == 'upgrade-insecure-requests' && $value) {
				$header .= 'upgrade-insecure-requests; ';
			}

			if ($directive == 'block-all-mixed-content' && $value) {
				$header .= 'block-all-mixed-content; ';
			}
		}

		if ($policy['report-only']) {
			$headerName .= '-Report-Only';
		}

		return (object) [
			'name'  => $headerName,
			'value' => trim($header)
		];
	}

	private static function formatCSPFetchDirective($directive, $sources) {
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
				$sourceString .= " 'nonce-%1\$s'";
				continue;
			}

			$sourceString .= sprintf(" '%s'", $source);
		}

		if (strlen($sourceString) > 0) {
			return sprintf('%s %s; ', $directive, $sourceString);
		}

		return '';
	}

	private static function formatCSPUserSource($sources) {
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
}