<?php
use src\Application;
use src\services\Request;
use src\services\Db;

require 'init.php';

$application = new Application($request, $db);

// Получаем заявки текущего пользователя с информацией о статусе
$applications = null;
if(isset($user->id)) {
    $applications = $application->getByUserIdWithStatus($user->id);
}

// Обработка удаления заявки
if($request->isGet && !empty($request->get('delete'))) {
    $deleteId = $request->get('delete');
    
    if(!is_numeric($deleteId)) {
        header('Location: 404.php');
        exit();
    }
    
    // Проверяем существование заявки
    $appToDelete = $application->getById((int)$deleteId);
    if(!$appToDelete) {
        header('Location: 404.php');
        exit();
    }
    
    // Проверяем принадлежность заявки пользователю
    if($application->belongsToUser((int)$deleteId, $user->id)) {
        if($application->delete((int)$deleteId)) {
            $_SESSION['flash'] = 'Заявка успешно удалена';
        } else {
            $_SESSION['flash'] = 'Ошибка при удалении заявки';
        }
    } else {
        $_SESSION['flash'] = 'Вы не можете удалить эту заявку';
    }
    
    header('Location: application.php');
    exit();
}

if($request->isPost) {
    $application->load($request->post());
    $application->user_id = $user->id; 
    
    try {
        $application->validate();
        if($application->saveApplication()) {
            $_SESSION['flash'] = 'Заявка создана!';
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
