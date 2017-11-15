<?php
include_once("Form.php");

class Form_Product extends Form
{
	public function __construct($data = array())
	{
		parent::__construct($data = array());
	}

	// Form checking
	public function checkErrors($arrayPOST)
	{
		$errors = [];
		$fieldname = array("name", "price", "category_id");

		foreach ($fieldname as $value) {
			if ($arrayPOST[$value] == "")
			{
				$errors[$value] = "Field required";
			}
		}
	return $errors;
	}
}

?>