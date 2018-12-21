
<?php

class ApiService {
    public $returnedData;
    public $apiToken;
    private $accountToken;
    private $contactId;

    function  __construct() {
      $this->apiToken = '';
      $this->accountToken = '';
      $this->contactId = '';
    }

    function callAPI($method, $url, $data, $appType){
       try {
         $curl = curl_init();
      
         switch ($method){
            case "POST":
               curl_setopt($curl, CURLOPT_POST, 1);
               if ($data)
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
               break;
            case "PUT":
               curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
               if ($data)
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
               break;
            default:
               if ($data)
                  $url = sprintf("%s?%s", $url, http_build_query($data));
         }
      
         // OPTIONS:
         // $escapedURL = curl_escape($curl, $url);
         // echo "<br/>URL: ".$url."<br/>";
         // if (!$curl){
         //    die("Couldn't initialize a cURL handle");
         // }
         // curl_setopt($curl, CURLOPT_URL, $escapedURL);
         // if ($appType == 'fleetio')
         // {
         //     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
         //         'Accept: */*',
         //         'Authorization: Token {$apiToken}',
         //         'Account-Token: 1cfafff6e0',
         //         'Content-Type: application/json',
         //         'accept-encoding: gzip, deflate'
         //     ));
         // } 
         $proxy = '159.65.88.174:12455';
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl, CURLOPT_PROXY, $proxy); // $proxy is ip of proxy server
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
         curl_setopt($curl, CURLOPT_TIMEOUT, 10);
         $httpCode = curl_getinfo($curl , CURLINFO_HTTP_CODE); // this results 0 every time
         echo "Curl info: ".$httpCode."<br/>";
         $this->returnedData = curl_exec($curl);
         if ($this->returnedData === false) $this->returnedData = curl_error($curl);
         echo "Returned Data: ".stripslashes($this->returnedData);
      } catch(Exception $exception) {
         echo "Exception Occured: ".$exception."<br/>";
      }
      
      echo "Returned Data: ".$this->returnedData;
      if(!$this->returnedData) {
            die("Connection Failure");
      }
      curl_close($curl);
      return $this->returnedData;
   }
}