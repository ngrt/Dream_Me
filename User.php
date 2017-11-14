<?php
class User
{
	private $bdd;
	private $username;
	private $password;
	private $email;
	private $admin;

	public function __construct($bdd, $username, $password, $email, $admin = 0)
	{
		$this->bdd = $bdd;
		$this->username = $username;
		$this->password = password_hash($password, PASSWORD_BCRYPT);
		$this->email = $email;
		$this->admin = $admin;
	}

	public function get_username()
	{
		return $this->username;
	}

	public function get_password()
	{
		return $this->password;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function get_admin()
	{
		return $this->admin;
	}

	public function checkExist()
	{
		$errors = [];

		// check username
		$sql = 'SELECT EXISTS (SELECT * FROM users WHERE username = :username) AS username_exists';
		$result = $this->bdd->prepare($sql);
		$data = array('username' => $this->username);

        $result->execute($data);

        $req = $result->fetch();

        if ($req["username_exists"])
        {
        	$errors["username"] = "This username already exists";
        }

		// check email
		$sql = 'SELECT EXISTS (SELECT * FROM users WHERE email = :email) AS email_exists';
		$result = $this->bdd->prepare($sql);
		$data = array('email' => $this->email);

        $result->execute($data);

        $req = $result->fetch();

        if ($req["email_exists"])
        {
        	$errors["email"] = "This email already exists";
        }
        return $errors;
	}

	public function subscription()
	{
		if (count($this->checkExist()) == 0)
		{
			$sql = 'INSERT INTO users (username, password, email, admin) VALUES (:username, :password, :email, :admin)';
			$result = $this->bdd->prepare($sql);

			$data = array(
                'username' => $this->username,
                'password' => $this->password,
                'email' => $this->email,
                'admin' => $this->admin
                );

			if ($result->execute($data))
			{
				return true;
			}
			else
			{
				return false;
			}	
		}
		else
		{
			return false;
		}

	}

	public function update($type, $new, $old)
	{

		$sql = 'UPDATE users SET '. $type . '= :new WHERE ' . $type .  '= :old';
		$result = $this->bdd->prepare($sql);

		$data = array(
            'new' => $new,
            'old' => $old,
            );

		if ($result->execute($data))
			{
				return true;
			}
			else
			{
				return false;
			}	
	}

	public function delete($type, $value)
	{
		$sql = 'DELETE FROM users WHERE ' . $type . '= :value';
		$result = $this->bdd->prepare($sql);

		$data = array(
            'value' => $value
            );

		if ($result->execute($data))
			{
				return true;
			}
			else
			{
				return false;
			}	
	}

	public function checkPassword($password)
	{
		$sql = 'SELECT password FROM users WHERE email=' . $this->email;
		$request = $this->bdd->query($sql);

		$data = $request->fetch();

		$password_hash = $data["password"];

		return password_verify($password, $password_hash);
	}


}
?>