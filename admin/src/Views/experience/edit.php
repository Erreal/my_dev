<?php $page_title = 'Редактировать запись опыта'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Редактирование: <?= \App\Core\View::escape($item['company_ru']) ?></div>
        <a href="/admin/experience" class="btn btn-ghost">← Назад</a>
    </div>

    <form method="POST" action="/admin/experience/edit/<?= (int)$item['id'] ?>" class="form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row half">
            <div>
                <label class="form-label" for="company_ru">Компания (RU)</label>
                <input type="text" id="company_ru" name="company_ru" class="form-input" required value="<?= \App\Core\View::escape($item['company_ru']) ?>">
            </div>
            <div>
                <label class="form-label" for="company_en">Компания (EN)</label>
                <input type="text" id="company_en" name="company_en" class="form-input" required value="<?= \App\Core\View::escape($item['company_en']) ?>">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="position_ru">Должность (RU)</label>
                <input type="text" id="position_ru" name="position_ru" class="form-input" required value="<?= \App\Core\View::escape($item['position_ru']) ?>">
            </div>
            <div>
                <label class="form-label" for="position_en">Должность (EN)</label>
                <input type="text" id="position_en" name="position_en" class="form-input" required value="<?= \App\Core\View::escape($item['position_en']) ?>">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="description_ru">Описание (RU)</label>
                <textarea id="description_ru" name="description_ru" class="form-textarea" rows="4"><?= \App\Core\View::escape($item['description_ru']) ?></textarea>
            </div>
            <div>
                <label class="form-label" for="description_en">Описание (EN)</label>
                <textarea id="description_en" name="description_en" class="form-textarea" rows="4"><?= \App\Core\View::escape($item['description_en']) ?></textarea>
            </div>
        </div>

        <div class="form-row third">
            <div>
                <label class="form-label" for="start_date">Дата начала</label>
                <input type="date" id="start_date" name="start_date" class="form-input" required value="<?= \App\Core\View::escape($item['start_date']) ?>">
            </div>
            <div>
                <label class="form-label" for="end_date">Дата окончания</label>
                <input type="date" id="end_date" name="end_date" class="form-input" value="<?= \App\Core\View::escape($item['end_date'] ?? '') ?>">
                <div class="form-hint">Оставьте пустым, если работаете до сих пор</div>
            </div>
            <div>
                <label class="form-label" for="sort_order">Порядок сортировки</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="<?= (int)$item['sort_order'] ?>" min="0">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/admin/experience" class="btn btn-ghost">Отмена</a>
        </div>
    </form>
</div>