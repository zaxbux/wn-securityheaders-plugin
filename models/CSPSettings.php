<?php

namespace Zaxbux\SecurityHeaders\Models;

use Model;
use Cache;
use October\Rain\Exception\ValidationException;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class CSPSettings extends Model {
	use \October\Rain\Database\Traits\Validation;

	public $implement = ['System.Behaviors.SettingsModel'];

	public $settingsCode = 'zaxbux_securityheaders_csp_settings';

	public $settingsFields = 'fields.yaml';

	public $rules = [
		'upgrade_insecure_requests' => ['boolean'],
		'block_all_mixed_content'   => ['boolean'],
	];

	public function initSettingsData() {
		$this->enabled = false;
	}

	public function beforeValidate() {
		if ($this->block_all_mixed_content && $this->upgrade_insecure_requests) {
			throw new ValidationException(['block_all_mixed_content' => 'Cannot enable Block All Mixed Content and Upgrade Insecure Requests at the same time.']);
		}
	}

	public function afterSave() {
		// Remove headers from cache
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_SECURITY_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_REPORT_TO);
	}
}