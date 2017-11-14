<?php
class User
{
	public function select($bdd, $db , $array, ...$fields)
	{
		$request = $bdd->prepare($sql);
	}

	public function checkExist($bdd, $username, $email)
	{
		$errors = [];

		// check username
		$sql = 'SELECT EXISTS (SELECT * FROM users WHERE name = :username) AS username_exists';
		$result = $bdd->prepare($sql);
		$data = array('username' => $username);

        $result->execute($data);

        $req = $result->fetch();

        if ($req["username_exists"])
        {
        	$errors["username"] = "This username already exists";
        }

		// check email
		$sql = 'SELECT EXISTS (SELECT * FROM users WHERE name = :email) AS email_exists';
		$result = $bdd->prepare($sql);
		$data = array('email' => $email);

        $result->execute($data);

        $req = $result->fetch();

        if ($req["email_exists"])
        {
        	$errors["email"] = "This email already exists";
        }

        return $errors;

	}

	public function subscription($bdd, $username, $password, $email, $admin = 0)
	{
		if (count($this->checkExist($bdd, $username, $email)) == 0)
		{
			$sql = 'INSERT INTO users (username, password, email, admin) VALUES (:username, :password, :email, :admin)';
			$result = $bdd->prepare($sql);

			$data = array(
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'admin' => $admin
                );

			if ($result->execute($data))
			{
				return true:
			}
			else
			{
				return false
			}	
		}
		else
		{
			return false;
		}

	}

	public function update()
	{

	}

	public function delete()
	{

	}


}
?>