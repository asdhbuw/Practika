<?php

spl_autoload_register(function($class){
    // Заменяем namespace разделители на слэши
    $classPath = str_replace('\\', '/', $class);
    
    // Если класс начинается с src\, убираем src\ так как мы уже в папке src
    if(strpos($classPath, 'src/') === 0) {
        $classPath = substr($classPath, 4);
    }
    
    $file = __DIR__ . '/' . $classPath . '.php';
    
    if(file_exists($file)) {
        require $file;
    }
});