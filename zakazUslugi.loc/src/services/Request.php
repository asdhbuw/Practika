<?php

namespace src\services;

class Request{
    public $isPost = false;
    public $isGet = false;
    public function __construct() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->isPost = true;
        }elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->isGet = true;
        }
    }

    public function clearParam($param): string{
        // Проверяем на null и приводим к строке
        if($param === null) {
            return '';
        }
        return trim(strip_tags((string)$param));
    }
    public function clearArray($array): array{
        $result = [];
        foreach($array as $key => $value){
            if(gettype($value) === 'array'){
                $result[$key] = $this->clearArray($value);
                continue;
            }
            $result[$key] = $this->clearParam($value);
        }
        return $result;
    }
    public function post($param = null): array|string{
        if($param){
            return $this->clearParam($_POST[$param] ?? null);
        }else{
            return $this->clearArray($_POST);
        }
    }
    public function get($param = null): array|string
    {
        if($param){
            return $this->clearParam($_GET[$param] ?? null);
        }else{
            return $this->clearArray($_GET);
        }
    }

}