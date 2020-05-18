<?php

namespace Zaxbux\SecurityHeaders\Classes;

class HttpHeader {
	private $name;
	private $value;

	public function __construct(string $name = null, string $value = null) {
		$this->name  = $name;
		$this->value = trim($value);
	}

	/**
	 * Get header name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get header value
	 * 
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Set header name
	 * 
	 * @param string $name
	 * @return HttpHeader
	 */
	public function setName(string $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Set header value
	 * 
	 * @param string $value
	 * @return HttpHeader
	 */
	public function setValue(string $value) {
		$this->value = trim($value);

		return $this;
	}
}
