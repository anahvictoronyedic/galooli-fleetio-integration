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
