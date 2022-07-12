<?php

require_once 'Models/Order.php';
require_once 'Models/Pizza.php';
require_once 'Models/Sauce.php';
require_once 'Models/Size.php';
require_once 'Services/CurrenciesService.php';

class OrderController
{
    public function handleRequest()
    {
        $action = $_GET['action'] ?? NULL;
        switch ($action) {
            case 'createOrder':
                $this->createOrder();
            break;
            case 'getPizzaSizes':
                $this->getPizzaSizes();
                break;
            default:
                $this->orderPage();
        }
    }

    public function getPizzaSizes()
    {
        $pizza_id = $_GET['pizzaId'];
        if (!$pizza_id)
            throw new Exception('Pizza is not provided', 400);

        $pizzaSizesModel = new PizzaSize();
        $pizzaSizes = $pizzaSizesModel->findByPizzaId($pizza_id);

        // Should be `return new JsonResponse()`
        echo json_encode($pizzaSizes);
    }

    public function orderPage()
    {
        // get pizzas
        $pizzaModel = new Pizza();
        $pizzas = $pizzaModel->findAll();

        // get sauces
        $sauceModel = new Sauce();
        $sauces = $sauceModel->findAll();

        // get current BYN rate
        $currenciesService = new CurrenciesService();
        $bynRate = $currenciesService->getBynRate();

        include "Views/orderPage.php";
    }

    public function createOrder()
    {
        $email = trim($_POST['email']);
        $pizza_size_id = (int)$_POST['pizzaSizeId'];
        $sauce_id = (int)$_POST['sauceId'];

        if (!$email || !$pizza_size_id || !$sauce_id)
            throw new Exception('Incorrect data is provided', 400);

        $orderModel = new Order();
        $createdOrder = $orderModel->createNewOrder($pizza_size_id, $sauce_id, $email);

        // Should be `return new JsonResponse()`
        echo json_encode($createdOrder);
    }

}