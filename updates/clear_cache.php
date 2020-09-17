<?php namespace Zaxbux\SecurityHeaders\Updates;

use DB;
use Cache;
use October\Rain\Database\Updates\Migration;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;

class ClearCache extends Migration {
	public function up() {
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_SECURITY_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_REFERRER_POLICY);
		Cache::forget(HeaderBuilder::CACHE_KEY_FRAME_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_TYPE_OPTIONS);
		Cache::forget(HeaderBuilder::CACHE_KEY_XSS_PROTECTION);
		Cache::forget(HeaderBuilder::CACHE_KEY_REPORT_TO);
	}
	
	public function down() {
		// No downgrade.
	}
}
