
<?php

class ApiService {
    public $returnedData;

    function  __construct() {
    }

    //If any API calls fails, log it, and show on the user interface for every 5 retrials

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
         
         if($appType == 'fleetio') {
            $headers = [
               'Authorization: Token 59f181f3e1f421c05cc96660134e9b7d1e008520',
               'Account-Token: a24faf79f8',
               'Accept: */*',
               'Accept-Encoding: gzip, deflate',
               'content-type: application/json'
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
         }
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         if(!IN_SERVER && ($appType != 'fleetio')) {
            $proxy = '159.65.88.174:12455';
            $credentials = 'ecagon:sqskynet123uid@';
            curl_setopt($curl, CURLOPT_PROXY, $proxy); // $proxy is ip of proxy server
            curl_setopt($curl, CURLOPT_PROXYUSERPWD,$credentials);
         }
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
         curl_setopt($curl, CURLOPT_TIMEOUT, 10);
         $this->returnedData = curl_exec($curl);
         if ($this->returnedData === false || $this->returnedData == null) 
            $this->returnedData = curl_error($curl);
      } catch(Exception $exception) {
         echo "Exception Occured: ".$exception."<br/>";
      }

      if(!$this->returnedData) {
            die("Connection Failure");
      }
      curl_close($curl);
      return $this->returnedData;
   }
}