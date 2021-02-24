$( document ).ready(function () {

    regionSelect = $("select[name='region_id']")
    departureDateInput = $("input[name='departure_date']")
    arrivalDateInput = $("input[name='arrival_date']")
    addDeliveryForm = $(".js-add-delivery-form")

    deliveriesFilterInputs = $(".js-filter-deliveries-by-date")
    deliveriesFilterStartDateInput = $("#start_date")
    deliveriesFilterEndDateInput = $("#end_date")

    deliveriesList = $("#deliveries_list")
    infoDiv = $("#info")

    getDeliveriesByDateRangeActionPath = "actions/get-deliveries-by-date-range.php";

    regionSelect.add(departureDateInput).on("change", function () {

        if (isDateAndRegionSelected()) {
            showArrivalDate()
        }
    })

    addDeliveryForm.submit(function (event) {

        event.preventDefault()

        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data: $(this).serialize(),
            success: function (response) {
                infoDiv.text(JSON.parse(response).text)
            }
        })
    })

    deliveriesFilterInputs.change(function () {

        if(isDeliveryFiltersSetuped()) {
            $.ajax({
                url: getDeliveriesByDateRangeActionPath,
                data: {
                    start_date: deliveriesFilterStartDateInput.val(),
                    end_date: deliveriesFilterEndDateInput.val()
                },
                type: "POST",
                success: function (response) {
                    clearDeliveriesList()
                    fillDeliveriesList(JSON.parse(response))
                }
            });
        }
    })

    function clearDeliveriesList() {
        deliveriesList.empty()
    }

    function fillDeliveriesList(deliveries) {
        $.each(deliveries, function (i, delivery) {
            deliveriesList.append("<li>"
                + delivery.courier_name
                + " "
                + delivery.region_name + "</li>"
                + " "
                + delivery.departure_date + "</li>"
                + " "
                + delivery.arrival_date + "</li>"
            );
        })
    }

    function isDateAndRegionSelected() {

        return (departureDateInput.val() != "")
            &&(regionSelect.val() != 0);
    }

    function showArrivalDate() {

        daysInWay = regionSelect.find(":selected").data("days-for-delivery");
        departureDate = departureDateInput.val();

        arrivalDate = calculateArrivalDate(departureDate, daysInWay)

        arrivalDateInput.val(
            arrivalDate
        );
    }
    
    function isDeliveryFiltersSetuped() {

        $setuped = true
        deliveriesFilterInputs.each(function (i, input) {
            if (input.value == "") {
                $setuped = false
            }
        })

        return $setuped
    }

    function calculateArrivalDate(departureDate, daysForDelivery) {

        return (new Date(
                    Date.parse(departureDate) + convertDaysToMs(daysForDelivery)
                    )
                ).toISOString().replace(/T.*Z/gm, "")
    }

    function convertDaysToMs(days) {

        return days * 24 * 60 * 60 * 1000
    }
})
