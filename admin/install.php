<?php
/**
 * First-run installation script.
 * 
 * Run this script once to set up the database and create the admin user.
 * Access: /admin/install.php
 * 
 * WARNING: Remove or protect this file after installation!
 */

require_once __DIR__ . '/config.php';

use App\Config\Database;

$step = $_GET['step'] ?? 'start';
$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'] ?? DB_HOST;
    $dbPort = $_POST['db_port'] ?? DB_PORT;
    $dbName = $_POST['db_name'] ?? DB_NAME;
    $dbUser = $_POST['db_user'] ?? DB_USER;
    $dbPass = $_POST['db_pass'] ?? DB_PASS;

    $adminUser = $_POST['admin_user'] ?? 'admin';
    $adminPass = $_POST['admin_pass'] ?? '';
    $adminPassConfirm = $_POST['admin_pass_confirm'] ?? '';

    // Validate
    if (empty($adminPass)) {
        $error = 'Введите пароль администратора.';
    } elseif ($adminPass !== $adminPassConfirm) {
        $error = 'Пароли не совпадают.';
    } elseif (strlen($adminPass) < 8) {
        $error = 'Пароль должен быть не менее 8 символов.';
    }

    if (!$error) {
        try {
            // Connect without database to create it
            $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$dbName}`");

            // Run migration SQL
            $migrationPath = __DIR__ . '/migrations/001_initial_schema.sql';
            if (file_exists($migrationPath)) {
                $sql = file_get_contents($migrationPath);
                // Remove the seed INSERT for admin user (we'll create our own)
                $sql = preg_replace(
                    "/INSERT INTO admin_users.*ON DUPLICATE KEY UPDATE username = username;/",
                    '',
                    $sql
                );
                $pdo->exec($sql);
            }

            // Create admin user with provided password
            $hash = password_hash($adminPass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$adminUser, $hash]);

            $success = 'Установка завершена! Теперь вы можете войти в админ-панель.';
            $step = 'complete';

        } catch (\Throwable $e) {
            $error = 'Ошибка установки: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Установка — Erreality Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f7;
            color: #1d1d1f;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            background: #fff;
            border-radius: 18px;
            padding: 2.5rem;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        h1 { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
        p { color: #6e6e73; font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.3rem; color: #1d1d1f; }
        input, select {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #d2d2d7;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #0071e3; }
        .btn {
            display: inline-block;
            padding: 0.7rem 1.5rem;
            background: #0071e3;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #0077ed; }
        .error { color: #d32f2f; font-size: 0.85rem; margin-bottom: 1rem; padding: 0.8rem; background: #fdecea; border-radius: 8px; }
        .success { color: #2e7d32; font-size: 0.85rem; margin-bottom: 1rem; padding: 0.8rem; background: #e8f5e9; border-radius: 8px; }
        .section-title { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6e6e73; margin: 1.5rem 0 0.8rem; }
        .complete-actions { margin-top: 1.5rem; }
        .complete-actions a { margin-right: 0.8rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Установка Erreality Admin</h1>
        <p>Настройка базы данных и создание учётной записи администратора.</p>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($step === 'complete'): ?>
            <div class="complete-actions">
                <a href="/admin/login" class="btn">Войти в админ-панель</a>
                <a href="/" style="color: #0071e3; font-size: 0.9rem; text-decoration: none;">На сайт</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="section-title">База данных</div>

                <div class="form-group">
                    <label for="db_host">Хост</label>
                    <input type="text" id="db_host" name="db_host" value="<?= htmlspecialchars(DB_HOST) ?>">
                </div>

                <div class="form-group">
                    <label for="db_port">Порт</label>
                    <input type="text" id="db_port" name="db_port" value="<?= htmlspecialchars(DB_PORT) ?>">
                </div>

                <div class="form-group">
                    <label for="db_name">Название базы данных</label>
                    <input type="text" id="db_name" name="db_name" value="<?= htmlspecialchars(DB_NAME) ?>">
                </div>

                <div class="form-group">
                    <label for="db_user">Пользователь MySQL</label>
                    <input type="text" id="db_user" name="db_user" value="<?= htmlspecialchars(DB_USER) ?>">
                </div>

                <div class="form-group">
                    <label for="db_pass">Пароль MySQL</label>
                    <input type="password" id="db_pass" name="db_pass" value="">
                </div>

                <div class="section-title">Администратор</div>

                <div class="form-group">
                    <label for="admin_user">Имя пользователя</label>
                    <input type="text" id="admin_user" name="admin_user" value="admin">
                </div>

                <div class="form-group">
                    <label for="admin_pass">Пароль</label>
                    <input type="password" id="admin_pass" name="admin_pass" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="admin_pass_confirm">Подтверждение пароля</label>
                    <input type="password" id="admin_pass_confirm" name="admin_pass_confirm" required minlength="8">
                </div>

                <button type="submit" class="btn">Установить</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>