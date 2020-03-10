<?php

namespace Zaxbux\SecurityHeaders\Rules;

use Illuminate\Contracts\Validation\Rule;

class CSPSource implements Rule {
	
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        foreach ($value as $source) {
			switch ($source['_group']) {
				case 'host':

				case 'scheme':

				case 'hash':
				
				default:
					return false;
			}
		}
    }

    /**
     * Validation callback method.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array  $params
     * @return bool
     */
    public function validate($attribute, $value, $params) {
        return $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return 'The :attribute must be a valid hash.';
    }
}