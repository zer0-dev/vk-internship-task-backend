<?php

namespace Task\Model;

class Product implements \JsonSerializable{

    private int $id;
    private string $name;
    private string $email;
    private int $count;
    private float $price;

    public function __construct(int $id, string $name, string $email, int $count, float $price){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->count = $count;
        $this->price = $price;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getCount(): int{
        return $this->count;
    }

    public function getPrice(): float{
        return $this->price;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setName(string $name): void{
        $this->name = $name;
    }

    public function setEmail(string $email): void{
        $this->email = $email;
    }

    public function setCount(int $count): void{
        $this->count = $count;
    }

    public function setPrice(float $price): void{
        $this->price = $price;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }

    public static function validateId(mixed $s): bool{
        return is_int($s) || is_numeric($s);
    }

    public static function validateName(mixed $s): bool{
        return strlen(trim($s)) != 0;
    }

    public static function validateEmail(mixed $s): bool{
        return preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $s);
    }

    public static function validateCount(mixed $s): bool{
        return is_int($s);
    }

    public static function validatePrice(mixed $s): bool{
        return is_float($s) || is_int($s);
    }

    public static function makeFromArray(array $arr): Product{
        return new Product($arr['id'], $arr['name'], $arr['email'], $arr['count'], $arr['price']);
    }
}