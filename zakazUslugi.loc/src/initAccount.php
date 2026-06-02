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

// Фильтрация по статусу
if($request->isGet && !empty($request->get('status')) && $applications) {
    $filterStatus = $request->get('status');
    
    if(is_numeric($filterStatus)) {
        $applications = array_filter($applications, function($app) use ($filterStatus) {
            return $app['status_id'] == $filterStatus;
        });
    }
}

// Обработка удаления заявки
if($request->isGet && !empty($request->get('delete'))) {
    $deleteId = $request->get('delete');
    
    if(is_numeric($deleteId)) {
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
        
        header('Location: account.php');
        exit();
    }
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
