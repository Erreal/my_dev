<?php $page_title = 'Проекты'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Все проекты</div>
        <a href="/admin/projects/create" class="btn btn-primary">➕ Создать проект</a>
    </div>

    <?php if (empty($projects)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📁</div>
            <div class="empty-state-text">Проектов пока нет. Создайте первый проект.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Название (RU)</th>
                        <th>Название (EN)</th>
                        <th>Статус</th>
                        <th>Избранный</th>
                        <th>Порядок</th>
                        <th class="actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= \App\Core\View::escape($project['title_ru']) ?></td>
                            <td><?= \App\Core\View::escape($project['title_en']) ?></td>
                            <td>
                                <span class="badge badge-<?= \App\Core\View::escape($project['status']) ?>">
                                    <?= \App\Core\View::escape($project['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($project['featured']): ?>
                                    <span class="badge badge-featured">★ Избранный</span>
                                <?php endif; ?>
                            </td>
                            <td><?= (int)$project['sort_order'] ?></td>
                            <td class="actions">
                                <a href="/admin/projects/<?= (int)$project['id'] ?>/screenshots" class="btn btn-ghost btn-sm">📷</a>
                                <a href="/admin/projects/edit/<?= (int)$project['id'] ?>" class="btn btn-secondary btn-sm">✏️</a>
                                <form method="POST" action="/admin/projects/delete/<?= (int)$project['id'] ?>" style="display:inline" onsubmit="return confirm('Удалить проект?')">
                                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\View::escape($csrf_token ?? '') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>