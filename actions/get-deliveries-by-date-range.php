<?php

require_once "../db/Delivery.php";

if (Delivery::validateGetByDate()) {

    echo json_encode(Delivery::getByDate($_POST["filter_date"]));
}
