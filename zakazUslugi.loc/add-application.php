<?php
$csrfToken = "YYWMo8nm2RWzZQ2blSGEi_JZkduKhNmkfzJyMJYc-_0gzLWXsbXtZP8oY8LdGMPHkzDWg-_bnOAHWyt-pUyIqg==";
$currentPage = 'add-application';
$isLoggedIn = false;
require 'src/initApplication.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">

<head>
    <title>Добавить заявку</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>
    
    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">

            <h1>Новая заявка</h1>

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

            <div class="feedback-index p-3">
                <form id="w0" action="" method="post">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
                    
                    <div class="mb-3 field-app-date required">
                        <label class="form-label" for="app-date">Выберите дату</label>
                        <input type="date" id="app-date" class="form-control" name="visit_date" 
                               value="<?= htmlspecialchars($application->getVisitDate()) ?>" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3 field-app-time required">
                        <label class="form-label" for="app-time">Выберите время посещения</label>
                        <input type="time" id="app-time" class="form-control" name="visit_time" 
                               value="<?= htmlspecialchars($application->getVisitTime()) ?>" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3 field-app-text required">
                        <label class="form-label" for="app-text">Причина посещения</label>
                        <textarea id="app-text" class="form-control" name="text" 
                            aria-required="true"><?= htmlspecialchars($application->getText()) ?></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">отправить заявку</button>
                        <a href="account.php" class="btn btn-secondary">отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

<?php include 'src/footer.php'; ?>

<?php include 'src/scripts.php'; ?>

</body>

</html>
