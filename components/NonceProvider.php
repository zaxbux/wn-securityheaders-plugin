<?php

namespace Zaxbux\SecurityHeaders\Components;

use Request;
use Cms\Classes\ComponentBase;

class NonceProvider extends ComponentBase {

	const SHORT_NAME = 'CSPNonce';

	// `string` property
	public $nonce;

	public function componentDetails() {
        return [
            'name'        => 'zaxbux.securityheaders::lang.components.CSPNonce.name',
            'description' => 'zaxbux.securityheaders::lang.components.CSPNonce.description'
        ];
	}

	public function init() {
		$this->nonce = Request::get('csp-nonce');
	}

	public function onRun() {
		$this->prepareVars();
	}

	public function prepareVars() {
		$this->page['csp_nonce'] = $this->nonce;
	}
}