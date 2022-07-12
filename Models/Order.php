<?php

require_once 'Models/Pizza.php';
require_once 'Models/PizzaSize.php';
require_once 'Models/Size.php';
require_once 'Models/Sauce.php';
require_once 'Services/PriceCalculatorService.php';


class Order implements JsonSerializable
{
    // db connection
    private $dbConnection;
    private $tableName = 'orders';

    private $pizzaSize;
    private $pizza;
    private $size;
    private $sauce;
    private $email;
    private $total_price;
    private $datetime;

    public function __construct()
    {
        $db = new DbClass();
        $this->dbConnection = $db->getConnection();
    }

    public function createNewOrder(int $pizza_size_id, int $sauce_id, string $email): Order
    {
        $this->email = $email;
        $this->datetime = date("Y-m-d H:i:s");

        // get pizzaSize
        $pizzaSizeModel = new PizzaSize();
        $this->pizzaSize = $pizzaSizeModel->findById($pizza_size_id);

        if (!$this->pizzaSize)
            throw new Exception('PizzaSize is not founded', 404);

        // get sauce
        $sauceModel = new Sauce();
        $this->sauce = $sauceModel->findById($sauce_id);

        if (!$this->sauce)
            throw new Exception('Sauce is not founded', 404);

        // get pizza
        $pizzaModel = new Pizza();
        $this->pizza = $pizzaModel->findById($this->pizzaSize['pizza_id']);

        // get size
        $sizeModel = new Size();
        $this->size = $sizeModel->findById($this->pizzaSize['size_id']);

        // calculate total price
        $priceCalculator = new PriceCalculatorService();
        $this->total_price = $priceCalculator->calculateOrderPrice($this->pizzaSize, $this->sauce);

        $this->saveOrder();

        return $this;
    }

    private function saveOrder()
    {
        try {
            $sql = 'INSERT INTO ' . $this->tableName . '(email, datetime, pizza_size_id, sauce_id, total_price) 
                    VALUES(:email, :datetime, :pizza_size_id, :sauce_id, :total_price)';

            $this->dbConnection
                ->prepare($sql)
                ->execute([
                    ':email'=> $this->email,
                    ':datetime' => $this->datetime,
                    ':pizza_size_id' => $this->pizzaSize['id'],
                    ':sauce_id' => $this->sauce['id'],
                    ':total_price' => $this->total_price,
                ]);

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'pizza' => $this->pizza['name'],
            'pizza_size_name' => $this->pizzaSize['name'],
            'size_name' => $this->size['name'],
            'size_cm' => $this->size['radius_cm'],
            'pizza_size_price' => $this->pizzaSize['price'],
            'sauce' => $this->sauce['name'],
            'sauce_price' => $this->sauce['price'],
            'order_datetime' => $this->datetime,
            'email' => $this->email,
            'total_price' => $this->total_price,
        ];
    }
}