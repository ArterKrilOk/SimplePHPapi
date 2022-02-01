<?php 

include_once "Api.php";
include_once "db/DBWorker.php";
include_once "core/ApiBase.php";
include_once "core/RestApiBase.php";

include_once "models/DBModel.php";
foreach (glob("models/*.php") as $filename)
{
    include_once $filename;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Region, Token');
header('Content-Type: application/json; charset=UTF-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization, Region, Token");
    header("HTTP/1.1 200 OK");
    die();
}

$url = (isset($_GET['q'])) ? $_GET['q'] : '';
$url = rtrim($url, '/');

$api = new Api($method, $url);
$api->execute();
?>