<?php

namespace src;

use src\exeptions\InvalidArgumentException;
use src\services\Request;
use src\services\Db;

class Admin {
    
    protected string $tableName = 'request';
    protected Request $request;
    protected Db $db;
    
    public string $new_visit_date;
    public string $new_visit_time;
    
    public function __construct(Request $request, Db $db) {
        $this->request = $request;
        $this->db = $db;
    }
    
    // Получить все заявки всех пользователей
    public function getAllApplications(): ?array {
        $sql = "SELECT a.*, s.name as status_name, u.name as user_name 
                FROM {$this->tableName} a 
                LEFT JOIN status s ON a.status_id = s.id 
                LEFT JOIN user u ON a.user_id = u.id 
                ORDER BY a.create_at DESC";
        
        $result = $this->db->querySql($sql);
        return !empty($result) ? $result : null;
    }
    
    // Получить заявку по ID
    public function getById(int $id): ?array {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $result = $this->db->querySql($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    // Получить заявку с полной информацией о пользователе
    public function getApplicationWithUserInfo(int $appId): ?array {
        $sql = "SELECT a.*, s.name as status_name, u.name as user_name, u.phone as user_phone, u.email as user_email
                FROM {$this->tableName} a 
                LEFT JOIN status s ON a.status_id = s.id 
                LEFT JOIN user u ON a.user_id = u.id
                WHERE a.id = ?";
        
        $result = $this->db->querySql($sql, [$appId]);
        return !empty($result) ? $result[0] : null;
    }
    
    // Обновление записи заявки
    protected function update(array $fields, int $id): bool {
        $setParts = [];
        foreach($fields as $key => $value){
            $setParts[] = "`{$key}` = '{$value}'"; 
        }
        
        $setString = implode(', ', $setParts);
        $sql = "UPDATE `{$this->tableName}` SET {$setString} WHERE `id` = {$id}";
        return $this->db->querySql($sql);
    }
    
    // изменение статуса заявки
    public function changeStatus(int $appId, int $newStatusId): bool {
        return $this->update(['status_id' => $newStatusId], $appId);
    }
    
    // Валидация изменения времени заявки
    public function validateTimeChange(): void {
        // Валидация даты
        if (empty($this->new_visit_date)) {
            throw new InvalidArgumentException("Не указана дата посещения");
        }
        
        $date = \DateTime::createFromFormat('Y-m-d', $this->new_visit_date);
        if (!$date || $date->format('Y-m-d') !== $this->new_visit_date) {
            throw new InvalidArgumentException("Неверный формат даты");
        }
        
        // Валидация времени
        if (empty($this->new_visit_time)) {
            throw new InvalidArgumentException("Не указано время посещения");
        }
        
        $time = \DateTime::createFromFormat('H:i', $this->new_visit_time);
        if (!$time) {
            throw new InvalidArgumentException("Неверный формат времени");
        }
    }
    
    // Изменить время посещения заявки (автоматически меняет статус на "Перенесена")
    public function changeApplicationTime(int $appId): bool {
        $this->validateTimeChange();
        
        
        return $this->update([
            'visit_date' => $this->new_visit_date,
            'visit_time' => $this->new_visit_time,
            'status_id' => 10
        ], $appId);
    }
    
    // Проверка доступности действий в зависимости от статуса
    public function canConfirm(int $statusId): bool {
        return $statusId == 5; // Только для статуса "Создана"
    }
    
    public function canStart(int $statusId): bool {
        return $statusId == 6 || $statusId == 10; // "Подтверждена" или "Перенесена"
    }
    
    public function canComplete(int $statusId): bool {
        return $statusId == 7; // "В работе"
    }
    
    public function canCancel(int $statusId): bool {
        return $statusId != 8 && $statusId != 9; // Нельзя отменить "Выполнена" и "Отменена"
    }
    
    public function isCompleted(int $statusId): bool {
        return $statusId == 8; // "Выполнена"
    }
    
    public function isCancelled(int $statusId): bool {
        return $statusId == 9; // "Отменена"
    }
}
