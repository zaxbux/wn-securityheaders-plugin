<?php return [
	'plugin' => [
		'name' => 'Security Headers',
		'description' => ''
	],
	'settings' => [
		'category' => 'Security Headers',
		'csp' => [
			'label' => 'CSP',
			'description' => 'Configure the Content-Security-Policy header.',
		],
		'miscellaneous' => [
			'label' => 'Miscellaneous',
			'description' => 'Configure additional HTTP headers for security.',
		],
		'csp_logs' => [
			'label' => 'CSP Reports',
			'description' => 'View CSP violation reports with their recorded time and details.',
		],
		'hsts' => [
			'label' => 'HSTS',
			'description' => 'Configure the Strict-Transport-Security header.',
		],
		'report_to' => [
			'label' => 'Reporting',
			'description' => 'Configure the Report-To header.',
		]
	],
	'components' => [
		'CSPNonce' => [
			'name' => 'CSP nonce provider',
			'description' => 'Use the CSP nonce on inline scripts, etc.'
		]
	],
	'permissions' => [
		'access_settings' => 'Access settings',
		'access_logs' => 'Access Logs',
		'view_widgets' => 'View Widgets',
	],
	'report_widgets' => [
		'csp_reports' => [
			'label' => 'CSP Violation Reports',
			'title' => 'CSP Violation Reports',
			'days' => 'Days',
			'report_only_text' => 'Report Only',
			'enforce_text' => 'Enforce',
		]
	],
	'fields' => [
		'cspsettings' => [
			'enabled' => [
				'label' => 'Enable',
				'comment' => 'Enable the Content-Security-Policy header. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP" target="_blank">Learn more</a>',
			],
			'report_only' => [
				'label' => 'Report Only',
				'comment' => 'Monitor, but do not enforce the Content-Security-Policy header. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy-Report-Only" target="_blank">Learn more</a>',
			],
			'log_violations' => [
				'label' => 'Log Violations',
				'comment' => 'Store policy violation reports from browsers. You should also enable the Report-To header in Miscellaneous Headers, as the report_uri directive is deprecated.',
			],
			'upgrade_insecure_requests' => [
				'label' => 'Upgrade Insecure Requests',
				'comment' => '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests" target="_blank">Learn more</a>',
			],
			'block_all_mixed_content' => [
				'label' => 'Block All Mixed Content',
				'comment' => '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/block-all-mixed-content" target="_blank">Learn more</a>',
			],
			'tab_default-src' => 'default-src',
			'tab_child-src' => 'child-src',
			'tab_connect-src' => 'connect-src',
			'tab_font-src' => 'font-src',
			'tab_frame-src' => 'frame-src',
			'tab_img-src' => 'img-src',
			'tab_manifest-src' => 'manifest-src',
			'tab_media-src' => 'media-src',
			'tab_object-src' => 'object-src',
			'tab_script-src' => 'script-src',
			'tab_style-src' => 'style-src',
			'tab_base-uri' => 'base-uri',
			'tab_plugin-types' => 'plugin-types',
			'tab_sandbox' => 'sandbox',
			'tab_form-action' => 'form-action',
			'tab_frame-ancestors' => 'frame-ancestors',
			'keyword_none' => [
				'label' => 'none',
				'comment' => 'No URLs match.',
			],
			'keyword_self' => [
				'label' => 'self',
				'comment' => 'Origin that the resource is being served from.',
			],
			'keyword_unsafe-eval' => [
				'label' => 'unsafe-eval',
				'comment' => 'Allows the use of eval() and similar methods.',
			],
			'keyword_unsafe-hashes' => [
				'label' => 'unsafe-hashes',
				'comment' => 'Allows to enable specific inline event handlers.',
			],
			'keyword_unsafe-inline' => [
				'label' => 'unsafe-inline',
				'comment' => 'Allows the use of inline resources.',
			],
			'keyword_nonce-source' => [
				'label' => 'nonce',
				'comment' => 'A whitelist for specific inline scripts using a cryptographic nonce.',
			],
			'keyword_strict-dynamic' => [
				'label' => 'strict-dynamic',
				'comment' => 'The trust explicitly given to a script shall be propagated to all the scripts loaded by that root script.',
			],
			'keyword_report-sample' => [
				'label' => 'report-sample',
				'comment' => 'Requires a sample of the violating code to be included in the violation report.',
			],
			'sandbox' => [
				'label' => 'sandbox',
				'comment' => 'Apply restrictions to a page\'s actions. This is ignored in Report-Only mode. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox" target="_blank">Learn more</a>',
				'allow-forms' => 'Allows the resource to submit forms. If this keyword is not used, form submission is blocked.',
				'allow-modals' => 'Lets the resource open modal windows.',
				'allow-orientation-lock' => 'Lets the resource lock the screen orientation.',
				'allow-pointer-lock' => 'Lets the resource use the Pointer Lock API.',
				'allow-popups' => 'Allows popups. If this keyword is not used, the popup will silently fail to open.',
				'allow-popups-to-escape-sandbox' => 'Lets the sandboxed document open new windows without those windows inheriting the sandboxing.',
				'allow-presentation' => 'Lets the resource start a presentation session.',
				'allow-same-origin' => 'If this token is not used, the resource is treated as being from a special origin that always fails the same-origin policy',
				'allow-scripts' => 'Lets the resource run scripts (but not create popup windows).',
				'allow-top-navigation' => 'Lets the resource navigate the top-level browsing context.',
				'allow-top-navigation-by-user-activation' => 'Lets the resource navigate the top-level browsing context, but only if initiated by a user gesture.',
			],
			'plugin_types' => [

			],
			'user_source_expressions' => [
				'label' => 'User Sources',
				'prompt' => 'Add Source',
				'host' => [
					'name' => 'Host',
					'description' => 'Hostname, IP address, or URL',
					'comment' => 'E.g. https://cdn.example.com',
				],
				'scheme' => [
					'name' => 'Scheme',
					'description' => 'Scheme',
					'comment' => 'E.g. http: https: data: blob:',
				],
				'hash' => [
					'name' => 'Hash',
					'description' => 'A sha256, sha384 or sha512 hash of scripts or styles.',
					'comment' => 'A sha256, sha384 or sha512 hash in the format: <hash-algorithm>-<base64-value>',
				],
				'media_type' => [
					'label' => 'Plugin MIME Type',
					'comment' => 'E.g. <code>application/x-shockwave-flash</code>'
				]
			]
		],
		'hstssettings' => [
			'enabled' => [
				'label' => 'Enable HTTP Strict Transport Security (HSTS)',
				'comment' => '<strong class="text-danger">Caution:</strong> If misconfigured, this can make your website inaccessible to users for an extended period of time. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security" target="_blank">Learn more</a>'
			],
			'max_age' => [
				'label' => 'Max Age',
				'comment' => 'Specify the duration HSTS headers are cached in browsers. Recommended: <strong>12 months</strong>',
				'option_1months' => '1 month',
				'option_2months' => '2 months',
				'option_3months' => '3 months',
				'option_4months' => '4 months',
				'option_5months' => '5 months',
				'option_6months' => '6 months',
				'option_12months' => '12 months',
				'option_24months' => '24 months',
			],
			'subdomains' => [
				'label' => 'Include Subdomains',
				'comment' => 'Every domain below this will inherit the same HSTS headers. <strong class="text-danger">Caution:</strong> Subdomains without HTTPS will be completely inaccessable.'
			],
			'preload' => [
				'label' => 'Preload',
				'comment' => 'Permit browsers to preload HSTS configuration automatically. <strong class="text-danger">Caution:</strong> Sites without HTTPS support will be completely inaccessible.'
			],
		],
		'miscellaneoussettings' => [
			'_unset' => '-- unset --',
			'referrer_policy' => [
				'label' => 'Referrer-Policy',
				'comment' => 'Recommended: <strong>strict-origin-when-cross-origin</strong>. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy" target="_blank">Learn more</a>',
			],
			'frame_options' => [
				'label' => 'X-Frame-Options',
				'comment' => 'Recommended: <strong>deny</strong>. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options" target="_blank">Learn more</a>',
			],
			'content_type_options' => [
				'label' => 'Enable X-Content-Type-Options "nosniff"',
				'comment' => 'Recommended: <strong>On</strong>. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options" target="_blank">Learn more</a>',
			],
			'xss_protection' => [
				'label' => 'X-XSS-Protection',
				'comment' => 'This header is deprecated, recommended: <strong>unset</strong>. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection" target="_blank">Learn more</a>',
				'option_disable' => 'Disable',
				'option_enable' => 'Enable',
				'option_block' => 'Block',
			],
			'report_to' => [
				'label' => 'Enable',
				'comment' => 'This will add a <code>csp-endpoint</code> group that will collect policy violation events <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/report-to" target="_blank">Learn more</a>',
			],
		],
		'reporting' => [
			'hint' => 'This log displays a list of potential errors that occur as a result of your content security policy configuration.',
		],
	],
	'columns' => [
		'csplog' => [
			'action' => 'Action',
			'document_uri' => 'Document URI',
			'violated_directive' => 'Directive',
			'blocked_uri' => 'Blocked URI',
		],
	],
	'filters' => [
		'action' => [
			'label' => 'Action',
			'options' => [
				'report_only' => 'Report Only',
				'enforce' => 'Enforce',
			]
		]
	],
	'forms' => [
		'csplogs' => [
			'name' => 'CSP Reports',
			'preview_title' => 'CSP Report',
			'action' => 'Action',
			'blocked_uri' => 'Blocked URI',
			'disposition' => 'Disposition',
			'document_uri' => 'Document URI',
			'effective_directive' => 'Effective Directive',
			'original_policy' => 'Original Policy',
			'referrer' => 'Referrer',
			'script_sample' => 'Script Sample',
			'status_code' => 'Status Code',
			'violated_directive' => 'Violated Directive',
			'user_agent' => 'User Agent',
			'original' => 'Original'
		]
	]
];