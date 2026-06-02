<?php
session_start();

require 'src/config.php';
require 'src/services/Db.php';

if(!isset($_SESSION['user_id'])) {
    die('Вы не авторизованы. <a href="login.php">Войти</a>');
}

try {
    $db = new src\services\Db($dbOptions);
    
    // Получаем актуальные данные пользователя из БД
    $sql = "SELECT `id`, `login`, `role` FROM `user` WHERE `id` = ?";
    $result = $db->querySql($sql, [$_SESSION['user_id']]);
    
    if(!empty($result)) {
        $userData = $result[0];
        
        // Обновляем сессию
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_login'] = $userData['login'];
        $_SESSION['user_role'] = $userData['role'];
        
        echo '<h1>✅ Сессия обновлена!</h1>';
        echo '<p>Новая роль: <strong>' . $userData['role'] . '</strong></p>';
        echo '<p><a href="debug-auth.php">Проверить авторизацию</a></p>';
        
        if($userData['role'] === 'admin') {
            echo '<p><a href="admin-panel.php">Перейти в админ-панель</a></p>';
        }
    } else {
        echo 'Пользователь не найден в БД';
    }
    
} catch(Exception $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
?>
