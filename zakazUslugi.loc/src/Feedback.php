<?php

namespace src;

use src\exeptions\InvalidArgumentException;
use src\Entity;


class Feedback extends Entity{
    protected string $tableName = 'feedback';
    
    
    public string $full_name;
    public string $phone;
    public string $text;
    public array $img;
    public ?int $status_id = null;
    public ?string $create_add;
    public string $agree;

    public function getName(){
        return $this->full_name ?? '';
    }

    public function getPhone(){
        return $this->phone ?? '';
    }

    public function getFeedback(){
        return $this->text ?? '';
    }
    

    public function loadFromForm(array $fields, array $files){
        $fields['img'] = $files;
        $this->load($fields);
    }

    public function validateFeedback(): void {
        // Проверка наличия ФИО
        if (empty($this->full_name)) {
            throw new InvalidArgumentException("Не передано ФИО");
        }

        // Проверка минимальной длины ФИО
        if (strlen($this->full_name) < 3) {
            throw new InvalidArgumentException("ФИО должно содержать минимум 3 символа");
        }

        // Проверка максимальной длины ФИО
        if (strlen($this->full_name) > 60) {
            throw new InvalidArgumentException("ФИО не должно превышать 60 символов");
        }

        //регулярные выражения
        if(!preg_match('/^[а-яА-Я\s\-]+$/u', $this->full_name)){
            throw new InvalidArgumentException("не то чета с нэймом");
        }
        if(!preg_match('/^[а-яА-Я\-]+\s[а-яА-Я]+\s[а-яА-Я]+$/u', $this->full_name)){
            throw new InvalidArgumentException("не то чета с нэймом");
        }

        //проверка телефона
        // Телефон содержит только цифры, пробелы, дефисы, скобки и знак +
        if(!preg_match('/^[\d\s\-\(\)\+]+$/', $this->phone)){
            throw new InvalidArgumentException("Телефон содержит недопустимые символы");
        }

        // Телефон в формате +7 (XXX) XXX-XX-XX или 8XXXXXXXXXX
        if(!preg_match('/^(\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/', $this->phone)){
            throw new InvalidArgumentException("Неверный формат телефона. Используйте формат: +7 (XXX) XXX-XX-XX");
        }

        
        // Проверка наличия текста отзыва
        if (empty($this->text)) {
            throw new InvalidArgumentException("Не передан текст отзыва");
        }

        // Проверка минимальной длины текста отзыва
        if (strlen($this->text) < 10) {
            throw new InvalidArgumentException("Текст отзыва должен содержать минимум 10 символов");
        }

        // Проверка максимальной длины текста отзыва
        if (strlen($this->text) > 1000) {
            throw new InvalidArgumentException("Текст отзыва не должен превышать 1000 символов");
        }


        if(empty($this->img)){
            throw new InvalidArgumentException("Файл не загружен");
        }

        if(empty($this->agree)){
            throw new InvalidArgumentException('необходимо принять согласие');
        }
        
    }

    public function save(): bool{
        $pathFile = 'uploads/' . $this->img['name'];
        if(!move_uploaded_file($this->img['tmp_name'], $pathFile)){
            throw new InvalidArgumentException('Ошибка при загрузке файла');
        }

        $fields = ['full_name' => $this->full_name, 'phone' => $this->phone,
         'text' => $this->text, 'img' => $pathFile, 'status_id' => 1];

        return $this->insert($fields);
    }

    // Получить все отзывы с названием статуса
    public function getAllWithStatus(): ?array {
        $sql = "SELECT f.*, s.name as status_name 
                FROM {$this->tableName} f 
                LEFT JOIN status s ON f.status_id = s.id 
                ORDER BY f.id DESC";
        
        $result = $this->db->querySql($sql);
        return !empty($result) ? $result : null;
    }

    // Получить только опубликованные отзывы
    public function getPublished(): ?array {
        $sql = "SELECT f.*, s.name as status_name 
                FROM {$this->tableName} f 
                LEFT JOIN status s ON f.status_id = s.id 
                WHERE f.status_id = 4
                ORDER BY f.id DESC";
        
        $result = $this->db->querySql($sql);
        return !empty($result) ? $result : null;
    }

    // Изменить статус отзыва
    public function changeStatus(int $feedbackId, int $newStatusId): bool {
        return $this->update(['status_id' => $newStatusId], $feedbackId);
    }
}
