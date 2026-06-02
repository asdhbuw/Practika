<?php
$csrfToken = "hPWly1ZKgEDNpONkNJz-WDT_UZvzK8XeQLsz3I6kCOfewsizOgvUDK6WsRZ59NMOBskiqYVfoa8r7H7s2NBfig==";
$currentPage = 'admin-panel';
$isLoggedIn = false;
require 'src/initAdminPanel.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">
    
<head>
    <title>заявки</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol id="w4" class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">заявки</li>
                </ol>
            </nav>
            <div class="application-index">

                <h1>Панель администратора - Заявки на сегодня</h1>

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
                    <div class="application-search">

                        <form id="w0" action="admin-panel.php" method="get">
                            <div class="form-group field-applicationsearch-status_id">
                                <label class="control-label" for="applicationsearch-status_id">Фильтр по статусу</label>
                                <select id="applicationsearch-status_id" class="form-control" name="status">
                                    <option value="">Все статусы</option>
                                    <option value="5" <?= (isset($_GET['status']) && $_GET['status'] == '5') ? 'selected' : '' ?>>Создана</option>
                                    <option value="6" <?= (isset($_GET['status']) && $_GET['status'] == '6') ? 'selected' : '' ?>>Подтверждена</option>
                                    <option value="7" <?= (isset($_GET['status']) && $_GET['status'] == '7') ? 'selected' : '' ?>>В работе</option>
                                    <option value="8" <?= (isset($_GET['status']) && $_GET['status'] == '8') ? 'selected' : '' ?>>Выполнена</option>
                                    <option value="9" <?= (isset($_GET['status']) && $_GET['status'] == '9') ? 'selected' : '' ?>>Отменена</option>
                                    <option value="10" <?= (isset($_GET['status']) && $_GET['status'] == '10') ? 'selected' : '' ?>>Перенесена</option>
                                </select>

                                <div class="help-block"></div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Найти</button>
                                <a class="btn btn-outline-secondary" href="admin-panel.php">Сбросить</a>
                            </div>

                        </form>
                    </div>

                    <div id="w1" class="list-view">
                        <div class="d-flex flex-wrap justify-content-between layout-card">
                            
                            <?php if($applications && count($applications) > 0): ?>
                                <?php foreach($applications as $app): ?>
                                    <div class="item" data-key="<?= $app['id'] ?>">
                                        <div class="card" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title">Заявка №<?= htmlspecialchars($app['id']) ?></h5>
                                                <p class="card-text"><?= htmlspecialchars($app['text']) ?></p>
                                                <div class="card-text">
                                                    <div class="opacity-50">дата и время посещения:</div>
                                                    <?= htmlspecialchars($app['visit_date']) ?> <?= htmlspecialchars($app['visit_time']) ?>
                                                </div>
                                                <div class="card-text">
                                                    <div class="opacity-50">дата и время создания:</div>
                                                    <?= htmlspecialchars($app['create_at']) ?>
                                                </div>
                                                <div class="card-text">
                                                    <div class="opacity-50">отправитель:</div>
                                                    <?= htmlspecialchars($app['user_name']) ?>
                                                </div>
                                                <div class="card-text">
                                                    <div class="opacity-50">статус:</div>
                                                    <?= htmlspecialchars($app['status_name']) ?>
                                                </div>
                                                
                                                <a class="btn btn-primary" href="admin-app.php?id=<?= $app['id'] ?>">Просмотр</a>
                                                
                                                <?php if($app['status_id'] == 5): ?>
                                                    <!-- Статус "Создана" - можно подтвердить или отменить -->
                                                    <a class="btn btn-success" href="admin-panel.php?id=<?= $app['id'] ?>&action=confirm">Подтвердить</a>
                                                    <a class="btn btn-danger" href="admin-panel.php?id=<?= $app['id'] ?>&action=cancel" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">Отменить</a>
                                                <?php elseif($app['status_id'] == 6 || $app['status_id'] == 10): ?>
                                                    <!-- Статус "Подтверждена" или "Перенесена" - можно начать работу или отменить -->
                                                    <a class="btn btn-info" href="admin-panel.php?id=<?= $app['id'] ?>&action=start">Начать работу</a>
                                                    <a class="btn btn-danger" href="admin-panel.php?id=<?= $app['id'] ?>&action=cancel" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">Отменить</a>
                                                <?php elseif($app['status_id'] == 7): ?>
                                                    <!-- Статус "В работе" - можно завершить или отменить -->
                                                    <a class="btn btn-warning" href="admin-panel.php?id=<?= $app['id'] ?>&action=complete">Завершить</a>
                                                    <a class="btn btn-danger" href="admin-panel.php?id=<?= $app['id'] ?>&action=cancel" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">Отменить</a>
                                                <?php elseif($app['status_id'] == 8): ?>
                                                    <!-- Статус "Выполнена" - кнопки неактивны -->
                                                    <button class="btn btn-secondary" disabled>Выполнена</button>
                                                <?php elseif($app['status_id'] == 9): ?>
                                                    <!-- Статус "Отменена" - кнопки неактивны -->
                                                    <button class="btn btn-secondary" disabled>Отменена</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Нет заявок на сегодня</p>
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
