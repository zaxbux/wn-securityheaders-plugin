<?php namespace Zaxbux\SecurityHeaders\Models;

use Model;
use Cache;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class PermissionsPolicySettings extends Model {
	use \Winter\Storm\Database\Traits\Validation;

	public $implement      = [
		\System\Behaviors\SettingsModel::class,
	];

	public $settingsCode   = 'zaxbux_securityheaders_permissionspolicy_settings';

	public $settingsFields = 'fields.yaml';
	
	public $rules = [
		'enabled' => ['boolean'],
	];

	public function initSettingsData() {
		$this->enabled     = false;
	}

	public function afterSave() {
		// Remove headers from cache
		Cache::forget(HeaderBuilder::CACHE_KEY_PERMISSIONS_POLICY);
	}
}
