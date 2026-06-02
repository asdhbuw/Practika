<?php
use src\User;
use src\services\Request;
use src\services\Db;

require 'init.php';

// Объект $user уже загружен в init.php с данными из токена
// Не нужно создавать новый объект или загружать данные заново

if($request->isPost) {
    $currentPassword = $request->post('currentPassword');
    $newPassword = $request->post('newPassword');
    $retypePassword = $request->post('retypePassword');
    
    try {
        if($user->changePassword($currentPassword, $newPassword, $retypePassword)) {
            $_SESSION['flash'] = 'Пароль успешно изменён!';
            header('Location: account.php');
            exit();
        }
    } catch(src\exeptions\InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
