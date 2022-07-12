<?php

class DbClass
{
    private $db_host = 'localhost';
    private $db_name = 'php_pizza';
    private $db_username = 'root';
    private $db_password = '12345';

    public function getConnection()
    {
        try {
            $connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_username, $this->db_password);
            $connection->exec("set names utf8");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode());
        }

        return $connection;
    }
}