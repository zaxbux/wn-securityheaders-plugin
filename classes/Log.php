<?php namespace Zaxbux\SecurityHeaders\Classes;

use Zaxbux\SecurityHeaders\Models\CSPLog;

class Log {
	public static function CSPReport($action, $cspBody, $originalData = null, $userAgent = null) {
		$cspReport = $cspBody['csp-report'];
		
		$entry = new CSPLog();
		$entry->fill([
			'action'              => $action,
			'original_data'       => $originalData,
			'blocked_uri'         => $cspReport['blocked-uri'] ?? null,
			'disposition'         => $cspReport['disposition'] ?? null,
			'document_uri'        => $cspReport['document-uri'] ?? null,
			'effective_directive' => $cspReport['effective-directive'] ?? null,
			'original_policy'     => $cspReport['original-policy'] ?? null,
			'referrer'            => $cspReport['referrer'] ?? null,
			'script_sample'       => $cspReport['script-sample'] ?? null,
			'status_code'         => $cspReport['status-code'] ?? null,
			'violated_directive'  => $cspReport['violated-directive'] ?? null,
			'user_agent'          => $userAgent,
		]);

		$entry->save();
	}
}
