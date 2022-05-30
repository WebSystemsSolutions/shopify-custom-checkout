$(document).ready(function () {
    function csrfToken() {
        return $(document)
            .find('meta[name="csrf-token"]')
            .attr("content");
    }

    function getShipmentMethods() {
        let $select = $('.js-country');
        let $country = $('.js-country option:selected').text();
        let $url = $select.attr('data-src');

        if (!$country) {
            return;
        }

        $.ajax({
            type: "POST",
            url: $url,
            data: {
                country: $country,
                _token: csrfToken()
            },
            success: response => {
                let jsonResponse = JSON.parse(response);
                let $shipmentSelect = $('.js-shipment');

                $shipmentSelect.find('option').remove();

                $shipmentSelect.append(new Option('', ''));

                jsonResponse.methods.forEach(function (item) {
                    let option = new Option(item.name + ' ' + item.price, item.id);
                    option.setAttribute('data-title', item.name);
                    option.setAttribute('data-price', item.price);

                    $shipmentSelect.append(option);

                    $('.js-cart-price').text('Cart items price: ' + jsonResponse.priceList.cartCost);
                    $('.js-shipment-price').text('Shipment price: ' + jsonResponse.priceList.shipmentCost);
                    $('.js-total-price').text('Total price: ' + jsonResponse.priceList.total);
                });

                $shipmentSelect.prop("disabled", false);

                let oldValue = $shipmentSelect.attr('data-old-method-id');

                if ($('.js-country option:selected').text() && oldValue) {
                    let options = $shipmentSelect.children();

                    $.map(options ,function(option) {
                        if (option.value === oldValue) {
                            option.selected = 'selected';
                        }
                    });

                    setShipmentMethod();
                }
            },
        });
    }

    function setShipmentMethod() {
        let $select = $('.js-shipment');
        let $option = $('.js-shipment option:selected');

        if (!$option.text()) {
            return;
        }

        let $url = $select.attr('data-src');
        let $title = $option.attr('data-title');
        let $price = $option.attr('data-price');

        $.ajax({
            type: "POST",
            url: $url,
            data: {
                title: $title,
                price: $price,
                _token: csrfToken()
            },
            success: response => {
                let priceList = JSON.parse(response);

                $('.js-cart-price').text('Cart items price: ' + priceList.cartCost);
                $('.js-shipment-price').text('Shipment price: ' + priceList.shipmentCost);
                $('.js-total-price').text('Total price: ' + priceList.total);
            },
        });
    }

    $('.js-country').on('change', function (e) {
        getShipmentMethods();
    });

    $('.js-shipment').on('change', function (e) {
        setShipmentMethod();
    });

    let flag = false;
    if ($('.js-country option:selected').text() && flag === false) {
        getShipmentMethods();

        flag = true;
    }
})
