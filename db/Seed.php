<?php

require_once "Db.php";

Db::getConnection()->query("drop table if exists deliveries");
Db::getConnection()->query("drop table if exists regions");
Db::getConnection()->query("drop table if exists couriers");

Db::getConnection()->query('create table regions(
                                        id integer not null auto_increment primary key,
                                        name varchar(255) not null,
                                        days_for_delivery int not null
                                    );'
);

Db::getConnection()->query('create table couriers(
                                        id integer not null auto_increment primary key,
                                        name varchar(255) not null
                                    );'
);

Db::getConnection()->query('create table deliveries(
                                        courier_id integer not null,
                                        region_id integer not null,
                                        departure_date date not null,
                                        arrival_date date not null,
                                        
                                        foreign key (courier_id) references couriers(id) on delete cascade,
                                        foreign key (region_id) references regions(id) on delete cascade
                                    );'
);

Db::getConnection()->query('insert into regions values 
            (null, "Санкт-Петербург", floor(rand()*(10-1+1)+1)),
            (null, "Уфа", floor(rand()*(10-1+1)+1)),
            (null, "Нижний Новгород", floor(rand()*(10-1+1)+1)),
            (null, "Владимир", floor(rand()*(10-1+1)+1)),
            (null, "Кострома", floor(rand()*(10-1+1)+1)),
            (null, "Екатеринбург", floor(rand()*(10-1+1)+1)),
            (null, "Ковров", floor(rand()*(10-1+1)+1)),
            (null, "Воронеж", floor(rand()*(10-1+1)+1)),
            (null, "Самара", floor(rand()*(10-1+1)+1)),
            (null, "Астрахань", floor(rand()*(10-1+1)+1))'
);

Db::getConnection()->query(    'insert into couriers values 
            (null, "Ivanov"), 
            (null, "Petrov"), 
            (null, "Smirnov"),
            (null, "Sidorov"), 
            (null, "Simpson"), 
            (null, "Flanders"), 
            (null, "Mo"), 
            (null, "Smitters"), 
            (null, "Fray"), 
            (null, "Bart")
            ;'
);

seedDeveliriesTable();

Db::getConnection()->close();

function seedDeveliriesTable()
{
    $regions = getRegions();
    $insertSql = "insert into deliveries values";

    foreach(getDateRange('2019-06-01', date("Y-m-d")) as $date){
        $currentDate = $date->format("Y-m-d");
        $values = [];

        foreach(getAvaibleCouriersByDate($currentDate) as $courier){
            $regionId = rand(0, count($regions) - 1);
            $daysForDelivery = $regions[$regionId]["days_for_delivery"];
            $arrivalDate = $date->add(new DateInterval("P" . $daysForDelivery . "D"))->format("Y-m-d");
            $values[] = "({$courier['id']},{$regionId},'$currentDate','$arrivalDate')";
        }
        if (count($values) > 0) {
            Db::getConnection()->query($insertSql . implode(",", $values));
        }
    }
}

function getDateRange($start, $end)
{
    return $dateRange = new DatePeriod(
                new DateTime( $start),
                new DateInterval('P1D'),
                new DateTime($end)
            );
}

function getRegions()
{
    return Db::getConnection()->query("select * from regions")->fetch_all(MYSQLI_ASSOC);
}

function getAvaibleCouriersByDate($date)
{
    return Db::getConnection()->query("select * from couriers 
                                        where not exists 
                                        (select * from deliveries 
                                        where courier_id = couriers.id and ('$date' between departure_date and arrival_date) 
                                        );"
    )->fetch_all(MYSQLI_ASSOC);
}
