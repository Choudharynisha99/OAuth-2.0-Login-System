<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "google_auth";
    public $conn;
    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection Failed: " . $this->conn->connect_error);
        }
    }
    public function __construct()
    {
        $this->connect();
    }
}
class Crud extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function InsertData($tableName, $data = [])
    {
        if (empty($tableName) || empty($data)) {
            return false;
        }
        $columns = implode("`,`", array_keys($data));
        $postvalues = array_map([$this->conn, 'real_escape_string'], array_values($data));
        $values = implode("','", $postvalues);
        $sql = "INSERT INTO `$tableName` (`$columns`) VALUES ('$values')";
        $query = $this->conn->query($sql);
        return $query ? true : false;
    }
    public function GetData($tablename, $where = "", $condition = "")
    {
        if (empty($tablename)) {
            return false;
        }
        $fetch = "SELECT * FROM `$tablename`";
        if (!empty($where)) {
            $fetch .= " WHERE $where";
        }
        if (!empty($condition)) {
            $fetch .= " $condition";
        }
        $query = $this->conn->query($fetch);
        $records = [];
        while ($row = $query->fetch_assoc()) {
            $records[] = $row;
        }
        return $records;
    }
    public function DeleteData($tablename, $where = "")
    {
        if (empty($tablename) || empty($where)) {
            return false;
        }
        $delete = "DELETE FROM `$tablename` WHERE $where";
        $query = $this->conn->query($delete);
        return $query ? true : false;
    }
    public function UpdateData($tableName, $data = [], $where = "")
    {
        if (empty($tableName) || empty($data) || empty($where)) {
            return false;
        }
        $updateParts = [];
        foreach ($data as $key => $value) {
            $value = $this->conn->real_escape_string($value);
            $updateParts[] = "`$key` = '$value'";
        }
        $updateString = implode(", ", $updateParts);
        $update = "UPDATE `$tableName` SET $updateString WHERE $where";
        $query = $this->conn->query($update);
        return $query ? true : false;
    }

    public function login($table, $email, $password = null)
    {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM `$table` WHERE `email`='$email' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password !== null && $password !== $user['password']) return false;
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            return true;
        }
        return false;
    }



    public function logout()
    {
        session_unset();
        session_destroy();
        return true;
    }
}
