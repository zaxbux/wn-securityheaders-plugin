<?php

namespace Zaxbux\SecurityHeaders\Models;

use Model;
use Cache;
use October\Rain\Exception\ValidationException;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class HSTSSettings extends Model {
	use \October\Rain\Database\Traits\Validation;

	public $implement      = ['System.Behaviors.SettingsModel'];

	public $settingsCode   = 'zaxbux_securityheaders_hsts_settings';

	public $settingsFields = 'fields.yaml';
	
	public $rules = [
		'enabled'    => ['boolean'],
		'max_age'    => ['integer', 'min:0', 'max:63072000'],
		'subdomains' => ['boolean'],
		'preload'    => ['boolean'],
	];

	public function initSettingsData() {
		$this->enabled     = false;
		$this->max_age     = 31536000;
		$this->subdomains  = false;
		$this->preload     = false;
	}

	public function afterSave() {
		// Remove headers from cache
		Cache::forget(HeaderBuilder::CACHE_KEY_STRICT_TRANSPORT_SECURITY);
	}
}