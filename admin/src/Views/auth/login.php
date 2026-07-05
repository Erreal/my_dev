<form method="POST" action="/admin/login" class="login-form">
    <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

    <div class="form-row">
        <label class="form-label" for="username">Имя пользователя</label>
        <input type="text" id="username" name="username" class="form-input" required autocomplete="username" autofocus>
    </div>

    <div class="form-row">
        <label class="form-label" for="password">Пароль</label>
        <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-primary">Войти</button>
</form>