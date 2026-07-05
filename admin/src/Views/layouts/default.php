<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Core\View::escape($app_name ?? 'Admin') ?></title>
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <a href="/admin/dashboard">Erreality Admin</a>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/dashboard" class="<?= str_starts_with($current_path ?? '', '/admin/dashboard') ? 'active' : '' ?>">
                    <span class="nav-icon">📊</span>
                    <span>Дашборд</span>
                </a>
                <a href="/admin/projects" class="<?= str_starts_with($current_path ?? '', '/admin/projects') ? 'active' : '' ?>">
                    <span class="nav-icon">📁</span>
                    <span>Проекты</span>
                </a>
                <a href="/admin/technologies" class="<?= str_starts_with($current_path ?? '', '/admin/technologies') ? 'active' : '' ?>">
                    <span class="nav-icon">⚙️</span>
                    <span>Технологии</span>
                </a>
                <a href="/admin/experience" class="<?= str_starts_with($current_path ?? '', '/admin/experience') ? 'active' : '' ?>">
                    <span class="nav-icon">💼</span>
                    <span>Опыт работы</span>
                </a>
                <a href="/admin/profile" class="<?= str_starts_with($current_path ?? '', '/admin/profile') ? 'active' : '' ?>">
                    <span class="nav-icon">👤</span>
                    <span>Профиль</span>
                </a>
                <a href="/admin/export" class="<?= str_starts_with($current_path ?? '', '/admin/export') ? 'active' : '' ?>">
                    <span class="nav-icon">📤</span>
                    <span>Экспорт JSON</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/admin/logout">Выйти</a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <header class="top-bar">
                <div class="top-bar-title">
                    <?= \App\Core\View::escape($page_title ?? '') ?>
                </div>
                <div class="top-bar-user">
                    <?= \App\Core\View::escape($auth_user ?? '') ?>
                </div>
            </header>

            <main class="page-content">
                <!-- Flash Messages -->
                <?php if (!empty($flash)): ?>
                    <?php foreach ($flash as $type => $message): ?>
                        <div class="flash flash-<?= \App\Core\View::escape($type) ?>">
                            <?= \App\Core\View::escape($message) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>
</body>
</html>