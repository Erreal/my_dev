<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Erreality Admin</title>
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-card">
        <h1 class="login-title">Erreality Admin</h1>
        <p class="login-subtitle">Войдите в систему управления</p>

        <?php if (!empty($flash)): ?>
            <?php foreach ($flash as $type => $message): ?>
                <div class="flash flash-<?= \App\Core\View::escape($type) ?>">
                    <?= \App\Core\View::escape($message) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>
</body>
</html>