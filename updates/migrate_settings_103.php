<?php namespace Zaxbux\SecurityHeaders\Updates;

use DB;
use Cache;
use October\Rain\Database\Updates\Migration;

class MigrateSettings103 extends Migration {
	public function up() {
		// Migrate settings from zaxbux_securityheaders_settings to new settings codes
		$oldSettings = DB::table('system_settings')->where('item', 'zaxbux_securityheaders_settings')->first();

		// Exit if old settings do not exist
		if (!$oldSettings) {
			return;
		}

		$oldSettings = \json_decode($oldSettings->value, true);

		$settings = [
			'csp_settings' => [],
			'hsts_settings' => [],
			'other_header_settings' => [],
		];

		// Miscellaneous headers
		$settings['other_header_settings']['referrer_policy']      = $oldSettings['referrer_policy'];
		$settings['other_header_settings']['frame_options']        = $oldSettings['frame_options'];
		$settings['other_header_settings']['content_type_options'] = $oldSettings['content_type_options'];
		$settings['other_header_settings']['xss_protection']       = $oldSettings['xss_protection'];

		DB::table('system_settings')->insert([
			'item' => 'zaxbux_securityheaders_other_header_settings',
			'value' => json_encode($settings['other_header_settings'])
		]);
		Cache::forget('system::settings.zaxbux_securityheaders_other_header_settings');

		// HSTS Settings
		$settings['hsts_settings']['enabled']    = $oldSettings['hsts_enable'];
		$settings['hsts_settings']['max_age']    = $oldSettings['hsts_max_age'];
		$settings['hsts_settings']['subdomains'] = $oldSettings['hsts_subdomains'];
		$settings['hsts_settings']['preload']    = $oldSettings['hsts_preload'];

		DB::table('system_settings')->insert([
			'item' => 'zaxbux_securityheaders_hsts_settings',
			'value' => json_encode($settings['hsts_settings'])
		]);
		Cache::forget('system::settings.zaxbux_securityheaders_hsts_settings');

		// CSP Settings
		foreach ($oldSettings['csp'] as $key => $value) {
			if ($key == 'sandbox') {
				$settings['csp_settings']['sandbox'] = [$value];
				continue;
			}

			if (\is_array($value)) {
				if ($key == 'plugin-types') {
					$settings['csp_settings']['plugin_types'] = $value['types'];
					continue;
				}

				$newValue = [];
				foreach ($value as $subKey => $subValue) {
					if ($subKey == '_sources') {
						$newValue['_user_sources'] = $subValue;
						continue;
					}

					$newValue[\str_replace('-', '_', $subKey)] = $subValue;
				}
				$settings['csp_settings'][\str_replace('-', '_', $key)] = $newValue;
				continue;
			}

			$settings['csp_settings'][\str_replace('-', '_', $key)] = $value;
		}

		DB::table('system_settings')->insert([
			'item' => 'zaxbux_securityheaders_csp_settings',
			'value' => json_encode($settings['csp_settings'])
		]);
		Cache::forget('system::settings.zaxbux_securityheaders_csp_settings');
	}
	
	public function down() {
		// This is a one-way migration, no downgrade.
	}
}
