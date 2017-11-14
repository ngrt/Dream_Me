<?php 
class Form
{
	private $data;
	public $surround = 'p';

// Form settings
	public function __construct($data = array()){
		$this->data  = $data;
	}

	private function surround($html){
		return "<{$this->surround}>{$html}</{$this->surround}>";
	}
// Different types of input
	public function input_text($name){
		$field = ucfirst($name);
		
		return $this->surround('
			<label for="'. $name .'">'.$field.'</label>
			<input type="text" id="' . $name . '" name="' . $name . '">
			');
	}
	public function input_password($name){
		$field = ucfirst($name);
		
		return $this->surround('
			<label for="'. $name .'">'.$field.'</label>
			<input type="password" id="' . $name . '" name="' . $name . '">
			');
	}


// Submit function with parameter string $type (ex: Submit, Register, etc...)
	public function submit($type){
		return $this->surround('
			<input type="submit" value="'.$type.'">');
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
}
?>