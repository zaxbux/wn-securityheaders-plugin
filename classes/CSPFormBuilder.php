<?php

namespace Zaxbux\SecurityHeaders\Classes;

class CSPFormBuilder {

	const CSP_GRAMMAR = [
		'serialized-source-list' => [
			'*none',
			'*source-expression',
		],
		'source-expression' => [
			'*keyword-source',
			'*nonce-source',
			'*user_source_expressions'
			//'*host-source',
			//'*scheme-source',
			//'*hash-source',
		],
		'user_source_expressions' => [
			'template' => 'user_source_expressions',
			'field_config' => [
				'label' => 'user_source_expressions.label'
			],
		],
		/*'scheme-source' => [
			'template' => 'scheme',
			'user_data' => true,
		],
		'host-source' => [
			'template' => 'host',
			'user_data' => true,
		],
		'hash-source' => [
			'template' => 'hash',
			'user_data' => true,
		],*/
		'nonce-source' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_nonce-source.label',
				'comment' => 'keyword_nonce-source.comment',
				'tab' => 'tab_:directive'
			]
		],
		'keyword-source' => [
			'*self',
			'*unsafe-inline',
			'*unsafe-eval',
			'*strict-dynamic',
			'*unsafe-hashes',
			'*report-sample',
		],
		'none' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_none.label',
				'comment' => 'keyword_none.comment',
				'tab' => 'tab_:directive'
			]
		],
		'self' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_self.label',
				'comment' => 'keyword_self.comment',
				'tab' => 'tab_:directive'
			]
		],
		'unsafe-inline' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_unsafe-inline.label',
				'comment' => 'keyword_unsafe-inline.comment',
				'tab' => 'tab_:directive'
			]
		],
		'unsafe-eval' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_unsafe-eval.label',
				'comment' => 'keyword_unsafe-eval.comment',
				'tab' => 'tab_:directive'
			]
		],
		'strict-dynamic' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_strict-dynamic.label',
				'comment' => 'keyword_strict-dynamic.comment',
				'tab' => 'tab_:directive'
			]
		],
		'unsafe-hashes' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_unsafe-hashes.label',
				'comment' => 'keyword_unsafe-hashes.comment',
				'tab' => 'tab_:directive'
			]
		],
		'report-sample' => [
			'template' => 'keyword',
			'field_config' => [
				'label' => 'keyword_report-sample.label',
				'comment' => 'keyword_report-sample.comment',
				'tab' => 'tab_:directive'
			]
		],
		'media-type-list' => [
			'*media-type'
		],
		'media-type' => [
			'template' => 'media_type',
			'field_config' => [
				'label' => 'user_source_expressions.label'
			],
		],
		'token' => [
			'template' => 'token',
			'field_config' => [
				'comment' => 'sandbox.comment',
				'tab' => 'tab_sandbox'
			]
		],
		'ancestor-source-list' => [
			'*none',
			'*ancestor-source'
		],
		'ancestor-source' => [
			'*self',
			'*user_ancestor_source_expressions'
			//'*scheme-source',
			//'*host-source'
		],
		'user_ancestor_source_expressions' => [
			'template' => 'user_ancestor_source_expressions',
			'field_config' => [
				'label' => 'user_source_expressions.label'
			]
		],
		'upgrade-insecure-requests' => [
			'field_config' => [
				'type' => 'checkbox',
				'label' => 'upgrade_insecure_requests.label',
				'comment' => 'upgrade_insecure_requests.comment',
				'commentHtml' => true,
				'span' => 'full',
				'trigger' => [
					'action' => 'disable',
					'field' => 'block_all_mixed_content',
					'condition' => 'checked',
				],
			],
		],
		'block-all-mixed-content' => [
			'field_config' => [
				'type' => 'checkbox',
				'label' => 'block_all_mixed_content.label',
				'comment' => 'block_all_mixed_content.comment',
				'commentHtml' => true,
				'span' => 'full',
				'trigger' => [
					'action' => 'disable',
					'field' => 'upgrade_insecure_requests',
					'condition' => 'checked',
				],
			],
		],
	];

	const CSP_DIRECTIVES = [
		'fetch' => [
			[
				'name' => 'default-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'child-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'connect-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'font-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'frame-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'img-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'manifest-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'media-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'object-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'script-src',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'style-src',
				'grammar' => 'serialized-source-list',
			],
		],
		'document' => [
			[
				'name' => 'base-uri',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'plugin-types',
				'grammar' => 'media-type-list',
			],
			[
				'name' => 'sandbox',
				'grammar' => 'token',
			],
		],
		'navigation' => [
			[
				'name' => 'form-action',
				'grammar' => 'serialized-source-list',
			],
			[
				'name' => 'frame-ancestors',
				'grammar' => 'ancestor-source-list',
			],
		],
		'other' => [
			[
				'name' => 'upgrade-insecure-requests',
				'grammar' => 'upgrade-insecure-requests',
			],
			[
				'name' => 'block-all-mixed-content',
				'grammar' => 'block-all-mixed-content',
			],
			
		],
	];

	const FIELD_TEMPLATES = [
		'keyword' => [
			'type'     => 'checkbox',
			'label'    => '',
			'comment'  => '',
			'tab'      => '',
			'span'     => 'storm',
			'cssClass' => 'col-md-3',
		],
		'token' => [
			'type' => 'checkboxlist',
			'commentHtml' => true,
			'quickselect' => false,
			'options' => [
				'allow-forms' => [
					'allow-forms',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-forms'
				],
				'allow-modals' => [
					'allow-modals',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-modals'
				],
				'allow-orientation-lock' => [
					'allow-orientation-lock',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-orientation-lock'
				],
				'allow-pointer-lock' => [
					'allow-pointer-lock',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-pointer-lock'
				],
				'allow-popups' => [
					'allow-popups',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-popups'
				],
				'allow-popups-to-escape-sandbox' => [
					'allow-popups-to-escape-sandbox',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-popups-to-escape-sandbox'
				],
				'allow-presentation' => [
					'allow-presentation',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-presentation'
				],
				'allow-same-origin' => [
					'allow-same-origin',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-same-origin'
				],
				'allow-scripts' => [
					'allow-scripts',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-scripts'
				],
				'allow-top-navigation' => [
					'allow-top-navigation',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-top-navigation'
				],
				'allow-top-navigation-by-user-activation' => [
					'allow-top-navigation-by-user-activation',
					'zaxbux.securityheaders::lang.fields.cspsettings.sandbox.allow-top-navigation-by-user-activation'
				],
			],
		],
		'user_source_expressions' => [
			'type' => 'repeater',
			'tab' => 'tab_:directive',
			'prompt' => 'user_source_expressions.prompt',
			'groups' => [
				'host' => [
					'name' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.name',
					'description' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.description',
					'icon' => 'icon-chain',
					'fields' => [
						'value' => [
							'type' => 'text',
							'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.name',
							'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.comment',
						],
					],
				],
				'scheme' => [
					'name' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.name',
					'description' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.description',
					'icon' => 'icon-chain',
					'fields' => [
						'value' => [
							'type' => 'text',
							'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.name',
							'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.comment',
						],
					],
				],
				'hash' => [
					'name' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.hash.name',
					'description' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.hash.description',
					'icon' => 'icon-hashtag',
					'fields' => [
						'value' => [
							'type' => 'text',
							'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.hash.name',
							'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.hash.comment',
						],
					],
				],
			],
		],
		'user_ancestor_source_expressions' =>  [
			'type' => 'repeater',
			'tab' => 'tab_:directive',
			'prompt' => 'user_source_expressions.prompt',
			'groups' => [
				'host' => [
					'name' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.name',
					'description' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.description',
					'icon' => 'icon-chain',
					'fields' => [
						'value' => [
							'type' => 'text',
							'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.name',
							'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.host.comment',
						],
					],
				],
				'scheme' => [
					'name' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.name',
					'description' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.description',
					'icon' => 'icon-chain',
					'fields' => [
						'value' => [
							'type' => 'text',
							'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.name',
							'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.scheme.comment',
						],
					],
				],
			],
		],
		'media_type' => [
			'type' => 'repeater',
			'tab' => 'tab_:directive',
			'prompt' => 'user_source_expressions.prompt',
			'titleFrom' => 'value',
			'form' => [
				'fields' => [
					'value' => [
						'type' => 'text',
						'label' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.media_type.label',
						'comment' => 'zaxbux.securityheaders::lang.fields.cspsettings.user_source_expressions.media_type.comment',
						'commentHtml' => true,
					],
				],
			],
		],
	];

	const LANG_PREFIX = 'zaxbux.securityheaders::lang.fields.cspsettings.';

	public function __construct() {

	}

	public function makeForm($widget) {
		// upgrade-insecure-requests and block-all-mixed-content
		foreach (self::CSP_DIRECTIVES['other'] as $directive) {
			$widget->addFields($this->getFieldConfig($directive['name'], $directive['grammar']));
		}

		$this->addTabFields('fetch', $widget);
		$this->addTabFields('navigation', $widget);
		$this->addTabFields('document', $widget);
	}

	private function addTabFields($tab, $widget) {
		foreach (self::CSP_DIRECTIVES[$tab] as $directive) {
			$fields = self::getFieldConfig($directive['name'], $directive['grammar']);

			foreach ($fields as $name => $config) {
				if ($name == 'user_source_expressions' || $name == 'user_ancestor_source_expressions') {
					$name = '_user_sources';
				}

				$fieldName = '%s[%s]';

				if ($directive['name'] == 'sandbox' || $directive['name'] == 'plugin-types') {
					$fieldName = '%s';
				}

				$widget->addTabFields([
					\sprintf($fieldName, \str_replace('-', '_', $directive['name']), \str_replace('-', '_', $name)) => $config
				]);
			}
		}
	}

	private function getFieldConfig($directive, $grammarName) {
		$fields = [];
		$grammarData = self::CSP_GRAMMAR[$grammarName];

		// Template needed
		if (\key_exists('template', $grammarData) && \key_exists('field_config', $grammarData)) {
			
			$template = self::FIELD_TEMPLATES[$grammarData['template']];

			$config = array_merge($template, $grammarData['field_config']);

			$config['tab'] = \str_replace([':directive'], [$directive], $config['tab']);

			return [
				\str_replace('-', '_', $grammarName) => self::addLangPrefix($config)
			];
		}

		// Full config provided
		if (\key_exists('field_config', $grammarData)) {
			return [
				\str_replace('-', '_', $grammarName) => self::addLangPrefix($grammarData['field_config'])
			];
		}

		foreach ($grammarData as $key) {
			if (\strpos($key, '*') === 0) {
				$fields += self::getFieldConfig($directive, \str_replace('*', '', $key));
			}
		}

		return $fields;
	}

	private static function addLangPrefix($fieldConfig) {
		$translatable = ['label', 'comment', 'tab', 'prompt'];

		foreach ($translatable as $key) {
			if (\key_exists($key, $fieldConfig)) {
				$fieldConfig[$key] = self::LANG_PREFIX.$fieldConfig[$key];
			}
		}

		return $fieldConfig;
	}
}