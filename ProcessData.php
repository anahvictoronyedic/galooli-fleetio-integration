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
        $this->apiURL = "";
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'galooli');
        $this->errors = $response['response']['errors'];
        if(isset($this->errors)) {
            return $this->errors;
        }
        $this->response = json_decode($get_data, true);
        $data = $response['response']['data'][0];
        return $this->returnedData;
    }

    function pushDataToFeetio($data_array)
    { 
        $this->apiURL = "";
        $get_data = $this->_apiService->callAPI('POST', $this->apiURL, json_encode($data_array), 'fleetio');
        $this->errors = $response['response']['errors'];
        if(isset($this->errors)) {
            return $this->errors;
        }
        $this->response = json_decode($get_data, true);
        $data = $response['response']['data'][0];
        return $this->returnedData;
    }

}
