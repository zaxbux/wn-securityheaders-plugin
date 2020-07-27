<?php

namespace Zaxbux\SecurityHeaders\Models;

use Model;
use Cache;
use October\Rain\Exception\ValidationException;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class MiscellaneousHeaderSettings extends Model {
	
	use \October\Rain\Database\Traits\Validation;

	public $implement = ['System.Behaviors.SettingsModel'];

	public $settingsCode = 'zaxbux_securityheaders_other_header_settings';

	public $settingsFields = 'fields.yaml';

	public $rules = [
		'referrer_policy'      => ['in:no-referrer,no-referrer-when-downgrade,origin,origin-when-cross-origin,same-origin,strict-origin,strict-origin-when-cross-origin,unsafe-url'],
		'frame_options'        => ['in:deny,sameorigin'],
		'content_type_options' => ['boolean'],
		'xss_protection'       => ['in:disable,enable,block'],
		'report_to'            => ['boolean'],
	];

	public function initSettingsData() {
		$this->referrer_policy      = 'strict-origin-when-cross-origin';
		$this->frame_options        = 'deny';
		$this->content_type_options = true;
		$this->xss_protection       = null;
		$this->report_to            = false;
	}

	public function afterSave() {
		// Remove headers from cache
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_SECURITY_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_REFERRER_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_FRAME_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_TYPE_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_XSS_PROTECTION);
		Cache::forget(HeaderBuilder::CACHE_KEY_REPORT_TO);
	}
}