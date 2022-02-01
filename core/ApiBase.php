<?php
abstract class ApiBase {

    protected $db;
    protected $url;
    protected $urlParameters;
    protected $parameters;
    protected $files;

    public $responce = array('code' => 500, 'data' => "500 Error");
    
    public function __construct($url = null, $urlParameters = null, $parameters = null, $files = null, $db = null)  
    {
        if(!isset($db))
            $this->db = DBWorker::getDefInstance();
        else
            $this->db = $db;

        if(isset($url))
            $this->url = $url;
        if(isset($urlParameters))
            $this->urlParameters = $urlParameters;
        if(isset($parameters))
            $this->parameters = $parameters;
        if(isset($files))
            $this->files = $files;
    }


    function __destruct()
    {
        if($this->db != null)
        {
            $this->db->closeConnection();
        }
    }

    // Request methods

    public function get()       {   }
    public function post()      {   }
    public function put()       {   }
    public function delete()    {   }

    protected function setResponce($code, $data)
    {
        $this->responce['code'] = $code;
        $this->responce['data'] = $data;
    }
}


?>