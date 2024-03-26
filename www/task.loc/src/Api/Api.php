<?php

namespace Task\Api;

use Task\Database\Database;
use Task\Config;
use Task\Model\Product;
use Task\Model\Error;

class Api {

  private static Database $db;

  public static function init(): void {
    Api::$db = new Database(Config::$db_cred);
  }

  public static function validate(array $p): string{
    if(!Product::validateId($p['id'])) return "ID must be integer";
    if(!Product::validateName($p['name'])) return "Name must not be empty";
    if(!Product::validateEmail($p['email'])) return "E-mail is not valid";
    if(!Product::validateCount($p['count'])) return "Count must be integer";
    if(!Product::validatePrice($p['price'])) return "Price must be float";
    return "ok";
  }

  public static function handleRequest(string $path, array $data): mixed{
    switch($path){
      case '/products/getAll':
        return Api::$db->getAllProducts();
      case '/products/get':
        if(!empty($data['id']) && Product::validateId($data['id'])){
          return Api::$db->getProduct(intval($data['id']));
        } else{
          return new Error(2, "Incorrect ID passed");
        }
      case '/products/add':
        $v = Api::validate($data['product']);
        if($v == "ok"){
          $p = Product::makeFromArray($data['product']);
          return Api::$db->addProduct($p);
        } else{
          return new Error(2, $v);
        }
      case '/products/edit':
        $v = Api::validate($data['product']);
        if($v == "ok"){
          $p = Product::makeFromArray($data['product']);
          return Api::$db->editProduct($p);
        } else{
          return new Error(2, $v);
        }
      case '/products/delete':
        if(!empty($data['id']) && Product::validateId($data['id'])){
          return Api::$db->deleteProduct(intval($data['id']));
        } else{
          return new Error(2, "Incorrect ID passed");
        }
      default:
        return new Error(3, "Unknown method passed");
    }
  }


}
