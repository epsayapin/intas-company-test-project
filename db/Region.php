<?php

require_once "Db.php";

class Region
{
    public static function getAll() : array
    {
        return Db::getConnection()->query("select * from regions;")->fetch_all(MYSQLI_ASSOC);
    }
}