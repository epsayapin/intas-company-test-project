<?php

require_once "Delivery.php";

echo json_encode(Delivery::getByDateRange($_POST["start_date"], $_POST["end_date"]));