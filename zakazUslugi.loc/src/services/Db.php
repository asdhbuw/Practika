<?php

namespace src\services;

use src\exeptions\DbExeption;

class Db extends \mysqli{
    public function __construct($config) {
        try{
            parent::__construct(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['dbname']);
        }catch(\mysqli_sql_exception $e){
            throw new DbExeption('Ошибка при подключении базы данных' .
             $e->getMessage());
        }
    }
    public function querySql(string $sql, array $params=[]) : array|bool{
        // Если есть параметры, используем подготовленные запросы
        if(!empty($params)){
            $stmt = $this->prepare($sql);
            if(!$stmt){
                throw new DbExeption('Ошибка подготовки запроса: ' . $this->error);
            }
            
            // Привязываем параметры
            $types = '';
            $values = [];
            foreach($params as $key => $value){
                $types .= 's'; // все параметры как строки
                $values[] = $value;
            }
            
            if(!empty($values)){
                $stmt->bind_param($types, ...$values);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result === false){
                return $stmt->affected_rows > 0;
            }
            
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        // Обычный запрос без параметров
        $result = parent::query($sql);
        
        if(is_bool($result)) return $result;
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}