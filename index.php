<?php
    require_once 'db/Region.php';
    require_once 'db/Delivery.php';
    require_once 'db/Courier.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script.js"></script>

    </head>
    <body>
        <h1>Intas-Company Test Project</h1>
        <span id="info"></span>

        <h2>Add Delivery</h2>
        <form method="POST" action="actions/add-delivery-to-schedule.php" class="js-add-delivery-form">

            <label>Дата отправки</label>
            <input name="departure_date" type="date">

            <label>Регион</label>
            <select name="region_id">
                <option value="0">Выберите регион</option>

                <?php
                    foreach (Region::getAll() as $region) {
                        echo "<option 
                                value='{$region["id"]}' 
                                data-days-for-delivery='{$region["days_for_delivery"]}'
                                >
                                    {$region["name"]}
                             </option>";
                    }
                ?>

            </select>

            <label>Курьер</label>
            <select name="courier_id">
                <option value="0">Выберите курьера</option>

                <?php
                    foreach (Courier::getAll() as $courier) {
                        echo "<option 
                                value='{$courier["id"]}'
                              >
                                {$courier["name"]}
                            </option>";
                }
                ?>

            </select>

            <label>Дата прибытия</label>
            <input name="arrival_date" type="date" readonly>

            <input type="submit" value="Добавить">
        </form>

        <h2>Delivery Index</h2>
            Фильтр
            <input type="date" id="filter_date" class="js-filter-deliveries-by-date">

            <ul id="deliveries_list">
            </ul>
    </body>
</html>