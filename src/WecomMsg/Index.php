<?php
namespace Margin\WecomMsg;
class Index
{
    
    private $companyId = '';

    private $agentId = '';
    private $secret = '';

   	public function __construct($companyId,$agentId,$secret)
   	{
   			$this->companyId = $companyId;
   			$this->agentId = $agentId;
   			$this->secret = $secret;

   	}

    private function getAccessToken()
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->companyId."&corpsecret=".$this->secret;
        $res = $this->httpPost($url);
        $res = json_decode($res,true);
        return $res['access_token'];
    }

    public function sendText($content,$userIds)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$this->getAccessToken();
        $data = [
            "touser" => $userIds,
            "msgtype" => "text",
            "agentid" => $this->agentId,
            "text" => [
                "content" => $content
            ],
            "safe" => 0
        ];
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