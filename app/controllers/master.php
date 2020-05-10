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
            error_log('show data: '.print_r($data, 1));
            if(isset($data->action))
            {
                $response = false;
                switch($data->action)
                {
                    case 'Register';

                        $res = ApiModel::getEmailTemplate($data);
                        if($res->code !== '6000'){
                            $response = new stdClass();
                            $response->code = '6016';
                            $response->message = 'Missconfiguration';
                            echo json_decode($response);
                            die;
                        }
                        $email = $this->prepareDataForEmail($res->html, $data);
                        $resp = $this->sendEmail($email);
                        echo json_encode($resp);

                    break;
                    case 'Reset Password';
                        error_log('reset password data: '.print_r($data, 1));
                        $res = ApiModel::getEmailTemplate($data);
                        if($res->code !== '6000'){
                            $response = new stdClass();
                            $response->code = '6016';
                            $response->message = 'Missconfiguration';
                            echo json_decode($response);
                            die;
                        }
                        $email = $this->prepareDataForEmail($res->html, $data);
                        $resp = $this->sendEmail($email);
                        echo json_encode($resp);
                    break;
                }
            }

        }

    }

    private function sendEmail($input)
    {

        // $to      = $input->userEmail;
        // $subject = $input->action;
        // $message = $input->template;
        // $headers = 'From: ' . $input->projectEmail . "\r\n" .
        //     'Reply-To: '. $input->projectEmail  . "\r\n" .
        //     'X-Mailer: PHP/' . phpversion();
    
        // mail($to, $subject, $message, $headers);
    
        $res = array();
        $res['code'] = '6000';
        $res['message'] = 'Success';
        $res['html'] = $input->template;

        return $res;
    }

    private function prepareDataForEmail($template, $input)
    {

        $dataForEmail = ApiModel::getDataForEmail($input);

        $template = str_replace('{projectUrl}', $dataForEmail->ProjectUrl, $template);
        $template = str_replace('{token}', $dataForEmail->Token, $template);

        $data = new stdClass();
        $data->action = $input->action;
        $data->userEmail = $dataForEmail->UserEmail;
        $data->token = $dataForEmail->Token;
        $data->template = $template;
        $data->projectUrl = $dataForEmail->ProjectUrl;
        $data->projectEmail = $dataForEmail->ProjectEmail;

        return $data;

    }

}