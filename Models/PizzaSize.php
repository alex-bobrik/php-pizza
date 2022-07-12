<?php

require_once 'DbClass.php';

class PizzaSize implements ISearchable
{
    private $dbConnection;
    private $tableName = 'pizza_sizes';

    public function __construct()
    {
        $db = new DbClass();
        $this->dbConnection = $db->getConnection();
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM ' . $this->tableName;

        return $this->dbConnection->query($sql)->fetchAll();
    }

    public function findById(int $id)
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id=:id';

        $result = $this->dbConnection
            ->prepare($sql);

        $result->execute([':id' => $id]);

        return $result->fetch();
    }

    public function findByPizzaId(int $pizza_id)
    {
        try {
            $sql = 'select ps.id, ps.name, ps.price, s.radius_cm from pizzas as p
                join pizza_sizes ps on p.id = ps.pizza_id
                join sizes s on ps.size_id = s.id
                where p.id = :pizza_id';

            $pizza_sizes = $this->dbConnection->prepare($sql);
            $pizza_sizes->execute([':pizza_id' => $pizza_id]);

            return $pizza_sizes->fetchAll();

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }
}