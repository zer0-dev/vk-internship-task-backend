<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

use Task\Api\Api;

require 'vendor/autoload.php';

Api::init();
$data = ($_SERVER['REQUEST_METHOD'] == 'GET') ? $_GET : json_decode(file_get_contents("php://input"), true);
echo json_encode(Api::handleRequest(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $data));