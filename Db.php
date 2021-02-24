<?php


class Db
{
    protected static $connection;
    public function __construct()
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        self::$connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    }
}