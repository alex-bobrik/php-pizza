<?php


class PriceCalculatorService
{
    public function calculateOrderPrice($pizzaSize, $sauce): float
    {
        return $pizzaSize['price'] + $sauce['price'];
    }
}