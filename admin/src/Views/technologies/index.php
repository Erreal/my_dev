<?php $page_title = 'Технологии'; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Все технологии</div>
        <a href="/admin/technologies/create" class="btn btn-primary">➕ Добавить</a>
    </div>

    <?php if (empty($technologies)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">⚙️</div>
            <div class="empty-state-text">Технологий пока нет.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Порядок</th>
                        <th class="actions">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($technologies as $tech): ?>
                        <tr>
                            <td><?= \App\Core\View::escape($tech['name']) ?></td>
                            <td><span class="badge badge-active"><?= \App\Core\View::escape($tech['category']) ?></span></td>
                            <td><?= (int)$tech['sort_order'] ?></td>
                            <td class="actions">
                                <a href="/admin/technologies/edit/<?= (int)$tech['id'] ?>" class="btn btn-secondary btn-sm">✏️</a>
                                <form method="POST" action="/admin/technologies/delete/<?= (int)$tech['id'] ?>" style="display:inline" onsubmit="return confirm('Удалить технологию?')">
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