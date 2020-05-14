<?php

namespace Zaxbux\SecurityHeaders\Classes;

class CSPDirectives {
	const SOURCE_LIST_DIRECTIVES = [
		'default-src',
		'child-src',
		'connect-src',
		'font-src',
		'frame-src',
		'img-src',
		'manifest-src',
		'media-src',
		'object-src',
		'script-src',
		'style-src',
		'base-uri',
		'form-action'
	];

	const SOURCE_LIST = [
		'none',
		'self',
		'unsafe-eval',
		'unsafe-hashes',
		'unsafe-inline',
		'nonce',
		'strict-dynamic',
		'report-sample',
	];

	const LANG_PREFIX = '';

	public static function getFormConfig() {
		$form = [];

		foreach (self::SOURCE_LIST_DIRECTIVES as $directive) {
			foreach (self::SOURCE_LIST as $source) {
				$form[\sprintf('csp[%s][%s]', $directive, $source)] = [
					'type'     => 'checkbox',
					'label'    => $source,
					'comment'  => self::LANG_PREFIX.$source,
					'tab'      => $directive,
					'span'     => 'storm',
					'cssClass' => 'col-md-3',
				];
			}

			$form[\sprintf('csp[%s][_sources]', $directive)] = [
				'type' => 'repeater',
				'tab' => $directive,
				'prompt' => 'zaxbux.securityheaders::lang.fields.settings.csp_sources',
				'titleFrom' => 'value',
				'groups' => '$/zaxbux/securityheaders/models/settings/csp-source.yaml',
			];
		}

		return $form;
	}
}