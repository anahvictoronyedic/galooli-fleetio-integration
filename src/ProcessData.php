<?php

require_once "ApiService.php";
require_once 'Database.php';

class ProcessData {
    public $returnedData;
    public $apiURL;
    public $_apiService;
    public $response;
    public $errors;
    private $odometerFromGalooli;
    private $currentDateTime;
    private $currentModifiedDateTime;
    private $galooliData;
    private $fleetioData;

    function  __construct() {
        $this->_apiService = new ApiService();
        $this->_apiService->apiToken = "";
    }

    function pullDataFromGalooli($isInitialization)
    {
        $lastPullTime = '';
        //get last update time
        $query = "SELECT value from configuration where name = 'last_gmt_update_time'";
        $tableRow = Database::getSingleRow($query);
        $lastPullTime = $tableRow["value"];
        echo "lastPullTime : ".$lastPullTime;
        $this->apiURL = "https://sdk.galooli-systems.com/galooliSDKService.svc/json/Assets_Report?userName=matrixvtrack&password=matv123?&requestedPropertiesStr=u.id,u.name,ac.status,ac.latitude,ac.longitude,ac.distance_[km],ac.engine_hours_[num],ac.main_fuel_tank_level_[liter]&lastGmtUpdateTime=".urlencode($lastPullTime);
         
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'galooli');
        $this->currentDateTime = date("Y-m-d h:i:s");
        $this->returnedData = json_decode($get_data, true);

        // echo "<br><br>";
        // var_dump($this->returnedData['CommonResult']['DataSet']['0']);
        echo "<br><br>";
        if($this->returnedData != null) {
            //update last update time
            $query = "UPDATE configuration SET value='".$this->currentDateTime."' where name = 'last_gmt_update_time'";
            if (Database::updateOrInsert($query)) {
                echo "Record updated successfully<br>";
            } else {
                echo "Error updating record: " . mysqli_error($conn)."<br/>";
            }
            foreach($this->returnedData['CommonResult']['DataSet'] as $returnedData) {
                echo "<br>";
                echo $returnedData['0']. ' ' . $returnedData['1'] . ' ' . $returnedData['2']. ' ' . $returnedData['3'] .
                ' ' . $returnedData['4'] . ' ' . $returnedData['5']. ' ' . $returnedData['6']. ' ' . $returnedData['7'];
                if ($isInitialization) {
                    $updateRecordQuery = "INSERT INTO pull_report(unit_id, unit_name, active_status, latitude, longitude, distance, engine_hours, fuel_report, created_at) 
                            VALUES('".$returnedData['0']."','".$returnedData['1']."','".$returnedData['2']."','".$returnedData['3']."'
                            ,'".$returnedData['4']."','".$returnedData['5']."','".$returnedData['6']."','".$returnedData['7']."', NOW())";
                
                } else {
                    $updateRecordQuery = "UPDATE pull_report SET active_status='".$returnedData['2']."', latitude = '".$returnedData['3']."', 
                        longitude = '".$returnedData['4']."', distance = '".$returnedData['5']."', 
                        engine_hours = '".$returnedData['6']."', fuel_report = '".$returnedData['7']."', modified_at = NOW() where unit_id = '".$returnedData['0']."'";
                }
                if (Database::updateOrInsert($updateRecordQuery)) {
                    echo "Pulled Data updated successfully<br>";
                } else {
                    echo "Error updating record: " . mysqli_error($conn)."<br/>";
                }
            }
        }
        
        return $this->returnedData;
    }

    function pullDataFromFleetio()
    {
        $this->apiURL = "https://secure.fleetio.com/api/v1/vehicles";
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'fleetio');
        var_dump($get_data);
    }

    /*
    first pull data from galooli every ten minutes, since maxGMTupdatetime doesn't show up, store last pulled data
    currenttime as lastGMTupdate time, and use this data for next pull request

    For each data pulled compare it with last data sent to fleetio, if any vehicles odometer has changed over 200 km
    update the vehicle data on fleetio

    */
    function checkforOdometerChange($currentModifiedDateTime) {
        $query = "SELECT * from push_report where modified_at = {$currentModifiedDateTime}";
        $fleetioTableRows = Database::selectFromTable($query);
        $query = "SELECT * from pull_report where modified_at = {$currentModifiedDateTime}";
        $galooliTableRows = Database::selectFromTable($query);

        //Check if distance/odometer reading since last push is greater than 200km, or if fuel guage has dropped or
        //increased by 5 litres
        foreach($galooliTableRows as $galooliRows) {
            foreach($fleetioTableRows as $fleetioRows) {
                if(($galooliRows['distance'] - $fleetioRows['distance']) > 200 ||
                    (($galooliRows['fuel_report'] - $fleetioRows['fuel_report']) > 5) || 
                    (($galooliRows['fuel_report'] - $fleetioRows['fuel_report']) < 5))  {
                    $this->processDataBeforePush($galooliRows); 
                }
            }
        }

    }  
    
    function checkforChangeWithinLastHour($currentModifiedDateTime) {
        $this->currentDateTime = date("Y-m-d h:i:s");
        $query = "SELECT * from pull_report where modified_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $tableRows = Database::selectFromTable($query);
        $this->processDataBeforePush($tableRows);
    } 

    function processDataBeforePush($dataToPush) {
        //process data into PHP array or JSON data
        var_dump($dataToPush);
        $processedData = $dataToPush;
        $this->pushDataToFeetio($processedData);
    } 

    function pushDataToFeetio($data_array)
    { 
        $this->apiURL = "";
        $get_data = $this->_apiService->callAPI('POST', $this->apiURL, json_encode($data_array), 'fleetio');
        $response = json_decode($get_data, true);
        $this->errors = $response['response']['errors'];
        if(isset($this->errors)) {
            return $this->errors;
        }
        $this->response = json_decode($get_data, true);
        $data = $response['response']['data'][0];
        return $this->returnedData;
    }
}
