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

// Получаем все заявки 
$applications = $admin->getAllApplications();

// Фильтрация по статусу
// if($request->isGet && !empty($request->get('status')) && $applications) {
//     $filterStatus = $request->get('status');
    
//     if(is_numeric($filterStatus)) {
//         $applications = $admin->filterByStatus($applications, (int)$filterStatus);
//     }
// }

// Обработка изменения статуса
if($request->isGet && !empty($request->get('id')) && !empty($request->get('action'))) {
    $appId = $request->get('id');
    $action = $request->get('action');
    
    if(!is_numeric($appId)) {
        header('Location: 404.php');
        exit();
    }
    
    // Проверяем существование заявки
    $appToModerate = $admin->getById((int)$appId);
    if(!$appToModerate) {
        header('Location: 404.php');
        exit();
    }
    
    $success = false;
    $message = '';
    
    // Определяем новый статус в зависимости от действия
    if($action === 'confirm') {
        $success = $admin->changeStatus((int)$appId, 6); // Подтверждена
        $message = 'Заявка подтверждена';
    } elseif($action === 'start') {
        $success = $admin->changeStatus((int)$appId, 7); // В работе
        $message = 'Работа над заявкой начата';
    } elseif($action === 'complete') {
        $success = $admin->changeStatus((int)$appId, 8); // Выполнена
        $message = 'Заявка выполнена';
    } elseif($action === 'cancel') {
        $success = $admin->changeStatus((int)$appId, 9); // Отменена
        $message = 'Заявка отменена';
    }
    
    if($success) {
        $_SESSION['flash'] = $message;
    } else {
        $_SESSION['flash'] = 'Ошибка при изменении статуса';
    }
    
    header('Location: admin-panel.php');
    exit();
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
