<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Zaxbux\SecurityHeaders\Models\CSPLog;

class Log {
	public static function CSPReport($action, $cspBody, $originalData = null, $userAgent = null) {
		$cspReport = $cspBody['csp-report'];
		
		$entry = new CSPLog();
		$entry->fill([
			'action'              => $action,
			'original_data'       => $originalData,
			'blocked_uri'         => isset($cspReport['blocked-uri']) ? $cspReport['blocked-uri'] : null,
			'disposition'         => isset($cspReport['disposition']) ? $cspReport['disposition'] : null,
			'document_uri'        => isset($cspReport['document-uri']) ? $cspReport['document-uri'] : null,
			'effective_directive' => isset($cspReport['effective-directive']) ? $cspReport['effective-directive'] : null,
			'original_policy'     => isset($cspReport['original-policy']) ? $cspReport['original-policy'] : null,
			'referrer'            => isset($cspReport['referrer']) ? $cspReport['referrer'] : null,
			'script_sample'       => isset($cspReport['script-sample']) ? $cspReport['script-sample'] : null,
			'status_code'         => isset($cspReport['status-code']) ? $cspReport['status-code'] : null,
			'violated_directive'  => isset($cspReport['violated-directive']) ? $cspReport['violated-directive'] : null,
			'user_agent'          => $userAgent,
		]);

		$entry->save();
	}
}