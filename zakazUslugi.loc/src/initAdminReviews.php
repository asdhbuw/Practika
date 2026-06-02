<?php
use src\Feedback;
use src\services\Request;
use src\services\Db;

require 'init.php';


if(!isset($user->id)) {
    $_SESSION['flash'] = 'Необходимо авторизоваться';
    header('Location: login.php');
    exit();
}

if($user->role !== 'admin') {
    $_SESSION['flash'] = 'Доступ запрещён. Требуются права администратора';
    header('Location: login.php');
    exit();
}

$feedback = new Feedback($request, $db);

// Получаем все отзывы
$reviews = $feedback->getAllWithStatus();

// Обработка изменения статуса
if($request->isGet && !empty($request->get('id')) && !empty($request->get('action'))) {
    $reviewId = $request->get('id');
    $action = $request->get('action');
    
    if(!is_numeric($reviewId)) {
        header('Location: 404.php');
        exit();
    }
    
    // Проверяем существование отзыва
    $reviewToModerate = $feedback->getById((int)$reviewId);
    if(!$reviewToModerate) {
        header('Location: 404.php');
        exit();
    }
    
    $success = false;
    $message = '';
    
    // Определяем новый статус в зависимости от действия
    if($action === 'publish') {
        $success = $feedback->changeStatus((int)$reviewId, 4); // Опубликован
        $message = 'Отзыв опубликован';
    } elseif($action === 'reject') {
        $success = $feedback->changeStatus((int)$reviewId, 3); // Отклонён
        $message = 'Отзыв отклонён';
    }
    
    if($success) {
        $_SESSION['flash'] = $message;
    } else {
        $_SESSION['flash'] = 'Ошибка при изменении статуса';
    }
    
    header('Location: admin-reviews.php');
    exit();
}

if(isset($_SESSION['flash'])){
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
