<?php
$csrfToken = "YYWMo8nm2RWzZQ2blSGEi_JZkduKhNmkfzJyMJYc-_0gzLWXsbXtZP8oY8LdGMPHkzDWg-_bnOAHWyt-pUyIqg==";
$currentPage = 'change-password';
$isLoggedIn = false;
require 'src/initChangePass.php';
?>
<!DOCTYPE html>
<html lang="ru-RU" class="h-100">

<head>
    <title>Смена пароля</title>
<?php include 'src/head.php'; ?>
</head>

<body class="d-flex flex-column h-100">

<?php include 'src/header.php'; ?>
    
    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">

            <h1>Смена пароля</h1>

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
                    
                    <div class="mb-3 field-current-password required">
                        <label class="form-label" for="app-current-password">Введите текущий пароль</label>
                        <input type="password" id="app-current-password" class="form-control" 
                               name="currentPassword" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3 field-new-password required">
                        <label class="form-label" for="app-new-password">Введите новый пароль</label>
                        <input type="password" id="app-new-password" class="form-control" 
                               name="newPassword" value="" aria-required="true">
                        <div class="form-text">Минимум 8 символов, должен содержать заглавную букву, цифру и специальный символ</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3 field-retype-password required">
                        <label class="form-label" for="app-retype-password">Подтвердите новый пароль</label>
                        <input type="password" id="app-retype-password" class="form-control" 
                               name="retypePassword" value="" aria-required="true">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">изменить пароль</button>
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
