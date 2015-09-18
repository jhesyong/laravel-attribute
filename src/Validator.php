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
		$className = $parameters[0];

		return $this->registrar->getAttribute($className)->hasKey($value);
	}
}