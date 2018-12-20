
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
         // else {
         //    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
         //       'authority: sdk.galooli-systems.com',
         //       'Content-Type: application/json',
         //       'accept-encoding: gzip, deflate',
         //       'pragma: no-cache',
         //       'cache-control: no-cache',
         //       'upgrade-insecure-requests: 1',
         //       'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
         //       'accept-language: en-US,en;q=0.9',
         //    ));
         // }
         // curl "https://sdk.galooli-systems.com/galooliSDKService.svc/json/Assets_Report?userName=matrixvtrack^&password=matv123?^&requestedPropertiesStr=ac.status,u.id,u.name,ac.latitude,ac.longitude,ac.distance_^\[km^\],ac.engine_hours_^\[num^\]^&lastGmtUpdateTime=2000-01-01^%^2000:00:00" -H "authority: sdk.galooli-systems.com" -H "pragma: no-cache" -H "cache-control: no-cache" -H "upgrade-insecure-requests: 1" -H "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36" -H "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8" -H "accept-encoding: gzip, deflate, br" -H "accept-language: en-US,en;q=0.9" -H "cookie: _ga=GA1.2.1649399925.1542783131" --compressed
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