<?php
include_once "core/ApiBase.php";


class Api {
    const API_NAMES = ['TestRest', 'Test'];

    private $method;
    private $url;
    private $urlParameters;
    private $apiName;
    private $parameters;

    public function __construct($method, $url)
    {
        $this->method = $method;
        $this->url = $url;
        $this->apiName = Api::getApiName($url);
        $this->urlParameters = Api::getUrlParameters($url);
        $this->parameters = Api::getFormData($method);
    }


    public function execute()
    {
        if(!in_array($this->apiName, Api::API_NAMES))
        {
            $this->sendResponse(array('code' => 404, 'data' => 'Error 404, Not Found'));
            return;
        }

        require_once __DIR__."/routes/".$this->apiName."Api.php";
        
        //TODO: Files  
        $apiClassName = $this->apiName."Api";

        $apiClass = new $apiClassName($this->url, $this->urlParameters, $this->parameters, null);
        
        $classMethod = strtolower($this->method);       // Get Api Method Name

        $apiClass->$classMethod();                      // Run Api Method

        $this->sendResponse($apiClass->responce);       // Send Responce
    }

    private function sendResponse($responce)
    {
        header("HTTP/1.1 ".$responce['code']." ".Api::requestStatus($responce['code']));
        // echo msgpack_pack($responce['data']);
        echo json_encode($responce['data']);
    }

    private static function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    private static function getApiName($url)
    {
        $urls = explode('/', $url);

        return $urls[0];
    }

    private static function getUrlParameters($url)
    {
        $urls = explode('/', $url);

        return array_slice($urls, 1);
    }

    private static function getFormData($method) {
        // GET and POST as is
        if ($method === 'GET') return $_GET;
        if ($method === 'POST') return $_POST;
     
        // Parse PUT, PATCH, DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));
     
        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }
     
        return $data;
    }
}

?>