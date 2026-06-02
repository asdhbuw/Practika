<?php
namespace src;
use src\exeptions\InvalidArgumentException;
use src\Entity;

class Application extends Entity{
    protected string $tableName = 'request';

    public int $user_id;
    public string $visit_date;
    public string $visit_time;
    public string $text;
    public string $status_id;

    public function validate(){
        // Проверка даты
        if (empty($this->visit_date)) {
            throw new InvalidArgumentException("Не указана дата посещения");
        }
        
        $date = \DateTime::createFromFormat('Y-m-d', $this->visit_date);
        if (!$date || $date->format('Y-m-d') !== $this->visit_date) {
            throw new InvalidArgumentException("Неверный формат даты");
        }
        
        // Дата не должна быть в прошлом
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        if ($date < $today) {
            throw new InvalidArgumentException("Дата посещения не может быть в прошлом");
        }
        
        // Проверка времени
        if (empty($this->visit_time)) {
            throw new InvalidArgumentException("Не указано время посещения");
        }
        
        $time = \DateTime::createFromFormat('H:i', $this->visit_time);
        if (!$time) {
            throw new InvalidArgumentException("Неверный формат времени");
        }
        
        // Проверка текста
        if (empty($this->text)) {
            throw new InvalidArgumentException("Не указано описание");
        }
        
        if (strlen($this->text) < 10) {
            throw new InvalidArgumentException("Описание должно содержать минимум 10 символов");
        }
        
        if (strlen($this->text) > 1000) {
            throw new InvalidArgumentException("Описание не должно превышать 1000 символов");
        }

    }

    public function saveApplication(){
        $fields = [
            'user_id' => $this->user_id,
            'visit_date' => $this->visit_date,
            'visit_time' => $this->visit_time,
            'text' => $this->text,
            'status_id' => 5
        ];

        return $this->insert($fields);
    }

    // Получить заявки пользователя
    public function getByUserId(int $userId): ?array {
        return $this->findByColumn('user_id', $userId);
    }

    public function belongsToUser(int $appId, int $userId){
        $app = $this->getById($appId);
        return $app && $app['user_id'] == $userId;
    }

    // Получить заявки с информацией о статусе
    public function getByUserIdWithStatus(int $userId): ?array {
        $sql = "SELECT a.*, s.name as status_name 
                FROM {$this->tableName} a 
                LEFT JOIN status s ON a.status_id = s.id 
                WHERE a.user_id = ? 
                ORDER BY a.create_at DESC";
        
        $result = $this->db->querySql($sql, [$userId]);
        return !empty($result) ? $result : null;
    }

    public function getVisitDate() {
        return $this->visit_date ?? '';
    }
    public function getVisitTime() {
        return $this->visit_time ?? '';
    }
    public function getText() {
        return $this->text ?? '';
    }
}