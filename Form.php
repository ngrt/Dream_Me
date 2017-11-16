<?php 
abstract class Form
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
	public function input_text($name, $arrayPOST){
		$field = str_replace("_", " ", ucfirst($name));
		
		return $this->surround('
			<label for="'. $name .'">'.$field.'</label>
			<input type="text" id="' . $name . '" name="' . $name . '" value="' . $arrayPOST . '">
		');
	}

	public function input_password($name){
		$field = str_replace("_", " ", ucfirst($name));
		
		return $this->surround('
			<label for="'. $name .'">'.$field.'</label>
			<input type="password" id="' . $name . '" name="' . $name . '">
		');
	}

	public function input_checkbox($name, $arrayPOST){
		$field = str_replace("_", " ", ucfirst($name));

		if ($arrayPOST)
		{
			return $this->surround('
				<input type="checkbox" id="' . $name . '" name="' . $name . '"checked>
				<label for="'. $name .'">'.$field.'</label>
			');
		}
		else
		{
			return $this->surround('
				<input type="checkbox" id="' . $name . '" name="' . $name . '">
				<label for="'. $name .'">'.$field.'</label>
			');
		}
	}

	private function getValue($index, $arrayPOST)
	{
		return isset($arrayPOST[$index]) ? $arrayPOST[$index] : null;
	}


// Submit function with parameter string $type (ex: Submit, Register, etc...)
	public function submit($type){
		return $this->surround('
			<input class="waves-effect waves-light btn" type="submit" value="'.$type.'">
			');
	}

// abstract function to define in each Child
	abstract function checkErrors($arrayPOST);

}
?>