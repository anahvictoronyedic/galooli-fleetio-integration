
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
         $escapedURL = curl_escape($curl, $url);
         echo "<br/>URL: ".$url."<br/>";
         if (!$curl){
            die("Couldn't initialize a cURL handle");
         }
         curl_setopt($curl, CURLOPT_URL, $escapedURL);
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
         curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36');
         curl_setopt($curl, CURLOPT_COOKIE, "_ga=GA1.2.1649399925.1542783131");
         curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
         curl_setopt($curl, CURLOPT_VERBOSE, 1);
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
         var_dump($curl);
      
         // EXECUTE:
         $this->returnedData = curl_exec($curl);
         $info = curl_getinfo($curl);
         echo "Curl Info: ";
         var_dump($info);

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