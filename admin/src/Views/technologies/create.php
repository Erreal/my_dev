<?php $page_title = 'Добавить технологию'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Новая технология</div>
        <a href="/admin/technologies" class="btn btn-ghost">← Назад</a>
    </div>

    <form method="POST" action="/admin/technologies/create" class="form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row">
            <label class="form-label" for="name">Название</label>
            <input type="text" id="name" name="name" class="form-input" required placeholder="React">
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="category">Категория</label>
                <select id="category" name="category" class="form-select">
                    <option value="frontend">Frontend</option>
                    <option value="backend">Backend</option>
                    <option value="tools">Tools</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="sort_order">Порядок сортировки</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="0" min="0">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="/admin/technologies" class="btn btn-ghost">Отмена</a>
        </div>
    </form>
</div>