<?php

use src\User;
use src\services\Request;
use src\services\Db;
require 'init.php';

// Обработка выхода
if(isset($_GET['action']) && $_GET['action'] === 'logout'){
    if($user->logout()){
        $_SESSION['flash'] = 'Вы успешно вышли из системы';
    }
    header('Location: http://localhost/zakazUslugi.loc/');
    exit();
}

$loginUser = new User($request, $db);

if($request->isPost){
    $loginUser->loadFromForm($request->post());
    
    // Сохраняем введенный логин для повторного заполнения формы
    $loginValue = $loginUser->loginAuth ?? '';
    
    try{
        $loginUser->validateAuth();
        if($loginUser->login()){
            
            $_SESSION['flash'] = 'Авторизация успешна!';
            
            // Получаем данные пользователя для определения роли
            $userData = $loginUser->identity();
            if($userData){
                $loginUser->load($userData);
            }
            
            // Редирект в зависимости от роли
            if($loginUser->role === 'admin'){
                header('Location: http://localhost/zakazUslugi.loc/admin-panel.php');
            }else{
                header('Location: http://localhost/zakazUslugi.loc/');
            }
            exit();
        }else{
            $error = 'Неверный логин или пароль';
        }
    }catch(src\exeptions\InvalidArgumentException $e){
        $error = $e->getMessage();
    }
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
