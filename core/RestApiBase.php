<?php

include_once "ApiBase.php";

abstract class RestApiBase extends ApiBase {

    //TODO: Rest APi base class

    public function get()
    {
        if(count($this->urlParameters) == 0)
        {
            $this->view();
            return;
        }

        if(is_numeric($this->urlParameters[0]))
        {
            $this->index(intval($this->urlParameters[0]));
        } else {
            $this->verb($this->urlParameters[0]);
        }
    }

    public function post()
    {
        $this->create();
    }

    public function put()
    {
        $this->update();
    }

    public function delete()
    {
        $this->remove();
    }

    public function index($index)   {   }    // GET       host/api/{name}/{index}
    public function verb($verb)     {   }    // GET       host/api/{name}/{verb}
    public function view()          {   }    // GET       host/api/{name}
    public function create()        {   }    // POST      host/api/{name}
    public function update()        {   }    // PUT       host/api/{name}
    public function remove()        {   }    // DELETE    host/api/{name}
}




?>