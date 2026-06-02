<?php

use src\Feedback;
use src\services\Request;
use src\services\Db;

require 'init.php';

$feedback = new Feedback($request, $db);

if($request->isPost){
    $feedback->loadFromForm($request->post(), $_FILES['img']);
    try{
        $feedback->validateFeedback();
        if($feedback->save()){
            $_SESSION['flash'] = 'Отзыв добавлен!';
            
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



$feedbacks = $feedback->findAll();

//var_dump($feedbacks);
