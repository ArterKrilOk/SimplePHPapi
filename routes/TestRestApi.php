<?php

class TestRestApi extends RestApiBase {
    public function view()
    {
        $this->setResponce(200, array('message'=>'View'));
    }

    public function create()
    {
        $this->setResponce(200, array('message'=>'Create'));
    }

    public function update()
    {
        $this->setResponce(200, array('message'=>'Update'));
    }

    public function remove()
    {
        $this->setResponce(200, array('message'=>'remove'));
    }

    public function index($index)
    {
        $this->setResponce(200, array('message'=>'index: '.$index));
    }

    public function verb($verb)
    {
        $this->setResponce(200, array('message'=>'verb: '.$verb));
    }
}

?>