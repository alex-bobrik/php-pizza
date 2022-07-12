<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order pizza</title>

    <link rel="stylesheet" href="~/../assets/css/bootstrap.css">
    <link rel="stylesheet" href="~/../assets/css/main.css">
    <script src="~/../assets/js/jquery.min.js"></script>
    <script src="~/../assets/js/bootstrap.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <form method="post" id="orderForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="pizzaId">Pizza</label>
                    <select name="pizzaId" id="pizzaSelect" class="form-control" required>
                        <option disabled selected value>select pizza</option>
                        <?php foreach ($pizzas as $pizza)
                            echo "<option value=" . $pizza['id'] . ">" . $pizza['name'] . "</option>>"
                        ?>
                    </select>
                </div>

                <!-- Fill by AJAX -->
                <div class="form-group">
                    <label for="pizzaSizeId">Pizza size</label>
                    <select name="pizzaSizeId" id="pizzaSizeSelect" class="form-control" required></select>
                </div>

                <div class="form-group">
                    <label for="sauceId">Sauce</label>
                    <select name="sauceId" class="form-control">
                        <?php foreach ($sauces as $sauce)
                            echo "<option value=" . $sauce['id'] . ">"
                                . $sauce['name'] . ', ' . $sauce['price'] . ' USD | '
                                . round($sauce['price'] * $bynRate, 2) . ' BYN' .
                                "</option>>"
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" value="Create order" class="form-control btn-primary" id="btnSubmitOrder">
                </div>
            </form>

            <div id="orderDetails" class="form-group" style="display: none">
                <hr>
                <h1>Order</h1>
                <div class="form-group">
                    <h4>Pizza</h4>
                    <p id="details_pizza_name">-</p>
                    <p id="details_size_cm">-</p>
                    <p id="details_pizza_size_price">-</p>
                </div>
                <div class="form-group">
                    <h4>Sauce</h4>
                    <p id="details_sauce_name">-</p>
                    <p id="details_sauce_price">-</p>
                </div>
                <div class="form-group">
                    <h4>Email</h4>
                    <p id="details_email"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h4>Total price</h4>
                    <p id="details_total_price">-</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            // Fill select with pizzaSizes
            $('#pizzaSelect').change(function (e)
            {
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: 'index.php?action=getPizzaSizes&pizzaId=' + $(this).val(),
                    success: function (response)
                    {
                        let pizzaSizes = JSON.parse(response);
                        $('#pizzaSizeSelect').empty();

                        $.each(pizzaSizes, function (i, item) {
                            console.log(item);
                            $('#pizzaSizeSelect').append($('<option>', {
                                value: item.id,
                                text : item.name + ",  " + item.radius_cm + "cm, " + item.price + " USD | " +
                                    (item.price * <?php echo $bynRate ?>).toFixed(2) + " BYN"
                            }));
                        });
                    }
                });
            });

            // Create order
            $('#orderForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'index.php?action=createOrder',
                    data: $(this).serialize(),
                    success: function(response)
                    {
                        $('#orderForm :input').prop('disabled',true);

                        // get order and fill order details
                        let order = JSON.parse(response);
                        $('#details_pizza_name').text(order.pizza);
                        $('#details_size_cm').text(order.size_cm + "cm");
                        $('#details_email').text(order.email);
                        $('#details_sauce_name').text(order.sauce);

                        $('#details_pizza_size_price').text(order.pizza_size_price + " USD | "
                            + (order.pizza_size_price * <?php echo $bynRate ?>).toFixed(2) + " BYN");

                        $('#details_sauce_price').text(order.sauce_price + " USD | "
                            + (order.sauce_price * <?php echo $bynRate ?>).toFixed(2) + " BYN");

                        $('#details_total_price').text(order.total_price + " USD | "
                            + (order.total_price * <?php echo $bynRate ?>).toFixed(2) + " BYN");

                        $('#orderDetails').css("display", "block");
                    }
                });
            });
        });
    </script>

</body>
</html>