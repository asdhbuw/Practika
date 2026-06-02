<?php

use src\User;
use src\services\Request;
use src\services\Db;

require 'init.php';


// Создаем новый экземпляр для регистрации нового пользователя
$newUser = new User($request, $db);

if($request->isPost){
    $newUser->loadFromForm($request->post());
    try{
        $newUser->validate();
        if($newUser->save()){
            $_SESSION['flash'] = 'Регистрация успешна!';
            
            // Перенаправление на ту же страницу для предотвращения повторной отправки формы
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }catch(src\exeptions\InvalidArgumentException $e){
        $error = $e->getMessage();
    }
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
