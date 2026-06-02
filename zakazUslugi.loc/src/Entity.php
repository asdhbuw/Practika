<?php

namespace src;

use src\services\Request;
use src\services\Db;

abstract class Entity{
    protected string $tableName;
    protected int $id;
    

    public function __construct(protected Request $request, protected Db $db){}

    public function load(array $fields) {
        foreach($fields as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }

    public function insert(array $fields) {
        $props = [];
        $placeholders = [];
        $values = [];
        
        foreach($fields as $key => $value){
            $props[] = "`{$key}`";
            $placeholders[] = '?';
            $values[] = $value;
        }
        
        $propViaSemicolon = implode(', ', $props);
        $placeholderViaSemicolon = implode(', ', $placeholders);
        
        $sql = "INSERT INTO `{$this->tableName}` ({$propViaSemicolon}) VALUES ({$placeholderViaSemicolon})";
        
        return $this->db->querySql($sql, $values);
    }

    public function update(array $fields, int $id): bool {
        $setParts = [];
        foreach($fields as $key => $value){
            $setParts[] = "`{$key}` = '{$value}'"; 
        }
        
    
        $setString = implode(', ', $setParts);
        $sql = "UPDATE `{$this->tableName}` SET {$setString} WHERE `id` = {$id}";
        return $this->db->querySql($sql);
    }    

    public function delete($id) {
        $sql = "DELETE FROM `{$this->tableName}` WHERE `id` = {$id}";
        return $this->db->querySql($sql);
    }

    public function findAll() : ?array{
        $sql = 'SELECT * FROM ' . $this->tableName;
        $result = $this->db->querySql($sql);
        if($result === []) return null;
        return $result;
    }

    public function find(string $table, string $column, mixed $value) : bool {
        $sql = "SELECT EXISTS (SELECT 1 FROM `{$table}` WHERE `{$column}` = '{$value}') AS `is_exists`";
        
        $result = $this->db->querySql($sql);

        if (isset($result[0]['is_exists'])) {
            return $result[0]['is_exists'];
        }
    
        return false;
    }

    public function findByColumn(string $columnName, $value, int $limit = 0): ?array {
        $sql = "SELECT * FROM `{$this->tableName}` WHERE `{$columnName}` = ?";
        $params = [$value];
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }
        $result = $this->db->querySql($sql, $params);
    
        return (!empty($result)) ? $result : null;
    }

    public function getById(int $id): ?array{
        $sql = "SELECT * FROM $this->tableName WHERE id = $id";
        $result = $this->db->querySQL($sql);
        return $result ? $result[0] : null;
    } 
}

