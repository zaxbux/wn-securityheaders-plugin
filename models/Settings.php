<?php

namespace Zaxbux\SecurityHeaders\Models;

use Model;
use Cache;
use October\Rain\Exception\ValidationException;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class Settings extends Model {
	use \October\Rain\Database\Traits\Validation;

	// Content-Security-Policy fetch directives
	const CSP_FETCH_DIRECTIVES = [
		'default-src',
		'child-src',
		'connect-src',
		'font-src',
		'frame-src',
		'img-src',
		'maifest-src',
		'media-src',
		'object-src',
		'script-src',
		'style-src'
	];

	// Content-Security-Policy document directives
	const CSP_DOC_DIRECTIVES = [
		'base-uri',
		'plugin-types',
		'sandbox'
	];

	// Content-Security-Policy navigation directives
	const CSP_NAVIGATION_DIRECTIVES = [
		'form-action',
		'frame-ancestors'
	];

	// Content-Security-Policy "other" directives
	const CSP_OTHER_DIRECTIVES = [
		'block-all-mixed-content',
		'upgrade-insecure-requests'
	];

	public $implement = ['System.Behaviors.SettingsModel'];
	public $settingsCode = 'zaxbux_securityheaders_settings';
	public $settingsFields = 'fields.yaml';
	public $rules = [
		'hsts_enable'          => ['boolean'],
		'hsts_max_age'         => ['integer', 'min:0', 'max:63072000'],
		'hsts_sub_domains'     => ['boolean'],
		'hsts_preload'         => ['boolean'],
		'referrer_policy'      => ['in:no-referrer,no-referrer-when-downgrade,origin,origin-when-cross-origin,same-origin,strict-origin,strict-origin-when-cross-origin,unsafe-url'],
		'frame_options'        => ['in:deny,sameorigin'],
		'content_type_options' => ['boolean'],
		'xss_protection'       => ['in:disable,enable,block'],
	];

	public function initSettingsData() {
		$this->hsts_enable            = false;
		$this->hsts_max_age           = '31536000';
		$this->hsts_subdomains        = false;
		$this->hsts_preload           = false;
		$this->referrer_policy        = 'strict-origin-when-cross-origin';
		$this->frame_options          = 'deny';
		$this->content_type_options   = true;
		$this->xss_protection         = null;
	}

	public function beforeValidate() {
		if ($this->csp['block-all-mixed-content'] && $this->csp['upgrade-insecure-requests']) {
			throw new ValidationException(['block-all-mixed-content' => 'Cannot enable block-all-mixed-content and upgrade-insecure-requests at the same time']);
		}
	}

	public function afterSave() {
		// Remove headers from cache
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_SECURITY_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_STRICT_TRANSPORT_SECURITY);
		Cache::forget(HeaderBuilder::CACHE_KEY_REFERRER_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_FRAME_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_TYPE_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_XSS_PROTECTION);
	}
}