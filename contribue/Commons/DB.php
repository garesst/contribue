<?php

namespace Commons;

use PDO;

class DB
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    // Constructor
    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
    }

    // Método para conectar a la base de datos
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }

    public function disconnect()
    {
        $this->conn =null;
    }

    // Método para ejecutar una consulta SQL
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Método para insertar datos en la base de datos
    public function insert2($table, $data) {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data)));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $stmt;
    }

    public function insert($table, $data) {
        $keys = implode(',', array_map(function ($key) {
            return '"'.$key.'"';
        }, array_keys($data)));

        $values = implode(',', array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data)));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function insertReturn($table, $data, $return) {
        $keys = implode(',', array_map(function ($key) {
            return '"'.$key.'"';
        }, array_keys($data)));

        $values = implode(',', array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data)));

        $sql = "INSERT INTO $table ($keys) VALUES ($values) RETURNING $return";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    // Método para actualizar datos en la base de datos
    public function update($table, $data, $conditions) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "$key=:$key,";
        }
        $fields = rtrim($fields, ',');

        $where = '';
        foreach ($conditions as $key => $value) {
            $where .= "$key=:$key AND ";
        }
        $where = rtrim($where, 'AND ');

        $sql = "UPDATE $table SET $fields WHERE $where";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge($data, $conditions));
        return $stmt;
    }

    // Método para eliminar datos de la base de datos
    public function delete($table, $conditions) {
        $where = '';
        foreach ($conditions as $key => $value) {
            $where .= "$key=:$key AND ";
        }
        $where = rtrim($where, 'AND ');

        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($conditions);
        return $stmt;
    }
}