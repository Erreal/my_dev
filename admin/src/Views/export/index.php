<?php $page_title = 'Экспорт JSON'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Экспорт данных в JSON</div>
    </div>

    <p style="color: #6e6e73; font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.6;">
        Экспортируйте все данные из базы данных в JSON-файлы, которые используются Next.js
        для генерации статических страниц. После экспорта необходимо запустить сборку Next.js
        для применения изменений на сайте.
    </p>

    <div style="background: #f5f5f7; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: #6e6e73;">
        <strong>Путь экспорта:</strong> <code style="background: #e5e5ea; padding: 0.15rem 0.4rem; border-radius: 4px;">frontend/src/lib/data/</code>
    </div>

    <form method="POST" action="/admin/export">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">

        <div style="display: flex; gap: 0.8rem;">
            <button type="submit" class="btn btn-primary" style="font-size: 1rem; padding: 0.8rem 1.5rem;">
                📤 Экспортировать все данные
            </button>
            <a href="/admin/dashboard" class="btn btn-ghost">На дашборд</a>
        </div>
    </form>
</div>