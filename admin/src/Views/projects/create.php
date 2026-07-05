<?php $page_title = 'Создать проект'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Новый проект</div>
        <a href="/admin/projects" class="btn btn-ghost">← Назад</a>
    </div>

    <form method="POST" action="/admin/projects/create" class="form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div class="form-row half">
            <div>
                <label class="form-label" for="slug_ru">Slug (RU)</label>
                <input type="text" id="slug_ru" name="slug_ru" class="form-input" required placeholder="cifrovaya-shema-himki">
                <div class="form-hint">Только латиница, цифры и дефисы</div>
            </div>
            <div>
                <label class="form-label" for="slug_en">Slug (EN)</label>
                <input type="text" id="slug_en" name="slug_en" class="form-input" required placeholder="digital-scheme-khimki">
                <div class="form-hint">Только латиница, цифры и дефисы</div>
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="title_ru">Название (RU)</label>
                <input type="text" id="title_ru" name="title_ru" class="form-input" required>
            </div>
            <div>
                <label class="form-label" for="title_en">Название (EN)</label>
                <input type="text" id="title_en" name="title_en" class="form-input" required>
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="short_description_ru">Краткое описание (RU)</label>
                <textarea id="short_description_ru" name="short_description_ru" class="form-textarea" rows="3"></textarea>
            </div>
            <div>
                <label class="form-label" for="short_description_en">Краткое описание (EN)</label>
                <textarea id="short_description_en" name="short_description_en" class="form-textarea" rows="3"></textarea>
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="full_description_ru">Полное описание (RU)</label>
                <textarea id="full_description_ru" name="full_description_ru" class="form-textarea" rows="5"></textarea>
            </div>
            <div>
                <label class="form-label" for="full_description_en">Полное описание (EN)</label>
                <textarea id="full_description_en" name="full_description_en" class="form-textarea" rows="5"></textarea>
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="role_ru">Роль (RU)</label>
                <input type="text" id="role_ru" name="role_ru" class="form-input" placeholder="Senior Frontend Developer">
            </div>
            <div>
                <label class="form-label" for="role_en">Роль (EN)</label>
                <input type="text" id="role_en" name="role_en" class="form-input" placeholder="Senior Frontend Developer">
            </div>
        </div>

        <div class="form-row half">
            <div>
                <label class="form-label" for="responsibilities_ru">Обязанности (RU)</label>
                <textarea id="responsibilities_ru" name="responsibilities_ru" class="form-textarea" rows="3"></textarea>
            </div>
            <div>
                <label class="form-label" for="responsibilities_en">Обязанности (EN)</label>
                <textarea id="responsibilities_en" name="responsibilities_en" class="form-textarea" rows="3"></textarea>
            </div>
        </div>

        <div class="form-row third">
            <div>
                <label class="form-label" for="external_url">Внешняя ссылка</label>
                <input type="url" id="external_url" name="external_url" class="form-input" placeholder="https://...">
            </div>
            <div>
                <label class="form-label" for="status">Статус</label>
                <select id="status" name="status" class="form-select">
                    <option value="active">Активен</option>
                    <option value="completed" selected>Завершён</option>
                    <option value="archived">В архиве</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="sort_order">Порядок сортировки</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="0" min="0">
            </div>
        </div>

        <div class="form-row">
            <label class="form-checkbox">
                <input type="checkbox" name="featured" value="1">
                Избранный проект (показывать на главной)
            </label>
        </div>

        <div class="form-row">
            <label class="form-label">Технологии</label>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.3rem;">
                <?php if (!empty($technologies)): ?>
                    <?php foreach ($technologies as $tech): ?>
                        <label class="form-checkbox" style="min-width: 120px;">
                            <input type="checkbox" name="technologies[]" value="<?= (int)$tech['id'] ?>">
                            <?= \App\Core\View::escape($tech['name']) ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="form-hint">Сначала добавьте технологии в разделе «Технологии».</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Создать проект</button>
            <a href="/admin/projects" class="btn btn-ghost">Отмена</a>
        </div>
    </form>
</div>