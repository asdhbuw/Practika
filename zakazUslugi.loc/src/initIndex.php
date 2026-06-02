<?php

use src\Feedback;

require 'init.php';

// Получаем опубликованные отзывы для главной страницы
$feedback = new Feedback($request, $db);
$publishedReviews = $feedback->getPublished();