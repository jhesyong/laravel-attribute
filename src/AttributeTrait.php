<?php

namespace Jhesyong\Attribute;

trait AttributeTrait
{
	protected $attributeData = null;

	/**
	 * Return a value => label array
	 * @return array
	 */
	protected abstract function getOptions();

	protected function getData()
	{
		if ($this->attributeData === null) {
			$this->attributeData = $this->getOptions();
		}

		return $this->attributeData;
	}

	public function hasKey($key)
	{
		$data = $this->getData();

		return array_key_exists($key, $data);
	}

	public function label($key)
	{
		$data = $this->getData();

		if ( ! array_key_exists($key, $data)) {
			return null;
		}

		return $data[$key];
	}

	public function hashArray($withEmpty = false)
	{
		$data = $this->getData();

		if ($withEmpty) {
			$data = ['' => 'Please Select'] + $data;
		}

		return $data;
	}

	public function pairArray($withEmpty = false)
	{
		$data = $this->getData();

		if ($withEmpty) {
			$data = ['' => 'Please Select'] + $data;
		}

		return array_map(
			function($label, $value) { return ['label' => $label, 'value' => $value]; },
			$data,
			array_keys($data)
		);
	}

	public function keys()
	{
		$data = $this->getData();

		return array_keys($data);
	}
}