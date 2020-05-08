<?php 

class Master extends Controller
{


    public function __construct(){
        return $this->index();
    }

    public function index(){

        $this->setRequest();

        if($this->getRequest() !== false){


            $data = $this->getRequest();

            if(isset($data->action))
            {
                $response = false;
                switch($data->action)
                {
                    case 'Login';
                        if($this->verifyData()!==false)
                        {
                            return $this->logUser();
                        }else{
                            $response = false;
                        }
                    break;
                }
                echo json_encode($response);
            }

        }

    }

    private function verifyData()
    {
        $data = new stdClass();
        $data->api = 'verify';
        $data->action = 'Login';
        $data->params = $this->getRequest()->params;
        $res = json_decode(API_model::doAPI($data));

        foreach($res as $key=>$value)
        {
            if(empty($value)){
                return false;
            }
        }

        return true;
        
    }

    private function logUser()
    {
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = $this->getRequest()->connection;
        $data->procedure = $this->getRequest()->procedure;
        $data->params = $this->getRequest()->params;

        $res = json_decode(API_model::doAPI($data));
        $resObj = $res[0];

        if($resObj->code !== '6000'){
            echo json_encode($resObj);
            die;
        }

        if($resObj->UserStatus !== '3'){
            echo json_encode($resObj);
            die;
        }

        $res = $this->createToken($resObj);
        $res->UserID = $resObj->UserID;
        echo json_encode($res);
        die;
    }

    private function createToken($input)
    {
        error_log('prepare data to token response here: '.print_r($input, 1));         
        $data = new stdClass();
        $data->api = 'token';
        $data->action = 'Create';
        $data->UserId = $input->UserID;
        $data->projectId = $this->getRequest()->params->projectId;
        $res = json_decode(API_model::doAPI($data));
        return $res;
    }
}