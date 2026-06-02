<?php

namespace src;

use src\exeptions\InvalidArgumentException;
use src\Entity;


class User extends Entity{
    protected string $tableName = 'user';

    public int $id;
    public string $full_name;
    public string $login;
    public string $email;
    public string $password;
    public string $phone;
    public string $passwordTwo;
    public string $token;
    public string $role = 'client'; // значение по умолчанию

    public string $loginAuth;
    public string $passwordAuth;

    public bool $isGuest;
    public bool $isAdmin;

    public function loadFromForm(array $fields){
        $this->load($fields);
    }

    public function validate() : void{
        //проверка наличия логина
        if (empty($this->login)) {
            throw new InvalidArgumentException("Не передан логин");
        }
        
        if($this->find($this->tableName, 'login', $this->login)){
            throw new InvalidArgumentException("Такой логин занят");
        }

        // Проверка минимальной длины логина
        if (strlen($this->login) < 3) {
            throw new InvalidArgumentException("Логин должен содержать минимум 3 символа");
        }
        
        // Проверка максимальной длины логина
        if (strlen($this->login) > 32) {
            throw new InvalidArgumentException("Логин не должен превышать 32 символа");
        }

        //проверка наличия фио
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
        if(!preg_match('/^[а-яА-Я\s\-]+$/u', $this->full_name)){
            throw new InvalidArgumentException("ФИО должно содержать только русские буквы, пробелы и дефисы");
        }
        if(!preg_match('/^[а-яА-Я\-]+\s[а-яА-Я]+\s[а-яА-Я]+$/u', $this->full_name)){
            throw new InvalidArgumentException("ФИО должно содержать Фамилию Имя Отчество");
        }


        // Проверка наличия пароля
        if (empty($this->password)) {
            throw new InvalidArgumentException("Не передан пароль");
        }
        // Проверка минимальной длины пароля
        if (strlen($this->password) < 8) {
            throw new InvalidArgumentException("Пароль должен содержать минимум 8 символов");
        }
        // Проверка максимальной длины пароля
        if (strlen($this->password) > 50) {
            throw new InvalidArgumentException("Пароль не должен превышать 50 символов");
        }
        // Пароль должен содержать хотя бы одну заглавную букву
        if (!preg_match('/[A-ZА-Я]/u', $this->password)) {
            throw new InvalidArgumentException("Пароль должен содержать хотя бы одну заглавную букву");
        }
        // Пароль должен содержать хотя бы одну цифру
        if (!preg_match('/\d/', $this->password)) {
            throw new InvalidArgumentException("Пароль должен содержать хотя бы одну цифру");
        }
        // Пароль должен содержать хотя бы один специальный символ
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $this->password)) {
            throw new InvalidArgumentException("Пароль должен содержать хотя бы один специальный символ (!@#$%^&* и т.д.)");
        }
        if (empty($this->passwordTwo)) {
            throw new InvalidArgumentException("Не передано подтверждение пароля");
        }
        //проверка соответствия повторного пароля
        if($this->password != $this->passwordTwo){
            throw new InvalidArgumentException("Пароли должны соответствовать");
        }

        //проверка email
        // Проверка наличия email
        if (empty($this->email)) {
            throw new InvalidArgumentException("Не передан email");
        }
        if($this->find($this->tableName, 'email', $this->email)){
            throw new InvalidArgumentException("Такой email занят");
        }
        // Проверка минимальной длины email
        if (strlen($this->email) < 5) {
            throw new InvalidArgumentException("Email должен содержать минимум 5 символов");
        }
        // Проверка максимальной длины email
        if (strlen($this->email) > 100) {
            throw new InvalidArgumentException("Email не должен превышать 100 символов");
        }
        // Проверка формата email
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Неверный формат email");
        }

        // проверка телефона
        if(!preg_match('/^[\d\s\-\(\)\+]+$/', $this->phone)){
            throw new InvalidArgumentException("Телефон содержит недопустимые символы");
        }
        // Телефон в формате +7 (XXX) XXX-XX-XX или 8XXXXXXXXXX
        if(!preg_match('/^(\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/', $this->phone)){
            throw new InvalidArgumentException("Неверный формат телефона. Используйте формат: +7 (XXX) XXX-XX-XX");
        }

    }

    public function validateAuth() : void{
        if (empty($this->loginAuth)) {
            throw new InvalidArgumentException("Не передан логин");
        }
        

        if (empty($this->passwordAuth)) {
            throw new InvalidArgumentException("Не передан пароль");
        }

    }

    public function refrechAuthToken(): string{
        // Генерируем уникальный токен (64 символа)
        $this->token = bin2hex(random_bytes(32));
        return $this->token;
    }
    
    public function createTokenCookie(string $token): void{
        // Записываем токен в cookie на 30 дней
        // httponly=true защищает от XSS атак
        // secure=false для локальной разработки (для HTTPS ставьте true)
        setcookie(
            'auth_token',           // имя cookie
            $token,                 // значение
            time() + (30 * 24 * 60 * 60), // срок жизни: 30 дней
            '/',                    // путь
            '',                     // домен
            false,                  // secure (для HTTPS = true)
            true                    // httponly (защита от JS)
        );
    }

    public function login(): bool{
        
        $userData = $this->findByColumn('login', $this->loginAuth, 1);

        if(!$userData){
            return false;
        }
        
       
        if($userData[0]['password'] === $this->passwordAuth){
            $token = $this->refrechAuthToken();
            
            // Сохраняем токен в БД
            $this->update(['token' => $token], $userData[0]['id']);
            
            // Записываем токен в cookie (БЕЗ сессии!)
            $this->createTokenCookie($token);
            
            return true;
        }
        
        return false;
    }   

    public function logout(): bool {
        // Получаем токен из cookie
        $token = $_COOKIE['auth_token'] ?? null;
        
        if($token){
            // Ищем пользователя по токену
            $userData = $this->findByColumn('token', $token, 1);
            
            if($userData){
                // Удаляем токен из БД
                $this->update(['token' => null], $userData[0]['id']);
            }
            
            // Удаляем cookie
            setcookie('auth_token', '', time() - 3600, '/');
            
            return true;
        }
        
        return false;
    }

    public function identity(): ?array{
        // Получаем токен из cookie
        $token = $_COOKIE['auth_token'] ?? null;
        
        if(!$token){
            return null;
        }
        
        
        $userData = $this->findByColumn('token', $token, 1);
        
        if($userData){
            return $userData[0]; 
        }
        
        return null;
    }

    public function save(): bool{
        // хеширование пароля перед сохранением
        //$hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $fields = [
            'name' => $this->full_name,
            'login' => $this->login,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => 'client'
        ];

        return $this->insert($fields);
    }
 
    public function getLogin(){
        return $this->login ?? '';
    }
    public function getLoginAuth(){
        return $this->loginAuth ?? '';
    }
    public function getPassword(){
        return $this->password ?? '';
    }
    public function getName(){
        return $this->full_name ?? '';
    }
    public function getEmail(){
        return $this->email ?? '';
    }
    public function getPhone(){
        return $this->phone ?? '';
    }

    public function changePassword(string $currentPassword, string $newPassword, string $retypePassword): bool {
        // Получаем данные текущего пользователя из БД
        $userData = $this->getById($this->id);
        
        if(!$userData) {
            throw new InvalidArgumentException("Пользователь не найден");
        }
        
        // Проверяем текущий пароль
        if($userData['password'] !== $currentPassword) {
            throw new InvalidArgumentException("Неверный текущий пароль");
        }
        
        // Валидация нового пароля
        if (empty($newPassword)) {
            throw new InvalidArgumentException("Не передан новый пароль");
        }
        
        if (strlen($newPassword) < 8) {
            throw new InvalidArgumentException("Новый пароль должен содержать минимум 8 символов");
        }
        
        if (strlen($newPassword) > 50) {
            throw new InvalidArgumentException("Новый пароль не должен превышать 50 символов");
        }
        
        if (!preg_match('/[A-ZА-Я]/u', $newPassword)) {
            throw new InvalidArgumentException("Новый пароль должен содержать хотя бы одну заглавную букву");
        }
        
        if (!preg_match('/\d/', $newPassword)) {
            throw new InvalidArgumentException("Новый пароль должен содержать хотя бы одну цифру");
        }
        
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $newPassword)) {
            throw new InvalidArgumentException("Новый пароль должен содержать хотя бы один специальный символ (!@#$%^&* и т.д.)");
        }
        
        // Проверка подтверждения пароля
        if (empty($retypePassword)) {
            throw new InvalidArgumentException("Не передано подтверждение нового пароля");
        }
        
        if($newPassword !== $retypePassword) {
            throw new InvalidArgumentException("Новый пароль и подтверждение не совпадают");
        }
        
        // Проверка, что новый пароль отличается от старого
        if($currentPassword === $newPassword) {
            throw new InvalidArgumentException("Новый пароль должен отличаться от текущего");
        }
        
        // Обновляем пароль в БД
        return $this->update(['password' => $newPassword], $this->id);
    }
    

}