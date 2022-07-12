<?php

require_once 'Models/Order.php';

class PriceCalculatorService
{
    public function calculateOrderPrice($pizzaSize, $sauce): float
    {
        return $pizzaSize['price'] + $sauce['price'];
    }
}