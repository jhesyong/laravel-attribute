<?php

namespace Jhesyong\Attribute;

class Registrar {

	/**
	 * Attribute mapping
	 * @var array
	 */
	public $attributes = [];

	public function register($attribute, $name = null)
	{
		$name = $name ?: snake_case(class_basename($attribute));

		$this->attributes[$name] = $attribute;
	}

	public function hasAttribute($name)
	{
		return array_key_exists($name, $this->attributes);
	}

	public function getAttribute($name)
	{
		if ($this->hasAttribute($name)) return $this->attributes[$name];

		return null;
	}

}