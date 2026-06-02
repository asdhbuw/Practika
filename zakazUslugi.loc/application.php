<?php
$csrfToken = "YYWMo8nm2RWzZQ2blSGEi_JZkduKhNmkfzJyMJYc-_0gzLWXsbXtZP8oY8LdGMPHkzDWg-_bnOAHWyt-pUyIqg==";
$currentPage = 'account';
$isLoggedIn = false;
require 'src/initApplication.php';
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="account.html">заявки</a></li>
                </ol>
            </nav>
            <div class="application-index">

                <h1>Заявка на посещение</h1>

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

                    <div id="w1" class="list-view">
                        <div class="d-flex flex-wrap justify-content-between layout-card">
                            
                            <?php if($applications && count($applications) > 0): ?>
    <?php foreach($applications as $app): ?>
        <div class="item" data-key="<?= $app['id'] ?>">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h3 class="card-title">
                        Заявка №<?= htmlspecialchars($app['id']) ?>
                    </h3>
                    <p class="card-text">
                        <?= htmlspecialchars($app['text']) ?>
                    </p>
                    <div class="card-text">
                        <div class="opacity-50">
                            дата и время посещения:
                        </div>
                        <?= htmlspecialchars($app['visit_date']) ?> <?= htmlspecialchars($app['visit_time']) ?>
                    </div>
                    <div class="card-text">
                        <div class="opacity-50">
                            дата и время создания:
                        </div>
                        <?= htmlspecialchars($app['create_at']) ?>
                    </div>
                    <div class="card-text">
                        <div class="opacity-50">
                            статус:
                        </div>
                        <?= htmlspecialchars($app['status_name']) ?>
                    </div>
                    
                    <a class="btn btn-danger" 
                       href="application.php?delete=<?= $app['id'] ?>" 
                       onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                        удалить
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-muted">У вас пока нет заявок</p>
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
