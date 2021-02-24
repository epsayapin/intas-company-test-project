<?php


require_once "Db.php";

class Region
{
    public static function create($params)
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        $db = new Db\Db($dbHost,$dbUser, $dbPass, $dbName);
        //$db->query("insert into deliveries values (null, {$params["courier_id"]}, {$params["region_id"]}, '{$params["departure_date"]}', '{$params["arrival_date"]}')");
    }

    public static function getAll()
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        $db = new Db\Db($dbHost,$dbUser, $dbPass, $dbName);
        return $db->query(
            "
                        select * from regions;
                        "
        )->fetchAll();
    }
}