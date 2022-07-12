<?php

require_once 'DbClass.php';

class Sauce implements ISearchable
{
    private $dbConnection;
    private $tableName = 'sauces';

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
}