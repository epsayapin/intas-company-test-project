<?php

require_once "Db.php";

class Courier
{
    public static function getAll() : array
    {
        return Db::getConnection()->query("select * from couriers;")->fetch_all(MYSQLI_ASSOC);
    }
}