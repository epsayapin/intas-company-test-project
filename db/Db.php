<?php

class Db
{
    public static function getConnection() : mysqli
    {
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPass = 'secret';
        $dbName = "intas_company_test_project";

        return new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    }
}