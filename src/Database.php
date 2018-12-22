<?php
require_once 'db.php';

class Database {
    private $query;

    public static function selectFromTable($query)
    {
        $result = mysqli_query($GLOBALS['db_server'], $query);
        $count = mysqli_num_rows($result);
        return $count > 0 ? mysqli_fetch_all($result, MYSQLI_BOTH): 0;
    }

    public static function getSingleRow($query)
    {
        $result = mysqli_query($GLOBALS['db_server'], $query);
        $count = mysqli_num_rows($result);
        return $count > 0 ? mysqli_fetch_array($result): 0;
    }

    public static function updateOrInsert($query)
    {
        $status = mysqli_query($GLOBALS['db_server'], $query);
        return $status == 1 ? true : false;
    }

}