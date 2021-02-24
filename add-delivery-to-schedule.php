<?php

require_once ("Delivery.php");

if(! Delivery::validatePost()) {
    echo json_encode([
        "text" => "Проверьте правильность указанных данных",
        "status" => 0,
        "post" => $_POST,
    ]);

    exit;
}

if (Delivery::isCourierAvaibleForDateRange($_POST["courier_id"], $_POST["departure_date"], $_POST["arrival_date"])) {
    Delivery::createFromPost($_POST);
    echo json_encode([
        "text" => "Отправка добавлена",
        "status" => 1,
        "post" => $_POST,
        "avaible" => Delivery::isCourierAvaibleForDateRange($_POST["courier_id"], $_POST["departure_date"], $_POST["arrival_date"])
    ]);
} else {
    echo json_encode([
        "text" => "Курьер недоступен на выбранную дату",
        "status" => 0,
        "post" => $_POST,
        "avaible" => Delivery::isCourierAvaibleForDateRange($_POST["courier_id"], $_POST["departure_date"], $_POST["arrival_date"])

    ]);

}
