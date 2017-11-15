<?php
include_once("Form.php");

class Form_Category extends Form
{
	public function __construct($data = array())
	{
		parent::__construct($data = array());
	}

	// Form checking
	public function checkErrors($arrayPOST)
	{
		$errors = [];
		$fieldname = array("name");

		foreach ($fieldname as $value) {
			if ($arrayPOST[$value] == "")
			{
				$errors[$value . "c"] = "Field required";
			}
		}
	return $errors;
	}
}

?>