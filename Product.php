<?php
class Product
{
	private $bdd;
	private $name;
	private $price;
	private $category_id;

	public function __construct($bdd, $name, $price, $category_id)
	{
		$this->bdd = $bdd;
		$this->name = $name;
		$this->price = $price;
		$this->category_id = $category_id;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function get_category_id()
	{
		return $this->category_id;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function set_price($price)
	{
		$this->price = $price;
	}

	public function set_category_id($category_id)
	{
		$this->category_id = $category_id;
	}


	public function checkExist()
	{
		$errors = [];
		// check name
		$sql = 'SELECT EXISTS (SELECT * FROM products WHERE name = :name) AS name_exists';
		$result = $this->bdd->prepare($sql);
		$data = array('name' => $this->name);

        $result->execute($data);

        $req = $result->fetch();

        if ($req["name_exists"])
        {
        	$errors["name"] = "This name already exists";
        }

        return $errors;
	}

	public function insert()
	{
		if (count($this->checkExist()) == 0)
		{
			$sql = 'INSERT INTO products (name, price, category_id) VALUES (:name, :price, :category_id)';
			$result = $this->bdd->prepare($sql);

			$data = array(
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id
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

	public function update($id)
	{
		$sql = 'UPDATE products SET name = :name, price = :price, category_id = :category_id WHERE id =' . $id;
		$result = $this->bdd->prepare($sql);

		$data = array(
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->category_id
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

	public function delete()
	{
		$sql = 'DELETE FROM products WHERE name = :name';
		$result = $this->bdd->prepare($sql);

		$data = array(
            'name' => $this->name
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
}
?>