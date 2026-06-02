<header id="header">
    <nav id="w0" class="navbar-expand-md navbar-dark bg-dark fixed-top navbar">
        <div class="container">
            <a class="navbar-brand" href="http://localhost/zakazUslugi.loc/">My Application</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#w0-collapse"
                aria-controls="w0-collapse" aria-expanded="false" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="w0-collapse" class="collapse navbar-collapse">
                <ul id="w1" class="navbar-nav nav">
                    <!-- Главная (доступна всем) -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage ?? '') == 'index' ? 'active' : '' ?>" href="index.php">Главная</a>
                    </li>
                    
                    <!-- Отзывы (доступны всем) -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage ?? '') == 'feedback' ? 'active' : '' ?>" href="feedback.php">Отзывы</a>
                    </li>
                    
                    <?php if (!isset($user->id)): ?>
                        <!-- ========== МЕНЮ ДЛЯ ГОСТЯ ========== -->
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'login' ? 'active' : '' ?>" href="login.php">Войти</a>
                        </li>
                        
                    <?php elseif($user->role === 'client'): ?>
                        <!-- ========== МЕНЮ ДЛЯ КЛИЕНТА ========== -->
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'account' ? 'active' : '' ?>" href="account.php">Личный кабинет</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'add-application' ? 'active' : '' ?>" href="add-application.php">Новая заявка</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'application' ? 'active' : '' ?>" href="application.php">Мои заявки</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'change-password' ? 'active' : '' ?>" href="change-password.php">Сменить пароль</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zakazUslugi.loc/login.php?action=logout">
                                Выход (<?= htmlspecialchars($user->login) ?>)
                            </a>
                        </li>
                        
                    <?php elseif($user->role === 'admin'): ?>
                        <!-- ========== МЕНЮ ДЛЯ АДМИНА ========== -->
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'admin-panel' ? 'active' : '' ?>" href="admin-panel.php">Панель админа</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'admin-reviews' ? 'active' : '' ?>" href="admin-reviews.php">Модерация отзывов</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'register' ? 'active' : '' ?>" href="register.php">Регистрация клиентов</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage ?? '') == 'change-password' ? 'active' : '' ?>" href="change-password.php">Сменить пароль</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/zakazUslugi.loc/login.php?action=logout">
                                Выход (<?= htmlspecialchars($user->login) ?>)
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
