<?php $page_title = 'Редактировать технологию'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Редактирование: <?= \App\Core\View::escape($tech['name']) ?></div>
        <a href="/admin/technologies" class="btn btn-ghost">← Назад</a>
    </div>

    <form method="POST" action="/admin/technologies/edit/<?= (int)$tech['id'] ?>" class="form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row">
            <label class="form-label" for="name">Название</label>
            <input type="text" id="name" name="name" class="form-input" required value="<?= \App\Core\View::escape($tech['name']) ?>">
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="category">Категория</label>
                <select id="category" name="category" class="form-select">
                    <option value="frontend" <?= $tech['category'] === 'frontend' ? 'selected' : '' ?>>Frontend</option>
                    <option value="backend" <?= $tech['category'] === 'backend' ? 'selected' : '' ?>>Backend</option>
                    <option value="tools" <?= $tech['category'] === 'tools' ? 'selected' : '' ?>>Tools</option>
                    <option value="other" <?= $tech['category'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="sort_order">Порядок сортировки</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="<?= (int)$tech['sort_order'] ?>" min="0">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/admin/technologies" class="btn btn-ghost">Отмена</a>
        </div>
    </form>
</div>