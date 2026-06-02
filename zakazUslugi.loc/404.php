<?php
$currentPage = '404';
require 'src/init.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">

<head>
    <title>Страница не найдена</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <div class="text-center mt-5">
                <h1 class="display-1 text-muted">404</h1>
                <h2 class="mb-4">Страница не найдена</h2>
                <p class="lead text-muted mb-4">
                    Запрашиваемая страница не существует или была удалена.
                </p>
                <a href="index.php" class="btn btn-primary">Вернуться на главную</a>
            </div>
        </div>
    </main>

<?php include 'src/footer.php'; ?>

<?php include 'src/scripts.php'; ?>

</body>

</html>
