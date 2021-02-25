<?php

require_once ("../db/Delivery.php");

if(! Delivery::validateCreateByPost()) {
    echo json_encode([
        "text" => "Проверьте правильность указанных данных",
        "post" => $_POST,
    ]);

    exit;
}

if (Delivery::isCourierAvaibleForDateRange($_POST["courier_id"], $_POST["departure_date"], $_POST["arrival_date"])) {
    Delivery::createFromPost($_POST);
    echo json_encode([
        "text" => "Отправка добавлена",
    ]);
} else {
    echo json_encode([
        "text" => "Курьер недоступен на выбранную дату",
    ]);
}
