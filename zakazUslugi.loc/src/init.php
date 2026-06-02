<?php

session_start(); 

require 'autoload.php';
require 'config.php';

try{
    $request = new src\services\Request();
    $db = new src\services\Db($dbOptions);
    
    $user = new src\User($request, $db);
    
   
    $token = $_COOKIE['auth_token'] ?? null;
    
    if($token){
        $userData = $user->identity();
        if($userData){
            // Загружаем данные пользователя в объект $user
            $user->load($userData);
        }
    }
    
    
    $accessRules = [
        'public' => [
            'index.php',
            'feedback.php',
            'login.php',
            'test-auth.php',
            'test-findByColumn.php',
            '404.php'
        ],
        
        'client' => [
            'account.php',
            'add-application.php',
            'application.php',
            'change-password.php',
            'addreview.php'
        ],
        
        'admin' => [
            'register.php',
            'admin-panel.php',
            'admin-app.php',
            'admin-reviews.php'
        ]
    ];
    
    function checkAccess($rules, $user) {
        $currentFile = basename($_SERVER['PHP_SELF']);
        
        // Публичные страницы - доступны всем
        if(in_array($currentFile, $rules['public'])){
            return true;
        }
        
        // Проверяем авторизацию по наличию данных в объекте $user
        if(!isset($user->id)){
            // Не авторизован - редирект на логин
            $_SESSION['flash'] = 'Для доступа к этой странице необходимо авторизоваться';
            header('Location: http://localhost/zakazUslugi.loc/login.php');
            exit();
        }
        
        $userRole = $user->role;
        
        // Админ имеет доступ ко всем страницам
        if($userRole === 'admin'){
            return true;
        }
        
        // Клиент - проверяем доступ к страницам клиента
        if($userRole === 'client'){
            if(in_array($currentFile, $rules['client'])){
                return true;
            }
            
            // Попытка доступа к админским страницам
            if(in_array($currentFile, $rules['admin'])){
                $_SESSION['flash'] = 'У вас нет прав доступа к этой странице';
                header('Location: http://localhost/zakazUslugi.loc/account.php');
                exit();
            }
        }
        
        // По умолчанию - нет доступа
        $_SESSION['flash'] = 'Доступ запрещен';
        header('Location: http://localhost/zakazUslugi.loc/login.php');
        exit();
    }
    
    checkAccess($accessRules, $user);
    
}catch(\src\exeptions\DbExeption $e){
    echo $e->getMessage();
    exit();
}
