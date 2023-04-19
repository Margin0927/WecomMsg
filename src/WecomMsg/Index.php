<?php

namespace WecomMsg;

class Index
{
    private $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=';

    private $key = '';

   	public function __construct($key)
   	{
   			$this->key = $key;
   	}

    public function sendText($content)
    {
        $url = $this->url.$this->key;
        $data = ['msgtype' => 'text', 'text' => ['content' => $content]];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $this->httpPost($url,[],$data);
    }



    function httpPost($url, $headers = [], $params = null, $time_out = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // setting the POST FIELD to curl
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);
        if ($errorNo && $httpCode != '200') {
            return false;
        }
        //close the connection
        curl_close($ch);
        return $response;
    }





}