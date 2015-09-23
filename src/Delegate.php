<?php

namespace Jhesyong\Attribute;

class Delegate
{
	protected $registrar;

	protected $attributeContext = null;

	public function __construct(Registrar $registrar)
	{
		$this->registrar = $registrar;
	}

	public function context($context)
	{
		$this->attributeContext = $context;

		return $this;
	}

	public function __call($name, $arguments)
	{
		// Registrar method
		if (method_exists($this->registrar, $name))
		{
			return call_user_func_array([$this->registrar, $name], $arguments);
		}

		// Attribute method
		$className = $arguments[0];

		if ($this->registrar->hasAttribute($className))
		{
			$attribute = $this->registrar->getAttribute($className);

			if ($this->attributeContext !== null) {
				$attribute->setContext($this->attributeContext);
				$this->attributeContext = null;
			}

			return call_user_func_array([$attribute, $name], array_slice($arguments, 1));
		}

		throw new \InvalidArgumentException("Unknown attribute {$className}");
	}
}
