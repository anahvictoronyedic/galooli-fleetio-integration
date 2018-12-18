
<?php

class ApiService {
    public $returnedData;
    private $apiToken;
    private $accountToken;
    private $contactId;

    function  __construct() {
      $this->apiToken = '';
      $this->accountToken = '';
      $this->contactId = '';
    }

    function callAPI($method, $url, $data, $appType){
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
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($appType == 'fleetio')
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: */*',
                'Authorization: Token {$apiToken}',
                'Account-Token: 1cfafff6e0',
                'Content-Type: application/json',
                'accept-encoding: gzip, deflate'
            ));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     
        // EXECUTE:
        $this->returnedData = curl_exec($curl);
        if(!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $this->returnedData;
   }
}