<?php require 'src/initIndex.php'; ?>

<!DOCTYPE html>
<html lang="ru-RU" class="h-100">

<head>
    <title>Главная</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if(isset($_SESSION['flash'])): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <?= htmlspecialchars($_SESSION['flash']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            
            <h1 class="mt-5">Главная страница</h1>
            
            <h2 class="mt-5">Отзывы наших клиентов</h2>
            
            <div class="row mt-4">
                <?php if($publishedReviews && count($publishedReviews) > 0): ?>
                    <?php foreach($publishedReviews as $review): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if(!empty($review['img'])): ?>
                                    <img src="<?= htmlspecialchars($review['img']) ?>" class="card-img-top" alt="Фото отзыва" style="max-height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($review['full_name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($review['text']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Пока нет отзывов</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php include 'src/footer.php'; ?>

<?php include 'src/scripts.php'; ?>

</body>

</html>