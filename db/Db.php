<?php

class Db
{
    public static function getConnection()
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        return new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    }
}