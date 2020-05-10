<?php
class ApiModel extends Controller
{
    public function doAPI($data)
    {
        $api = PREFIX.$data->api.DNS;
        unset($data->api);
        $postData = json_encode($data);
        // error_log('api: '.$api);
        // error_log('data: '.print_r($postData, 1));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::getHeader($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $res = curl_exec($ch);
        if(isset($res)){
            return $res; 
        }
        curl_close($ch);
    }

    public function getEmailTemplate($input)
    {
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'EMAIL';
        $data->procedure = __FUNCTION__;
        $data->params->action = $input->action;
        $data->params->projectId = $input->projectId;
        // error_log('sent to api database: '.print_r($data, 1));
        $res = self::responseObject(self::doAPI($data));
        // error_log('response from api database: '.print_r($res, 1));
        return $res[0];
    }

    public function getReplacement($input)
    {
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'CORE';
        $data->procedure = __FUNCTION__;
        $data->params->action = $input->action;
        $data->params->userId = $input->userId;
        $data->params->projectId = $input->projectId;

        $res = self::responseObject(self::doAPI($data));

        return $res[0];
    }

    public function getDataForEmail($input)
    {
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'EMAIL';
        $data->procedure = __FUNCTION__;
        $data->params->action = $input->action;
        $data->params->userId = $input->userId;
        $data->params->projectId = $input->projectId;

        $res = self::responseObject(self::doAPI($data));
        return $res[0];
    }


    public function responseObject($data)
    {
        $resObj = json_decode($data);
        return $resObj;
    }

    public function getHeader($data)
    {
        $signature = base64_encode(hash_hmac('sha256', $data, SIGNATURE, true));
        $header = array('Content-Type:application/json', 'APP-SECURITY-AUTH:'.$signature);
        // error_log('header: '.print_r($header, 1));
        return $header;
    }
}