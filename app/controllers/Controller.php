<?php

class Controller
{

    private $request;
    private $client;
    private $project;
    private $user;
    private $password;
    private $sendData;
    private $api;

    // section setter
    public function setRequest()
    {
        $this->request = json_decode(file_get_contents('php://input'));
    }

    public function setData(){
        $this->sendData = new stdClass();
        $this->sendData->request = json_encode($this->request);
    }

    public function setClient($input)
    {
        $this->client = $input; 
    }

    public function setProject($input)
    {
        $this->project = $input;
    }

    public function setUser($input)
    {
        $this->user = $input;
    }

    public function setPassword($input)
    {
        $this->password = $input;
    }



    // section getter

    public function getRequest()
    {
        return $this->request;
    }

    public function getData()
    {
        return $this->sendData;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getProject()
    {
        return $this->project;

    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }
}