<?php

require_once "Db.php";

$conn = Db::getConnection();

$sql = 'drop table deliveries;';

$conn->query($sql);

$sql = "drop table regions;";

$conn->query($sql);

$sql = "drop table couriers;";

$conn->query($sql);

$sql = '
            create table regions(
                id integer not null auto_increment primary key,
                name varchar(255) not null,
                days_for_delivery int not null
            );';

$conn->query($sql);

$sql = '
            insert into regions values (null, "Санкт-Петербург", floor(rand()*(10-1+1)+1)),
									(null, "Уфа", floor(rand()*(10-1+1)+1)),
									(null, "Нижний Новгород", floor(rand()*(10-1+1)+1)),
									(null, "Владимир", floor(rand()*(10-1+1)+1)),
									(null, "Кострома", floor(rand()*(10-1+1)+1)),
									(null, "Екатеринбург", floor(rand()*(10-1+1)+1)),
									(null, "Ковров", floor(rand()*(10-1+1)+1)),
									(null, "Воронеж", floor(rand()*(10-1+1)+1)),
									(null, "Самара", floor(rand()*(10-1+1)+1)),
									(null, "Астрахань", floor(rand()*(10-1+1)+1))
			';

$conn->query($sql);

$sql = '									
            create table couriers(
                id integer not null auto_increment primary key,
                name varchar(255) not null
            );';

$conn->query($sql);


$sql = 'insert into couriers values (null, "Ivanov"), 
                                    (null, "Petrov"), 
                                    (null, "Smirnov"),
                                    (null, "Sidorov"), 
                                    (null, "Simpson"), 
                                    (null, "Flanders"), 
                                    (null, "Mo"), 
                                    (null, "Smitters"), 
                                    (null, "Fray"), 
                                    (null, "Bart")
                                    ;';

$conn->query($sql);




$sql = '
            create table deliveries(
                courier_id integer not null,
                region_id integer not null,
                departure_date date not null,
                arrival_date date not null,
                
                foreign key (courier_id) references couriers(id) on delete cascade,
                foreign key (region_id) references regions(id) on delete cascade
            );
      ';

$conn->query($sql);

$sql = "insert into deliveries values (1, 1, '2021-02-01','2021-02-01'), (1, 1, '2021-02-01','2021-02-01'), (1, 1, '2021-02-01','2021-02-01')";

$conn->query($sql);

$sql = "
select * from couriers limit 1
        join deliveries on(courier_id = couriers.id)
        where 
            select count(*) from deliveries
              where deliveries.courier_id = couriers.id = 0
        ;
        ";

$courier = $conn->query($sql)->fetch_assoc();

echo print_r($courier);

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

