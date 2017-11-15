<?php 

include_once("Form.php");

class Form_User extends Form
{
	public function __construct($data = array())
	{
		parent::__construct($data = array());
	}

// Form checking
	public function checkErrors($arrayPOST)
	{
		$errors = [];
		$fieldname = array("username", "password", "password_confirmation", "email");

		foreach ($fieldname as $value) {
			if (!isset($arrayPOST[$value]))
			{
				$errors[$value] = "Field required";
			}
		}

	// Check username length 
		if (strlen($arrayPOST["username"]) < 3 || strlen($arrayPOST["username"]) > 10)
		{
			$errors["username"] = "The username must be between 3 and 10 characters";
		}

	// Check email format
		if (!filter_var($arrayPOST["email"], FILTER_VALIDATE_EMAIL)) 
		{
			$errors["email"] = "This email is not valid\n";
    	}

	// Password checks
		if (strlen($arrayPOST["password"]) > 2 && strlen($arrayPOST["password"]) < 11)
		{
			if ($arrayPOST["password_confirmation"] != $arrayPOST["password"])
			{
				$errors["password_confirmation"] = "Invalid password or password confirmation";	
			}
		}
		else
		{
			$errors["password"] = "The password must be between 3 and 10 characters";
		}
	return $errors;
	}

	public function checkPassUpdateErrors($arrayPOST)
	{
		$errors = [];

		if ((strlen($arrayPOST["new_password_confirmation"]) > 2 && strlen($arrayPOST["new_password_confirmation"]) < 11) || (strlen($arrayPOST["new_password"]) > 2 && strlen($arrayPOST["new_password"]) < 11))
		{
			if ($arrayPOST["new_password"] != $arrayPOST["new_password_confirmation"])
			{
				$errors["new_password_confirmation"] = "Invalid password or password confirmation";	
			}
		}
		else
		{
			$errors["password_syntax"] = "The password must be between 3 and 10 characters";
		}
	return $errors;
	}

	public function checkMailUpdateErrors($arrayPOST)
	{
		$errors = [];

		if (!filter_var($arrayPOST["new_email"], FILTER_VALIDATE_EMAIL)) 
		{
			$errors["new_email"] = "This email is not valid\n";
    	}

		return $errors;
	}
}
?>