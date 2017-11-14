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
	protected function checkErrors($arrayPOST)
	{
		$errors = array();
		$fieldname = array("username", "password", "password_confirmation", "email");

		foreach ($fieldname as $value) {
			if (!isset($arrayPOST[$value]))
			{
				array_push($errors, $value);
				$errors[$value] = "Field required";
			}
		}

	// Check username length 
		if (strlen($arrayPOST["username"]) < 3 || strlen($arrayPOST["username"]) > 10)
		{
			array_push($errors, "username");
			$errors["username"] = "The username must be between 3 and 10 characters";
		}

	// Check email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
    		array_push($errors, "email");
			$errors["email"] = "This email is not valid";
    	}

	// Password checks
		if (strlen($_POST["password"]) > 2 && strlen($_POST["password"]) < 11)
		{
			if ($_POST["password_confirmation"] != $_POST["password"])
			{
				array_push($errors, "password_confirmation");
				$errors["password_confirmation"] = "Invalid password or password confirmation";	
			}
		}
		else
		{
			array_push($errors, "password_length");
			$errors["password_length"] = "The password must be between 3 and 10 characters";
		}
	return $errors;
	}
}
?>