<?php

class TestRestApi extends RestApiBase {
    public function get()
    {
        $this->setResponce(200, array('message'=>'GET'));
    }

    public function post()
    {
        $this->setResponce(200, array('message'=>'POST'));
    }

    public function put()
    {
        $this->setResponce(200, array('message'=>'PUT'));
    }

    public function delete()
    {
        $this->setResponce(200, array('message'=>'DELETE'));
    }

}

?>