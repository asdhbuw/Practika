<?php
$csrfToken = "Y8NMvvT3LR7_0FE4QlfcxYPKc6Y2OK44IrCGNdMqbagnjTjstcRneKWCP3EvEIap9vpLxXJbx1sS4-9C6ksc4w==";
$currentPage = 'admin-app';
$isLoggedIn = false;
require 'src/initAdminApp.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">

<head>
    <title>Просмотр заявки</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">
   
<?php include 'src/header.php'; ?>
    
    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol id="w4" class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Главная</a></li>
                    <li class="breadcrumb-item"><a href="admin-panel.php">Панель администратора</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Просмотр заявки</li>
                </ol>
            </nav>

            <?php if(!empty($error)):?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
                <a href="admin-panel.php" class="btn btn-primary">Вернуться к списку заявок</a>
            <?php elseif($appData): ?>
                <h1>Заявка №<?= htmlspecialchars($appData['id']) ?></h1>

                <?php if(!empty($flash)):?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($flash) ?>
                    </div>
                <?php endif ?>

                <!-- Информация о заявке -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Информация о заявке</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">ID заявки:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['id']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Пользователь:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['user_name']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Телефон:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['user_phone']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Email:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['user_email']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Дата посещения:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['visit_date']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Время посещения:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['visit_time']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Описание:</div>
                            <div class="col-md-9"><?= nl2br(htmlspecialchars($appData['text'])) ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Статус:</div>
                            <div class="col-md-9">
                                <span class="badge bg-info"><?= htmlspecialchars($appData['status_name']) ?></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Дата создания:</div>
                            <div class="col-md-9"><?= htmlspecialchars($appData['create_at']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Форма изменения времени -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Изменить время посещения</h5>
                        
                        <form id="w0" action="" method="post">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
                            
                            <div class="mb-3">
                                <label class="form-label" for="app-date">Новая дата</label>
                                <input type="date" id="app-date" class="form-control" name="visit_date" 
                                       value="<?= htmlspecialchars($appData['visit_date']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="app-time">Новое время</label>
                                <input type="time" id="app-time" class="form-control" name="visit_time" 
                                       value="<?= htmlspecialchars($appData['visit_time']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Изменить время</button>
                        </form>
                    </div>
                </div>

                <!-- Кнопки управления статусом -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Управление заявкой</h5>
                        
                        <div class="d-flex gap-2">
                            <a href="admin-panel.php" class="btn btn-secondary">Вернуться к списку</a>
                            
                            <?php if($admin->canConfirm($appData['status_id'])): ?>
                                <!-- Статус "Создана" - доступна кнопка "Подтвердить" -->
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=confirm" 
                                   class="btn btn-success">
                                    Подтвердить
                                </a>
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=cancel" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                    Отменить
                                </a>
                            <?php elseif($admin->canStart($appData['status_id'])): ?>
                                <!-- Статус "Подтверждена" или "Перенесена" - доступна кнопка "Начать работу" -->
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=start" 
                                   class="btn btn-info">
                                    Начать работу
                                </a>
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=cancel" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                    Отменить
                                </a>
                            <?php elseif($admin->canComplete($appData['status_id'])): ?>
                                <!-- Статус "В работе" - доступна кнопка "Завершить" -->
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=complete" 
                                   class="btn btn-warning">
                                    Завершить
                                </a>
                                <a href="admin-panel.php?id=<?= $appData['id'] ?>&action=cancel" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                    Отменить
                                </a>
                            <?php elseif($admin->isCompleted($appData['status_id'])): ?>
                                <!-- Статус "Выполнена" - кнопки неактивны -->
                                <button class="btn btn-secondary" disabled>Заявка выполнена</button>
                            <?php elseif($admin->isCancelled($appData['status_id'])): ?>
                                <!-- Статус "Отменена" - кнопки неактивны -->
                                <button class="btn btn-secondary" disabled>Заявка отменена</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

<?php include 'src/footer.php'; ?>

<?php include 'src/scripts.php'; ?>

</body>

</html>
