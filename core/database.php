<?php
session_start();
class Database
{
    private $connect;

    public function __construct()
    {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'shopthoitrang';
        $this->connect = mysqli_connect($host, $username, $password, $database);
        $this->connect->set_charset('utf8');
        if ($this->connect->connect_error) {
            die($this->connect->connect_error);
        }
    }

    public function getAll($sql)
    {
        return $this->connect->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getFirst($sql)
    {
        return $this->connect->query($sql)->fetch_assoc();
    }

    public function getById($table, $id)
    {
        return $this->connect->query("SELECT * FROM `$table` WHERE id = $id")->fetch_assoc();
    }

    public function count($sql)
    {
        return count($this->getAll($sql));
    }

    public function create($table, $data)
    {
        $column = implode(',', array_keys($data));
        $values = implode("','", $data);
        $sql = "INSERT INTO `$table` ($column) VALUES ('$values')";
        return $this->connect->query($sql);
    }

    public function update($table, $data, $id)
    {
        $sql = "UPDATE `$table` SET ";
        $arr = [];
        foreach ($data as $key => $value) {
            $arr[] = "`$key` = '$value'";
        }
        $sql .= implode(',', $arr);
        $sql .= " WHERE id = $id";
        return $this->connect->query($sql);
    }

    public function delete($table, $id)
    {
        return $this->connect->query("DELETE FROM `$table` WHERE id = $id");
    }

    public function getLastId($table)
    {
        $sql = "select * from `$table` order by id desc limit 1";
        return $this->connect->query($sql)->fetch_assoc();
    }

    public function query($sql)
    {
        return $this->connect->query($sql);
    }
}

$db = new Database;

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    die();
}
