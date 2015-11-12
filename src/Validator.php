<?php

namespace Jhesyong\Attribute;

class Validator
{
	protected $registrar;

	public function __construct(Registrar $registrar)
	{
		$this->registrar = $registrar;
	}

	public function validate($attribute, $value, $parameters)
	{
		$attribute = $this->registrar->getAttribute($parameters[0]);

		if (array_key_exists(1, $parameters)) {
			$attribute->setContext($parameters[1]);
		}

		foreach ((array) $value as $item) {
			if ( ! $attribute->hasKey($item)) {
				return false;
			}
		}

		return true;
	}
}
