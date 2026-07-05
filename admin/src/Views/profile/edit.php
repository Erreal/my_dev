<?php $page_title = 'Редактировать профиль'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Профиль</div>
    </div>

    <form method="POST" action="/admin/profile" class="form" enctype="multipart/form-data">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row half">
            <div>
                <label class="form-label" for="name_ru">Имя (RU)</label>
                <input type="text" id="name_ru" name="name_ru" class="form-input" required value="<?= \App\Core\View::escape($profile['name_ru'] ?? '') ?>">
            </div>
            <div>
                <label class="form-label" for="name_en">Имя (EN)</label>
                <input type="text" id="name_en" name="name_en" class="form-input" required value="<?= \App\Core\View::escape($profile['name_en'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="position_ru">Должность (RU)</label>
                <input type="text" id="position_ru" name="position_ru" class="form-input" required value="<?= \App\Core\View::escape($profile['position_ru'] ?? '') ?>">
            </div>
            <div>
                <label class="form-label" for="position_en">Должность (EN)</label>
                <input type="text" id="position_en" name="position_en" class="form-input" required value="<?= \App\Core\View::escape($profile['position_en'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="summary_ru">О себе (RU)</label>
                <textarea id="summary_ru" name="summary_ru" class="form-textarea" rows="5" required><?= \App\Core\View::escape($profile['summary_ru'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="form-label" for="summary_en">О себе (EN)</label>
                <textarea id="summary_en" name="summary_en" class="form-textarea" rows="5" required><?= \App\Core\View::escape($profile['summary_en'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-row">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input" required value="<?= \App\Core\View::escape($profile['email'] ?? '') ?>">
        </div>

        <div class="form-row third">
            <div>
                <label class="form-label" for="github_url">GitHub URL</label>
                <input type="url" id="github_url" name="github_url" class="form-input" value="<?= \App\Core\View::escape($profile['github_url'] ?? '') ?>">
            </div>
            <div>
                <label class="form-label" for="linkedin_url">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-input" value="<?= \App\Core\View::escape($profile['linkedin_url'] ?? '') ?>">
            </div>
            <div>
                <label class="form-label" for="telegram_url">Telegram URL</label>
                <input type="url" id="telegram_url" name="telegram_url" class="form-input" value="<?= \App\Core\View::escape($profile['telegram_url'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="photo">Фото</label>
                <input type="file" id="photo" name="photo" class="form-input" accept="image/jpeg,image/png,image/webp">
                <?php if (!empty($profile['photo'])): ?>
                    <div class="form-hint">Текущее: <?= \App\Core\View::escape(basename($profile['photo'])) ?></div>
                <?php endif; ?>
            </div>
            <div>
                <label class="form-label" for="resume_file">Резюме (PDF)</label>
                <input type="file" id="resume_file" name="resume_file" class="form-input" accept=".pdf">
                <?php if (!empty($profile['resume_file'])): ?>
                    <div class="form-hint">Текущее: <?= \App\Core\View::escape(basename($profile['resume_file'])) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</div>