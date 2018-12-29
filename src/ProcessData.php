<?php

require_once "ApiService.php";
require_once 'Database.php';

if($_GET['call_function'] == 'pushfleetio') {
    $processData = new ProcessData();
    $processData->checkforChangeWithinLastHour();
}
else if($_GET['call_function']  == 'pullGalooli') {
    $processData = new ProcessData();
    $processData->pullDataFromGalooli(false);
}

class ProcessData {
    public $returnedData;
    public $apiURL;
    public $_apiService;
    public $response;
    public $errors;
    private $currentDateTime;
    private $isInitialization;
    private $fleetioUpdate = false;

    function  __construct() {
        $this->_apiService = new ApiService();
        $this->_apiService->apiToken = "";
    }

    //CRON JOB: this function should run every ten minutes
    function pullDataFromGalooli($isInitialization)
    {

        $this->isInitialization = $isInitialization;
        //get last update time
        $query = "SELECT value from configuration where name = 'last_gmt_update_time'";
        $tableRow = Database::getSingleRow($query);
        $lastPullTime = $tableRow["value"];
        // echo "lastPullTime : ".$lastPullTime;
        $this->apiURL = "https://sdk.galooli-systems.com/galooliSDKService.svc/json/Assets_Report?userName=matrixvtrack&password=matv123?&requestedPropertiesStr=u.id,u.name,ac.status,ac.latitude,ac.longitude,ac.distance_[km],ac.engine_hours_[num],ac.main_fuel_tank_level_[liter]&lastGmtUpdateTime=".urlencode($lastPullTime);
         
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'galooli');
        $this->currentDateTime = date("Y-m-d h:i:s");
        $this->returnedData = json_decode($get_data, true);

