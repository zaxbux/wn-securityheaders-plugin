<?php return [
    'plugin' => [
        'name' => 'Security Headers',
        'description' => ''
    ],
    'settings' => [
        'label' => 'Security Headers',
        'description' => 'Configure HTTP headers for site security.',
        'category' => 'Security',
    ],
    'components' => [
        'CSPNonce' => [
            'name' => 'CSP nonce provider',
            'description' => 'Use the CSP nonce on inline scripts, etc.'
        ]
    ],
    'permissions' => [
        'access_settings' => 'Access settings'
    ],
    'fields' => [
        'settings' => [
            '_unset' => '-- unset --',
            'hsts_enable' => [
                'label' => 'Enable HTTP Strict Transport Security (HSTS)',
                'comment' => '<strong class="text-danger">Caution:</strong> If misconfigured, this can make your website inaccessible to users for an extended period of time. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security" target="_blank">Learn more</a>'
            ],
            'hsts_max_age' => [
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
            'hsts_subdomains' => [
                'label' => 'Include Subdomains',
                'comment' => 'Every domain below this will inherit the same HSTS headers. <strong class="text-danger">Caution:</strong> Subdomains without HTTPS will be completely inaccessable.'
            ],
            'hsts_preload' => [
                'label' => 'Preload',
                'comment' => 'Permit browsers to preload HSTS configuration automatically. <strong class="text-danger">Caution:</strong> Sites without HTTPS support will be completely inaccessible.'
            ],
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

            'csp_none' => 'No URLs match.',
            'csp_self' => 'Origin that the resource is being served from.',
            'csp_unsafe-eval' =>  'Allows the use of eval() and similar methods.',
            'csp_unsafe-hashes' => 'Allows to enable specific inline event handlers.',
            'csp_unsafe-inline' => 'Allows the use of inline resources.',
            'csp_nonce' => 'A whitelist for specific inline scripts using a cryptographic nonce.',
            'csp_strict-dynamic' => 'The trust explicitly given to a script shall be propagated to all the scripts loaded by that root script.',
            'csp_report-sample' => 'Requires a sample of the violating code to be included in the violation report.',
            'csp_sources' => 'Add Source',
            'csp_source_host' => [
                'name' => 'Host',
                'description' => 'Hostname, IP address, or URL',
                'comment' => '',
            ],
            'csp_source_scheme' => [
                'name' => 'Scheme',
                'description' => 'Scheme',
                'comment' => 'http: https: data: blob: etc',
            ],
            'csp_source_hash' => [
                'name' => 'Hash',
                'description' => 'A sha256, sha384 or sha512 hash of scripts or styles.',
                'comment' => '<hash-algorithm>-<base64-value> A sha256, sha384 or sha512 hash',
            ],
            'csp_report_only' => [
                'label' => 'Report Only',
                'comment' => 'Monitor, but do not enforce the Content-Security-Policy header. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy-Report-Only" target="_blank">Learn more</a>',
            ],
            'csp_block_all_mixed_content' => [
                'label' => 'Block All Mixed Content',
                'comment' => '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/block-all-mixed-content" target="_blank">Learn more</a>',
            ],
            'csp_upgrade_insecure_requests' => [
                'label' => 'Upgrade Insecure Requests',
                'comment' => '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests" target="_blank">Learn more</a>',
            ],
            'csp_sandbox' => [
                'label' => 'Sandbox',
                'comment' => 'Apply restrictions to a page\'s actions. <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox" target="_blank">Learn more</a>',
            ],
            'csp_enable' => [
                'label' => 'Enable',
                'comment' => '',
            ],
        ],
    ],
];