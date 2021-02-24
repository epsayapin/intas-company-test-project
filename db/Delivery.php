<?php

require_once ("Db.php");

class Delivery
{
    public static function createFromPost($post) : void
    {
        Db::getConnection()->query("insert into deliveries values (
                        {$post["courier_id"]}, 
                        {$post["region_id"]}, 
                        '{$post["departure_date"]}', 
                        '{$post["arrival_date"]}'
                    )
                 ");
    }

    public static function getAll() : array
    {
        return Db::getConnection()->query("
                            select deliveries.departure_date, deliveries.arrival_date, 
                            couriers.name as courier_name, 
                            regions.name as region_name 
                            from deliveries 
                            join couriers on(couriers.id = deliveries.courier_id)
                            join regions on(regions.id = deliveries.region_id)
                         ")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public static function isCourierAvaibleForDateRange($courierId, $startDate, $endDate)
    {
        $query = "select count(*) 
                    from deliveries 
                      where (courier_id = $courierId) 
                      and (
                            ('$startDate' between departure_date and arrival_date) 
                            or 
                            ('$endDate' between departure_date and arrival_date)
                          );
                  ";

        return !(boolean)(Db::getConnection()->query($query)->fetch_all(MYSQLI_ASSOC))[0]["count(*)"];
    }

    public static function validatePost() : bool
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

    public static function getByDateRange($startDate, $endDate) : array
    {
        return Db::getConnection()->query("
                        select deliveries.departure_date, deliveries.arrival_date, 
                        couriers.name as courier_name, 
                        regions.name as region_name 
                        from deliveries 
                        join couriers on(couriers.id = deliveries.courier_id)
                        join regions on(regions.id = deliveries.region_id)
                        where 
                          (deliveries.departure_date between '$startDate' and '$endDate') 
                          and (deliveries.arrival_date between '$startDate' and '$endDate');
                        ")
            ->fetch_all(MYSQLI_ASSOC);
    }
}