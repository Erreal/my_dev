<?php $page_title = 'Опыт работы'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Все записи</div>
        <a href="/admin/experience/create" class="btn btn-primary">➕ Добавить</a>
    </div>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">💼</div>
            <div class="empty-state-text">Записей пока нет.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Компания (RU)</th>
                        <th>Должность (RU)</th>
                        <th>Дата начала</th>
                        <th>Дата окончания</th>
                        <th>Порядок</th>
                        <th class="actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= \App\Core\View::escape($item['company_ru']) ?></td>
                            <td><?= \App\Core\View::escape($item['position_ru']) ?></td>
                            <td><?= \App\Core\View::escape($item['start_date']) ?></td>
                            <td><?= \App\Core\View::escape($item['end_date'] ?? 'Настоящее время') ?></td>
                            <td><?= (int)$item['sort_order'] ?></td>
                            <td class="actions">
                                <a href="/admin/experience/edit/<?= (int)$item['id'] ?>" class="btn btn-secondary btn-sm">✏️</a>
                                <form method="POST" action="/admin/experience/delete/<?= (int)$item['id'] ?>" style="display:inline" onsubmit="return confirm('Удалить запись?')">
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