        // var_dump($this->returnedData['CommonResult']['DataSet']['0']);
        echo "<br><br>";
        if($this->returnedData != null) {
            //update last update time
            $query = "UPDATE configuration SET value='".$this->currentDateTime."' where name = 'last_gmt_update_time'";
            if (Database::updateOrInsert($query)) {
                echo "LastGMTupdate time Record updated successfully<br>";
            } else {
                echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
            }
            $this->currentDateTime = date("Y-m-d H:i:s");
            foreach($this->returnedData['CommonResult']['DataSet'] as $returnedData) {
                if ($this->isInitialization) {
                    $updateRecordQuery = "INSERT INTO pull_report(unit_id, unit_name, active_status, latitude, longitude, distance, engine_hours, fuel_report, created_at) 
                            VALUES('".$returnedData['0']."','".$returnedData['1']."','".$returnedData['2']."','".$returnedData['3']."'
                            ,'".$returnedData['4']."','".$returnedData['5']."','".$returnedData['6']."','".$returnedData['7']."', NOW())";

                    $saveIDQuery = "INSERT INTO id_mapping(id_galooli, plate_number) 
                            VALUES('".$returnedData['0']."','".$returnedData['1']."')";
                    if (Database::updateOrInsert($saveIDQuery)) {
                        echo "IDs inserted into query<br>";
                    } else {
                        echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
                    }
                    $this->saveToFleetioTable($returnedData);
               
                } else {
                    $updateRecordQuery = "UPDATE pull_report SET active_status='".$returnedData['2']."', latitude = '".$returnedData['3']."', 
                        longitude = '".$returnedData['4']."', distance = '".$returnedData['5']."', 
                        engine_hours = '".$returnedData['6']."', fuel_report = '".$returnedData['7']."', modified_at = '{$this->currentDateTime}' where unit_id = '".$returnedData['0']."'";
                }
                if (Database::updateOrInsert($updateRecordQuery)) {
                    echo "Pulled Data updated successfully  "; // this can be like logged
                } else {
                    echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
                }
            }
            if ($this->isInitialization) 
                $this->pullDataFromFleetio();
            else {
                $this->checkforOdometerChange($this->currentDateTime);
            }
                
        }
        return $this->returnedData;
    }

    //NB: this is used for initialization
    function pullDataFromFleetio()
    {
        $this->apiURL = "https://secure.fleetio.com/api/v1/vehicles";
        $get_data = $this->_apiService->callAPI('GET', $this->apiURL, false, 'fleetio');
        $this->returnedData = json_decode($get_data, true);
        foreach($this->returnedData as $returnedData) {
            echo $returnedData['id']. ' ' . $returnedData['name'];
            $this->mapCorrespondingIds($returnedData['id'], $returnedData['name']);
        }
    }

    //NB: this is used for initialization
    function mapCorrespondingIds($vehicle_id, $vehicle_name)
    {
        $mapIDQuery = "UPDATE id_mapping SET id_fleetio='{$vehicle_id}' where plate_number = '{$vehicle_name}'";
        if (Database::updateOrInsert($mapIDQuery)) {
            echo "Id Mapped<br>";
        } else {
            echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
        }
    }

    /*
    first pull data from galooli every ten minutes, since maxGMTupdatetime doesn't show up, store last pulled data
    currenttime as lastGMTupdate time, and use this data for next pull request

    For each data pulled compare it with last data sent to fleetio, if any vehicles odometer has changed over 200 km
    update the vehicle data on fleetio

    */
    function checkforOdometerChange($currentModifiedDateTime) {
        echo "<br>checkforOdometerChange or fuel change over 5 litres<br/>";
        $query = "SELECT * from push_report";
        $fleetioTableRows = Database::selectFromTable($query);
        $query = "SELECT * from pull_report where modified_at = '{$currentModifiedDateTime}'";
        $galooliTableRows = Database::selectFromTable($query);

        //Check if distance/odometer reading since last push is greater than 200km, or if fuel guage has dropped or
        //increased by 5 litres
        if($galooliTableRows && $fleetioTableRows) {
            for($i = 0; $i < count($galooliTableRows);  $i++) {
                $distanceTest = $galooliTableRows[$i]['distance'] - $fleetioTableRows[$i]['distance'];
                $fuelTest = $galooliTableRows[$i]['fuel_report'] - $fleetioTableRows[$i]['fuel_report'];
                echo "<br><br>Difference in odometer: ".$distanceTest;
                echo "  Difference in fuel: ".$fuelTest."<br>";
                if($galooliTableRows[$i]['fuel_report'] == 0)  {
                    echo "Error in Galooli Data: fuel report is Zero";
                    continue; 
                }
                if($distanceTest > 200 || $fuelTest > 5 || $fuelTest < -5)  {
                    //save to fleetio table
                    echo "<br>Conditions Met<br>";
                    $this->saveToFleetioTable($galooliTableRows[$i]);
                    $this->processDataBeforePush($galooliTableRows[$i]); 
                }
            }
            if ($this->fleetioUpdate) {
                $query = "UPDATE configuration SET value='".$this->currentDateTime."' where name = 'last_fleetio_push_time'";
                if (Database::updateOrInsert($query)) {
                    echo "LastGMTupdate time Record updated successfully<br>";
                } else {
                    echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
                }
                $this->fleetioUpdate = false;
            }
        } else {
            echo "No data to update";
        }
        

    }  
    

    // CRON JOB: this function should run every one hour, and can be changed from user interface
    // to be anything of 30 mins interval
    function checkforChangeWithinLastHour() {
        echo "Change has occured within last hour <br/>";
        $query = "SELECT * from pull_report where modified_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";

        $galooliTableRows = Database::selectFromTable($query);
        if($galooliTableRows) {
            foreach($galooliTableRows as $galooliRow) {
                //save to fleetio table
                $this->saveToFleetioTable($galooliRow);
                $this->processDataBeforePush($galooliRow);
            }
            if ($this->fleetioUpdate) {
                $query = "UPDATE configuration SET value='".$this->currentDateTime."' where name = 'last_fleetio_push_time'";
                if (Database::updateOrInsert($query)) {
                    echo "LastGMTupdate time Record updated successfully<br>";
                } else {
                    echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
                }
                $this->fleetioUpdate = false;
            }
        }
    } 

    function saveToFleetioTable($galooliRow) {
        if ($this->isInitialization) {
            echo "Inserting into push report";
            $updateRecordQuery = "INSERT INTO push_report(unit_id, unit_name, active_status, latitude, longitude, distance, engine_hours, fuel_report, created_at) 
                    VALUES('".$galooliRow['0']."','".$galooliRow['1']."','".$galooliRow['2']."','".$galooliRow['3']."'
                    ,'".$galooliRow['4']."','".$galooliRow['5']."','".$galooliRow['6']."','".$galooliRow['7']."', NOW())";
       
        } else {
            $updateRecordQuery = "UPDATE push_report SET active_status='".$galooliRow['active_status']."', latitude = '".$galooliRow['latitude']."', 
                longitude = '".$galooliRow['longitude']."', distance = '".$galooliRow['distance']."', 
                engine_hours = '".$galooliRow['engine_hours']."', fuel_report = '".$galooliRow['fuel_report']."', modified_at = NOW() where unit_id = '".$galooliRow['unit_id']."'";
        }
        if (Database::updateOrInsert($updateRecordQuery)) {
            echo "Data updated successfully in push report [FLEETIO Table]<br>";
        } else {
            echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
        }
    }

    function processDataBeforePush($dataToPush) {
        echo "Data to update found <br/>";
        $query = "SELECT id_fleetio from id_mapping where id_galooli = '".$dataToPush['unit_id']."'";
        $tableRow = Database::getSingleRow($query);
        $fleetioID = $tableRow["id_fleetio"];
        echo "Fleetio ID: ".$fleetioID;
        $this->pushDataToFeetio($dataToPush, $fleetioID);
    } 

    function pushDataToFeetio($data_array, $fleetioID)
    {
        /*
        TODO: push latitude and longitude to /location_entries
            push odometer and engine hours to /meter_entries
            push fuel_report to /fuel_entries
        */
        if ($fleetioID != 0) {
            $this->fleetioUpdate = true;
            $this->currentDateTime = date("Y-m-d");
            //PUSH Odometer
            $post_data_array = array('vehicle_id' => $fleetioID,
                                'date' => $this->currentDateTime,
                                'value' => $data_array['distance']);
            $jsonDataArray = json_encode($post_data_array);
            echo "<br><br>Encoded Json for odometer: ";
            var_dump($jsonDataArray);
            $this->apiURL = "https://secure.fleetio.com/api/v1/meter_entries";
            $return_data = $this->_apiService->callAPI('POST', $this->apiURL, $jsonDataArray, 'fleetio');
            $response = json_decode($return_data, true);
            echo "<br><br>Response From meter entries: ";
            var_dump($response);
            echo '<br>';

            //PUSH Engine hours
            $post_data_array = array('vehicle_id' => $fleetioID,
                                'date' => $this->currentDateTime,
                                'meter_type' => "secondary",
                                'value' => $data_array['engine_hours']);
            $jsonDataArray = json_encode($post_data_array);
            echo "<br><br>Encoded Json for engine hours: ";
            var_dump($jsonDataArray);
            $this->apiURL = "https://secure.fleetio.com/api/v1/meter_entries";
            $return_data = $this->_apiService->callAPI('POST', $this->apiURL, $jsonDataArray, 'fleetio');
            $response = json_decode($return_data, true);
            echo "<br><br>Response From meter entries: ";
            var_dump($response);
            echo '<br>';

            //PUSH LOCATION DATA
            $post_data_array = array('vehicle_id' => $fleetioID,
                                'contact_id' => "",
                                'date' => $this->currentDateTime,
                                'latitude' => $data_array['latitude'],
                                'longitude' => $data_array['longitude']);
            $jsonDataArray = json_encode($post_data_array);
            echo "<br><br>Encoded Json for location: ";
            var_dump($jsonDataArray);
            $this->apiURL = "https://secure.fleetio.com/api/v1/location_entries";
            $return_data = $this->_apiService->callAPI('POST', $this->apiURL, $jsonDataArray, 'fleetio');
            $response = json_decode($return_data, true);
            echo "<br><br>Response From location entries: ";
            var_dump($response);
            echo '<br>';
        }
        

        //PUSH FUEL ENTRIES DATA
        // $latitude = $data_array['latitude'];
        // $longitude = $data_array['longitude'];
        // $jsonDataArray = '{"vehicle_id": {$fleetioID}, "contact_id": "","meter_type": "",
        //                     "date": {$this->currentDateTime}, "latitude": {$latitude}, "longitude": {$longitude}}';
        // $this->apiURL = "https://secure.fleetio.com/api/v1/meter_entries";
        // $return_data = $this->_apiService->callAPI('POST', $this->apiURL, $jsonDataArray, 'fleetio');
        // $response = json_decode($return_data, true);
        // var_dump($response);
    }

    function logError($errorData)
    {
        $updateErrorLog = "INSERT INTO error_log(message) VALUES('{$errorData}')";
        if (Database::updateOrInsert($updateErrorLog)) {
            echo "Error Log Updated<br>";
        } else {
            echo "Error updating record: " . mysqli_error($GLOBALS['db_server'])."<br/>";
        }
    }
}
