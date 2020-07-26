<?php namespace Zaxbux\SecurityHeaders\Models;

use Model;

/**
 * Model
 */
class CSPLog extends Model
{
	use \October\Rain\Database\Traits\Validation;
	

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'zaxbux_securityheaders_reporting_csp';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $fillable = [
		'action',
		'original_data',
		'blocked_uri',
		'disposition',
		'document_uri',
		'effective_directive',
		'original_policy',
		'referrer',
		'script_sample',
		'status_code',
		'violated_directive',
		'user_agent',
	];

	/*public function scopeBrowser($query, $filters = []) {
		return $query;
	}

	public function scopeOperatingSystem($query, $filters = []) {
		return $query;
	}*/

	public function prettyPrinted() {
		return \json_encode(\json_decode($this->original_data, true), JSON_PRETTY_PRINT);
	}
}
