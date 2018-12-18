# galooli-fleetio-integration
A Php application to integrate data between galooli tracking application and fleetio management application


version 1.0
Brief
pull data from galooli app using: https://sdk.galooli-systems.com/galooliSDKService.svc/json/Assets_Report endpoint to get data such as u.unit_t,u.id,u.name,ac.status,ac.latitude,ac.longitude,ac.distance_[km]

there are two tables for data, last pushed data to fleetio and current data pulled from galooli
Table fields will be :
unit id, unit name, active status, latitude, longitude, distance, engine hours : in hours.

error log table
Table fields: 
error message, date time

settings table
settings name, settings value

endpoint to push to fleetio: POST methods
https://secure.fleetio.com/api/v1/location_entries  -- to set location[latitude,longitude]
sample request data
{
    "vehicle_id": 555,
    "contact_id": 21685,
    "date": "2018-12-03",
    "latitude": "5.66666",
    "longitude": "0.5656565"
}


https://secure.fleetio.com/api/v1/meter_entries  --  to set odometer/distance
sample request data:
{
    "vehicle_id": 5,
    "date": "2018-12-03",
    "meter_type": set as secondary if you wanna set engine hours, or blank if odometer/distance
    "value": "500",
    "void": set true if value is not incremental - not necesary
}

