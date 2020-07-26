<?php

namespace Zaxbux\SecurityHeaders\Classes;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NonceGeneratorMiddleware {
	/**
	 * Generate a cryptographic nonce for the CSP header
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$nonce = \base64_encode(random_bytes(16)); // Must be base64 - https://www.w3.org/TR/CSP2/#nonce_value

		$request->attributes->add(['csp-nonce' => $nonce]);

		return $next($request);
	}
}