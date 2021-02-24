<?php


require_once "Db.php";

class Delivery extends Db
{

    public static function createFromPost($post)
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        $db = new Db\Db($dbHost,$dbUser, $dbPass, $dbName);
        $db->query("insert into deliveries values (null, {$post["courier_id"]}, {$post["region_id"]}, '{$post["departure_date"]}', '{$post["arrival_date"]}')");
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
                        select deliveries.departure_date, deliveries.arrival_date, 
                        couriers.name as courier_name, 
                        regions.name as region_name 
                        from deliveries 
                        join couriers on(couriers.id = deliveries.courier_id)
                        join regions on(regions.id = deliveries.region_id)
                        "
        )->fetchAll();
    }

    public static function isCourierAvaibleForDateRange($courierId, $startDate, $endDate)
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        $query = "select count(*) 
                    from deliveries 
                    where (courier_id = $courierId) 
                      and (
                        ('$startDate' between departure_date and arrival_date) 
                        or 
                        ('$endDate' between departure_date and arrival_date)
                        );";

        $db = new Db\Db($dbHost,$dbUser, $dbPass, $dbName);

        return !(boolean)($db->query($query)->fetchAll())[0]["count(*)"];
    }

    public static function validatePost()
    {
        return (isset($_POST["arrival_date"])
                && isset($_POST["departure_date"])
                && isset($_POST["arrival_date"])
                && isset($_POST["courier_id"])
                && ($_POST["courier_id"] > 0)
                && isset($_POST["region_id"])
                && ($_POST["region_id"] > 0)
        );
    }

    public static function getByDateRange($startDate, $endDate)
    {
        $dbHost = 'localhost';
        $dbUser = 'zhenya';
        $dbPass = '222';
        $dbName = "intas_company_test_project";

        $db = new Db\Db($dbHost,$dbUser, $dbPass, $dbName);
        return $db->query(
            "
                        select deliveries.departure_date, deliveries.arrival_date, 
                        couriers.name as courier_name, 
                        regions.name as region_name 
                        from deliveries 
                        join couriers on(couriers.id = deliveries.courier_id)
                        join regions on(regions.id = deliveries.region_id)
                        where 
                          (deliveries.departure_date between '$startDate' and '$endDate') 
                          and (deliveries.arrival_date between '$startDate' and '$endDate');
                        "
        )->fetchAll();
    }
}