<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>Intas-Company Test Project</h1>
    <span id="info"></span>

    <h2>Add Delivery</h2>

    <form method="POST" action="add-delivery-to-schedule.php">

        <label for="date">Дата отправки</label>
        <input name="departure_date" type="date">

        <label for="region">Регион</label>
        <select name="region_id">
            <option value="0">Выберите регион</option>
            <?php
                require_once "Region.php";
                foreach (Region::getAll() as $region) {
                    echo "<option value='{$region["id"]}' data-days-in-way='{$region["days_for_delivery"]}'>{$region["name"]}</option>";
                }
            ?>

        </select>

        <label for="courier">Курьер</label>
        <select name="courier_id">
            <option value="0">Выберите курьера</option>
            <?php
                require_once "Courier.php";
                foreach (Courier::getAll() as $courier) {
                    echo "<option value='{$courier["id"]}'>{$courier["name"]}</option>";
            }
            ?>

        </select>

        <label>Дата прибытия</label>
        <input name="arrival_date" type="date" placeholder="Выберите дату доставки" readonly>

        <input type="submit" value="Добавить">
    </form>

    <h2>Delivery Index</h2>
        Фильтр
        <input type="date" id="start_date" class="js-filter-deliveries-by-date">
        <input type="date" id="end_date" class="js-filter-deliveries-by-date">

        <ul id="deliveries_list">

        </ul>
</body>

<script>
    function isDateAndRegionSelected() {
        return ($("input[name='departure_date']").val() != "")
                &&($("select[name='region_id']").val() != 0);
    }
    
    function showArrivalDate() {
        $daysInWay = $("select[name='region_id']").find(":selected").data("days-in-way");
        $departureDate = $("input[name='departure_date']").val();
        $arrivalDate = (new Date(Date.parse($departureDate) + $daysInWay * 24 * 60 * 60 * 1000)).toISOString().replace(/T.*Z/gm, "")

        $("input[name='arrival_date']").val(
                $arrivalDate
            );
    }

    $('select[name="region_id"]').on("change", function () {
        if (isDateAndRegionSelected()) {
            showArrivalDate()
        }
    })

    function calculateDepartureDate() {
        $daysInWay = $("select[name='region_id']").find(":selected").data("days-in-way");
        $departureDate = $("input[name='departure_date']").val();

        return Date.parse($departureDate) + $daysInWay * 24 * 60 * 60;
    }

    $('input[name="departure_date"]').on("change", function () {
        if (isDateAndRegionSelected()) {
            showArrivalDate()
        }
    })

    $('form').submit(function (event) {
        event.preventDefault()

        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data: $(this).serialize(),
            success: function (response) {
                $("#info").text(JSON.parse(response).text)
            }
        })
    })

    $(".js-filter-deliveries-by-date").change(function () {
        $.ajax({
            url: "get-deliveries-by-date-range.php",
            data: {
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
            },
            type: "POST",
            success: function (response) {

                $("#deliveries_list").empty()

                $.each(JSON.parse(response), function (i, delivery) {
                    $("#deliveries_list").append("<li>"  + delivery.courier_name + "</li>");
                })
            }
        });
    })

</script>
</html>