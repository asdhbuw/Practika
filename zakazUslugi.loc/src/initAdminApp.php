<?php
use src\Admin;
use src\services\Request;
use src\services\Db;

require 'init.php';

// Проверка авторизации пользователя
if(!isset($user->id)) {
    $_SESSION['flash'] = 'Необходимо авторизоваться';
    header('Location: login.php');
    exit();
}

// Проверка прав администратора
if($user->role !== 'admin') {
    $_SESSION['flash'] = 'Доступ запрещён. Требуются права администратора';
    header('Location: login.php');
    exit();
}

$admin = new Admin($request, $db);
$appData = null;
$error = null;

// Получаем ID заявки из GET-параметра
$appId = $request->get('id');

if(empty($appId) || !is_numeric($appId)) {
    header('Location: 404.php');
    exit();
} else {
    // Получаем данные заявки с полной информацией
    $appData = $admin->getApplicationWithUserInfo((int)$appId);
    
    if(!$appData) {
        header('Location: 404.php');
        exit();
    }
}

// Обработка изменения времени заявки
if($request->isPost && $appData) {
    $admin->new_visit_date = $request->post('visit_date');
    $admin->new_visit_time = $request->post('visit_time');
    
    try {
        if($admin->changeApplicationTime((int)$appId)) {
            $_SESSION['flash'] = 'Время посещения успешно изменено';
            header('Location: admin-app.php?id=' . $appId);
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
