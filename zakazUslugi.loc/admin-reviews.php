<?php
$csrfToken = "hPWly1ZKgEDNpONkNJz-WDT_UZvzK8XeQLsz3I6kCOfewsizOgvUDK6WsRZ59NMOBskiqYVfoa8r7H7s2NBfig==";
$currentPage = 'admin-reviews';
$isLoggedIn = false;
require 'src/initAdminReviews.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">
    
<head>
    <title>Модерация отзывов</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol id="w4" class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Модерация отзывов</li>
                </ol>
            </nav>
            <div class="reviews-index">

                <h1>Панель администратора - Модерация отзывов</h1>

                <?php if(!empty($error)):?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif ?>
                
                <?php if(!empty($flash)):?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($flash) ?>
                    </div>
                <?php endif ?>

                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="1000">
                    <div class="reviews-search mb-4">
                        <form id="w0" action="admin-reviews.php" method="get">
                            <div class="form-group field-reviewsearch-status_id">
                                <label class="control-label" for="reviewsearch-status_id">Фильтр по статусу</label>
                                <select id="reviewsearch-status_id" class="form-control" name="status">
                                    <option value="">Все статусы</option>
                                    <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : '' ?>>На модерации</option>
                                    <option value="3" <?= (isset($_GET['status']) && $_GET['status'] == '3') ? 'selected' : '' ?>>Отклонён</option>
                                    <option value="4" <?= (isset($_GET['status']) && $_GET['status'] == '4') ? 'selected' : '' ?>>Опубликован</option>
                                </select>
                            </div>

                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary">Найти</button>
                                <a class="btn btn-outline-secondary" href="admin-reviews.php">Сбросить</a>
                            </div>
                        </form>
                    </div>

                    <div id="w1" class="list-view">
                        <div class="row">
                            
                            <?php if($reviews && count($reviews) > 0): ?>
                                <?php 
                                // Фильтрация по статусу если задан
                                if(isset($_GET['status']) && is_numeric($_GET['status'])) {
                                    $filterStatus = (int)$_GET['status'];
                                    $reviews = array_filter($reviews, function($review) use ($filterStatus) {
                                        return $review['status_id'] == $filterStatus;
                                    });
                                }
                                ?>
                                
                                <?php foreach($reviews as $review): ?>
                                    <div class="col-md-6 mb-4" data-key="<?= $review['id'] ?>">
                                        <div class="card h-100">
                                            <?php if(!empty($review['img'])): ?>
                                                <img src="<?= htmlspecialchars($review['img']) ?>" class="card-img-top" alt="Фото отзыва" style="max-height: 200px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title">Отзыв №<?= htmlspecialchars($review['id']) ?></h5>
                                                <p class="card-text"><strong>ФИО:</strong> <?= htmlspecialchars($review['full_name']) ?></p>
                                                <p class="card-text"><strong>Телефон:</strong> <?= htmlspecialchars($review['phone']) ?></p>
                                                <p class="card-text"><strong>Отзыв:</strong> <?= htmlspecialchars($review['text']) ?></p>
                                                <p class="card-text">
                                                    <strong>Статус:</strong> 
                                                    <span class="badge <?= $review['status_id'] == 4 ? 'bg-success' : ($review['status_id'] == 3 ? 'bg-danger' : 'bg-warning') ?>">
                                                        <?= htmlspecialchars($review['status_name']) ?>
                                                    </span>
                                                </p>
                                                
                                                <div class="mt-3">
                                                    <?php if($review['status_id'] == 1): ?>
                                                        <!-- На модерации - показываем обе кнопки -->
                                                        <a class="btn btn-success" href="admin-reviews.php?id=<?= $review['id'] ?>&action=publish">Опубликовать</a>
                                                        <a class="btn btn-danger" href="admin-reviews.php?id=<?= $review['id'] ?>&action=reject" onclick="return confirm('Вы уверены, что хотите отклонить отзыв?')">Отклонить</a>
                                                    <?php elseif($review['status_id'] == 4): ?>
                                                        <!-- Опубликованный - только кнопка отклонить -->
                                                        <a class="btn btn-danger" href="admin-reviews.php?id=<?= $review['id'] ?>&action=reject" onclick="return confirm('Вы уверены, что хотите отклонить отзыв?')">Отклонить</a>
                                                    <?php elseif($review['status_id'] == 3): ?>
                                                        <!-- Отклонённый - только кнопка опубликовать -->
                                                        <a class="btn btn-success" href="admin-reviews.php?id=<?= $review['id'] ?>&action=publish">Опубликовать</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Нет отзывов</p>
                            <?php endif; ?>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

<?php include 'src/footer.php'; ?>

<?php include 'src/scripts.php'; ?>

</body>

</html>
