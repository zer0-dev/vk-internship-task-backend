<?php
namespace Task\Database;

use Task\Config;
use Task\Model\Product;
use Task\Model\Error;
use Mysqli;

class Database{

    private $db;

    public function __construct($db_cred){
        $this->db = new mysqli($db_cred['host'], $db_cred['user'], $db_cred['password'], $db_cred['db']);
    }

    public function getAllProducts(): mixed{
        $res = [];
		$prep = $this->db->prepare("SELECT * FROM products");
		$prep->execute();
		$req = $prep->get_result();
		
		if(!empty($this->db->error)){
            return new Error(1, $this->db->error);
		}
		
        while($row = $req->fetch_assoc()){
            $res[] = new Product($row['id'], $row['name'], $row['email'], $row['count'], $row['price']);
        }

        if(count($res) == 0){
            return new Error(2, 'No products');
        }
		return $res;
    }

    public function getProduct(int $id): mixed{
		$prep = $this->db->prepare("SELECT * FROM products WHERE id = ?");
		$prep->bind_param('i', $id);
		$prep->execute();
		$req = $prep->get_result();
		
		if(!empty($this->db->error)){
            return new Error(1, $this->db->error);
		}
		
		$row = $req->fetch_assoc();
        if(is_null($row)){
            return new Error(2, 'Product not found');
        }
		return new Product($row['id'], $row['name'], $row['email'], $row['count'], $row['price']);
    }

    public function addProduct(Product $p): mixed{
        $prep = $this->db->prepare("INSERT INTO products (name, email, count, price) VALUES (?, ?, ?, ?)");
        $name = $p->getName();
        $email = $p->getEmail();
        $count = $p->getCount();
        $price = $p->getPrice();
		$prep->bind_param('ssid', $name, $email, $count, $price);
		$prep->execute();
		$req = $prep->get_result();
		
		if(!empty($this->db->error)){
			return new Error(1, $this->db->error);
		}
        $p->setId($this->db->insert_id);
		
		return $p;
    }

    public function editProduct(Product $p): mixed{
        $prep = $this->db->prepare("UPDATE products SET name = ?, email = ?, count = ?, price = ? WHERE id = ?");
        $id = $p->getId();
        $name = $p->getName();
        $email = $p->getEmail();
        $count = $p->getCount();
        $price = $p->getPrice();
		$prep->bind_param('ssidi', $name, $email, $count, $price, $id);
		$prep->execute();
		$req = $prep->get_result();
		
		if(!empty($this->db->error)){
			return new Error(1, $this->db->error);
		}

        if($this->db->affected_rows == 0){
            return new Error(2, 'Product not found');
        }
		
		return $p;
    }

    public function deleteProduct(int $id): mixed{
        $prep = $this->db->prepare("DELETE FROM products WHERE id = ?");
		$prep->bind_param('i', $id);
		$prep->execute();
		$req = $prep->get_result();
		
		if(!empty($this->db->error)){
            return new Error(1, $this->db->error);
		}
		
        if($this->db->affected_rows == 0){
            return new Error(2, 'Product not found');
        }
		return ['status' => true];
    }
}