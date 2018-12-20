<?php

require_once "ApiService.php";

class ProcessData {
    public $returnedData;
    public $apiURL;
    public $_apiService;
    public $response;
    public $errors;

    function  __construct() {
        $this->_apiService = new ApiService();
        $this->_apiService->apiToken = "";
    }

    function pullDataFromGalooli()
    {
        echo "Pulling Data from galooli...<br/>";;
        $this->apiURL = "https://sdk.galooli-systems.com/galooliSDKService.svc/json/Assets_Report?userName=matrixvtrack&password=matv123?&requestedPropertiesStr=ac.status,u.id,u.name,ac.latitude,ac.longitude,ac.distance_[km],ac.engine_hours_[num]&lastGmtUpdateTime=2000-01-01 2000:00:00";
        
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'galooli');
        $response = json_decode($get_data, true);
        echo 'Response: '.$get_data."<br/>";;
        $this->errors = $response['pageIndex'];
        if(isset($this->errors)) {
            return $this->errors;
        }
        $this->response = json_decode($get_data, true);
        $this->returnedData = $response['items'][0];
        return $this->returnedData;
    }

    

    function pushDataToFeetio($data_array)
    { 
        $this->apiURL = "";
        $get_data = $this->_apiService->callAPI('POST', $this->apiURL, json_encode($data_array), 'fleetio');
        $response = json_decode($make_call, true);
        $this->errors = $response['response']['errors'];
        if(isset($this->errors)) {
            return $this->errors;
        }
        $this->response = json_decode($get_data, true);
        $data = $response['response']['data'][0];
        return $this->returnedData;
    }

}
