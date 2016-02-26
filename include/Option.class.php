<?php

class Option
{
	public $label;
	public $desc;
	public $values = array();
	public $texts = array();

	public function __construct($lbl, $dsc)
	{
		$this->label = $lbl;
		$this->desc = $dsc;
	}
	
	public function addValue($key, $value, $text)
	{
		$this->values[$key] = $value;
		$this->texts[$key] = $text;
	}
}
