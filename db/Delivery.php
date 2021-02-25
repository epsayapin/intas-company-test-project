<?php

require_once ("Db.php");

class Delivery
{
    public static function createFromPost($post) : void
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("insert into deliveries values (?, ?, ?, ?)");
        $stmt->bind_param(
            'iiss',
            $post["courier_id"],
            $post["region_id"],
            $post["departure_date"],
            $post["arrival_date"]
        );
        $stmt->execute();
        $stmt->close();
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

    public static function isCourierAvaibleForDateRange($courierId, $startDate, $endDate) : bool
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

        return (boolean)(Db::getConnection()->query($query)->fetch_all(MYSQLI_ASSOC))[0]["count(*)"] == 0;
    }

    public static function getByDate($filterDate) : array
    {
        return Db::getConnection()->query("
                        select deliveries.departure_date, deliveries.arrival_date, 
                        couriers.name as courier_name, 
                        regions.name as region_name 
                        from deliveries 
                        join couriers on(couriers.id = deliveries.courier_id)
                        join regions on(regions.id = deliveries.region_id)
                        where 
                          ('$filterDate' between deliveries.departure_date and deliveries.arrival_date) 
                        ")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public static function validateCreateByPost() : bool
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

    public static function validateGetByDate() : bool
    {
        return (boolean)(strtotime($_POST["filter_date"]));
    }
}