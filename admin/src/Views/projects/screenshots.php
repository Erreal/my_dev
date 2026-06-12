<?php $page_title = 'Скриншоты: ' . ($project['title_ru'] ?? ''); ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Скриншоты проекта: <?= \App\Core\View::escape($project['title_ru'] ?? '') ?></div>
        <a href="/admin/projects" class="btn btn-ghost">← Назад к проектам</a>
    </div>

    <!-- Upload form -->
    <form method="POST" action="/admin/projects/<?= (int)$project['id'] ?>/screenshots" enctype="multipart/form-data" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e5ea;">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row">
            <label class="form-label" for="screenshots">Выберите изображения</label>
            <input type="file" id="screenshots" name="screenshots[]" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple required>
            <div class="form-hint">Максимальный размер: 10 MB. Допустимые форматы: JPG, PNG, GIF, WebP.</div>
        </div>

        <button type="submit" class="btn btn-primary">Загрузить</button>
    </form>

    <!-- Screenshots grid -->
    <?php if (empty($screenshots)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📷</div>
            <div class="empty-state-text">Скриншотов пока нет. Загрузите изображения.</div>
        </div>
    <?php else: ?>
        <div class="screenshot-grid">
            <?php foreach ($screenshots as $screenshot): ?>
                <div class="screenshot-item">
                    <img src="/admin/uploads/thumbnails/<?= \App\Core\View::escape($screenshot['thumbnail']) ?>"
                         alt="<?= \App\Core\View::escape($screenshot['title_ru'] ?? '') ?>"
                         loading="lazy">
                    <div class="screenshot-info">
                        <span class="filename"><?= \App\Core\View::escape($screenshot['filename']) ?></span>
                        <form method="POST" action="/admin/screenshots/delete/<?= (int)$screenshot['id'] ?>" style="display:inline" onsubmit="return confirm('Удалить скриншот?')">
                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">
                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>