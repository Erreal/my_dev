<?php $page_title = 'Дашборд'; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= (int)($stats['projects'] ?? 0) ?></div>
        <div class="stat-label">Проектов</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int)($stats['featured'] ?? 0) ?></div>
        <div class="stat-label">Избранных проектов</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int)($stats['technologies'] ?? 0) ?></div>
        <div class="stat-label">Технологий</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int)($stats['experience'] ?? 0) ?></div>
        <div class="stat-label">Записей опыта</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Быстрые действия</div>
    </div>
    <div style="display: flex; gap: 0.8rem; flex-wrap: wrap;">
        <a href="/admin/projects/create" class="btn btn-primary">➕ Новый проект</a>
        <a href="/admin/technologies/create" class="btn btn-secondary">➕ Новая технология</a>
        <a href="/admin/experience/create" class="btn btn-secondary">➕ Новая запись опыта</a>
        <a href="/admin/export" class="btn btn-secondary">📤 Экспорт JSON</a>
    </div>
</div>