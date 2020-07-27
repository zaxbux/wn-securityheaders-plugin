<?php namespace Zaxbux\SecurityHeaders\Updates;

use DB;
use October\Rain\Database\Updates\Migration;

class MigrateSettings103 extends Migration {
	public function up() {
		// Migrate settings from zaxbux_securityheaders_settings to new settings codes
		$oldSettings = DB::table('system_settings')->where('zaxbux_securityheaders_settings')->first();

		// Exit if old settings do not exist
		if (!$oldSettings) {
			return;
		}

		$oldSettings = \json_decode($oldSettings['value'], true);

		$settings = [
			'csp_settings' => [],
			'hsts_settings' => [],
			'other_header_settings' => [],
		];

		// Miscellaneous headers
		$settings['other_header_settings']['referrer_policy']      = $oldSettings['referrer-policy'];
		$settings['other_header_settings']['frame_options']        = $oldSettings['frame-options'];
		$settings['other_header_settings']['content_type_options'] = $oldSettings['content-type-options'];
		$settings['other_header_settings']['xss_protection']       = $oldSettings['xss-protection'];

		DB::table('system_settings')->insert([
			'name' => 'zaxbux_securityheaders_other_header_settings',
			'value' => json_encode($settings['other_header_settings'])
		]);

		// HSTS Settings
		$settings['hsts_settings']['enabled']    = $oldSettings['hsts-enable'];
		$settings['hsts_settings']['max_age']    = $oldSettings['hsts-max-age'];
		$settings['hsts_settings']['subdomains'] = $oldSettings['hsts-subdomains'];
		$settings['hsts_settings']['preload']    = $oldSettings['hsts-preload'];

		DB::table('system_settings')->insert([
			'name' => 'zaxbux_securityheaders_hsts_settings',
			'value' => json_encode($settings['hsts_settings'])
		]);

		// CSP Settings
		foreach ($oldSettings['csp'] as $key => &$value) {
			if (\is_array($value)) {
				if ($key == 'plugin-types') {
					$settings['csp_settings']['plugin_types'] = $value['types'];
					continue;
				}
				if (\array_key_exists('_sources', $value)) {
					$userSources = $value['_sources'];
					$value['_user_sources'] = $userSources;
					unset($value['_sources']);
				}
			}

			$settings['csp_settings'][\str_replace('_', '-', $key)] = $value;
		}

		DB::table('system_settings')->insert([
			'name' => 'zaxbux_securityheaders_csp_settings',
			'value' => json_encode($settings['csp_settings'])
		]);
	}
	
	public function down() {
		// This is a one-way migration, no downgrade.
	}
}
